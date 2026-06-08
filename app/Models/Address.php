<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'geocoded_at' => 'datetime',
            'is_default' => 'boolean',
        ];
    }

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}
