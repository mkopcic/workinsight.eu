<?php

namespace App\Domain\Customers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    protected $guarded = [];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'geocoded_at' => 'datetime',
        'is_default' => 'boolean',
    ];

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function isGeocoded(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }
}
