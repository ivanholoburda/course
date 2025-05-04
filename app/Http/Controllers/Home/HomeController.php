<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExpiredProductResource;
use App\Http\Resources\ProductResource;
use App\Models\User;
use App\Services\ProductService;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    public function index(): Response
    {
        /** @var User $user */
        $user = auth()->user();
        $products = $this->productService->getUserLastProducts($user);
        $expiredProducts = $this->productService->getExpiredProducts($user);

        return Inertia::render('Home/Index', [
            'products' => ProductResource::collection($products),
            'expiredProducts' => ExpiredProductResource::collection($expiredProducts),
        ]);
    }
}
