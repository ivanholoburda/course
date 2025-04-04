<?php

namespace App\Repository;

use App\Models\User;

class UserRepository extends AbstractRepository
{
    public function model(): string
    {
        return User::class;
    }
}
