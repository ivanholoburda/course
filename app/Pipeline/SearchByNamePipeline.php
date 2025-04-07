<?php

namespace App\Pipeline;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class SearchByNamePipeline
{
    public function handle(Builder $builder, Closure $next): Builder
    {
        if (!request()->filled('name')) {
            return $next($builder);
        }

        return $next(
            $builder
                ->where('name', 'like', '%' . request()->input('name') . '%')
        );
    }
}
