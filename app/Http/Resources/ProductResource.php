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
        $today = Carbon::today();

        // Кінцева дата — через open_days після додавання (тобто сьогодні + open_days)
        $expiryDate = $today->copy()->addDays($this->open_days);

        // Якщо вказана загальна дата придатності, то беремо ту, що раніше
        if ($this->due_date) {
            $dueDate = Carbon::parse($this->due_date);
            $expiryDate = $dueDate->lessThan($expiryDate) ? $dueDate : $expiryDate;
        }

        // Обчислюємо кількість днів до кінцевої дати
        $daysLeft = $today->diffInDays($expiryDate, false);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'due_date' => $this->due_date,
            'open_days' => $this->open_days,
            'image' => Str::startsWith($this->image, ['http://', 'https://', '/'])
                ? $this->image
                : Storage::url($this->image),
            'days_left' => max($daysLeft, 0),
        ];
    }
}
