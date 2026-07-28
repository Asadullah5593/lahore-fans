@extends('layouts.admin')

@section('title', 'Add user')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-body hover:text-brand">&larr; Back to users</a>
        <h1 class="mt-1 text-2xl font-bold text-ink">Add user</h1>
    </div>

    <div class="rounded-lg border border-line bg-white p-6 shadow-sm">
        @include('admin.users._form')
    </div>
@endsection
