<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pensioner extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['para_synced_at' => 'datetime'];
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function deliveryLine(): BelongsTo
    {
        return $this->belongsTo(DeliveryLine::class);
    }
}
