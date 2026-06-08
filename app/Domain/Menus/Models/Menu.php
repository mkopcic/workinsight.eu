<?php

namespace App\Domain\Menus\Models;

use App\Domain\Shared\Enums\MenuStatus;
use App\Domain\Shared\Enums\MenuType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $guarded = [];

    protected $casts = [
        'menu_type' => MenuType::class,
        'status' => MenuStatus::class,
        'week_start' => 'date',
        'published_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', MenuStatus::Published->value);
    }
}
