<?php

namespace App\Domain\Orders\Models;

use App\Domain\Shared\Enums\MenuType;
use App\Domain\Shared\Enums\SubscriptionPlan;
use App\Domain\Shared\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Subscription extends Model
{
    protected $guarded = [];

    protected $casts = [
        'menu_type' => MenuType::class,
        'plan' => SubscriptionPlan::class,
        'status' => SubscriptionStatus::class,
        'weekday_pattern' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function subscriber(): MorphTo
    {
        return $this->morphTo();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('status', SubscriptionStatus::Active->value);
    }
}
