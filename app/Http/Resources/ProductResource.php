<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

/** @mixin Product */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'due_date' => $this->due_date->format('d.m.Y'),
            'open_days' => $this->open_days,
            'image' => Str::startsWith($this->image, ['http://', 'https://', '/'])
                ? $this->image
                : Storage::url($this->image),
            'days_left' => $this->days_left,
        ];
    }
}
