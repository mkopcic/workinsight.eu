<?php

namespace App\Domain\Access\Models;

use App\Domain\Delivery\Models\DeliveryLine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffProfile extends Model
{
    protected $guarded = [];

    protected $casts = ['active' => 'boolean'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function defaultLine(): BelongsTo
    {
        return $this->belongsTo(DeliveryLine::class, 'default_line_id');
    }
}
