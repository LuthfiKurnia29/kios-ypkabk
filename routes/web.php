<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SiteContentController;
use App\Http\Controllers\UnboxingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public Guest Routes (No Registration/Login Required - NFR-02)
Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/user', [UserController::class, 'index'])->name('user.home');
Route::get('/katalog', [UserController::class, 'katalog'])->name('katalog');
Route::get('/tentang-kami', [UserController::class, 'tentang'])->name('tentang');
Route::get('/transparansi', [UserController::class, 'transparansi'])->name('transparansi');
Route::get('/dampak', [UserController::class, 'dampak'])->name('dampak');
Route::get('/galeri-unboxing', [UserController::class, 'unboxing'])->name('unboxing');
Route::view('/cara-donasi', 'user.pages.cara-donasi')->name('cara-donasi');
Route::get('/kontak', fn () => view('user.pages.kontak'))->name('kontak');
Route::post('/kontak', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

// Products (Public read-only)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Public Guest Orders & Checkout (FR-06 / NFR-02)
Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
Route::post('/purchases/{purchase}/payment-proof', [PurchaseController::class, 'uploadPaymentProof'])->name('purchases.upload-proof');

// Public Donations Form (FR-04)
Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');

// Reviews
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::redirect('/tulis-ulasan', '/')->name('tulis-ulasan');
Route::get('/ulasan/{token}', [ReviewController::class, 'create'])
    ->middleware('throttle:30,1')
    ->name('reviews.create');
Route::post('/ulasan/{token}', [ReviewController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('reviews.submit');

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

    Route::get('/admin/products', [ProductController::class, 'adminIndex'])->name('admin.products.index');
    Route::get('/admin/products/{product}', [ProductController::class, 'adminShow'])->name('admin.products.show');
    Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::put('/admin/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::delete('/admin/products/{product}/images/{image}', [ProductImageController::class, 'destroy'])->name('admin.products.images.destroy');
    Route::delete('/admin/products/{product}/cover-image', [ProductImageController::class, 'destroyCover'])->name('admin.products.cover.destroy');

    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    Route::get('/admin/purchases', [PurchaseController::class, 'index'])->name('admin.purchases.index');
    Route::get('/admin/purchases/export', [PurchaseController::class, 'export'])->name('admin.purchases.export');
    Route::patch('/admin/purchases/{purchase}/status', [PurchaseController::class, 'updateStatus'])->name('admin.purchases.update-status');
    Route::patch('/admin/purchases/{purchase}/shipping', [PurchaseController::class, 'updateShipping'])->name('admin.purchases.update-shipping');

    Route::get('/admin/donations', [DonationController::class, 'index'])->name('admin.donations.index');
    Route::get('/admin/donations/export', [DonationController::class, 'export'])->name('admin.donations.export');
    Route::patch('/admin/donations/{donation}/status', [DonationController::class, 'updateStatus'])->name('admin.donations.update-status');

    // Site Content Management
    Route::get('/admin/site-content', [SiteContentController::class, 'edit'])->name('admin.site-content.edit');
    Route::put('/admin/site-content', [SiteContentController::class, 'update'])->name('admin.site-content.update');
    Route::put('/admin/site-content/hero', [SiteContentController::class, 'updateHero'])->name('admin.site-content.update-hero');
    Route::put('/admin/site-content/abk', [SiteContentController::class, 'updateAbk'])->name('admin.site-content.update-abk');
    Route::put('/admin/site-content/info', [SiteContentController::class, 'updateInfo'])->name('admin.site-content.update-info');

    // Reviews Management (Curated)
    Route::get('/admin/reviews', [SiteContentController::class, 'reviewsIndex'])->name('admin.reviews.index');
    Route::patch('/admin/reviews/{review}/toggle', [SiteContentController::class, 'toggleReview'])->name('admin.reviews.toggle');
    Route::patch('/admin/reviews/{review}/order', [SiteContentController::class, 'updateReviewOrder'])->name('admin.reviews.order');
    Route::delete('/admin/reviews/{review}', [SiteContentController::class, 'destroyReview'])->name('admin.reviews.destroy');

    // Unboxing Management
    Route::get('/admin/unboxing', [UnboxingController::class, 'index'])->name('admin.unboxing.index');
    Route::post('/admin/unboxing', [UnboxingController::class, 'store'])->name('admin.unboxing.store');
    Route::put('/admin/unboxing/{unboxing}', [UnboxingController::class, 'update'])->name('admin.unboxing.update');
    Route::delete('/admin/unboxing/{unboxing}', [UnboxingController::class, 'destroy'])->name('admin.unboxing.destroy');
    Route::patch('/admin/unboxing/{unboxing}/toggle-visibility', [UnboxingController::class, 'toggleVisibility'])->name('admin.unboxing.toggle-visibility');

    // Reports / Expenses Management
    Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/admin/reports/income', [ReportController::class, 'income'])->name('admin.reports.income');
    Route::get('/admin/reports/export', [ReportController::class, 'export'])->name('admin.reports.export');
    Route::post('/admin/reports', [ReportController::class, 'store'])->name('admin.reports.store');
    Route::put('/admin/reports/{expense}', [ReportController::class, 'update'])->name('admin.reports.update');
    Route::delete('/admin/reports/{expense}', [ReportController::class, 'destroy'])->name('admin.reports.destroy');

    // Contact Messages Management
    Route::get('/admin/messages', [ContactController::class, 'adminIndex'])->name('admin.messages.index');
    Route::get('/admin/messages/{message}', [ContactController::class, 'adminShow'])->name('admin.messages.show');
    Route::patch('/admin/messages/{message}/toggle-read', [ContactController::class, 'toggleRead'])->name('admin.messages.toggle-read');
    Route::delete('/admin/messages/{message}', [ContactController::class, 'destroy'])->name('admin.messages.destroy');
});
