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
                Route::patch('orders/{order}/shipping', [\App\Http\Controllers\Admin\OrderController::class, 'kirimPesanan'])->name('orders.ship');
                Route::resource('users', 'UserController')->except(['show']);
                Route::get('history', 'HistoryController@index')->name('history');
                Route::get('sales-report', 'SalesReportController@index')->name('sales-report');
                Route::get('sales-report/download-pdf', 'SalesReportController@downloadPdf')->name('sales-report.download-pdf');
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
                Route::patch('orders/{order}/shipping', [\App\Http\Controllers\Admin\OrderController::class, 'kirimPesanan'])->name('orders.ship');
                // riwayat pesanan untuk petugas (sama seperti admin)
                Route::get('history', 'HistoryController@index')->name('history');

                // sales report page for petugas with simple statistics
                Route::get('sales', function (Illuminate\Http\Request $request) {
                    $month = $request->get('month', now()->format('m'));
                    $year = $request->get('year', now()->format('Y'));

                    $query = \App\Models\Transaction::whereYear('created_at', $year)
                                                   ->whereMonth('created_at', $month);

                    $total = (clone $query)->count();
                    $totalRevenue = (clone $query)->where('status', 'valid')->sum('total');
                    $valid = (clone $query)->where('status', 'valid')->count();
                    $waiting = (clone $query)->where('status', 'pending')->count();
                    $rejected = (clone $query)->where('status', 'cancelled')->count();

                    $transactions = \App\Models\Transaction::with('user')
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->orderBy('created_at', 'desc')
                        ->get();

                    // Chart data
                    $dailySales = \App\Models\Transaction::selectRaw('DATE(created_at) as date, SUM(total) as total')
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->where('status', 'valid')
                        ->groupBy('date')
                        ->orderBy('date')
                        ->pluck('total', 'date')
                        ->toArray();

                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int)$month, (int)$year);
                    $chartLabels = [];
                    $chartData = [];

                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $chartLabels[] = $day;
                        $chartData[] = $dailySales[$date] ?? 0;
                    }

                    return view('dashboard.staff_sales', compact(
                        'month', 'year', 'total', 'totalRevenue', 'valid', 'waiting', 'rejected',
                        'transactions', 'chartLabels', 'chartData'
                    ));
                })->name('sales');
                Route::get('sales/download-pdf', function (Illuminate\Http\Request $request) {
                    $month = $request->get('month', now()->format('m'));
                    $year = $request->get('year', now()->format('Y'));

                    $query = \App\Models\Transaction::whereYear('created_at', $year)
                                                   ->whereMonth('created_at', $month);

                    $total = (clone $query)->count();
                    $totalRevenue = (clone $query)->where('status', 'valid')->sum('total');
                    $valid = (clone $query)->where('status', 'valid')->count();
                    $waiting = (clone $query)->where('status', 'pending')->count();
                    $rejected = (clone $query)->where('status', 'cancelled')->count();

                    $transactions = \App\Models\Transaction::with('user')
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->orderBy('created_at', 'desc')
                        ->get();

                    $monthNames = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                    ];

                    $data = [
                        'month' => $monthNames[$month],
                        'year' => $year,
                        'totalTransactions' => $total,
                        'totalRevenue' => $totalRevenue,
                        'validCount' => $valid,
                        'pendingCount' => $waiting,
                        'rejectedCount' => $rejected,
                        'transactions' => $transactions
                    ];

                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf', $data);
                    $filename = "laporan_penjualan_{$month}_{$year}.pdf";

                    return $pdf->download($filename);
                })->name('sales.download-pdf');
            });
    });

    // 3. Dashboard User & Fitur Belanja (Khusus Role User)
    Route::middleware(['role:user'])->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        
        // Order history user
        Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');

        // Checkout page
        // Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
        // Route::post('/orders', [CartController::class, 'checkout'])->name('order.store');
        // Route::post('/checkout/address', [CartController::class, 'saveAddress'])->name('checkout.address.store');
        Route::get('/orders/{id}', [CartController::class, 'showOrder'])->name('orders.show');
        Route::post('/orders/{id}/upload', [CartController::class, 'uploadProof'])->name('orders.upload');
        Route::patch('/orders/{id}/receive', [CartController::class, 'confirmReceived'])->name('orders.receive');
        Route::patch('/orders/{id}/cancel', [\App\Http\Controllers\OrderController::class, 'cancelOrder'])->name('orders.cancel');
    });

    // Fitur Keranjang (untuk semua role yang sudah login - di luar user role group)
    Route::get('/cart', [CartController::class, 'index'])->name('cart'); 
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}/{size?}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout (untuk semua role yang sudah login)
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::get('/checkout/direct', [CartController::class, 'checkout'])->name('checkout.direct');
    Route::post('/orders', [CartController::class, 'checkout'])->name('order.store');
    Route::post('/checkout/address', [CartController::class, 'saveAddress'])->name('checkout.address.store');
    
    // Route Kelola Akun (Profile)
    Route::get('/dashboard/profile', function () {
        return view('dashboard.profile');
    })->name('profile');
    
    // Update Profile
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Detail Produk
    Route::get('/product/detail/{id}', function ($id) {
        $product = Product::findOrFail($id);
        // Ensure stock is decoded to array, handle if null or invalid JSON
        $product->stock = is_string($product->stock) ? json_decode($product->stock, true) : ($product->stock ?? []);
        if (!is_array($product->stock)) {
            $product->stock = [];
        }
        return view('dashboard.product_detail', compact('product'));
    })->name('product.detail');

}); // Tutup auth middleware group

// Redirect jika akses /dashboard langsung
Route::get('/dashboard', [HomeController::class, 'index']);