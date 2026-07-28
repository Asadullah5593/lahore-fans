<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class WhereToBuyController extends Controller
{
    /** Cache key holding the prepared public outlet payload. */
    public const CACHE_KEY = 'where_to_buy.cities';

    public function index()
    {
        $cities = Cache::rememberForever(self::CACHE_KEY, fn () => $this->buildPayload());

        return View::make('pages.where-to-buy', ['cities' => $cities]);
    }

    /**
     * Build the ordered, city-grouped collection of active outlets.
     *
     * Only cities that have at least one active store are included. The result
     * is a plain array (cache-friendly) shaped for both the Blade cards and the
     * JSON handed to the map script.
     */
    protected function buildPayload(): array
    {
        return City::query()
            ->ordered()
            ->with(['stores' => fn ($q) => $q->active()->ordered()])
            ->whereHas('stores', fn ($q) => $q->active())
            ->get()
            ->map(fn (City $city) => [
                'id' => $city->id,
                'name' => $city->name,
                // The admin panel no longer asks for a centre point. Cities that
                // still have one keep it; the rest fall back to the centroid of
                // their own outlets. The whereHas above guarantees at least one
                // outlet, so the average is never null.
                'lat' => $city->lat ?? round($city->stores->avg('lat'), 7),
                'lng' => $city->lng ?? round($city->stores->avg('lng'), 7),
                'zoom' => $city->zoom,
                'stores' => $city->stores->map(fn ($store) => [
                    'id' => $store->id,
                    'name' => $store->name,
                    'address' => $store->address,
                    'phone' => $store->phone,
                    'lat' => $store->lat,
                    'lng' => $store->lng,
                    'google_maps_url' => $store->google_maps_url,
                ])->all(),
            ])
            ->all();
    }
}
