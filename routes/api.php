<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnderecoController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('usuarios/{id}/enderecos', [EnderecoController::class, 'index']);
    Route::post('usuarios/{id}/enderecos', [EnderecoController::class, 'store']);
    Route::put('enderecos/{id}', [EnderecoController::class, 'update']);
    Route::delete('enderecos/{id}', [EnderecoController::class, 'destroy']);
});