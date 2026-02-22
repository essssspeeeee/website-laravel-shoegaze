<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController; // 1. PASTIKAN INI ADA

// --- Public Routes ---
Route::get('/', function () {
    return redirect()->route('login');
});

// Search Route
Route::get('/search', [SearchController::class, 'index'])->name('search');

// --- Auth Routes ---
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --- Middleware Protected Routes ---
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('dashboard.admin');
        })->name('admin.dashboard');
    });

    // Dashboard Petugas
    Route::middleware(['role:petugas'])->group(function () {
        Route::get('/staff/dashboard', function () {
            return view('dashboard.petugas');
        })->name('staff.dashboard');
    });

    // Dashboard User
    Route::middleware(['role:user'])->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');
    });

    // Profile & Product
    Route::get('/dashboard/profile', function () {
        return view('dashboard.profile');
    })->name('profile');
    
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.custom.update');

    Route::get('/product/detail/{id}', function ($id) {
        return view('dashboard.product_detail');
    })->name('product.detail');

    // Keranjang / Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart'); // Diarahkan ke controller
    
    // 2. PERBAIKAN: Route ini sekarang memanggil CartController
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
});

// Route Dashboard General
Route::get('/dashboard', [HomeController::class, 'index']);