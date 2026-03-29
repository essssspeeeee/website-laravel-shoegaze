<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Models\Product;


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
            $totalProducts = \App\Models\Product::count();
            $totalOrders   = \App\Models\Transaction::count();
            $totalUsers    = \App\Models\User::count();
            $totalRevenue  = \App\Models\Transaction::where('status','valid')->sum('total');
            $latestOrders  = \App\Models\Transaction::with('user')
                                ->orderBy('id','desc')
                                ->limit(3)
                                ->get();
            return view('dashboard.admin', compact(
                'totalProducts','totalOrders','totalUsers','totalRevenue','latestOrders'
            ));
        })->name('admin.dashboard');

        // resourceful product management for admins
        Route::namespace('App\\Http\\Controllers\\Admin')
            ->prefix('admin')
            ->name('admin.')
            ->group(function () {
                Route::resource('products', 'ProductController')->except(['show']);
                Route::resource('orders', 'OrderController')->only(['index','update']);
                Route::resource('users', 'UserController')->except(['show']);
                Route::get('history', 'HistoryController@index')->name('history');
            });
    });

    // 2. Dashboard Petugas
    Route::middleware(['role:petugas'])->group(function () {
        Route::get('/staff/dashboard', function () {
            $totalProducts = \App\Models\Product::count();
            $totalOrders   = \App\Models\Transaction::count();
            $totalUsers    = \App\Models\User::count();
            $totalRevenue  = \App\Models\Transaction::where('status','valid')->sum('total');
            $latestOrders  = \App\Models\Transaction::with('user')
                                ->orderBy('id','desc')
                                ->limit(3)
                                ->get();
            return view('dashboard.petugas', compact(
                'totalProducts','totalOrders','totalUsers','totalRevenue','latestOrders'
            ));
        })->name('staff.dashboard');

        // Kelola produk untuk petugas (sama seperti admin)
        Route::namespace('App\\Http\\Controllers\\Admin')
            ->prefix('staff')
            ->name('staff.')
            ->group(function () {
                Route::resource('products', 'ProductController')->except(['show']);
                // permit petugas to view orders as well
                Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
                Route::patch('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'update'])->name('orders.update');
                // riwayat pesanan untuk petugas (sama seperti admin)
                Route::get('history', 'HistoryController@index')->name('history');

                // sales report page for petugas with simple statistics
                Route::get('sales', function () {
                    $total     = \App\Models\Transaction::count();
                    $valid     = \App\Models\Transaction::where('status', 'valid')->count();
                    $waiting   = \App\Models\Transaction::where('status', 'waiting')->count();
                    $rejected  = \App\Models\Transaction::where('status', 'rejected')->count();
                    return view('dashboard.staff_sales', compact('total','valid','waiting','rejected'));
                })->name('sales');
            });
    });

    // 3. Dashboard User & Fitur Belanja (Khusus Role User)
    Route::middleware(['role:user'])->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        
        // Fitur Keranjang
        Route::get('/cart', [CartController::class, 'index'])->name('cart'); 
        Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/remove/{id}/{size?}', [CartController::class, 'remove'])->name('cart.remove');
        // Checkout page
        Route::match(['get', 'post'], '/checkout', [CartController::class, 'checkout'])->name('checkout');
        Route::get('/orders/{id}', [CartController::class, 'showOrder'])->name('orders.show');
        Route::post('/orders/{id}/upload', [CartController::class, 'uploadProof'])->name('orders.upload');
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
        $product = Product::findOrFail($id);
        return view('dashboard.product_detail', compact('product'));
    })->name('product.detail');

});

// Redirect jika akses /dashboard langsung
Route::get('/dashboard', [HomeController::class, 'index']);