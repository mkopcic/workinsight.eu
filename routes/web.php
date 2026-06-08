<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Interni dashboard (pregled dokumentacije + domene) — samo prijavljeni korisnici.
// Dokumentacija time nije javna; gosti se preusmjeravaju na /login (Fortify).
Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
