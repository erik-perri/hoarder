<?php

use App\Http\Controllers\Auth\CheckStatusController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/verify-email', [VerifyEmailController::class, 'notice'])->name('verification.notice');

    Route::post('/verify-email', [VerifyEmailController::class, 'store'])
         ->name('verification.send')
         ->middleware(['throttle:6,1']);

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, 'verify'])
         ->name('verification.verify')
         ->middleware(['signed', 'throttle:6,1']);
});

Route::get('/auth-status', [CheckStatusController::class, 'status'])->name('auth-status');
