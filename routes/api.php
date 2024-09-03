<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::get('products',[ProductController::class,'index']);
Route::post('products',[ProductController::class,'store']);
Route::get('product/{id}',[ProductController::class,'show']);
Route::delete('product/{id}',[ProductController::class,'destroy']);
Route::put('product/{id}',[ProductController::class,'update']);

Route::get('categories',[CategoryController::class,'index']);
Route::post('categories',[CategoryController::class,'store']);
Route::put('categories/{id}',[CategoryController::class,'update']);
Route::get('categories/{id}',[CategoryController::class,'show']);
Route::delete('categories/{id}',[CategoryController::class,'destroy']);

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group( function () {
    Route::apiResource('orders', OrderController::class);
});

//Route::get('orders',[OrderController::class,'index']);
//Route::post('orders',[OrderController::class,'store']);
//Route::post('orders/{id}',[OrderController::class,'update']);
//Route::get('orders/{id}',[OrderController::class,'show']);
//Route::delete('orders/{id}',[OrderController::class,'destroy']);


Route::get('users',[OrderController::class,'get_user']);
