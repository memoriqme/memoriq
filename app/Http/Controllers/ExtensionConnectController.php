<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExtensionConnectController extends Controller
{
    public function show(Request $request)
    {
        if (! $request->user()) {
            return redirect('/login?redirect=/extension/connect');
        }

        if (method_exists($request->user(), 'hasVerifiedEmail') && ! $request->user()->hasVerifiedEmail()) {
            return redirect('/email/verify');
        }

        $request->user()->tokens()
            ->where('name', 'Memoriq browser extension')
            ->delete();

        $token = $request->user()->createToken('Memoriq browser extension', [
            'extension:read',
            'extension:write',
        ], now()->addDays(30))->plainTextToken;

        return view('extension-connect', [
            'payload' => [
                'type' => 'MEMORIQ_EXTENSION_TOKEN',
                'token' => $token,
                'user' => [
                    'id' => $request->user()->id,
                    'email' => $request->user()->email,
                ],
                'appBaseUrl' => $request->getSchemeAndHttpHost(),
            ],
        ]);
    }
}
