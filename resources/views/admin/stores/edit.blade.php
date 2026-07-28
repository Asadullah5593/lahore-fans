@extends('layouts.admin')

@section('title', 'Edit outlet')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.stores.index') }}" class="text-sm font-semibold text-body hover:text-brand">&larr; Back to outlets</a>
        <h1 class="mt-1 text-2xl font-bold text-ink">Edit outlet</h1>
        <p class="text-sm text-body">{{ $store->name }}</p>
    </div>

    <div class="rounded-lg border border-line bg-white p-6 shadow-sm">
        @include('admin.stores._form')
    </div>
@endsection
