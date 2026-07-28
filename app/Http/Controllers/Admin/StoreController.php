<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Models\City;
use App\Models\Store;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with('city')
            ->orderBy('city_id')
            ->ordered()
            ->paginate(20);

        return view('admin.stores.index', compact('stores'));
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
