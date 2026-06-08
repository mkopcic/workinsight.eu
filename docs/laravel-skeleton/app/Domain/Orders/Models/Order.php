<?php

namespace App\Domain\Orders\Models;

use App\Domain\Access\Models\User;
use App\Domain\Shared\Enums\OrderStatus;
use App\Domain\Shared\Enums\OrderType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'order_type' => OrderType::class,
        'status' => OrderStatus::class,
        'placed_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $o) {
            $o->public_id ??= (string) Str::ulid();
            $o->order_number ??= 'ORD-'.now()->format('Ymd').'-'.strtoupper(Str::random(5));
        });
    }

    public function subscriber(): MorphTo
    {
        return $this->morphTo();
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function placedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ordered_by_user_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(OrderLine::class);
    }
}
