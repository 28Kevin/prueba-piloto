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

}
