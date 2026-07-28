@php
    $editing = $city->exists;
    $action = $editing ? route('admin.cities.update', $city) : route('admin.cities.store');
    $inputClass = 'w-full rounded border-line text-sm focus:border-brand focus:ring-brand';
@endphp

@if ($errors->any())
    <div class="mb-6 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ $action }}" class="max-w-xl space-y-4">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div>
        <label for="name" class="mb-1 block text-sm font-semibold text-ink">City name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $city->name) }}" class="{{ $inputClass }}" required>
    </div>

    <p class="text-xs text-body">
        The map centres itself on this city's outlets automatically — there is nothing else to fill in.
    </p>

    {{-- Centre coordinates, map zoom and sort order are hidden from the form.
         The centre is derived from the city's outlets (see WhereToBuyController),
         zoom keeps the database default of 12, and cities are listed
         alphabetically unless a sort order was set previously.

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="lat" class="mb-1 block text-sm font-semibold text-ink">Center latitude</label>
            <input id="lat" name="lat" type="text" value="{{ old('lat', $city->lat) }}" class="{{ $inputClass }}" required>
        </div>
        <div>
            <label for="lng" class="mb-1 block text-sm font-semibold text-ink">Center longitude</label>
            <input id="lng" name="lng" type="text" value="{{ old('lng', $city->lng) }}" class="{{ $inputClass }}" required>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="zoom" class="mb-1 block text-sm font-semibold text-ink">Map zoom</label>
            <input id="zoom" name="zoom" type="number" min="1" max="20" value="{{ old('zoom', $city->zoom ?? 12) }}" class="{{ $inputClass }}" required>
            <p class="mt-1 text-xs text-body">How closely the map zooms when this city is focused (12 is a good default).</p>
        </div>
        <div>
            <label for="sort_order" class="mb-1 block text-sm font-semibold text-ink">Sort order</label>
            <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $city->sort_order ?? 0) }}" class="{{ $inputClass }}">
            <p class="mt-1 text-xs text-body">Lower numbers appear first on the public page.</p>
        </div>
    </div>
    --}}

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="lf-btn">{{ $editing ? 'Save changes' : 'Create city' }}</button>
        <a href="{{ route('admin.cities.index') }}" class="rounded border border-line px-5 py-2.5 text-sm font-semibold text-ink hover:border-brand hover:text-brand">Cancel</a>
    </div>
</form>
