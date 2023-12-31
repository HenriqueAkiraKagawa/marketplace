<?php

use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\TipoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
                                                             
//Rotas Tipos
Route::middleware('api')->prefix('tipos')->group(function ()   {
    Route::get('/', [TipoController::class, 'index']);
    Route::post('/', [TipoController::class, 'store']);
    Route::get('/{tipo}', [TipoController::class, 'show']);
    Route::put('/{tipo}', [TipoController::class, 'update']);
    Route::delete('/{tipo}', [TipoController::class, 'destroy']);
});

//17/08/23
//Rotas Produto
Route::middleware('api')->prefix('produtos')->group(function ()   {
    Route::get('/', [ProdutoController::class, 'index']);
    Route::post('/', [ProdutoController::class, 'store']);
    Route::get('/{produto}', [ProdutoController::class, 'show']);
    Route::put('/{produto}', [ProdutoController::class, 'update']);
    Route::delete('/{produto}', [ProdutoController::class, 'destroy']);
});

//23/08/23
//Rotas Avaliacao
Route::middleware('api')->prefix('avaliacaos')->group(function ()   {
    Route::get('/', [AvaliacaoController::class, 'index']);
    Route::post('/', [AvaliacaoController::class, 'store']);
    Route::delete('/{avaliacao}', [AvaliacaoController::class, 'destroy']);
});

//25/08/23
//Rotas Marketplace
Route::middleware('api')->prefix('marketplaces')->group(function ()   {
    Route::get('/', [MarketplaceController::class, 'index']);
    Route::post('/', [MarketplaceController::class, 'store']);
    Route::get('/{marketplace}', [MarketplaceController::class, 'show']);
    Route::put('/{marketplace}', [MarketplaceController::class, 'update']);
    Route::delete('/{marketplace}', [MarketplaceController::class, 'destroy']);
});
