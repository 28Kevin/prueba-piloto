<?php

namespace App\Http\Controllers;

use App\Repositories\Products\ProductRepository;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Resources\Product\ProductFormResource;

class ProductController extends Controller
{

    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function store(ProductStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validated();

            $response = $this->productRepository->store($validatedData);

            $product = new ProductFormResource($response);

            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'Guardado con éxito',
                'product' => $product
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'code' => 500,
                'message' => 'Error al guardar',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
            ]);
        }
    }


}
