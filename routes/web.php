<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/user', [UserController::class, 'index'])->name('user.home');
Route::get('/katalog', [UserController::class, 'katalog'])->name('katalog');
