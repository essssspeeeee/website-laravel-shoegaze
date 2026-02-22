<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.custom.update');

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard routes
Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('dashboard.admin');
    })->name('admin.dashboard'); // Tambahkan nama
});

Route::middleware(['role:petugas'])->group(function () {
    Route::get('/staff/dashboard', function () {
        return view('dashboard.petugas');
    })->name('staff.dashboard'); // Tambahkan nama
});

Route::middleware(['role:user'])->group(function () {
    Route::get('/home', function () {
        return view('dashboard.user');
    })->name('home'); // Tambahkan nama
});

Route::get('/dashboard/profile', function () {
    return view('dashboard.profile'); // Ini mengarah ke folder dashboard/profile.blade.php
})->name('profile');

// Tambahkan di bagian bawah web.php
Route::get('/product/detail', function () {
    return view('dashboard.product_detail');
})->name('product.detail');