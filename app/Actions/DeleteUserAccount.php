<?php

namespace App\Actions;

use App\Models\MemoriqConversation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class DeleteUserAccount
{
    /**
     * Delete the user and all hosted account data.
     *
     * Does not write the user's email to application logs. A minimal audit entry
     * with user ID and timestamp may remain in server logs until normal rotation.
     */
    public function delete(User $user): void
    {
        $userId = (int) $user->id;
        $userEmail = $user->email;

        DB::transaction(function () use ($userId, $userEmail): void {
            MemoriqConversation::query()
                ->where('user_id', $userId)
                ->each(function (MemoriqConversation $conversation): void {
                    $this->deleteStoredBody($conversation);
                    $conversation->delete();
                });

            DB::table('password_reset_tokens')->where('email', $userEmail)->delete();
            DB::table('sessions')->where('user_id', $userId)->delete();
            DB::table('personal_access_tokens')
                ->where('tokenable_type', User::class)
                ->where('tokenable_id', $userId)
                ->delete();

            $deleted = User::query()->whereKey($userId)->delete();

            if ($deleted !== 1) {
                throw new RuntimeException('Account row could not be deleted.');
            }
        });

        Log::info('Memoriq account deleted', [
            'user_id' => $userId,
            'deleted_at' => now()->toIso8601String(),
        ]);
    }

    private function deleteStoredBody(MemoriqConversation $conversation): void
    {
        if (! $conversation->body_storage_disk || ! $conversation->body_storage_path) {
            return;
        }

        Storage::disk($conversation->body_storage_disk)->delete($conversation->body_storage_path);
    }
}
