<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductBuyRequest;
use App\Repositories\Products\ProductRepository;
use App\Repositories\Products\SaleRepository;
use App\Repositories\Inventory\InventoryRepository;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Resources\Product\ProductFormResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductRepository $productRepository;
    private InventoryRepository $inventoryRepository;
    private SaleRepository $saleRepository;

    public function __construct(ProductRepository $productRepository, InventoryRepository $inventoryRepository, SaleRepository $saleRepository)
    {
        $this->productRepository = $productRepository;
        $this->inventoryRepository = $inventoryRepository;
        $this->saleRepository = $saleRepository;
    }

    public function store(ProductStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validated();

            $validatedData = collect($validatedData)->except('quantity')->toArray();

            $response = $this->productRepository->store($validatedData);

            $this->inventoryRepository->store([
                'product_id' => $response->id,
                'quantity' => $request->input('quantity')
            ]);

            $product = new ProductFormResource($response);

            DB::commit();

            return response()->json([
                'data' => $product
            ], 201);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'errors' => [[
                    'status' => '500',
                    'title' => 'Error al guardar el producto',
                    'detail' => $th->getMessage(),
                    'meta' => [
                        'line' => $th->getLine()
                    ]
                ]]
            ], 500);
        }
    }

    public function info($id)
    {
        try {
            DB::beginTransaction();

            $product = $this->productRepository->find($id);

            if (!$product) {
                return response()->json([
                    'errors' => [[
                        'status' => '404',
                        'title' => 'Producto no encontrado'
                    ]]
                ], 404);
            }

            DB::commit();

            return response()->json([
                'data' => $product
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'errors' => [[
                    'status' => '500',
                    'title' => 'Error al obtener el producto',
                    'detail' => $th->getMessage(),
                    'meta' => [
                        'line' => $th->getLine()
                    ]
                ]]
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $this->productRepository->delete($id);

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
                        'line' => $th->getLine()
                    ]
                ]]
            ], 500);
        }
    }

    public function list(Request $request)
    {
        try {
            DB::beginTransaction();

            $products = $this->productRepository->list(['typeData' => 'all']);

            if (!$products) {
                return response()->json([
                    'errors' => [[
                        'status' => '404',
                        'title' => 'Productos no encontrados'
                    ]]
                ], 404);
            }

            DB::commit();

            return response()->json([
                'data' => $products
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'errors' => [[
                    'status' => '500',
                    'title' => 'Error al obtener el producto',
                    'detail' => $th->getMessage(),
                    'meta' => [
                        'line' => $th->getLine()
                    ]
                ]]
            ], 500);
        }
    }

    public function buy(ProductBuyRequest $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validated();

            $response = $this->saleRepository->store($validatedData);

            if(isset($response->id)){
               $this->inventoryRepository->updateProduct($response->product_id, $validatedData["quantity"]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Compra realizada con éxito',
            ], 201);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'errors' => [[
                    'status' => '500',
                    'title' => 'Error al guardar el producto',
                    'detail' => $th->getMessage(),
                    'meta' => [
                        'line' => $th->getLine()
                    ]
                ]]
            ], 500);
        }
    }

}
