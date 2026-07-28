@extends('layouts.admin')

@section('title', 'Outlets')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-ink">Outlets</h1>
            <p class="text-sm text-body">{{ $stores->total() }} outlet(s) across the dealer network.</p>
        </div>
        <a href="{{ route('admin.stores.create') }}" class="lf-btn">+ Add outlet</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-line bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-body">
                    <tr>
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
                            <td colspan="5" class="px-4 py-10 text-center text-body">
                                No outlets yet. <a href="{{ route('admin.stores.create') }}" class="font-semibold text-brand">Add the first one</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $stores->links() }}</div>
@endsection
