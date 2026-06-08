<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BillingMonthlySummary extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['total_amount' => 'decimal:2'];
    }

    public function billable(): MorphTo
    {
        return $this->morphTo();
    }
}
