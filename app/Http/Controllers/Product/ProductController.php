<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    public function index(): Response
    {
        /** @var User $user */
        $user = auth()->user();
        $products = $this
            ->productService
            ->getUserProducts($user);

        return Inertia::render('MyCare/Index', [
            'products' => ProductResource::collection($products),
        ]);
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
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success to create product.',
            'product' => new ProductResource($product),
        ]);
    }

    /**
     * @throws \Exception
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $data = $request->validated();
        $product = $this->productService->update($user, $product, $data, $request->file('image'));

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product. Check data and try again.',
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success to update product.',
            'product' => new ProductResource($product),
        ]);
    }

    /**
     * @throws \Exception
     */
    public function createFromBarCode(string $barCode): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $product = $this->productService->createFromBarCode($user, $barCode);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product. Check data and try again.',
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success to create product.',
            'product' => new ProductResource($product),
        ]);
    }


    public function destroy(Product $product): RedirectResponse
    {
        $this->productService->delete($product->id);

        return redirect()->route('products.index');
    }
}
