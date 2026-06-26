<?php
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\NivelAcessoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::post('/usuarios', [UsuarioController::class, 'store'])
    ->middleware('throttle:10,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
    Route::get('/usuarios/{id}/situacao', [UsuarioController::class, 'situacao']);
    Route::patch('/usuarios/{id}/verificar-email', [UsuarioController::class, 'verificarEmail']);

    Route::get('/usuarios/{id}/enderecos', [EnderecoController::class, 'index']);
    Route::post('/usuarios/{id}/enderecos', [EnderecoController::class, 'store']);
    Route::put('/enderecos/{id}', [EnderecoController::class, 'update']);
    Route::delete('/enderecos/{id}', [EnderecoController::class, 'destroy']);

    Route::get('/niveis-acesso', [NivelAcessoController::class, 'index']);
    Route::post('/niveis-acesso', [NivelAcessoController::class, 'store']);
    Route::put('/niveis-acesso/{id}', [NivelAcessoController::class, 'update']);
    Route::delete('/niveis-acesso/{id}', [NivelAcessoController::class, 'destroy']);
});