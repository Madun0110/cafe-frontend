<?php

use Illuminate\Support\Facades\Route;

// ===============================================
// IMPORT CONTROLLER BERDASARKAN STRUKTUR FOLDER TERBARU
// ===============================================

// Controller Publik (Berada di App\Http\Controllers\Customer\)
use App\Http\Controllers\Customer\CustomerMenuController;
use App\Http\Controllers\Customer\CustomerOrderController;

// Controller Admin (Berada di App\Http\Controllers\Admin\)
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminOrderController;

// ==================== PUBLIC ROUTES ====================
// Menggunakan CustomerMenuController untuk rute menu publik
Route::get('/', [CustomerMenuController::class, 'index'])->name('menu');

// Cart Routes
Route::post('/cart/add', [CustomerMenuController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CustomerMenuController::class, 'getCart'])->name('cart.get');
Route::post('/cart/remove', [CustomerMenuController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/clear', [CustomerMenuController::class, 'clearCart'])->name('cart.clear');

// Order Routes (Public) - Menggunakan CustomerOrderController
Route::get('/order', [CustomerOrderController::class, 'showOrderForm'])->name('order.form');
Route::post('/order', [CustomerOrderController::class, 'store'])->name('order.store');

// ---

// ========================================================
// RUTE ADMINISTRATOR (AUTENTIKASI & TERPROTEKSI)
// ========================================================

// 1. Rute Admin Authentication (TIDAK ADA MIDDLEWARE)
Route::prefix('admin')->name('admin.')->group(function () {
    // Menampilkan formulir login (showLoginForm ada di AdminController)
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');

    // Memproses POST data login (login ada di AdminController)
    Route::post('login', [AdminController::class, 'login'])->name('login.submit');
});


// 2. Rute Admin Terproteksi (WAJIB LOGIN)
Route::prefix('admin')->name('admin.')->group(function () {

    // Rute Logout (harus POST)
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');

    // Dashboard (AdminController)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Products Management (AdminController)
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::post('/products', [AdminController::class, 'createProduct'])->name('products.store');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');

    // Kitchen Monitor (AdminController)
    Route::get('/kitchen', [AdminController::class, 'kitchen'])->name('kitchen');

    // ====== KELOLA KATEGORI (AdminMenuController) ======
    Route::get('/categories', [AdminMenuController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminMenuController::class, 'createCategory'])->name('categories.store');
    Route::put('/categories/{id}', [AdminMenuController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminMenuController::class, 'deleteCategory'])->name('categories.delete');
    // ========================================================

    // ==================== ORDER HISTORY ROUTES (AdminOrderController) ====================
    Route::resource('orders', AdminOrderController::class)->only([
        'index', 'show'
    ]);

    Route::patch('/orders/{orderId}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update_status');
});

// ==================== API TEST ROUTES (TIDAK BERUBAH) ====================
// Perlu diingat, di sini kita harus menggunakan jalur namespace lengkap untuk ApiService
Route::get('/test-api', function () {
    $apiService = new \App\Services\ApiService();
    $result = $apiService->testConnection();
    return response()->json($result);
});

Route::get('/api-status', function () {
    $apiService = new \App\Services\ApiService();
    $status = $apiService->getApiStatus();
    return response()->json($status);
});

Route::get('/system-summary', function () {
    $apiService = new \App\Services\ApiService();
    $summary = $apiService->getSystemSummary();
    return response()->json($summary);
});
