<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Pipeline\SearchByNamePipeline;
use App\Pipeline\SearchByDaysLeftPipeline;
use App\Pipeline\SortByCreatedAtPipeline;
use App\Repository\ExpiredProductRepository;
use App\Repository\ProductRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Pipeline;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ExpiredProductRepository $expiredProductRepository,
        private readonly OpenBeautyClient  $client,
        private readonly ProductDateService $productDateService,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function create(User $user, UploadedFile $file, array $data): ?Product
    {
        unset($data['image']);

        $path = $file->store('products', 'public');
        $daysLeft = $this->productDateService->calculateDaysLeft((int) $data['open_days'], Carbon::parse($data['due_date']));

        /** @var Product|null */
        return $this->productRepository->create($data + [
                'image' => $path,
                'user_id' => $user->id,
                'days_left' => $daysLeft,
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

        $data['days_left'] = $this->productDateService->calculateDaysLeft(
            isset($data['open_days']) ? (int) $data['open_days'] : $product->open_days,
            isset($data['due_date']) ? Carbon::parse($data['due_date']) : $product->due_date,
        );

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

    public function getExpiredProducts(User $user): Collection
    {
        return $this->expiredProductRepository->getUserExpiredProducts($user);
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
                SearchByDaysLeftPipeline::class,
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
