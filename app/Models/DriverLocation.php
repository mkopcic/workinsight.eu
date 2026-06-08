<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverLocation extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'service_date' => 'date',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'recorded_at' => 'datetime',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_user_id');
    }
}
