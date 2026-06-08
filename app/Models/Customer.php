<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $guarded = [];

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
}
