<?php

namespace App\Pipeline;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SearchByDaysLeftPipeline
{
    public function handle(Builder $builder, Closure $next): Builder
    {
        if (!request()->filled('days_left')) {
            return $next($builder);
        }

        $daysLeft = (int) request()->input('days_left');

        // Фільтруємо по розрахованому значенню
        return $next(
            $builder->whereRaw('DATEDIFF(due_date, CURDATE()) = ?', [$daysLeft])
        );
    }
}