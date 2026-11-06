{{-- Header mirroring the live lahorefan.com chrome. Included inside
     layouts/public.blade.php, which already provides <head> and @vite.
     Every outbound link comes from config/brand.php. --}}
@php
    // Keyed by label so the markup can stay hand-written — the dropdown and the
    // drawer panels have structure a generic loop would flatten — while still
    // taking every URL from config rather than hardcoding it here.
    $nav = collect(config('brand.nav'))->keyBy('label');
    $social = config('brand.social');
    $siteUrl = config('brand.site_url');
@endphp
<header class="site-header">
        {{-- Top info bar (desktop only) --}}
        <div class="lf-topbar d-none d-xl-block">
            <div class="container-fluid lf-header-inner">
                <div class="row-flex">
                    <div class="spacer" aria-hidden="true"></div>

                    <div class="lf-topbar-message">
                        Pakistan's 1st Designer Fan
                    </div>

                    <div class="lf-topbar-right">
                        <a href="tel:+923041116600" class="lf-topbar-phone">
                            <i class="bi bi-telephone"></i>
                            +92 304 1116600
                        </a>

                        <div class="lf-social">
                            <a href="{{ $social['facebook'] }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                            <a href="{{ $social['instagram'] }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                            <a href="{{ $social['youtube'] }}" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Compact bar (mobile / tablet only) --}}
        <div class="lf-mobile-bar d-flex d-xl-none align-items-center justify-content-between">
            <button type="button" class="lf-icon-btn" data-bs-toggle="offcanvas" data-bs-target="#mainNavOffcanvas"
                aria-controls="mainNavOffcanvas" aria-label="Open menu">
                <i class="bi bi-list"></i>
            </button>

            <a href="{{ $siteUrl }}" class="lf-logo">
                <img src="{{ asset('logo.png') }}" alt="Lahore Fan">
            </a>

            <span class="lf-mobile-bar-spacer" aria-hidden="true"></span>
        </div>

        {{-- Main navigation (desktop only) --}}
        <nav class="navbar navbar-expand-xl lf-main-nav d-none d-xl-flex">
            <div class="container-fluid lf-header-inner">

                <a class="navbar-brand lf-logo" href="{{ $siteUrl }}">
                    <img src="{{ asset('logo.png') }}" alt="Lahore Fan">
                </a>

                <div class="lf-nav-wrap">
                    <ul class="navbar-nav lf-nav align-items-xl-center">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ $nav['Home']['url'] }}">Home</a>
                        </li>
                        <li class="nav-item lf-dropdown">
                            {{-- Like lahorefan.com: hovering Shop opens the category
                                 dropdown, clicking it goes to the shop page. --}}
                            <a class="nav-link lf-dropdown-toggle" href="{{ $nav['Shop']['url'] }}">
                                Shop <i class="bi bi-chevron-down" style="margin-top: 5px;"></i>
                            </a>
                            <ul class="lf-dropdown-menu">
                                @foreach ($nav['Shop']['children'] as $child)
                                    <li><a href="{{ $child['url'] }}">{{ Str::title($child['label']) }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $nav['About']['url'] }}">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $nav['Contact']['url'] }}">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $nav['Blog']['url'] }}">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $nav['FAQs']['url'] }}">FAQs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $nav['Hybrid Econo Power']['url'] }}">Hybrid Econo Power</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $nav['Conventional Motor']['url'] }}">Conventional Motor</a>
                        </li>
                    </ul>
                </div>

                {{-- Same two buttons, in the same order, as the lahorefan.com header.
                     "Dealers Location" is this app, so it links to our own home page. --}}
                <div class="lf-cta-group">
                    <a href="{{ route('where-to-buy') }}" class="btn btn-lf-solid">Dealers Location</a>
                    <a href="{{ config('brand.ravian_url') }}" target="_blank" rel="noopener" class="btn btn-lf-solid">Ravian</a>
                </div>
            </div>
        </nav>

        {{-- Navigation drawer (mobile / tablet only), slides in from the left --}}
        <div class="offcanvas offcanvas-start lf-offcanvas" tabindex="-1" id="mainNavOffcanvas"
            aria-labelledby="mainNavOffcanvasLabel">

            <div class="offcanvas-header lf-offcanvas-header">
                <button type="button" class="lf-icon-btn" data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>

                <a href="{{ $siteUrl }}" class="lf-logo" id="mainNavOffcanvasLabel">
                    <img src="{{ asset('logo.png') }}" alt="Lahore Fan">
                </a>

                <span class="lf-mobile-bar-spacer" aria-hidden="true"></span>
            </div>

            <div class="offcanvas-body lf-offcanvas-body">
                <div class="lf-drawer-panels">
                    <div class="lf-drawer-panel-track">
                        <div class="lf-drawer-panel">
                            <ul class="lf-drawer-nav list-unstyled mb-0">
                                <li class="lf-drawer-item active">
                                    <a href="{{ $nav['Home']['url'] }}">Home</a>
                                </li>
                                <li class="lf-drawer-item">
                                    {{-- JS intercepts this to slide the Shop panel in; the
                                         href is the no-JS fallback. --}}
                                    <a href="{{ $nav['Shop']['url'] }}" data-open-panel="shop">Shop <i
                                            class="bi bi-chevron-right"></i></a>
                                </li>
                                <li class="lf-drawer-item">
                                    <a href="{{ $nav['About']['url'] }}">About</a>
                                </li>
                                <li class="lf-drawer-item">
                                    <a href="{{ $nav['Contact']['url'] }}">Contact</a>
                                </li>
                                <li class="lf-drawer-item">
                                    <a href="{{ $nav['FAQs']['url'] }}">FAQs</a>
                                </li>
                                <li class="lf-drawer-item">
                                    <a href="{{ route('where-to-buy') }}">Dealers Location</a>
                                </li>
                                <li class="lf-drawer-item">
                                    <a href="{{ config('brand.ravian_url') }}" target="_blank" rel="noopener">Ravian</a>
                                </li>
                            </ul>
                        </div>

                        <div class="lf-drawer-panel">
                            <button type="button" class="lf-drawer-back" data-close-panel>
                                <i class="bi bi-chevron-left"></i> Back
                            </button>
                            <ul class="lf-drawer-nav list-unstyled mb-0">
                                @foreach ($nav['Shop']['children'] as $child)
                                    <li class="lf-drawer-item">
                                        <a href="{{ $child['url'] }}">{{ Str::title($child['label']) }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="lf-drawer-footer">
                    <div class="lf-drawer-hotline">
                        <i class="bi bi-headset"></i>
                        <span>HOTLINE <a href="tel:+923041116600">+92 304 111 6600</a></span>
                    </div>
                    <div class="lf-drawer-location">
                        <a href="{{ config('brand.address_map_url') }}" target="_blank" rel="noopener">Location : 55X7+FF Gujranwala</a>
                    </div>
                </div>
            </div>
        </div>
    </header>