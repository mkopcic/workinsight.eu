<?php

namespace App\Domain\Integration\Models;

use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    public const UPDATED_AT = null;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
