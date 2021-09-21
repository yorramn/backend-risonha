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
        Route::get('/', [CategoriaController::class, 'index'])->middleware('permission:mostrar categorias');
        Route::get('/{id}', [CategoriaController::class, 'show'])->middleware('permission:mostrar categoria');
        Route::post('/', [CategoriaController::class, 'store'])->middleware('permission:cadastrar categoria');
        Route::put('/{id}', [CategoriaController::class, 'update'])->middleware('permission:editar categoria');
        Route::delete('/{id}', [CategoriaController::class, 'destroy'])->middleware('permission:excluir categoria');
    });

    Route::group(['prefix' => '/produto'], function () {
        Route::get('/', [ProdutoController::class, 'index'])->middleware('permission:mostrar produtos');
        Route::get('/{id}', [ProdutoController::class, 'show'])->middleware('permission:mostrar produto');
        Route::post('/', [ProdutoController::class, 'store'])->middleware('permission:cadastrar produto');
        Route::put('/{id}', [ProdutoController::class, 'update'])->middleware('permission:editar produto');
        Route::delete('/{id}', [ProdutoController::class, 'destroy'])->middleware('permission:excluir produto');
    });

    Route::group(['prefix' => '/cliente'], function () {
        Route::get('/', [ClienteController::class, 'index'])->middleware('permission:mostrar clientes');
        Route::get('/{id}', [ClienteController::class, 'show'])->middleware('permission:mostrar cliente');
        Route::post('/store', [ClienteController::class, 'store'])->middleware('permission:cadastrar cliente');
        Route::put('/{id}', [ClienteController::class, 'update'])->middleware('permission:editar cliente');
        Route::delete('/{id}', [ClienteController::class, 'destroy'])->middleware('permission:excluir cliente');
    });

    Route::group(['prefix' => '/promocao'], function () {
        Route::get('/', [PromocaoController::class, 'index'])->middleware('permission:mostrar promocaos');
        Route::get('/{id}', [PromocaoController::class, 'show'])->middleware('permission:mostrar promocao');
        Route::post('/', [PromocaoController::class, 'store'])->middleware('permission:cadastrar promocao');
        Route::put('/{id}', [PromocaoController::class, 'update'])->middleware('permission:editar promocao');
        Route::delete('/{id}', [PromocaoController::class, 'destroy'])->middleware('permission:excluir promocao');
    });

    Route::group(['prefix' => '/venda'], function () {
        Route::get('/', [VendaController::class, 'index'])->middleware('permission:mostrar vendas');
        Route::get('/{id}', [VendaController::class, 'show'])->middleware('permission:mostrar venda');
        Route::post('/', [VendaController::class, 'store'])->middleware('permission:cadastrar venda');
        Route::put('/{id}', [VendaController::class, 'update'])->middleware('permission:atualizar venda');
    });

    Route::group(['prefix' => '/encomenda'], function () {
        Route::get('/', [EncomendaController::class, 'index'])->middleware('permission:mostrar encomendas');
        Route::get('/{id}', [EncomendaController::class, 'show'])->middleware('permission:mostrar encomenda');
        Route::post('/', [EncomendaController::class, 'store'])->middleware('permission:cadastrar encomendas');
        Route::put('/{id}', [EncomendaController::class, 'update'])->middleware('permission:atualizar encomendas');
    });

});



