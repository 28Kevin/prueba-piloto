<?php

use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

/**
 * @OA\Post(
 *     path="/api/v1-inventory-store",
 *     summary="Crear un nuevo registro de inventario",
 *     tags={"Inventario"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"product_id", "quantity"},
 *             @OA\Property(property="product_id", type="integer", example=1),
 *             @OA\Property(property="quantity", type="integer", example=100)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Inventario creado exitosamente"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Errores de validación"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor"
 *     )
 * )
 */
// POST /api/v1-inventory-store
// Crea un nuevo registro de inventario con un producto y cantidad especificada.
Route::post('v1-inventory-store', [InventoryController::class, 'store'])->name('inventory.store');

/**
 * @OA\Get(
 *     path="/api/v1-inventory-by-product/{productId}",
 *     summary="Obtener inventario por ID de producto",
 *     tags={"Inventario"},
 *     @OA\Parameter(
 *         name="productId",
 *         in="path",
 *         description="ID del producto",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Inventario obtenido exitosamente"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Inventario no encontrado"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor"
 *     )
 * )
 */
// GET /api/v1-inventory-by-product/{productId}
// Obtiene el inventario correspondiente a un producto específico por su ID.
Route::get('v1-inventory-by-product/{productId}', [InventoryController::class, 'getByProduct'])->name('inventory.by-product');
