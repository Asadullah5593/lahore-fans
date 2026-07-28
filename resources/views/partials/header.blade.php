{{-- Header copied from the lf_design reference project. Included inside
     layouts/public.blade.php, which already provides <head> and @vite. --}}
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
                            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                            <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
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

            <a href="/" class="lf-logo">
                <img src="{{ asset('logo.png') }}" alt="Lahore Fan">
            </a>

            <span class="lf-mobile-bar-spacer" aria-hidden="true"></span>
        </div>

        {{-- Main navigation (desktop only) --}}
        <nav class="navbar navbar-expand-xl lf-main-nav d-none d-xl-flex">
            <div class="container-fluid lf-header-inner">

                <a class="navbar-brand lf-logo" href="/">
                    <img src="{{ asset('logo.png') }}" alt="Lahore Fan">
                </a>

                <div class="lf-nav-wrap">
                    <ul class="navbar-nav lf-nav align-items-xl-center">
                        <li class="nav-item">
                            <a class="nav-link active" href="/">Home</a>
                        </li>
                        <li class="nav-item lf-dropdown">
                            {{-- Like lahorefan.com: Shop is only a hover toggle for the
                                 dropdown — there is no shop page, clicking does nothing.
                                 A mouse click also blurs the link (event.detail is 0 for
                                 keyboard "clicks"), otherwise :focus-within would hold
                                 the dropdown open after the cursor leaves. --}}
                            <a class="nav-link lf-dropdown-toggle" href="#" onclick="if (event.detail) this.blur(); return false;">
                                Shop <i class="bi bi-chevron-down" style="margin-top: 5px;"></i>
                            </a>
                            <ul class="lf-dropdown-menu">
                                <li><a href="/ceiling-fans">Ceiling Fans</a></li>
                                <li><a href="/bracket-fans">Bracket Fans</a></li>
                                <li><a href="/pedestal-fan">Pedestal Fan</a></li>
                                <li><a href="/exhaust-fan">Exhaust Fan</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/about">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/contact">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/blog">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/faqs">FAQs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/hybrid-econo-power">Hybrid Econo Power</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/conventional-motor">Conventional Motor</a>
                        </li>
                    </ul>
                </div>

                <div class="lf-cta-group">
                    <a href="/ravian" class="btn btn-lf-solid">Ravian</a>
                    <a href="/catalogue" class="btn btn-lf-solid">Catalogue</a>
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

                <a href="/" class="lf-logo" id="mainNavOffcanvasLabel">
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
                                    <a href="/">Home</a>
                                </li>
                                <li class="lf-drawer-item">
                                    <a href="/shop" data-open-panel="shop">Shop <i
                                            class="bi bi-chevron-right"></i></a>
                                </li>
                                <li class="lf-drawer-item">
                                    <a href="/about">About</a>
                                </li>
                                <li class="lf-drawer-item">
                                    <a href="/contact">Contact</a>
                                </li>
                                <li class="lf-drawer-item">
                                    <a href="/faqs">FAQs</a>
                                </li>
                                <li class="lf-drawer-item">
                                    <a href="/ravian">Ravian</a>
                                </li>
                            </ul>
                        </div>

                        <div class="lf-drawer-panel">
                            <button type="button" class="lf-drawer-back" data-close-panel>
                                <i class="bi bi-chevron-left"></i> Back
                            </button>
                            <ul class="lf-drawer-nav list-unstyled mb-0">
                                <li class="lf-drawer-item">
                                    <a href="/ceiling-fans">Ceiling Fans</a>
                                </li>
                                <li class="lf-drawer-item">
                                    <a href="/bracket-fans">Bracket Fans</a>
                                </li>
                                <li class="lf-drawer-item">
                                    <a href="/pedestal-fan">Pedestal Fans</a>
                                </li>
                                <li class="lf-drawer-item">
                                    <a href="/exhaust-fan">Exhaust Fans</a>
                                </li>
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
                        <a href="https://goo.gl/maps/ZK9WUARk6hoyrmPr8" target="_blank" rel="noopener">Location : 55X7+FF Gujranwala</a>
                    </div>
                </div>
            </div>
        </div>
    </header>