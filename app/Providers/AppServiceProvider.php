<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Health::checks([
            DatabaseCheck::new(),
            CacheCheck::new(),
            UsedDiskSpaceCheck::new()->warnWhenUsedSpaceIsAbovePercentage(70)->failWhenUsedSpaceIsAbovePercentage(90),
            DebugModeCheck::new(),
            EnvironmentCheck::new(),
        ]);
    }
}
