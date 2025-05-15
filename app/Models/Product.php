<?php

namespace App\Models;

use App\Events\ProductEditedEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property string $country
 * @property Carbon $due_date
 * @property int $open_days
 * @property string $image
 * @property int $days_left
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
        'days_left',
    ];

    protected $casts = [
        'due_date' => 'datetime:Y-m-d',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
