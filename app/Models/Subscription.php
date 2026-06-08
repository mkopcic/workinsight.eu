<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Subscription extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'weekday_pattern' => 'array',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function subscriber(): MorphTo
    {
        return $this->morphTo();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
