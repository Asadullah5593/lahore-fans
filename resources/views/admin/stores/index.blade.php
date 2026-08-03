@extends('layouts.admin')

@section('title', 'Outlets')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-ink">Outlets</h1>
            <p class="text-sm text-body">
                @if ($hasFilters)
                    Showing {{ $stores->total() }} of {{ \App\Models\Store::count() }} outlets.
                @else
                    {{ $stores->total() }} outlet(s) across the dealer network.
                @endif
            </p>
        </div>
        <a href="{{ route('admin.stores.create') }}" class="lf-btn">+ Add outlet</a>
    </div>

    {{-- Filters. The selects submit on change; the search box submits on Enter
         or when the button is pressed. data-loading-form paints the bar busy. --}}
    <form method="GET" action="{{ route('admin.stores.index') }}" data-loading-form
          class="mb-4 rounded-lg border border-line bg-white p-4 shadow-sm">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <label for="f-q" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-body">Search</label>
                <input id="f-q" name="q" type="search" value="{{ $filters['q'] }}"
                       placeholder="Name, owner, address or phone"
                       class="w-full rounded border-line text-sm focus:border-brand focus:ring-brand">
            </div>

            <div>
                <label for="f-city" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-body">City</label>
                {{-- data-searchable: admin-ui.js wraps this in a combobox with a
                     search box. The native select stays the source of truth, so
                     the filter still works if the script never runs. --}}
                <select id="f-city" name="city" data-auto-submit data-searchable data-search-placeholder="Type a city…"
                        class="w-full rounded border-line text-sm focus:border-brand focus:ring-brand">
                    <option value="">All cities</option>
                    @foreach ($cities as $id => $name)
                        <option value="{{ $id }}" @selected((string) $filters['city'] === (string) $id)>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="f-status" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-body">Status</label>
                    <select id="f-status" name="status" data-auto-submit
                            class="w-full rounded border-line text-sm focus:border-brand focus:ring-brand">
                        <option value="">All</option>
                        <option value="active" @selected($filters['status'] === 'active')>Active</option>
                        <option value="hidden" @selected($filters['status'] === 'hidden')>Hidden</option>
                    </select>
                </div>
                <div>
                    <label for="f-featured" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-body">Featured</label>
                    <select id="f-featured" name="featured" data-auto-submit
                            class="w-full rounded border-line text-sm focus:border-brand focus:ring-brand">
                        <option value="">All</option>
                        <option value="yes" @selected($filters['featured'] === 'yes')>Starred</option>
                        <option value="no" @selected($filters['featured'] === 'no')>Not starred</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-3 flex flex-wrap items-center gap-3">
            <button type="submit" class="lf-btn py-2 text-sm">Apply</button>
            @if ($hasFilters)
                <a href="{{ route('admin.stores.index') }}"
                   class="rounded border border-line px-4 py-2 text-sm font-semibold text-ink hover:border-brand hover:text-brand">Clear</a>
                <span class="text-sm text-body">
                    {{ $stores->total() }} match{{ $stores->total() === 1 ? '' : 'es' }}
                </span>
            @endif
        </div>
    </form>

    <div class="overflow-hidden rounded-lg border border-line bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-body">
                    <tr>
                        <th class="px-4 py-3" title="Featured dealer"><span aria-hidden="true">&#9733;</span><span class="sr-only">Featured</span></th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">City</th>
                        <th class="px-4 py-3">Phone</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($stores as $store)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                {{-- One click stars or unstars the dealer; the list re-orders on reload. --}}
                                <form method="POST" action="{{ route('admin.stores.feature', $store) }}">
                                    @csrf
                                    <button type="submit"
                                            class="text-lg leading-none transition hover:scale-125 {{ $store->is_featured ? 'text-accent' : 'text-gray-300 hover:text-accent' }}"
                                            title="{{ $store->is_featured ? 'Featured — click to unstar' : 'Click to feature this dealer' }}"
                                            aria-label="{{ $store->is_featured ? 'Remove featured status' : 'Mark as featured' }}">
                                        {!! $store->is_featured ? '&#9733;' : '&#9734;' !!}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 font-semibold text-ink">{{ $store->name }}</td>
                            <td class="px-4 py-3 text-body">{{ $store->city?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-body">{{ $store->phone }}</td>
                            <td class="px-4 py-3">
                                @if ($store->is_active)
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-200 px-2.5 py-0.5 text-xs font-semibold text-gray-600">Hidden</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.stores.edit', $store) }}"
                                       class="rounded border border-line px-3 py-1 font-semibold text-ink hover:border-brand hover:text-brand">Edit</a>
                                    <form method="POST" action="{{ route('admin.stores.destroy', $store) }}"
                                          onsubmit="return confirm('Delete “{{ addslashes($store->name) }}”? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded border border-line px-3 py-1 font-semibold text-red-600 hover:border-red-500 hover:bg-red-50">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-body">
                                @if ($hasFilters)
                                    No outlets match those filters.
                                    <a href="{{ route('admin.stores.index') }}" class="font-semibold text-brand">Clear them</a>.
                                @else
                                    No outlets yet. <a href="{{ route('admin.stores.create') }}" class="font-semibold text-brand">Add the first one</a>.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $stores->links() }}</div>
@endsection
