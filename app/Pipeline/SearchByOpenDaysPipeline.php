<?php

namespace App\Pipeline;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class SearchByOpenDaysPipeline
{
    public function handle(Builder $builder, Closure $next): Builder
    {
        if (!request()->filled('open_days')) {
            return $next($builder);
        }

        return $next(
            $builder
                ->where('open_days', request()->input('open_days'))
        );
    }
}
