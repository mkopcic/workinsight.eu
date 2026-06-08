<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LineAssignment extends Model
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

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_user_id');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }
}
