<?php

namespace App\Domain\Delivery\Models;

use App\Domain\Access\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryStatusLog extends Model
{
    public $timestamps = false; // koristi samo created_at (append-only)

    protected $guarded = [];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(fn (DeliveryStatusLog $l) => $l->created_at ??= now());
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_user_id');
    }
}
