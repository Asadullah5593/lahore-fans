<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Models\City;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::withCount('stores')->ordered()->paginate(20);

        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        return view('admin.cities.create', [
            'city' => new City(['zoom' => 12, 'sort_order' => 0]),
        ]);
    }

    public function store(CityRequest $request)
    {
        City::create($request->validated());

        return redirect()->route('admin.cities.index')
            ->with('success', 'City created successfully.');
    }

    public function edit(City $city)
    {
        return view('admin.cities.edit', compact('city'));
    }

    public function update(CityRequest $request, City $city)
    {
        $city->update($request->validated());

        return redirect()->route('admin.cities.index')
            ->with('success', 'City updated successfully.');
    }

    public function destroy(City $city)
    {
        // Cities with outlets are protected by the FK (restrictOnDelete);
        // surface a friendly message instead of a database error.
        if ($city->stores()->exists()) {
            return redirect()->route('admin.cities.index')
                ->with('error', 'Cannot delete a city that still has outlets. Reassign or remove them first.');
        }

        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', 'City deleted.');
    }
}
