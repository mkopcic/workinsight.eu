<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryLine extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }

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
}
