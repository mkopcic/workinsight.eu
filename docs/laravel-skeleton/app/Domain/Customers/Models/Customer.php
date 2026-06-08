<?php

namespace App\Domain\Customers\Models;

use App\Domain\Access\Models\User;
use App\Domain\Contracts\Models\Contract;
use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Models\Subscription;
use App\Domain\Shared\Enums\ProfileStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = ['status' => ProfileStatus::class];

    protected static function booted(): void
    {
        static::creating(fn (Customer $c) => $c->public_id ??= (string) \Illuminate\Support\Str::ulid());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function defaultAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'default_address_id');
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

    public function fullName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
