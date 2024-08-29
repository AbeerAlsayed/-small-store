<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProductController;
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


Route::get('/image-upload', [ImageController::class,'index']);
Route::post('/image-upload', [ImageController::class,'store']);


Route::get('products',[ProductController::class,'index']);
Route::post('products',[ProductController::class,'store']);
Route::get('product/{id}',[ProductController::class,'show']);
Route::delete('product/{id}',[ProductController::class,'destroy']);
Route::post('product/{id}',[CategoryController::class,'update']);

Route::get('categories',[CategoryController::class,'index']);
Route::post('categories',[CategoryController::class,'store']);
Route::post('categories/{id}',[CategoryController::class,'update']);
Route::get('category/{id}',[CategoryController::class,'show']);
Route::delete('category/{id}',[CategoryController::class,'destroy']);

Route::get('Subcategories',[CategoryController::class,'show_sub']);
