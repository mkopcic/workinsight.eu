<?php

namespace App\Domain\Delivery\Models;

use App\Domain\Access\Models\User;
use App\Domain\Shared\Enums\LineAssignmentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LineAssignment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'service_date' => 'date',
        'status' => LineAssignmentStatus::class,
    ];

    public function line(): BelongsTo
    {
        return $this->belongsTo(DeliveryLine::class, 'delivery_line_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_user_id');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }

    public function scopeForDate(Builder $q, $date): Builder
    {
        return $q->whereDate('service_date', $date);
    }
}
