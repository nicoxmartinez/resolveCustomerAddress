<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::get('registro', [AuthController::class, 'create'])->name('register');
Route::post('registro', [AuthController::class, 'store'])->name('register.store');
Route::post('login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('logout', [AuthController::class, 'destroy'])->name('logout');