<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrderLine extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'delivery_date' => 'date',
            'unit_price' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }

    public function beneficiary(): MorphTo
    {
        return $this->morphTo();
    }
}
