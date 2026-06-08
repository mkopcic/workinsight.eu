<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Backup (spatie/laravel-backup) — dnevni backup + ciscenje starih + monitor zdravlja.
Schedule::command('backup:clean')->daily()->at('01:30');
Schedule::command('backup:run')->daily()->at('02:00');
Schedule::command('backup:monitor')->daily()->at('06:00');

// Health (spatie/laravel-health) — periodicna provjera; salje mail na neuspjeh.
Schedule::command('health:check')->everyFifteenMinutes();
