<?php

namespace App\Repository;

use App\Models\Product;

class ProductRepository extends AbstractRepository
{
    public function model(): string
    {
        return Product::class;
    }
}
