<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;

// --- Public Routes ---
Route::get('/', function () {
    return redirect()->route('login');
});

// Search Route
Route::get('/search', [SearchController::class, 'index'])->name('search');

// --- Auth Routes (Login & Register) ---
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --- Middleware Protected Routes (Harus Login) ---
Route::middleware(['auth'])->group(function () {
    
    // 1. Dashboard Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('dashboard.admin');
        })->name('admin.dashboard');
    });

    // 2. Dashboard Petugas
    Route::middleware(['role:petugas'])->group(function () {
        Route::get('/staff/dashboard', function () {
            return view('dashboard.petugas');
        })->name('staff.dashboard');
    });

    // 3. Dashboard User & Fitur Belanja (Khusus Role User)
    Route::middleware(['role:user'])->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        
        // Fitur Keranjang
        Route::get('/cart', [CartController::class, 'index'])->name('cart'); 
        Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    });

    // 4. Fitur Umum (Bisa diakses semua role setelah login)
    
    // Route Kelola Akun (Profile) - Ini yang tadi bikin error
    Route::get('/dashboard/profile', function () {
        return view('dashboard.profile');
    })->name('profile');
    
    // Update Profile
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Detail Produk
    Route::get('/product/detail/{id}', function ($id) {
        return view('dashboard.product_detail'); 
    })->name('product.detail');

});

// Redirect jika akses /dashboard langsung
Route::get('/dashboard', [HomeController::class, 'index']);