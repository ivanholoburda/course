<?php

namespace App\Pipeline;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class SortByCreatedAtPipeline
{
    public function handle(Builder $builder, Closure $next): Builder
    {
        if (!request()->filled('direction')) {
            return $next(
                $builder
                    ->orderBy('created_at', 'desc')
            );
        }

        return $next(
            $builder
                ->orderBy('created_at', request()->input('direction') ?? 'asc')
        );
    }
}
