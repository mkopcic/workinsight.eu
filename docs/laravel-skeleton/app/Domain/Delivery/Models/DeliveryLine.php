<?php

namespace App\Domain\Delivery\Models;

use App\Domain\Access\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryLine extends Model
{
    protected $guarded = [];

    protected $casts = ['active' => 'boolean'];

    public function defaultDriver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'default_driver_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(LineAssignment::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('active', true);
    }
}
