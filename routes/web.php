<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\DiscordController;
use App\Http\Controllers\Auth\SteamController;
use App\Http\Controllers\Routes\DashboardController;
use App\Http\Controllers\Routes\HomepageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Homepage
Route::get('/', [HomepageController::class, 'index'])
    ->middleware('setup-complete')
    ->name('home');

Route::get('linked', [HomepageController::class, 'linked'])
    ->middleware(['auth', 'is-linked'])
    ->name('linked');

Route::get('discord', function() {
    return redirect('https://discord.gg/gxFxuwDuaw');
})->middleware(['auth', 'is-linked'])->name('discord');


// Dashboard
//Route::prefix('dashboard')->as('dashboard.')->middleware(['auth'])->group(function () {
//    Route::get('/', [DashboardController::class, 'index'])->name('index');
//});


// Login Logout Routes
Route::get('login', [AuthController::class, 'redirect'])->name('login')->middleware('guest');
Route::get('logout', [AuthController::class, 'remove'])->name('logout')->middleware('auth');

// OpenID & OAuth Routes
Route::prefix('auth')->as('auth.')->group(function () {
    // Steam OpenID
    Route::middleware('guest')->group(function () {
        Route::get('steam', [SteamController::class, 'redirect'])->name('steam');
        Route::get('steam/callback', [SteamController::class, 'callback'])->name('steam.callback');
    });

    // Discord OAuth
    Route::middleware(['auth', 'has-discord'])->group(function () {
        Route::get('discord', [DiscordController::class, 'redirect'])->name('discord');
        Route::get('discord/callback', [DiscordController::class, 'callback'])->name('discord.callback');
    });
});
