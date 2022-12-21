<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('users')->as('users.')->group(function () {
        Route::prefix('me')->as('me.')->group(function () {
            Route::get('/', [UserController::class, 'me'])->name('info');
            Route::get('purchases',)->name('purchases');
        });

        Route::middleware('permission:users.query')->group(function () {
            Route::get('{user}', [UserController::class, 'get'])->name('get');
            Route::get('steam/{user:steam_id}', [UserController::class, 'get'])->name('get.steamId');
            Route::get('discord/{user:discord_id}', [UserController::class, 'get'])->name('get.discordId');
            Route::get('gmodstore/{user:gmod_store_id}', [UserController::class, 'get'])->name('get.gmodStoreId');
        });

        Route::get('/', [UserController::class, 'list'])->name('list');

        Route::delete('{user}', [UserController::class, 'delete'])->name('delete')
            ->middleware('permission:users.delete');

        Route::get('{user}/purchases', [UserController::class, 'purchases'])->name('purchases')
            ->middleware('permission:users.purchases');
    });
});
