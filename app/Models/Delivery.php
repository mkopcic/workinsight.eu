<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Delivery extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'service_date' => 'date',
            'address_snapshot' => 'array',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'delivered_at' => 'datetime',
        ];
    }

    public function deliveryLine(): BelongsTo
    {
        return $this->belongsTo(DeliveryLine::class);
    }

    public function lineAssignment(): BelongsTo
    {
        return $this->belongsTo(LineAssignment::class);
    }

    public function recipient(): MorphTo
    {
        return $this->morphTo();
    }

    public function carriedOverFrom(): BelongsTo
    {
        return $this->belongsTo(Delivery::class, 'carried_over_from_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DeliveryItem::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(DeliveryStatusLog::class);
    }
}
