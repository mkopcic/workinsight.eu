<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InvoiceExport extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'sent_at' => 'datetime',
        ];
    }

    public function billable(): MorphTo
    {
        return $this->morphTo();
    }

    public function lines(): HasMany
    {
        return $this->hasMany(InvoiceExportLine::class);
    }
}
