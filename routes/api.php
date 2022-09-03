<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\Product;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);

Route::get('products', [ProductController::class, 'index']);

//Las rutas dentro de esta funcion estan protegidas para que no se pueda acceder a ella sin estar autenticados
Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('myproducts/{id}', [ProductController::class, 'myproducts']);
    Route::post('create', [ProductController::class, 'store']);
    Route::get('edit/{id}', [ProductController::class, 'edit']);
    Route::post('update', [ProductController::class, 'update']);
    Route::delete('delete/{id}', [ProductController::class, 'destroy']);
    Route::get('user', [UserController::class, 'user']);
    Route::post('update-user', [UserController::class, 'update_user']);
    Route::delete('delete-user/{id}', [UserController::class, 'delete_user']);
    Route::get('show-product/{id}', [ProductController::class, 'show']);
    Route::get('show-user/{id}', [UserController::class, 'show']);
});

