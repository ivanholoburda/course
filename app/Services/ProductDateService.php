<?php

namespace App\Services;

use Carbon\Carbon;

class ProductDateService
{
    public function calculateDaysLeft(int $openDays, Carbon $dueDate): float
    {
        $today = Carbon::today();
        $expiryDate = $today->copy()->addDays($openDays);
        $expiryDate = $dueDate->lessThan($expiryDate) ? $dueDate : $expiryDate;

        return $today->diffInDays($expiryDate);
    }
}
