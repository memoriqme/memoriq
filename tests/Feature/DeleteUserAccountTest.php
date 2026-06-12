<?php

namespace Tests\Feature;

use App\Models\MemoriqConversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DeleteUserAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_delete_their_account_and_data(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('correct-password'),
            'encryption_key_jwk' => 'encrypted-mek',
            'encryption_key_salt' => 'salt',
        ]);

        MemoriqConversation::create([
            'user_id' => $user->id,
            'payload_version' => 2,
            'encrypted_header' => base64_encode('header'),
            'encrypted_body' => base64_encode('body'),
            'body_bytes' => 10,
        ]);

        $this->actingAs($user, 'web');

        $response = $this->postJson('/user/delete', [
            'password' => 'correct-password',
            'delete_confirmed' => true,
        ]);

        $response->assertOk()->assertJson(['status' => 'deleted']);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseCount('memoriq_conversations', 0);
    }
}
