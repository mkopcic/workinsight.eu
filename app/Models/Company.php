<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function hqAddress(): BelongsTo
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
}
