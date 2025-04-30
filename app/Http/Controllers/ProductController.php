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

use OpenApi\Annotations as OA;

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
     *     @OA\Response(response=201, description="Producto creado exitosamente"),
     *     @OA\Response(response=422, description="Errores de validación")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/v1-product-info/{id}",
     *     summary="Obtener información de un producto",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Información del producto"),
     *     @OA\Response(response=404, description="Producto no encontrado")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/v1-product-delete/{id}",
     *     summary="Eliminar un producto",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Producto eliminado"),
     *     @OA\Response(response=404, description="Producto no encontrado")
     * )
     */
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
