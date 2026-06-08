<?php

namespace App\Domain\Integration\Models;

use App\Domain\Shared\Enums\ParaSyncSource;
use Illuminate\Database\Eloquent\Model;

class ParaSyncRun extends Model
{
    protected $guarded = [];

    protected $casts = [
        'source' => ParaSyncSource::class,
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];
}
