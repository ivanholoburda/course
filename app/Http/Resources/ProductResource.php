<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $today = Carbon::today();
        $dueDate = Carbon::parse($this->due_date);
        $openedAt = $this->opened_at ? Carbon::parse($this->opened_at) : null;

        if (!$openedAt) {
            $daysLeft = $today->diffInDays($dueDate, false);
        } else {
            $afterOpeningExpiry = $openedAt->copy()->addDays($this->open_days);
            $finalExpiry = $dueDate->lessThan($afterOpeningExpiry) ? $dueDate : $afterOpeningExpiry;
            $daysLeft = $today->diffInDays($finalExpiry, false);
        }

        $user = Auth::user();

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
            'can_edit' => $user && $user->id === $this->user_id,
            'can_delete' => $user && $user->id === $this->user_id,
        ];
    }
}
