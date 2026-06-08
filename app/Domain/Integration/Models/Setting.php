<?php

namespace App\Domain\Integration\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'value' => 'array',
    ];
}
