<?php

namespace App\Repository;

use App\Models\ExpiredProduct;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ExpiredProductRepository extends AbstractRepository
{
    public function model(): string
    {
        return ExpiredProduct::class;
    }

    public function getUserExpiredProducts(User $user): Collection
    {
        return $this
            ->query()
            ->with('product')
            ->where('user_id', $user->id)
            ->get();
    }

    public function deleteUserExpiredProducts(User $user): bool
    {
        return $this
            ->query()
            ->where('user_id', $user->id)
            ->delete();
    }
}
