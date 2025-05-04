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

        $dueDate = Carbon::parse($this->due_date);
        $openedAt = $this->opened_at ? Carbon::parse($this->opened_at) : null;

        // Якщо продукт ще не відкритий — враховуємо тільки термін придатності
        if (!$openedAt) {
            $daysLeft = $today->diffInDays($dueDate, false);
        } else {
            // Вираховуємо дату, до якої продукт можна використовувати після відкриття
            $afterOpeningExpiry = $openedAt->copy()->addDays($this->open_days);
            $finalExpiry = $dueDate->lessThan($afterOpeningExpiry) ? $dueDate : $afterOpeningExpiry;
            $daysLeft = $today->diffInDays($finalExpiry, false);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'due_date' => $this->due_date,
            'open_days' => $this->open_days,
            'opened_at' => $this->opened_at,
            'image' => Str::startsWith($this->image, ['http://', 'https://', '/'])
                ? $this->image
                : Storage::url($this->image),
            'days_left' => max($daysLeft, 0),
        ];
    }
}
