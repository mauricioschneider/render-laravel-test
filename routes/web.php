<?php

use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use App\Support\Testing\TestUser;

// Laravel's built-in authentication routes for email verification
Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware(['auth'])
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, '__invoke'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// A simple route to test the generation of the verification link.
Route::get('/generate-verification-link', function () {
    $testUser = new TestUser(id: 1, email: 'john.doe@example.com');
    $testUser->sendEmailVerificationNotification();
    return "Verification link has been generated and logged.";
});

// A protected route that requires a verified email
Route::get('/dashboard', function () {
    return "Welcome to the dashboard!";
})->middleware(['auth', 'verified'])->name('dashboard');