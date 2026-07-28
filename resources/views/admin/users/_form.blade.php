@php
    $editing = $user->exists;
    $action = $editing ? route('admin.users.update', $user) : route('admin.users.store');
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

<form method="POST" action="{{ $action }}" class="max-w-xl space-y-4">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div>
        <label for="name" class="mb-1 block text-sm font-semibold text-ink">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="{{ $inputClass }}" required autocomplete="name">
    </div>

    <div>
        <label for="email" class="mb-1 block text-sm font-semibold text-ink">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="{{ $inputClass }}" required autocomplete="username">
        <p class="mt-1 text-xs text-body">Used to sign in to this admin panel.</p>
    </div>

    <div>
        <label for="password" class="mb-1 block text-sm font-semibold text-ink">
            Password
            @if ($editing)
                <span class="font-normal text-body">(leave blank to keep the current one)</span>
            @endif
        </label>
        <input id="password" name="password" type="password" class="{{ $inputClass }}" autocomplete="new-password" @required(! $editing)>
        <p class="mt-1 text-xs text-body">
            At least 12 characters, with upper and lower case letters, a number and a symbol.
        </p>
    </div>

    <div>
        <label for="password_confirmation" class="mb-1 block text-sm font-semibold text-ink">Confirm password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="{{ $inputClass }}" autocomplete="new-password" @required(! $editing)>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="lf-btn">{{ $editing ? 'Save changes' : 'Create user' }}</button>
        <a href="{{ route('admin.users.index') }}" class="rounded border border-line px-5 py-2.5 text-sm font-semibold text-ink hover:border-brand hover:text-brand">Cancel</a>
    </div>
</form>
