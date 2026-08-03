@php
    $editing = $store->exists;
    $action = $editing ? route('admin.stores.update', $store) : route('admin.stores.store');
    $inputClass = 'w-full rounded border-line text-sm focus:border-brand focus:ring-brand';
@endphp

@if ($errors->any())
    <div class="mb-6 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <p class="font-semibold">Please fix the following:</p>
        <ul class="mt-1 list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ $action }}">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Left: text fields --}}
        <div class="space-y-4">
            <div>
                <label for="name" class="mb-1 block text-sm font-semibold text-ink">Outlet name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $store->name) }}" class="{{ $inputClass }}" required>
            </div>

            <div>
                <label for="owner_name" class="mb-1 block text-sm font-semibold text-ink">Owner <span class="font-normal text-body">(optional)</span></label>
                <input id="owner_name" name="owner_name" type="text" value="{{ old('owner_name', $store->owner_name) }}" class="{{ $inputClass }}">
                <p class="mt-1 text-xs text-body">Shown on the outlet card as “Owner: …”. Leave blank to hide it.</p>
            </div>

            <div>
                <label for="city_id" class="mb-1 block text-sm font-semibold text-ink">City</label>
                <select id="city_id" name="city_id" class="{{ $inputClass }}" required>
                    <option value="">— Select a city —</option>
                    @foreach ($cities as $id => $name)
                        <option value="{{ $id }}" @selected((int) old('city_id', $store->city_id) === $id)>{{ $name }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-body">
                    Not listed? <a href="{{ route('admin.cities.create') }}" class="font-semibold text-brand">Add a city</a> first.
                </p>
            </div>

            <div>
                <label for="address" class="mb-1 block text-sm font-semibold text-ink">Address</label>
                <input id="address" name="address" type="text" value="{{ old('address', $store->address) }}" class="{{ $inputClass }}" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="mb-1 block text-sm font-semibold text-ink">Phone</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $store->phone) }}" class="{{ $inputClass }}" required>
                </div>
                <div>
                    <label for="phone_two" class="mb-1 block text-sm font-semibold text-ink">Second phone <span class="font-normal text-body">(optional)</span></label>
                    <input id="phone_two" name="phone_two" type="text" value="{{ old('phone_two', $store->phone_two) }}" class="{{ $inputClass }}">
                </div>
            </div>

            <div>
                <label for="google_maps_url" class="mb-1 block text-sm font-semibold text-ink">Google Maps link <span class="font-normal text-body">(optional)</span></label>
                <input id="google_maps_url" name="google_maps_url" type="url" value="{{ old('google_maps_url', $store->google_maps_url) }}" class="{{ $inputClass }}" placeholder="https://g.co/kgs/...">
                <p class="mt-1 text-xs text-body">The “See more” link on each outlet card.</p>
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 text-sm font-semibold text-ink">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $store->is_active)) class="rounded border-line text-brand focus:ring-brand">
                    Active (visible on the public page)
                </label>

                <label class="flex items-center gap-2 text-sm font-semibold text-ink" title="Starred dealers show a green card and lead their city">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $store->is_featured)) class="rounded border-line text-brand focus:ring-brand">
                    <span class="text-accent" aria-hidden="true">&#9733;</span> Featured
                </label>
                {{-- Sort order is hidden from the form. Existing values are kept as-is;
                     new outlets default to 0 and fall back to alphabetical ordering.
                <div>
                    <label for="sort_order" class="mb-1 block text-sm font-semibold text-ink">Sort order</label>
                    <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $store->sort_order ?? 0) }}" class="w-28 rounded border-line text-sm focus:border-brand focus:ring-brand">
                </div>
                --}}
            </div>
        </div>

        {{-- Right: coordinates + map picker --}}
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="lat" class="mb-1 block text-sm font-semibold text-ink">Latitude</label>
                    <input id="lat" name="lat" type="text" value="{{ old('lat', $store->lat) }}" class="{{ $inputClass }}" required>
                </div>
                <div>
                    <label for="lng" class="mb-1 block text-sm font-semibold text-ink">Longitude</label>
                    <input id="lng" name="lng" type="text" value="{{ old('lng', $store->lng) }}" class="{{ $inputClass }}" required>
                </div>
            </div>
            <p class="text-xs text-body">
                Click the map to drop the pin, or paste coordinates from Google Maps (right-click a place → the numbers at the top).
                You can paste the whole “lat, lng” pair into either box — it fills both automatically.
            </p>
            <div id="store-map" class="h-[340px] w-full rounded border border-line"></div>
        </div>
    </div>

    <div class="mt-8 flex items-center gap-3">
        <button type="submit" class="lf-btn">{{ $editing ? 'Save changes' : 'Create outlet' }}</button>
        <a href="{{ route('admin.stores.index') }}" class="rounded border border-line px-5 py-2.5 text-sm font-semibold text-ink hover:border-brand hover:text-brand">Cancel</a>
    </div>
</form>

@push('scripts')
    @vite('resources/js/admin-map.js')
@endpush
