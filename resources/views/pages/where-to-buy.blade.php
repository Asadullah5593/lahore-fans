@extends('layouts.public')

@section('title', 'Where to Buy | Pakistan\'s Number One Fans Manufacturer in Lahore')
@section('description', 'Find authorised Lahore Fan dealers and outlets across Pakistan — Lahore, Karachi, Faisalabad, Gujranwala, Peshawar and more. Locate the shop nearest you on the map.')
@section('keywords', 'Lahore Fan, where to buy, dealers, outlets, Pakistan, Lahore, Karachi, Gujranwala, Faisalabad, ceiling fans, pedestal fans')
@section('og_title', 'Where to Buy | Lahore Fan')

@section('content')
    {{-- Page banner: solid dark on mobile, banner photo (with overlay) on desktop --}}
    <section class="page-banner relative">
        <div class="hidden lg:absolute lg:inset-0 lg:block lg:bg-black/40"></div>
        <div class="lf-container relative py-10 text-center lg:py-24">
            <h1 class="text-2xl font-bold capitalize text-white lg:text-4xl">Where to Buy</h1>
            <nav class="mt-2 text-sm font-semibold" aria-label="Breadcrumb">
                <a href="{{ config('brand.site_url') }}" class="text-white hover:text-accent">Home</a>
                <span class="px-2 text-white/70">&rsaquo;</span>
                <span class="text-accent">Where to Buy</span>
            </nav>
        </div>
    </section>

    <div id="where-to-buy" class="lf-container py-10 lg:py-14">
        {{-- Intro --}}
        <div class="mb-5 text-center">
            <p class="mx-auto max-w-2xl text-body">
                Lahore Fan products are available at authorised dealers across Pakistan.
                Use the map to find the outlet nearest you, or allow location access to jump straight to your city.
            </p>
        </div>

        {{-- Location button: pinned to the top of the screen on mobile while the
             store list scrolls (a direct child of the tall container so the
             sticky range spans the whole list); normal flow on desktop. --}}
        <div class="sticky top-0 z-40 -mx-4 mb-8 border-b border-line bg-white px-4 py-3 sm:-mx-[15px] sm:px-[15px] lg:static lg:z-auto lg:mx-0 lg:border-0 lg:bg-transparent lg:px-0 lg:py-0">
            <div class="flex justify-center">
                <button id="get-location-btn" type="button" class="lf-btn">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Get my location</span>
                </button>
            </div>
        </div>

        @if (empty($cities))
            <p class="rounded border border-line bg-gray-50 p-8 text-center text-body">
                Our dealer list is being updated. Please check back soon or contact us at
                <a href="tel:{{ config('brand.phone.tel') }}" class="font-semibold text-brand">{{ config('brand.phone.display') }}</a>.
            </p>
        @else
            <div class="flex flex-col gap-6 lg:flex-row">
                {{-- Store cards --}}
                <div id="stores-container" class="w-full space-y-6 lg:max-h-[80vh] lg:w-1/2 lg:overflow-y-auto lg:pr-3">
                    @foreach ($cities as $city)
                        <section class="city-group" data-city-id="{{ $city['id'] }}"
                                 data-lat="{{ $city['lat'] }}" data-lng="{{ $city['lng'] }}" data-zoom="{{ $city['zoom'] }}">
                            <h2 class="city-header mb-3 flex items-center gap-2 text-lg font-bold uppercase tracking-wide text-ink">
                                <span class="inline-block h-4 w-1 rounded bg-brand"></span>
                                {{ $city['name'] }}
                            </h2>
                            <div class="space-y-3">
                                @foreach ($city['stores'] as $store)
                                    <article class="store-card cursor-pointer rounded-lg border border-line bg-gray-50 px-4 py-3 leading-snug transition-all duration-300 hover:-translate-y-0.5 hover:shadow-card"
                                             data-store-id="{{ $store['id'] }}" data-lat="{{ $store['lat'] }}" data-lng="{{ $store['lng'] }}">
                                        <h3 class="text-[15px] font-bold text-ink">{{ $store['name'] }}</h3>
                                        @if (!empty($store['owner_name']))
                                            <p class="mt-0.5 text-body">Owner: {{ $store['owner_name'] }}</p>
                                        @endif
                                        <p class="mt-0.5 text-body">{{ $store['address'] }}</p>
                                        @php
                                            // Some dealers publish two numbers; show whichever exist.
                                            $numbers = array_filter([$store['phone'] ?? null, $store['phone_two'] ?? null]);
                                        @endphp
                                        @if ($numbers)
                                            <p class="mt-0.5 font-medium text-brand">Phone: {{ implode(' / ', $numbers) }}</p>
                                        @endif
                                        @if (!empty($store['google_maps_url']))
                                            <a href="{{ $store['google_maps_url'] }}" target="_blank" rel="noopener"
                                               class="mt-1.5 inline-flex items-center gap-1 font-semibold text-ink hover:text-accent"
                                               onclick="event.stopPropagation();">
                                                See more <span aria-hidden="true">&rarr;</span>
                                            </a>
                                        @endif
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>

                {{-- Map: pinned to the bottom of the screen on mobile while the
                     list scrolls (sticky within the list+map wrapper); unchanged
                     sticky side panel on desktop. --}}
                <div class="sticky bottom-2 z-30 w-full rounded-xl bg-white p-1.5 shadow-[0_2px_14px_rgba(0,0,0,0.2)] lg:static lg:z-auto lg:w-1/2 lg:rounded-none lg:bg-transparent lg:p-0 lg:shadow-none">
                    <div id="map" class="h-[30vh] w-full overflow-hidden rounded-lg border border-line lg:sticky lg:top-[110px] lg:h-[80vh] lg:rounded-none"></div>
                </div>
            </div>
        @endif
    </div>

    {{-- Data payload for the map script --}}
    <script type="application/json" id="wtb-data">@json($cities)</script>
@endsection

@push('scripts')
    @vite('resources/js/where-to-buy.js')
@endpush
