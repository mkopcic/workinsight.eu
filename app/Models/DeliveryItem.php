<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryItem extends Model
{
    protected $guarded = [];

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function orderLine(): BelongsTo
    {
        return $this->belongsTo(OrderLine::class);
    }

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }
}
