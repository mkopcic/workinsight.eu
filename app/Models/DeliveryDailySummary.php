<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryDailySummary extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['service_date' => 'date'];
    }

    public function deliveryLine(): BelongsTo
    {
        return $this->belongsTo(DeliveryLine::class);
    }
}
