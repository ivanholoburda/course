<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Repository\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function create(User $user, UploadedFile $file, array $data): ?Product
    {
        unset($data['image']);

        $path = $file->store('products', 'public');

        /** @var Product|null */
        return $this->productRepository->create($data + [
                'image' => $path,
                'user_id' => $user->id,
            ]);
    }

    public function getUserProducts(User $user): Collection
    {
        return $this->productRepository
            ->query()
            ->where('user_id', $user->id)
            ->limit(3)
            ->get();
    }
}
