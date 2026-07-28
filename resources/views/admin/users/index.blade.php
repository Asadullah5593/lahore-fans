@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-ink">Users</h1>
            <p class="text-sm text-body">Accounts that can sign in to this admin panel. There is no public sign-up — users are added here.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="lf-btn">+ Add user</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-line bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-line text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-body">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Added</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($users as $listedUser)
                        @php $isSelf = $listedUser->is(auth()->user()); @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold text-ink">
                                {{ $listedUser->name }}
                                @if ($isSelf)
                                    <span class="ml-1 rounded bg-gray-100 px-1.5 py-0.5 text-xs font-semibold text-body">You</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-body">{{ $listedUser->email }}</td>
                            <td class="px-4 py-3 text-body">{{ $listedUser->created_at?->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $listedUser) }}"
                                       class="rounded border border-line px-3 py-1 font-semibold text-ink hover:border-brand hover:text-brand">Edit</a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $listedUser) }}"
                                          onsubmit="return confirm('Delete “{{ addslashes($listedUser->email) }}”? They will lose access immediately.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                @disabled($isSelf)
                                                class="rounded border border-line px-3 py-1 font-semibold {{ $isSelf ? 'cursor-not-allowed text-gray-300' : 'text-red-600 hover:border-red-500 hover:bg-red-50' }}"
                                                @if($isSelf) title="You cannot delete your own account" @endif>Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-body">No users yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
@endsection
