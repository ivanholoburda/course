<?php

namespace App\Pipeline;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class SearchByDaysLeftPipeline
{
    public function handle(Builder $builder, Closure $next): Builder
    {
        if (!request()->filled('days_left')) {
            return $next($builder);
        }

        return $next(
            $builder
                ->where('days_left', request()->input('days_left'))
        );
    }
}
