<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public Guest Routes (No Registration/Login Required - NFR-02)
Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/user', [UserController::class, 'index'])->name('user.home');
Route::get('/katalog', [UserController::class, 'katalog'])->name('katalog');
Route::get('/tentang-kami', [UserController::class, 'tentang'])->name('tentang');
Route::get('/transparansi', [UserController::class, 'transparansi'])->name('transparansi');

// Products (Public read-only)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Public Guest Orders & Checkout (FR-06 / NFR-02)
Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');

// Public Donations Form (FR-04)
Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');

// Reviews
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

// Admin Auth Only (No public registration per NFR-02)
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authLogin'])
    ->middleware(['guest', 'throttle:login'])
    ->name('login.attempt');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::post('/refresh-token', [AuthController::class, 'refreshToken'])
    ->name('token.refresh');

// Admin Back-Office Panel (FR-06 / NFR-04)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::put('/admin/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

    Route::get('/admin/purchases', [PurchaseController::class, 'index'])->name('admin.purchases.index');
    Route::patch('/admin/purchases/{purchase}/status', [PurchaseController::class, 'updateStatus'])->name('admin.purchases.update-status');

    Route::get('/admin/donations', [DonationController::class, 'index'])->name('admin.donations.index');
    Route::patch('/admin/donations/{donation}/status', [DonationController::class, 'updateStatus'])->name('admin.donations.update-status');
});

// --- Halaman statis frontend ---
Route::view('/dampak', 'user.pages.dampak')->name('dampak');
Route::view('/cara-donasi', 'user.pages.cara-donasi')->name('cara-donasi');
Route::view('/kontak', 'user.pages.kontak')->name('kontak');

