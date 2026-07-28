<?php

namespace App\Providers;

use App\Http\Controllers\WhereToBuyController;
use App\Models\City;
use App\Models\Store;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

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
        // Flush the public outlet cache whenever a store or city changes so
        // admin edits appear on the where-to-buy page immediately.
        $flush = fn () => Cache::forget(WhereToBuyController::CACHE_KEY);

        Store::saved($flush);
        Store::deleted($flush);
        City::saved($flush);
        City::deleted($flush);
    }
}
