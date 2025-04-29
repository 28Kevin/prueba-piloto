<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/v1-product-store', [ProductController::class, 'store']);



