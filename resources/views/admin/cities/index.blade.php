@extends('layouts.admin')

@section('title', 'Cities')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-ink">Cities</h1>
            <p class="text-sm text-body">Cities group the outlets. The map focuses on each city's own outlets automatically.</p>
        </div>
        <a href="{{ route('admin.cities.create') }}" class="lf-btn">+ Add city</a>
    </div>

    <form method="GET" action="{{ route('admin.cities.index') }}" data-loading-form
          class="mb-4 flex flex-wrap items-end gap-3 rounded-lg border border-line bg-white p-4 shadow-sm">
        <div class="min-w-[220px] flex-1">
            <label for="f-city-q" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-body">Search</label>
            <input id="f-city-q" name="q" type="search" value="{{ $q }}" placeholder="City name"
                   class="w-full rounded border-line text-sm focus:border-brand focus:ring-brand">
        </div>
        <button type="submit" class="lf-btn py-2 text-sm">Search</button>
        @if ($q !== '')
            <a href="{{ route('admin.cities.index') }}"
               class="rounded border border-line px-4 py-2 text-sm font-semibold text-ink hover:border-brand hover:text-brand">Clear</a>
            <span class="text-sm text-body">{{ $cities->total() }} match{{ $cities->total() === 1 ? '' : 'es' }}</span>
        @endif
    </form>

    <div class="overflow-hidden rounded-lg border border-line bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-body">
                    <tr>
                        {{-- Order / Latitude / Longitude / Zoom are no longer editable, so they are not listed.
                        <th class="px-4 py-3">Order</th>
                        <th class="px-4 py-3">Latitude</th>
                        <th class="px-4 py-3">Longitude</th>
                        <th class="px-4 py-3">Zoom</th>
                        --}}
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Outlets</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($cities as $city)
                        <tr class="hover:bg-gray-50">
                            {{--
                            <td class="px-4 py-3 text-body">{{ $city->sort_order }}</td>
                            <td class="px-4 py-3 text-body">{{ $city->lat }}</td>
                            <td class="px-4 py-3 text-body">{{ $city->lng }}</td>
                            <td class="px-4 py-3 text-body">{{ $city->zoom }}</td>
                            --}}
                            <td class="px-4 py-3 font-semibold text-ink">{{ $city->name }}</td>
                            <td class="px-4 py-3 text-body">{{ $city->stores_count }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.cities.edit', $city) }}"
                                       class="rounded border border-line px-3 py-1 font-semibold text-ink hover:border-brand hover:text-brand">Edit</a>
                                    <form method="POST" action="{{ route('admin.cities.destroy', $city) }}"
                                          onsubmit="return confirm('Delete “{{ addslashes($city->name) }}”?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                @disabled($city->stores_count > 0)
                                                class="rounded border border-line px-3 py-1 font-semibold {{ $city->stores_count > 0 ? 'cursor-not-allowed text-gray-300' : 'text-red-600 hover:border-red-500 hover:bg-red-50' }}"
                                                @if($city->stores_count > 0) title="Remove its outlets first" @endif>Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-10 text-center text-body">No cities yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $cities->links() }}</div>
@endsection
