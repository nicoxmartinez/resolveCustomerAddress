<?php

use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;

/*
Route::get('/', function () {
    return view('index');
});
*/

Route::view('/', 'index')->name('index');
Route::view('login', 'login')->name('login');
Route::view('registro', 'register')->name('register');
Route::get('buscar', [ClienteController::class, 'index'])->name('search');
Route::get('crear', [ClienteController::class,'create'])->name('create');
Route::get('editar/{id}', [ClienteController::class, 'edit'])->name('edit');