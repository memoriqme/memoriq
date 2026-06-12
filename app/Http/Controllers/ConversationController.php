<?php

namespace App\Http\Controllers;

use App\Models\MemoriqConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ConversationController extends Controller
{
    public const FREE_STORAGE_LIMIT_BYTES = 104857600;
    public const MAX_ENCRYPTED_HEADER_BYTES = 262144;
    public const MAX_ENCRYPTED_BODY_BYTES = 10485760;

    public function index(Request $request)
    {
        $perPage = min((int) $request->integer('per_page', 25), 50);

        $conversations = MemoriqConversation::query()
            ->where('user_id', $request->user()->id)
            ->select([
                'id',
                'payload_version',
                'encrypted_header',
                'body_bytes',
                'created_at',
                'updated_at',
            ])
            ->latest('created_at')
            ->paginate($perPage);

        return response()->json($conversations);
    }

    public function syncStatus(Request $request)
    {
        $userId = $request->user()->id;
        $latestUpdatedAt = MemoriqConversation::query()
            ->where('user_id', $userId)
            ->max('updated_at');

        return response()->json([
            'conversationCount' => MemoriqConversation::query()
                ->where('user_id', $userId)
                ->count(),
            'latestUpdatedAt' => $latestUpdatedAt ? (string) $latestUpdatedAt : null,
        ]);
    }

    public function storage(Request $request)
    {
        $usedBytes = (int) MemoriqConversation::query()
            ->where('user_id', $request->user()->id)
            ->selectRaw('COALESCE(SUM(LENGTH(encrypted_header) + body_bytes), 0) as used_bytes')
            ->value('used_bytes');

        return response()->json([
            'usedBytes' => $usedBytes,
            'limitBytes' => self::FREE_STORAGE_LIMIT_BYTES,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'encrypted_header' => ['required', 'string', 'regex:/^[A-Za-z0-9+\/=]+$/', 'max:'.self::MAX_ENCRYPTED_HEADER_BYTES],
            'encrypted_body' => ['required', 'string', 'regex:/^[A-Za-z0-9+\/=]+$/', 'max:'.self::MAX_ENCRYPTED_BODY_BYTES],
        ]);

        if (! filled($request->user()->encryption_key_jwk) || ! filled($request->user()->encryption_key_salt)) {
            return response()->json([
                'message' => 'Encryption is not configured for this account.',
            ], 409);
        }

        $newBytes = strlen($validated['encrypted_header']) + strlen($validated['encrypted_body']);
        if ($this->usedStorageBytes($request) + $newBytes > self::FREE_STORAGE_LIMIT_BYTES) {
            return response()->json([
                'message' => 'Storage limit exceeded. Export or delete saved chats before saving more.',
            ], 413);
        }

        $conversation = MemoriqConversation::create([
            'user_id' => $request->user()->id,
            'payload_version' => 2,
            'encrypted_header' => $validated['encrypted_header'],
            'encrypted_body' => $validated['encrypted_body'],
            'body_storage_disk' => null,
            'body_storage_path' => null,
            'body_bytes' => strlen($validated['encrypted_body']),
        ]);

        return response()->json($conversation->only([
            'id',
            'payload_version',
            'encrypted_header',
            'body_bytes',
            'created_at',
            'updated_at',
        ]), 201);
    }

    public function show(Request $request, string $conversation)
    {
        $conversation = $this->conversationForUser($request, $conversation);

        return response()->json([
            'id' => $conversation->id,
            'payload_version' => $conversation->payload_version,
            'encrypted_header' => $conversation->encrypted_header,
            'encrypted_body' => $this->encryptedBody($conversation),
            'body_bytes' => $conversation->body_bytes,
            'created_at' => $conversation->created_at,
            'updated_at' => $conversation->updated_at,
        ]);
    }

    public function updateHeader(Request $request, string $conversation)
    {
        $conversation = $this->conversationForUser($request, $conversation);

        $validated = $request->validate([
            'encrypted_header' => ['required', 'string', 'regex:/^[A-Za-z0-9+\/=]+$/', 'max:'.self::MAX_ENCRYPTED_HEADER_BYTES],
        ]);

        $currentHeaderBytes = strlen($conversation->encrypted_header);
        $newHeaderBytes = strlen($validated['encrypted_header']);
        if ($this->usedStorageBytes($request) - $currentHeaderBytes + $newHeaderBytes > self::FREE_STORAGE_LIMIT_BYTES) {
            return response()->json([
                'message' => 'Storage limit exceeded. Export or delete saved chats before saving more.',
            ], 413);
        }

        $conversation->forceFill([
            'encrypted_header' => $validated['encrypted_header'],
        ])->save();

        return response()->json([
            'id' => $conversation->id,
            'payload_version' => $conversation->payload_version,
            'encrypted_header' => $conversation->encrypted_header,
            'body_bytes' => $conversation->body_bytes,
            'created_at' => $conversation->created_at,
            'updated_at' => $conversation->updated_at,
        ]);
    }

    public function destroy(Request $request, string $conversation)
    {
        $conversation = MemoriqConversation::query()
            ->where('user_id', $request->user()->id)
            ->whereKey($conversation)
            ->first();

        if (! $conversation) {
            return response()->noContent();
        }

        $this->deleteStoredBody($conversation);
        $conversation->delete();

        return response()->noContent();
    }

    public function destroyAll(Request $request)
    {
        MemoriqConversation::query()
            ->where('user_id', $request->user()->id)
            ->each(function (MemoriqConversation $conversation) {
                $this->deleteStoredBody($conversation);
                $conversation->delete();
            });

        return response()->noContent();
    }

    private function encryptedBody(MemoriqConversation $conversation): string
    {
        if ($conversation->encrypted_body !== null) {
            if ($conversation->body_bytes <= 0 || strlen($conversation->encrypted_body) === $conversation->body_bytes) {
                return $conversation->encrypted_body;
            }

            return $this->encryptedBodyFromDatabaseChunks($conversation);
        }

        $missingBodyMessage = 'Encrypted conversation body is missing from server storage. Please re-import the backup after deploying the latest version.';

        abort_unless($conversation->body_storage_disk && $conversation->body_storage_path, 410, $missingBodyMessage);
        abort_unless(Storage::disk($conversation->body_storage_disk)->exists($conversation->body_storage_path), 410, $missingBodyMessage);

        $body = Storage::disk($conversation->body_storage_disk)->get($conversation->body_storage_path);

        abort_if($body === null, 410, $missingBodyMessage);

        return $body;
    }

    private function encryptedBodyFromDatabaseChunks(MemoriqConversation $conversation): string
    {
        $chunkSize = 700000;
        $body = '';

        for ($offset = 0; $offset < $conversation->body_bytes; $offset += $chunkSize) {
            $chunk = DB::table($conversation->getTable())
                ->where('id', $conversation->id)
                ->selectRaw('SUBSTRING(encrypted_body, ?, ?) as chunk', [$offset + 1, $chunkSize])
                ->value('chunk');

            if ($chunk === null || $chunk === '') {
                break;
            }

            $body .= $chunk;
        }

        abort_unless(strlen($body) === $conversation->body_bytes, 500, 'Encrypted conversation body could not be read completely from database storage.');

        return $body;
    }

    private function usedStorageBytes(Request $request): int
    {
        return (int) MemoriqConversation::query()
            ->where('user_id', $request->user()->id)
            ->selectRaw('COALESCE(SUM(LENGTH(encrypted_header) + body_bytes), 0) as used_bytes')
            ->value('used_bytes');
    }

    private function conversationForUser(Request $request, string $conversation): MemoriqConversation
    {
        return MemoriqConversation::query()
            ->where('user_id', $request->user()->id)
            ->whereKey($conversation)
            ->firstOrFail();
    }

    private function deleteStoredBody(MemoriqConversation $conversation): void
    {
        if (! $conversation->body_storage_disk || ! $conversation->body_storage_path) {
            return;
        }

        Storage::disk($conversation->body_storage_disk)->delete($conversation->body_storage_path);
    }
}
