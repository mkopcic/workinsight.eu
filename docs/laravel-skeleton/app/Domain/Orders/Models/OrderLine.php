<?php

namespace App\Domain\Orders\Models;

use App\Domain\Menus\Models\Meal;
use App\Domain\Menus\Models\MenuItem;
use App\Domain\Shared\Enums\OrderLineStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrderLine extends Model
{
    protected $guarded = [];

    protected $casts = [
        'delivery_date' => 'date',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
        'status' => OrderLineStatus::class,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function beneficiary(): MorphTo
    {
        return $this->morphTo();
    }
}
