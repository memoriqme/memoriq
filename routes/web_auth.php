<?php

use App\Actions\DeleteUserAccount;
use App\Http\Controllers\CustomVerifyEmailController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| AUTH Routes
|--------------------------------------------------------------------------
| SPA GET routes + Fortify handles POST login/register/password/profile.
*/


Route::get('/account', function (Request $request) {
    $meta = [];
    $meta['title'] = "Memoriq - My Account";
    $meta['description'] = "Access your Memoriq account settings.";
    $meta['soc_title'] = "Memoriq Account";
    $meta['soc_description'] = "Access your Memoriq account settings.";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    return view('welcome', ['meta' => $meta]);

})->name('account');


Route::get('/login', function (Request $request) {
    $meta = [];
    $meta['title'] = "Memoriq - Login";
    $meta['description'] = "Log in to your Memoriq account.";
    $meta['soc_title'] = "Memoriq Login";
    $meta['soc_description'] = "Log in to your Memoriq account.";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    return view('welcome', ['meta' => $meta]);
})->name('login');


Route::get('/register', function (Request $request) {
    $meta = [];
    $meta['title'] = "Memoriq - Sign Up for a Free Account";
    $meta['description'] = "";
    $meta['soc_title'] = "Memoriq Sign Up";
    $meta['soc_description'] = "Sign up for a free Memoriq account.";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    return view('welcome', ['meta' => $meta]);
})->name('register');


Route::get('/email/verify', function (Request $request) {
    $meta = [];
    $meta['title'] = "Memoriq - Verify Your Email Address";
    $meta['description'] = "";
    $meta['soc_title'] = "Memoriq";
    $meta['soc_description'] = "Verify Your Email Address";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    return view('welcome', ['meta' => $meta]);
});

Route::get('/email/verified', function (Request $request) {
    $meta = [];
    $meta['title'] = "Memoriq - Email Verified";
    $meta['description'] = "";
    $meta['soc_title'] = "Memoriq";
    $meta['soc_description'] = "Email Verified";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    return view('welcome', ['meta' => $meta]);
});


$verificationLimiter = config('fortify.limiters.verification', '6,1');

Route::get('/email/verify/{id}/{hash}', [CustomVerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:' . $verificationLimiter])
    ->name('verification.verify');

    

Route::get('password/reset/{token}', function() {
    $meta = [];
    $meta['title'] = "Memoriq - Reset Password";
    $meta['description'] = "";
    $meta['soc_title'] = "Memoriq";
    $meta['soc_description'] = "Reset Password";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    return view('welcome', ['meta' => $meta]);
});


Route::get('/forgot-password', function (Request $request) {
    $meta = [];
    $meta['title'] = "Memoriq - Password Recovery";
    $meta['description'] = "";
    $meta['soc_title'] = "Memoriq";
    $meta['soc_description'] = "Password Recovery";
    $meta['image'] = "thumb-main.png";
    $meta['image_w'] = 1200;
    $meta['image_h'] = 1200;

    return view('welcome', ['meta' => $meta]);
})->name('forgotPassword');


Route::middleware(['auth:web', 'throttle:memoriq-destructive'])->post('/user/delete', function (Request $request, DeleteUserAccount $deleteUserAccount) {
    $request->validate([
        'password' => ['required', 'string'],
        'delete_confirmed' => ['accepted'],
    ]);

    $user = $request->user();

    if (! Hash::check($request->input('password'), $user->password)) {
        return response()->json(['message' => 'Wrong password'], 422);
    }

    $deleteUserAccount->delete($user);

    Auth::guard('web')->logout();

    Session::invalidate();
    Session::regenerateToken();

    return response()->json(['status' => 'deleted']);
});
