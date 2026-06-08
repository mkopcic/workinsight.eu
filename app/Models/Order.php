<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Order extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'placed_at' => 'datetime',
            'total_amount' => 'decimal:2',
        ];
    }

    public function subscriber(): MorphTo
    {
        return $this->morphTo();
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function orderedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ordered_by_user_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(OrderLine::class);
    }
}
