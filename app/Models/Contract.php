<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'valid_from' => 'date',
            'valid_until' => 'date',
            'signed_at' => 'datetime',
            'uploaded_at' => 'datetime',
            'reminder_sent_at' => 'datetime',
        ];
    }

    public function contractable(): MorphTo
    {
        return $this->morphTo();
    }
}
