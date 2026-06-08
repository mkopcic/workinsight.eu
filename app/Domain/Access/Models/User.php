<?php

namespace App\Domain\Access\Models;

use App\Domain\Customers\Models\Company;
use App\Domain\Customers\Models\Customer;
use App\Domain\Delivery\Models\Delivery;
use App\Domain\Delivery\Models\DriverLocation;
use App\Domain\Delivery\Models\LineAssignment;
use App\Domain\Shared\Enums\UserStatus;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $guarded = [];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
        ];
    }

    /**
     * Only users with the "admin" role may access the Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('admin');
    }

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    public function staffProfile(): HasOne
    {
        return $this->hasOne(StaffProfile::class);
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function ownedCompany(): HasOne
    {
        return $this->hasOne(Company::class, 'owner_user_id');
    }

    public function lineAssignments(): HasMany
    {
        return $this->hasMany(LineAssignment::class, 'driver_user_id');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class, 'line_assignment_id');
    }

    public function driverLocations(): HasMany
    {
        return $this->hasMany(DriverLocation::class, 'driver_user_id');
    }
}
