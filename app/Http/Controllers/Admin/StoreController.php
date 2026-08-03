<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Models\City;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'city' => $request->query('city', ''),
            'status' => $request->query('status', ''),
            'featured' => $request->query('featured', ''),
        ];

        $stores = Store::with('city')
            ->when($filters['q'] !== '', function ($query) use ($filters) {
                // One box searches the fields someone would actually have to hand.
                $term = '%'.str_replace(['%', '_'], ['\%', '\_'], $filters['q']).'%';

                $query->where(fn ($q) => $q
                    ->where('name', 'like', $term)
                    ->orWhere('owner_name', 'like', $term)
                    ->orWhere('address', 'like', $term)
                    ->orWhere('phone', 'like', $term)
                    ->orWhere('phone_two', 'like', $term));
            })
            ->when($filters['city'] !== '', fn ($q) => $q->where('city_id', $filters['city']))
            ->when($filters['status'] !== '', fn ($q) => $q->where('is_active', $filters['status'] === 'active'))
            ->when($filters['featured'] !== '', fn ($q) => $q->where('is_featured', $filters['featured'] === 'yes'))
            ->orderBy('city_id')
            ->ordered()
            ->paginate(20)
            ->withQueryString();

        return view('admin.stores.index', [
            'stores' => $stores,
            'filters' => $filters,
            'cities' => $this->cityOptions(),
            'hasFilters' => collect($filters)->contains(fn ($v) => $v !== ''),
        ]);
    }

    /** One-click star/unstar from the list, so several can be set quickly. */
    public function toggleFeatured(Store $store)
    {
        $store->update(['is_featured' => ! $store->is_featured]);

        return back()->with('success', sprintf(
            '%s %s.',
            $store->name,
            $store->is_featured ? 'is now featured' : 'is no longer featured'
        ));
    }

    public function create()
    {
        return view('admin.stores.create', [
            'store' => new Store(['is_active' => true, 'sort_order' => 0]),
            'cities' => $this->cityOptions(),
        ]);
    }

    public function store(StoreRequest $request)
    {
        Store::create($request->validated());

        return redirect()->route('admin.stores.index')
            ->with('success', 'Outlet created successfully.');
    }

    public function edit(Store $store)
    {
        return view('admin.stores.edit', [
            'store' => $store,
            'cities' => $this->cityOptions(),
        ]);
    }

    public function update(StoreRequest $request, Store $store)
    {
        $store->update($request->validated());

        return redirect()->route('admin.stores.index')
            ->with('success', 'Outlet updated successfully.');
    }

    public function destroy(Store $store)
    {
        $store->delete();

        return redirect()->route('admin.stores.index')
            ->with('success', 'Outlet deleted.');
    }

    /** Cities as an id => name map for the select field. */
    protected function cityOptions()
    {
        return City::ordered()->pluck('name', 'id');
    }
}
