use App\Http\Controllers\NivelAcessoController;

Route::get('/niveis-acesso', [NivelAcessoController::class, 'index']);
Route::post('/niveis-acesso', [NivelAcessoController::class, 'store']);
Route::get('/niveis-acesso/{id}', [NivelAcessoController::class, 'show']);
Route::put('/niveis-acesso/{id}', [NivelAcessoController::class, 'update']);
Route::delete('/niveis-acesso/{id}', [NivelAcessoController::class, 'destroy']);