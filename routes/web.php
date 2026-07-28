<?php

use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\WhereToBuyController;
use Illuminate\Support\Facades\Route;

/*
| Public — the outlets/where-to-buy page is the site root. The /where-to-buy
| path is kept as a permanent redirect for links that use it.
*/
Route::get('/', [WhereToBuyController::class, 'index'])->name('where-to-buy');
Route::permanentRedirect('/where-to-buy', '/');

/*
| Admin — a small authenticated back office for managing outlets and cities.
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.stores.index'))->name('home');

    Route::resource('stores', StoreController::class)->except('show');
    Route::resource('cities', CityController::class)->except('show');
    Route::resource('users', UserController::class)->except('show');
});

/*
| Breeze signs users in to /dashboard, which just forwards to the back office.
| The profile page it normally ships with has been removed — names, emails and
| passwords are managed from Admin → Users, which enforces a last-admin guard
| that the old "delete my account" form did not have.
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => redirect()->route('admin.stores.index'))->name('dashboard');
});

require __DIR__.'/auth.php';
