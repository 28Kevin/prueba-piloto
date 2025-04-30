<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/v1-product-store', [ProductController::class, 'store']);
Route::delete('/v1-product-delete/{id}', [ProductController::class, 'delete']);
Route::get('/v1-product-info/{id}', [ProductController::class, 'info']);
Route::post('/v1-product-list', [ProductController::class, 'list']);


Route::post('/v1-buy-product', [ProductController::class, 'buy']);




