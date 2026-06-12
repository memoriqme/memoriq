<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EncryptionController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'configured' => filled($user->encryption_key_jwk) && filled($user->encryption_key_salt),
            'keyData' => $user->encryption_key_jwk,
            'salt' => $user->encryption_key_salt,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'encryptedMek' => ['required', 'string', 'regex:/^[A-Za-z0-9+\/=]+$/', 'max:4096'],
            'salt' => ['required', 'string', 'regex:/^[A-Za-z0-9+\/=]+$/', 'max:256'],
        ]);

        $user = $request->user();
        $user->forceFill([
            'encryption_key_jwk' => $validated['encryptedMek'],
            'encryption_key_salt' => $validated['salt'],
        ])->save();

        return response()->json(['status' => 'success']);
    }
}
