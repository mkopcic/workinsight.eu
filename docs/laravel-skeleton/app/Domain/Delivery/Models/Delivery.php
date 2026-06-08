<?php

namespace App\Domain\Delivery\Models;

use App\Domain\Shared\Enums\DeliveryStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Delivery extends Model
{
    protected $table = 'deliveries';

    protected $guarded = [];

    protected $casts = [
        'service_date' => 'date',
        'address_snapshot' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'status' => DeliveryStatus::class,
        'delivered_at' => 'datetime',
    ];

    public function line(): BelongsTo
    {
        return $this->belongsTo(DeliveryLine::class, 'delivery_line_id');
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(LineAssignment::class, 'line_assignment_id');
    }

    public function recipient(): MorphTo
    {
        return $this->morphTo();
    }

    public function items(): HasMany
    {
        return $this->hasMany(DeliveryItem::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(DeliveryStatusLog::class);
    }

    public function carriedOverFrom(): BelongsTo
    {
        return $this->belongsTo(Delivery::class, 'carried_over_from_id');
    }

    public function scopeForDate(Builder $q, $date): Builder
    {
        return $q->whereDate('service_date', $date);
    }

    public function scopePending(Builder $q): Builder
    {
        return $q->where('status', DeliveryStatus::Pending->value);
    }
}
