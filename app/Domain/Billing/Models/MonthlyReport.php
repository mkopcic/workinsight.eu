<?php

namespace App\Domain\Billing\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    protected $guarded = [];

    protected $casts = [
        'generated_at' => 'datetime',
        'emailed_at' => 'datetime',
    ];
}
