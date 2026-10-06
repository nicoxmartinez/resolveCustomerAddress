<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\TipoClienteController;
use App\Http\Controllers\TipoDocumentoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/tipoCliente/lista', TipoClienteController::class);
Route::get('/tipoDocumento/lista', TipoDocumentoController::class);
Route::get('/cliente/lista', [ClienteController::class, 'index']);
Route::get('/cliente/id/{id}', [ClienteController::class, 'show']);
Route::post('/cliente/crear', [ClienteController::class, 'store']);
Route::put('/cliente/modificar', [ClienteController::class, 'update']);
