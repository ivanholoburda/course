<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\User;
use App\Services\ProductService;
use App\Services\UserService;
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
        $products = $this->productService->getUserProducts($user);

        return Inertia::render('Home/Index', [
            'products' => ProductResource::collection($products),
        ]);
    }
}
