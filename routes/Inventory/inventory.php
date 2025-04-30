<?php

use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::post('v1-inventory-store', [InventoryController::class, 'store'])->name('inventory.store');
Route::get('v1-inventory-by-product/{productId}', [InventoryController::class, 'getByProduct'])->name('inventory.by-product');


