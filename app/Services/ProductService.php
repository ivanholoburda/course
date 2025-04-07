<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Pipeline\SearchByNamePipeline;
use App\Pipeline\SearchByOpenDaysPipeline;
use App\Pipeline\SortByCreatedAtPipeline;
use App\Repository\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Pipeline;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly OpenBeautyClient  $client,
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

    /**
     * @throws \Exception
     */
    public function update(User $user, Product $product, array $data, ?UploadedFile $file): ?Product
    {
        $additionalFields = [
            'user_id' => $user->id,
        ];

        if ($file) {
            unset($data['image']);
            $path = $file->store('products', 'public');
            $additionalFields['image'] = $path;
        }

        /** @var Product|null */
        return $this->productRepository->update($product, $data + $additionalFields);
    }

    public function getUserLastProducts(User $user): Collection
    {
        return $this->productRepository
            ->query()
            ->where('user_id', $user->id)
            ->latest()
            ->limit(3)
            ->get();
    }

    /**
     * @throws \Exception
     */
    public function createFromBarCode(User $user, string $barCode): ?Product
    {
        $data = $this->client->getByBarCode($barCode);
        if (empty($data)) {
            return null;
        }

        /** @var Product|null */
        return $this->productRepository->create($data + [
                'user_id' => $user->id,
            ]);
    }

    public function getUserProducts(User $user): Collection
    {
        $query = $this->productRepository
            ->query()
            ->where('user_id', $user->id);

        $pipeline = app(Pipeline::class)
            ->send($query)
            ->through([
                SearchByNamePipeline::class,
                SearchByOpenDaysPipeline::class,
                SortByCreatedAtPipeline::class
            ])
            ->thenReturn();

        return $pipeline->get();
    }

    public function delete(int $productId): bool
    {
        return $this->productRepository->delete($productId);
    }
}
