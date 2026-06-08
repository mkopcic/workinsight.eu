<?php

namespace App\Domain\Customers\Models;

use App\Domain\Access\Models\User;
use App\Domain\Billing\Models\InvoiceExport;
use App\Domain\Contracts\Models\Contract;
use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Models\Subscription;
use App\Domain\Shared\Enums\ProfileStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Company extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = ['status' => ProfileStatus::class];

    protected static function booted(): void
    {
        static::creating(fn (Company $c) => $c->public_id ??= (string) Str::ulid());
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function headquarters(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'hq_address_id');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(CompanyContact::class);
    }

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'owner');
    }

    public function subscriptions(): MorphMany
    {
        return $this->morphMany(Subscription::class, 'subscriber');
    }

    public function orders(): MorphMany
    {
        return $this->morphMany(Order::class, 'subscriber');
    }

    public function contracts(): MorphMany
    {
        return $this->morphMany(Contract::class, 'contractable');
    }

    public function invoiceExports(): MorphMany
    {
        return $this->morphMany(InvoiceExport::class, 'billable');
    }
}
