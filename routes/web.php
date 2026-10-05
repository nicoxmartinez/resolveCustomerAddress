<?php

use Illuminate\Support\Facades\Route;

/*
Route::get('/', function () {
    return view('index');
});
*/

Route::view('/', 'index')->name('index');
Route::view('buscar', 'search')->name('search');
Route::view('crear', 'create')->name('create');
Route::view('migraciones', 'migration')->name('migration');
Route::view('usuarios', 'users')->name('users');