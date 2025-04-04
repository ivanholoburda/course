<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property string $country
 * @property string $due_date
 * @property int $open_days
 * @property string $image
 */
class Product extends Model
{
    protected $fillable = [
        'name',
        'country',
        'due_date',
        'open_days',
        'image',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
