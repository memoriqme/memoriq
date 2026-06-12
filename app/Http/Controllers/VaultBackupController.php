<?php

namespace App\Http\Controllers;

use App\Models\MemoriqConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VaultBackupController extends Controller
{
    private const FORMAT_VERSION = 1;
    private const MAX_CONVERSATIONS_PER_IMPORT = 5000;

    public function export(Request $request)
    {
        $user = $request->user();

        abort_unless(filled($user->encryption_key_jwk) && filled($user->encryption_key_salt), 409, 'Encryption is not configured for this account.');

        $conversations = MemoriqConversation::query()
            ->where('user_id', $user->id)
            ->oldest('created_at')
            ->get()
            ->map(fn (MemoriqConversation $conversation) => [
                'payloadVersion' => $conversation->payload_version,
                'encryptedHeader' => $conversation->encrypted_header,
                'encryptedBody' => $this->encryptedBody($conversation),
                'bodyBytes' => $conversation->body_bytes,
                'createdAt' => optional($conversation->created_at)->toISOString(),
                'updatedAt' => optional($conversation->updated_at)->toISOString(),
            ])
            ->values();

        $version = $this->backupVersion();
        $exportedAt = now();
        $backup = [
            'type' => 'memoriq.encrypted-vault.backup',
            'formatVersion' => self::FORMAT_VERSION,
            'exportedAt' => $exportedAt->toISOString(),
            'app' => [
                'name' => config('app.name'),
                'version' => $version,
            ],
            'encryption' => [
                'encryptedMek' => $user->encryption_key_jwk,
                'salt' => $user->encryption_key_salt,
                'keyWrap' => 'pbkdf2-sha256-200000-aeskw',
                'payload' => 'aes-256-gcm',
            ],
            'conversations' => $conversations,
        ];

        $filename = sprintf(
            'memoriq-%s-%s.json',
            $exportedAt->format('Ymd-His'),
            $this->safeFilenamePart($version),
        );

        return response()->json($backup)
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'backup' => ['required', 'file', 'max:102400'],
        ]);

        $backup = json_decode((string) file_get_contents($validated['backup']->getRealPath()), true);

        abort_unless(is_array($backup), 422, 'Invalid backup JSON.');
        abort_unless(($backup['type'] ?? null) === 'memoriq.encrypted-vault.backup', 422, 'This is not a Memoriq encrypted vault backup.');
        abort_unless((int) ($backup['formatVersion'] ?? 0) === self::FORMAT_VERSION, 422, 'Unsupported backup format version.');
        abort_unless(filled(data_get($backup, 'encryption.encryptedMek')) && filled(data_get($backup, 'encryption.salt')), 422, 'Backup is missing encryption key metadata.');
        abort_unless(is_array($backup['conversations'] ?? null), 422, 'Backup is missing conversations.');
        abort_unless(count($backup['conversations']) <= self::MAX_CONVERSATIONS_PER_IMPORT, 422, 'Backup contains too many conversations.');

        $totalBytes = 0;
        foreach ($backup['conversations'] as $entry) {
            abort_unless(is_array($entry) && filled($entry['encryptedHeader'] ?? null) && filled($entry['encryptedBody'] ?? null), 422, 'A conversation in the backup is missing encrypted data.');
            abort_unless(is_string($entry['encryptedHeader']) && is_string($entry['encryptedBody']), 422, 'A conversation in the backup has invalid encrypted data.');
            abort_unless(strlen($entry['encryptedHeader']) <= ConversationController::MAX_ENCRYPTED_HEADER_BYTES, 422, 'A conversation header in the backup is too large.');
            abort_unless(strlen($entry['encryptedBody']) <= ConversationController::MAX_ENCRYPTED_BODY_BYTES, 422, 'A conversation body in the backup is too large.');
            abort_unless(preg_match('/^[A-Za-z0-9+\/=]+$/', $entry['encryptedHeader']) === 1, 422, 'A conversation header in the backup is not valid encrypted data.');
            abort_unless(preg_match('/^[A-Za-z0-9+\/=]+$/', $entry['encryptedBody']) === 1, 422, 'A conversation body in the backup is not valid encrypted data.');
            $totalBytes += strlen($entry['encryptedHeader']) + strlen($entry['encryptedBody']);
        }

        abort_unless($totalBytes <= ConversationController::FREE_STORAGE_LIMIT_BYTES, 422, 'Backup exceeds the hosted vault storage limit.');

        $user = $request->user();

        DB::transaction(function () use ($user, $backup) {
            MemoriqConversation::query()
                ->where('user_id', $user->id)
                ->each(function (MemoriqConversation $conversation) {
                    $this->deleteStoredBody($conversation);
                    $conversation->delete();
                });

            $user->forceFill([
                'encryption_key_jwk' => data_get($backup, 'encryption.encryptedMek'),
                'encryption_key_salt' => data_get($backup, 'encryption.salt'),
            ])->save();

            foreach ($backup['conversations'] as $entry) {
                $this->importConversation($user->id, $entry);
            }
        });

        return response()->json([
            'status' => 'imported',
            'conversationCount' => count($backup['conversations']),
            'appVersion' => data_get($backup, 'app.version'),
            'exportedAt' => $backup['exportedAt'] ?? null,
        ]);
    }

    private function importConversation(int $userId, array $entry): void
    {
        MemoriqConversation::create([
            'user_id' => $userId,
            'payload_version' => (int) ($entry['payloadVersion'] ?? 2),
            'encrypted_header' => $entry['encryptedHeader'],
            'encrypted_body' => $entry['encryptedBody'],
            'body_storage_disk' => null,
            'body_storage_path' => null,
            'body_bytes' => (int) ($entry['bodyBytes'] ?? strlen($entry['encryptedBody'])),
            'created_at' => filled($entry['createdAt'] ?? null) ? Carbon::parse($entry['createdAt']) : now(),
            'updated_at' => filled($entry['updatedAt'] ?? null) ? Carbon::parse($entry['updatedAt']) : now(),
        ]);
    }

    private function encryptedBody(MemoriqConversation $conversation): string
    {
        if ($conversation->encrypted_body !== null) {
            return $conversation->encrypted_body;
        }

        $missingBodyMessage = 'Encrypted conversation body is missing from server storage. Please re-import from your original backup after deploying the latest version.';

        abort_unless($conversation->body_storage_disk && $conversation->body_storage_path, 410, $missingBodyMessage);
        abort_unless(Storage::disk($conversation->body_storage_disk)->exists($conversation->body_storage_path), 410, $missingBodyMessage);

        $body = Storage::disk($conversation->body_storage_disk)->get($conversation->body_storage_path);

        abort_if($body === null, 410, $missingBodyMessage);

        return $body;
    }

    private function deleteStoredBody(MemoriqConversation $conversation): void
    {
        if (! $conversation->body_storage_disk || ! $conversation->body_storage_path) {
            return;
        }

        Storage::disk($conversation->body_storage_disk)->delete($conversation->body_storage_path);
    }

    private function backupVersion(): string
    {
        $configured = config('app.version');
        if (filled($configured)) {
            return $configured;
        }

        return $this->gitHash() ?: 'dev';
    }

    private function gitHash(): ?string
    {
        $headPath = base_path('.git/HEAD');
        if (! is_file($headPath)) {
            return null;
        }

        $head = trim((string) file_get_contents($headPath));
        if (str_starts_with($head, 'ref: ')) {
            $refPath = base_path('.git/'.trim(substr($head, 5)));
            return is_file($refPath) ? substr(trim((string) file_get_contents($refPath)), 0, 12) : null;
        }

        return substr($head, 0, 12);
    }

    private function safeFilenamePart(string $value): string
    {
        return trim((string) preg_replace('/[^A-Za-z0-9_.-]+/', '-', $value), '-') ?: 'dev';
    }
}
