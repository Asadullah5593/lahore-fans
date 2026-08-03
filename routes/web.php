<?php

use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\WhereToBuyController;
use App\Models\City;
use App\Models\Store;
use Illuminate\Support\Facades\Artisan;
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

    Route::post('stores/{store}/feature', [StoreController::class, 'toggleFeatured'])->name('stores.feature');
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

/*
|--------------------------------------------------------------------------
| Temporary deploy hook — DELETE THIS BLOCK once the dealers are live
|--------------------------------------------------------------------------
|
| The production host has no terminal, so migrations and the dealer seeder are
| triggered over HTTP instead. This is a destructive endpoint: seeding DELETES
| every row in `stores` before inserting.
|
| It stays inert until DEPLOY_TOKEN is set in .env, answers 404 to anything
| without the exact token, and is rate limited. Remove the route and unset the
| token as soon as the data is in — a permanent remote "wipe and reseed" URL is
| not something to leave on a live site.
|
| env() is read directly rather than via config() on purpose: without a terminal
| there is no way to run `config:clear`, so a stale config cache must not be able
| to lock you out of your own deploy hook.
*/
Route::get('__deploy/{token}', function (string $token) {
    $expected = (string) env('DEPLOY_TOKEN', '');

    // 404 rather than 403: an unset or wrong token should look like the route
    // simply does not exist. hash_equals keeps the comparison timing-safe.
    abort_if($expected === '' || ! hash_equals($expected, $token), 404);

    $log = '';

    foreach ([
        ['migrate', ['--force' => true]],
        ['db:seed', ['--class' => 'DealerSeeder', '--force' => true]],
        ['cache:clear', []],
    ] as [$command, $options]) {
        Artisan::call($command, $options);
        $log .= "$ php artisan {$command}\n".Artisan::output()."\n";
    }

    $log .= sprintf(
        "Result: %d outlets across %d cities (%d with a second phone, %d with an owner).\n",
        Store::count(),
        City::whereHas('stores')->count(),
        Store::whereNotNull('phone_two')->count(),
        Store::whereNotNull('owner_name')->count(),
    );

    $log .= "\nDone. Now delete this route and remove DEPLOY_TOKEN from .env.\n";

    return response($log, 200)->header('Content-Type', 'text/plain; charset=utf-8');
})->middleware('throttle:5,60');
