<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;

// Halaman login (GET)
Route::get('/login', function () {
    return view('login');   // nama view: resources/views/login.blade.php
})->name('login');

// Proses login (POST)
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

// Halaman default root diarahkan ke login (atau welcome, pilih salah satu)
Route::get('/', function () {
    return redirect()->route('login');   // atau return view('welcome');
});

// Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register']);