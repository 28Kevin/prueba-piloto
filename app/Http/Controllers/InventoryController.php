<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inventory\InventoryStoreRequest;
use App\Http\Resources\Inventory\InventoryFormResource;
use App\Repositories\Inventory\InventoryRepository;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    private InventoryRepository $inventoryRepository;

    public function __construct(InventoryRepository $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }

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
     *             @OA\Property(property="quantity", type="integer", example=50)
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

    public function store(InventoryStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $response = $this->inventoryRepository->store($request->validated());

            $product = new InventoryFormResource($response);

            DB::commit();

            return response()->json([
                'data' => $product,
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'errors' => [[
                    'status' => '500',
                    'title' => 'Error al guardar el producto',
                    'detail' => $th->getMessage(),
                    'meta' => [
                        'line' => $th->getLine(),
                        'file' => $th->getFile()
                    ]
                ]]
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1-inventory-delete/{id}",
     *     summary="Eliminar un registro de inventario",
     *     tags={"Inventario"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del inventario",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inventario eliminado exitosamente"
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
    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $this->inventoryRepository->delete($id);

            DB::commit();

            return response()->json([
                'meta' => [
                    'message' => 'Producto eliminado con éxito.'
                ]
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'errors' => [[
                    'status' => '500',
                    'title' => 'Error al eliminar el producto',
                    'detail' => $th->getMessage(),
                    'meta' => [
                        'line' => $th->getLine(),
                        'file' => $th->getFile()
                    ]
                ]]
            ], 500);
        }
    }

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
     *         description="Inventario encontrado exitosamente"
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
    public function getByProduct($id)
    {
        try {
            DB::beginTransaction();

            $product =  $this->inventoryRepository->list(['product_id' => $id, 'typeData' => 'all'], ['product']);

            DB::commit();

            return response()->json([
                'meta' => [
                    'message' => 'Producto encontrado con éxito.',
                    'data' => $product
                ]
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'errors' => [[
                    'status' => '500',
                    'title' => 'Error al obtener el producto',
                    'detail' => $th->getMessage(),
                    'meta' => [
                        'line' => $th->getLine(),
                        'file' => $th->getFile()
                    ]
                ]]
            ], 500);
        }
    }
}
