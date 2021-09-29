<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EncomendaController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\PromocaoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|


*/

Auth::routes();

Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/store', [UserController::class, 'store'])->name('register');



Route::group(['prefix' => '/role'], function () {
    Route::post('/defineCargo', [UserController::class, 'defineCargo']);
    Route::get('/', [RoleController::class, 'index']);
    Route::get('/{id}', [RoleController::class, 'show']);
    Route::post('/', [RoleController::class, 'store']);
    Route::put('/{id}', [RoleController::class, 'update']);
    Route::delete('/{id}', [RoleController::class, 'destroy']);
});
Route::group(['prefix' => '/permission'], function () {
    Route::get('/', [PermissionController::class, 'index']);
    Route::get('/{id}', [PermissionController::class, 'show']);
    Route::post('/', [PermissionController::class, 'store']);
    Route::put('/{id}', [PermissionController::class, 'update']);
    Route::delete('/{id}', [PermissionController::class, 'destroy']);
});



Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::group(['prefix' => '/categoria'], function () {
        Route::get('', [CategoriaController::class, 'index'])->middleware('permission:mostrar categorias');
        Route::post('', [CategoriaController::class, 'store'])->middleware('permission:cadastrar categoria');
        Route::group(['prefix' => '{id}'], function () {
            Route::get('', [CategoriaController::class, 'show'])->middleware('permission:mostrar categoria');
            Route::put('', [CategoriaController::class, 'update'])->middleware('permission:editar categoria');
            Route::delete('', [CategoriaController::class, 'destroy'])->middleware('permission:excluir categoria');
        });
    });

    Route::group(['prefix' => '/produto'], function () {
        Route::group(['prefix' => '{id}'], function () {
            Route::get('', [ProdutoController::class, 'show'])->middleware('permission:mostrar produto');
            Route::put('', [ProdutoController::class, 'update'])->middleware('permission:editar produto');
            Route::delete('', [ProdutoController::class, 'destroy'])->middleware('permission:excluir produto');
        });
        Route::get('', [ProdutoController::class, 'index'])->middleware('permission:mostrar produtos');
        Route::post('', [ProdutoController::class, 'store'])->middleware('permission:cadastrar produto');
    });

    Route::group(['prefix' => '/cliente'], function () {
        Route::group(['prefix' => '{id}'], function(){
            Route::get('', [ClienteController::class, 'show'])->middleware('permission:mostrar cliente');
            Route::put('', [ClienteController::class, 'update'])->middleware('permission:editar cliente');
            Route::post('', [ClienteController::class, 'atualizar'])->middleware('permission:editar cliente');
            Route::delete('', [ClienteController::class, 'destroy'])->middleware('permission:excluir cliente');
        });
        Route::get('', [ClienteController::class, 'index'])->middleware('permission:mostrar clientes');
        Route::post('', [ClienteController::class, 'store'])->middleware('permission:cadastrar cliente');
    });

    Route::group(['prefix' => '/promocao'], function () {
        Route::group(['prefix' => '{id}'], function(){
            Route::get('', [PromocaoController::class, 'show'])->middleware('permission:mostrar promocao');
            Route::put('', [PromocaoController::class, 'update'])->middleware('permission:editar promocao');
            Route::delete('', [PromocaoController::class, 'destroy'])->middleware('permission:excluir promocao');
        });
        Route::get('', [PromocaoController::class, 'index'])->middleware('permission:mostrar promocaos');
        Route::post('', [PromocaoController::class, 'store'])->middleware('permission:cadastrar promocao');
    });

    Route::group(['prefix' => '/venda'], function () {
        Route::group(['prefix' => '{id}'], function(){
            Route::get('', [VendaController::class, 'show'])->middleware('permission:mostrar venda');
            Route::put('', [VendaController::class, 'update'])->middleware('permission:atualizar venda');
        });
        Route::get('', [VendaController::class, 'index'])->middleware('permission:mostrar vendas');
        Route::post('', [VendaController::class, 'store'])->middleware('permission:cadastrar venda');
    });

    Route::group(['prefix' => '/encomenda'], function () {
        Route::group(['prefix' => '{id}'], function(){
            Route::get('', [EncomendaController::class, 'show'])->middleware('permission:mostrar encomenda');
            Route::put('', [EncomendaController::class, 'update'])->middleware('permission:atualizar encomendas');
        });
        Route::get('', [EncomendaController::class, 'index'])->middleware('permission:mostrar encomendas');
        Route::post('', [EncomendaController::class, 'store'])->middleware('permission:cadastrar encomendas');
    });
});
