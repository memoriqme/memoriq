<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\EncryptionController;
use App\Http\Controllers\VaultBackupController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the framework and are assigned to the "api"
| middleware group.
|
*/

Route::middleware(['auth:sanctum', 'throttle:memoriq-api', 'abilities:extension:read'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'verified', 'throttle:memoriq-api'])->group(function () {
    Route::get('/user/encryption-key', [EncryptionController::class, 'show'])
        ->middleware('abilities:extension:read');
    Route::post('/user/encryption-key', [EncryptionController::class, 'store'])
        ->middleware(['abilities:vault:manage', 'throttle:memoriq-destructive']);

    Route::get('/vault/export', [VaultBackupController::class, 'export'])
        ->middleware('abilities:vault:manage');
    Route::post('/vault/import', [VaultBackupController::class, 'import'])
        ->middleware(['abilities:vault:manage', 'throttle:memoriq-destructive']);

    Route::get('/conversations', [ConversationController::class, 'index'])
        ->middleware('abilities:extension:read');
    Route::get('/conversations/sync', [ConversationController::class, 'syncStatus'])
        ->middleware('abilities:extension:read');
    Route::get('/conversations/storage', [ConversationController::class, 'storage'])
        ->middleware('abilities:extension:read');
    Route::post('/conversations', [ConversationController::class, 'store'])
        ->middleware(['abilities:extension:write', 'throttle:memoriq-write']);
    Route::delete('/conversations', [ConversationController::class, 'destroyAll'])
        ->middleware(['abilities:vault:manage', 'throttle:memoriq-destructive']);
    Route::delete('/conversations/{conversation}', [ConversationController::class, 'destroy'])
        ->middleware(['abilities:vault:manage', 'throttle:memoriq-destructive']);
    Route::patch('/conversations/{conversation}/header', [ConversationController::class, 'updateHeader'])
        ->middleware(['abilities:extension:write', 'throttle:memoriq-write']);
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])
        ->middleware('abilities:extension:read');

    Route::get('/extension/me', function (Request $request) {
        return response()->json([
            'user' => $request->user(),
            'encryptionConfigured' => filled($request->user()->encryption_key_jwk) && filled($request->user()->encryption_key_salt),
        ]);
    })->middleware('abilities:extension:read');
    Route::post('/extension/conversations', [ConversationController::class, 'store'])
        ->middleware(['abilities:extension:write', 'throttle:memoriq-write']);
});
