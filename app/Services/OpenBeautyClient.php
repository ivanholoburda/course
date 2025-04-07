<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class OpenBeautyClient
{
    public function getByBarCode(string $barCode): array
    {
        $result = Http::get(config('services.open_beauty.base_url') . "/$barCode.json")
            ->json();

        if ($result['status'] !== 1) {
            return [];
        }

        return [
            'name' => $result['product']['generic_name'],
            'country' => $result['product']['countries'],
            'due_date' => Carbon::parse($result['product']['created_t'])->format('Y-m-d'),
            'open_days' => 360,
            'image' => $result['product']['image_front_url'],
        ];
    }
}
