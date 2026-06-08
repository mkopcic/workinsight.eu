<?php

namespace App\Domain\Customers\Models;

use App\Domain\Delivery\Models\Delivery;
use App\Domain\Delivery\Models\DeliveryLine;
use App\Domain\Shared\Enums\PensionerStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Pensioner extends Model
{
    protected $guarded = [];

    protected $casts = [
        'para_synced_at' => 'datetime',
        'status' => PensionerStatus::class,
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function deliveryLine(): BelongsTo
    {
        return $this->belongsTo(DeliveryLine::class);
    }

    public function deliveries(): MorphMany
    {
        return $this->morphMany(Delivery::class, 'recipient');
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('status', PensionerStatus::Active->value);
    }
}
