<?php

namespace App\Domain\Contracts\Models;

use App\Domain\Shared\Enums\ContractStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Contract extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'status' => ContractStatus::class,
        'valid_from' => 'date',
        'valid_until' => 'date',
        'signed_at' => 'datetime',
        'uploaded_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(fn (Contract $c) => $c->public_id ??= (string) Str::ulid());
    }

    public function contractable(): MorphTo
    {
        return $this->morphTo();
    }

    /** Ugovori koji isticu za N dana i jos nemaju poslani podsjetnik. */
    public function scopeExpiringIn(Builder $q, int $days): Builder
    {
        return $q->whereDate('valid_until', now()->addDays($days)->toDateString())
                 ->whereNull('reminder_sent_at')
                 ->where('status', ContractStatus::Signed->value);
    }
}
