<?php

namespace App\Domain\Billing\Models;

use App\Domain\Shared\Enums\InvoiceExportStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InvoiceExport extends Model
{
    protected $guarded = [];

    protected $casts = [
        'payload' => 'array',
        'status' => InvoiceExportStatus::class,
        'sent_at' => 'datetime',
    ];

    public function billable(): MorphTo
    {
        return $this->morphTo();
    }

    public function lines(): HasMany
    {
        return $this->hasMany(InvoiceExportLine::class);
    }

    public static function makeIdempotencyKey(string $billableType, int $billableId, int $year, int $month): string
    {
        return sprintf('%s:%d:%04d-%02d', class_basename($billableType), $billableId, $year, $month);
    }
}
