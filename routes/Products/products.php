<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/**
 * @OA\Post(
 *     path="/api/v1-product-store",
 *     summary="Crear un nuevo producto",
 *     tags={"Productos"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "price", "quantity"},
 *             @OA\Property(property="name", type="string", example="Keyboard"),
 *             @OA\Property(property="price", type="number", format="float", example=49.99),
 *             @OA\Property(property="quantity", type="integer", example=10)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Producto creado exitosamente"
 *     ),
 *     @OA\Response(response=422, description="Errores de validación")
 * )
 */
Route::post('/v1-product-store', [ProductController::class, 'store']);

/**
 * @OA\Delete(
 *     path="/api/v1-product-delete/{id}",
 *     summary="Eliminar un producto",
 *     tags={"Productos"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID del producto",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Producto eliminado"),
 *     @OA\Response(response=404, description="Producto no encontrado")
 * )
 */
Route::delete('/v1-product-delete/{id}', [ProductController::class, 'delete']);

/**
 * @OA\Get(
 *     path="/api/v1-product-info/{id}",
 *     summary="Obtener información de un producto",
 *     tags={"Productos"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID del producto",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Información del producto"),
 *     @OA\Response(response=404, description="Producto no encontrado")
 * )
 */
Route::get('/v1-product-info/{id}', [ProductController::class, 'info']);

/**
 * @OA\Post(
 *     path="/api/v1-product-list",
 *     summary="Listar productos (paginado, filtrado, etc.)",
 *     tags={"Productos"},
 *     @OA\RequestBody(
 *         required=false,
 *         @OA\JsonContent(
 *             @OA\Property(property="page", type="integer", example=1),
 *             @OA\Property(property="per_page", type="integer", example=10)
 *         )
 *     ),
 *     @OA\Response(response=200, description="Listado de productos")
 * )
 */
Route::post('/v1-product-list', [ProductController::class, 'list']);

/**
 * @OA\Post(
 *     path="/api/v1-buy-product",
 *     summary="Comprar un producto (actualiza el inventario)",
 *     tags={"Productos"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"product_id", "quantity", "total"},
 *             @OA\Property(property="product_id", type="integer", example=1),
 *             @OA\Property(property="quantity", type="integer", example=3),
 *             @OA\Property(property="total", type="number", format="float", example=149.97)
 *         )
 *     ),
 *     @OA\Response(response=201, description="Compra realizada exitosamente"),
 *     @OA\Response(response=422, description="Errores de validación")
 * )
 */
Route::post('/v1-buy-product', [ProductController::class, 'buy']);
