<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function store(CreateProductRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $data = $request->validated();
        $product = $this->productService->create($user, $request->file('image'), $data);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product. Check data and try again.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success to create product.',
            'product' => new ProductResource($product),
        ]);
    }
}
