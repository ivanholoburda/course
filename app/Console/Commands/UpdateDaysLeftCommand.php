<?php

namespace App\Console\Commands;

use App\Models\ExpiredProduct;
use App\Models\Product;
use Illuminate\Console\Command;

class UpdateDaysLeftCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-days-left';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update days left';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Product::query()
            ->where('days_left', '>', 0)
            ->decrement('days_left');

        $expiredProductsData = Product::query()
            ->where('days_left', '<=', 0)
            ->whereNotIn('id', function ($query) {
                $query->select('product_id')->from('expired_products');
            })
            ->get(['id', 'user_id'])
            ->map(function ($product) {
                return [
                    'product_id' => $product->id,
                    'user_id' => $product->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

        ExpiredProduct::query()
            ->insert($expiredProductsData);
    }
}
