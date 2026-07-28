{{-- Footer mirroring the live lahorefan.com chrome. Included inside
     layouts/public.blade.php, which already provides <head> and @vite.
     Every outbound link comes from config/brand.php. --}}
@php
    $footerLinks = config('brand.footer_links');
    $social = config('brand.social');
@endphp
<footer class="lf-footer">
        <div class="lf-footer__main">
            <div class="lf-footer__container">

                <div class="lf-footer__col lf-footer__col--brand">
                    <a href="{{ config('brand.site_url') }}" class="lf-footer__logo">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAApEAAAD6BAMAAAALqQFIAAADJmlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNy4yLWMwMDAgNzkuMWI2NWE3OWI0LCAyMDIyLzA2LzEzLTIyOjAxOjAxICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgMjMuNSAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NjYyNUZEMENBMTU2MTFFRDkzODRDOEJBRTY4RUI3ODAiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NjYyNUZEMERBMTU2MTFFRDkzODRDOEJBRTY4RUI3ODAiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo2NjI1RkQwQUExNTYxMUVEOTM4NEM4QkFFNjhFQjc4MCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo2NjI1RkQwQkExNTYxMUVEOTM4NEM4QkFFNjhFQjc4MCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Powjh3oAAAAZdEVYdFNvZnR3YXJlAEFkb2JlIEltYWdlUmVhZHlxyWU8AAAAJ1BMVEVHcEz7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"
                            alt="Lahore Fan" width="150" height="57">
                    </a>
                    <p class="lf-footer__desc">We are first designers and manufacturers of most original and unique
                        designs in the third generation of fan manufacturing.</p>
                    <div class="lf-footer__call">
                        <svg viewBox="0 0 24 24" class="lf-footer__call-icon" aria-hidden="true" fill="currentColor">
                            <path d="M12 3c-4.97 0-9 4.03-9 9v7c0 1.1.9 2 2 2h4v-8H5v-1c0-3.87 3.13-7 7-7s7 3.13 7 7v1h-4v8h4c1.1 0 2-.9 2-2v-7c0-4.97-4.03-9-9-9zM7 15v4H5v-4h2zm12 4h-2v-4h2v4z"/>
                        </svg>
                        <div>
                            <a href="tel:+923041116600" class="lf-footer__call-label">CALL US: 9AM TO 5PM</a>
                            <span class="lf-footer__call-number">+92 304 1116600</span>
                        </div>
                    </div>
                </div>

                <div class="lf-footer__col">
                    <h4 class="lf-footer__title">Useful Links</h4>
                    <ul class="lf-footer__links">
                        @foreach ($footerLinks['Useful Links'] as $link)
                            <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="lf-footer__col">
                    <h4 class="lf-footer__title">Categories</h4>
                    <ul class="lf-footer__links lf-footer__links--categories">
                        @foreach ($footerLinks['Categories'] as $link)
                            <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="lf-footer__col">
                    <h4 class="lf-footer__title">Contact Us</h4>
                    <ul class="lf-footer__contact">
                        <li>
                            <svg viewBox="0 0 24 24" class="lf-icon" aria-hidden="true">
                                <path fill="currentColor"
                                    d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.01-.24 11.36 11.36 0 0 0 3.58.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 11.36 11.36 0 0 0 .57 3.58 1 1 0 0 1-.25 1.01l-2.2 2.2Z" />
                            </svg>
                            <a href="tel:+923041116600">+92 304 1116600</a>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" class="lf-icon" aria-hidden="true">
                                <path fill="currentColor"
                                    d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Zm0 2v.01L12 12l8-5.99V6H4Zm16 12V8.24l-8 6-8-6V18h16Z" />
                            </svg>
                            <a href="mailto:lahorefans@gmail.com">lahorefans@gmail.com</a>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" class="lf-icon lf-icon--pin" aria-hidden="true">
                                <path fill="currentColor"
                                    d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z" />
                            </svg>
                            {{-- Opens the Google Maps pin, as on lahorefan.com.
                                 `.lf-footer__contact a:hover` already tints it green. --}}
                            <a href="{{ config('brand.address_map_url') }}" target="_blank" rel="noopener">LAHORE FANS, Kotli pir-ahmad,<br>NH 5, Kotli Pir Ahmed Shah,<br>Gujranwala, Punjab</a>
                        </li>
                    </ul>
                    <ul class="lf-footer__social">
                        <li><a href="{{ $social['facebook'] }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a></li>
                        <li><a href="{{ $social['instagram'] }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a></li>
                        <li><a href="{{ $social['youtube'] }}" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a></li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="lf-footer__bottom">
            <p>Copyright &copy; <span class="lf-footer__year"></span> Lahore Fans &ndash; All Rights Reserved. Developed
                by <a href="{{ config('brand.developer.url') }}" target="_blank" rel="noopener">{{ config('brand.developer.label') }}</a>.</p>
        </div>
    </footer>

    <script>
        document.querySelectorAll('.lf-footer__year').forEach(function(el) {
            el.textContent = new Date().getFullYear();
        });
    </script>

    {{-- Floating social bar — a round button per network that widens into a
         labelled pill on hover/focus, as on lahorefan.com. `items-start` keeps
         each button sized to its own content instead of stretching to the
         widest one. --}}
    @php
        $floatingSocial = [
            ['url' => config('brand.social.facebook'), 'label' => 'Facebook', 'icon' => 'bi-facebook'],
            ['url' => config('brand.social.instagram'), 'label' => 'Instagram', 'icon' => 'bi-instagram'],
            ['url' => config('brand.social.youtube'), 'label' => 'YouTube', 'icon' => 'bi-youtube'],
        ];
    @endphp
    <div class="fixed left-2.5 top-1/2 z-[9999] flex -translate-y-1/2 flex-col items-start gap-[0.15rem]">
        @foreach ($floatingSocial as $network)
            <a href="{{ $network['url'] }}" target="_blank" rel="noopener" aria-label="{{ $network['label'] }}"
               class="group flex h-11 items-center rounded-full bg-brand text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70">
                <span class="grid h-11 w-11 shrink-0 place-items-center text-xl"><i class="bi {{ $network['icon'] }}"></i></span>
                {{-- max-width animates where `width: auto` cannot; the padding
                     grows with it so the pill has no dead space when closed. --}}
                <span aria-hidden="true"
                      class="max-w-0 overflow-hidden whitespace-nowrap text-sm font-semibold transition-all duration-300 ease-out group-hover:max-w-[12rem] group-hover:pr-5 group-focus-visible:max-w-[12rem] group-focus-visible:pr-5">{{ $network['label'] }}</span>
            </a>
        @endforeach
    </div>

    {{-- Back to top — appears after scrolling, on every viewport as on lahorefan.com --}}
    <button type="button" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Back to top"
            x-data="{ show: false }" @scroll.window="show = window.scrollY > 300" x-show="show" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="scale-75 opacity-0"
            x-transition:enter-end="scale-100 opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="scale-100 opacity-100"
            x-transition:leave-end="scale-75 opacity-0"
            class="fixed bottom-12 right-3 z-[9999] flex h-11 w-11 items-center justify-center rounded-full bg-brand text-white shadow-lg transition hover:bg-brand-dark">
        <i class="bi bi-chevron-up"></i>
    </button>
