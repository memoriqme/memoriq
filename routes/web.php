<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExtensionConnectController;


// AUTH
require __DIR__.'/web_auth.php';


Route::get('/manifest.webmanifest', function () {
    return response()->file(public_path('manifest.webmanifest'), [
        'Content-Type' => 'application/manifest+json',
    ]);
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('welcome');
});

Route::get('/settings', function () {
    return view('welcome');
});

Route::get('/privacy', function () {
    return view('welcome');
});

Route::get('/terms', function () {
    return view('welcome');
});

Route::get('/share', function () {
    return view('welcome');
});

Route::get('/extension/connect', [ExtensionConnectController::class, 'show'])
    ->name('extension.connect');
