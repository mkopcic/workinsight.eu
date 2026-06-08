<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'delivery_date' => 'date',
            'price' => 'decimal:2',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }
}
