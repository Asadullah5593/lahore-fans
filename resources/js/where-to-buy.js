import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

// Brand colours (kept in sync with tailwind.config.js).
const BRAND_GREEN = '#008242';
const ACCENT_ORANGE = '#ff8400';

// --- Data ------------------------------------------------------------------

const dataEl = document.getElementById('wtb-data');
const cities = dataEl ? JSON.parse(dataEl.textContent) : [];

// Flat store index: id -> { store, cityId, marker }
const storeIndex = new Map();

// --- Map -------------------------------------------------------------------

const mapEl = document.getElementById('map');
let map;

/** Build a coloured teardrop pin as a Leaflet divIcon (no external images). */
function makePin(color) {
    return L.divIcon({
        className: 'wtb-pin',
        html: `<svg width="28" height="40" viewBox="0 0 24 36" xmlns="http://www.w3.org/2000/svg">
                 <path d="M12 0C5.4 0 0 5.4 0 12c0 9 12 24 12 24s12-15 12-24C24 5.4 18.6 0 12 0z" fill="${color}"/>
                 <circle cx="12" cy="12" r="5" fill="#ffffff"/>
               </svg>`,
        iconSize: [28, 40],
        iconAnchor: [14, 40],
        popupAnchor: [0, -36],
    });
}

const greenPin = makePin(BRAND_GREEN);
const orangePin = makePin(ACCENT_ORANGE);

/** Popup content built from data (textContent — never HTML interpolation). */
function buildPopup(store) {
    const wrap = document.createElement('div');
    wrap.className = 'text-sm';

    const name = document.createElement('strong');
    name.textContent = store.name;
    wrap.appendChild(name);

    if (store.owner_name) {
        const owner = document.createElement('div');
        owner.textContent = `Owner: ${store.owner_name}`;
        wrap.appendChild(owner);
    }

    const addr = document.createElement('div');
    addr.textContent = store.address;
    wrap.appendChild(addr);

    // Dealers may list a second number; a dealer with none shows no phone line.
    const numbers = [store.phone, store.phone_two].filter(Boolean);
    if (numbers.length) {
        const phone = document.createElement('div');
        phone.textContent = `Phone: ${numbers.join(' / ')}`;
        wrap.appendChild(phone);
    }

    if (store.google_maps_url) {
        const link = document.createElement('a');
        link.href = store.google_maps_url;
        link.target = '_blank';
        link.rel = 'noopener';
        link.textContent = 'See more →';
        link.style.color = BRAND_GREEN;
        link.style.fontWeight = '600';
        wrap.appendChild(link);
    }
    return wrap;
}

function initMap() {
    if (!mapEl) return;

    // Centre roughly on Pakistan until we know the user's location.
    map = L.map(mapEl, { scrollWheelZoom: false }).setView([30.3753, 69.3451], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);

    const bounds = [];

    cities.forEach((city) => {
        city.stores.forEach((store) => {
            const marker = L.marker([store.lat, store.lng], { icon: greenPin }).addTo(map);
            marker.bindPopup(buildPopup(store));
            storeIndex.set(store.id, { store, cityId: city.id, marker });
            bounds.push([store.lat, store.lng]);
        });
    });

    if (bounds.length) {
        map.fitBounds(bounds, { padding: [40, 40], maxZoom: 12 });
    }

    // The mobile and desktop layouts differ structurally (pinned bottom card
    // vs sticky side panel), and Chrome keeps stale composited layers for the
    // map and sticky boxes when the viewport crosses the breakpoint — no DOM
    // reflow reliably clears it. A clean reload is the only dependable reset;
    // the page rebuilds its own state on load (geolocation re-runs and the
    // list reorders again). Debounced so dragging a resize handle across the
    // boundary reloads once, not per pixel.
    const desktopMq = window.matchMedia('(min-width: 1024px)');
    let reloadTimer;
    desktopMq.addEventListener('change', () => {
        clearTimeout(reloadTimer);
        reloadTimer = setTimeout(() => window.location.reload(), 250);
    });
}

/** Centre the map on a store and open its popup. */
function focusStore(storeId) {
    const entry = storeIndex.get(storeId);
    if (!entry || !map) return;

    map.flyTo([entry.store.lat, entry.store.lng], 15, { duration: 0.8 });
    // Open the popup once the flight lands, so its auto-pan doesn't fight
    // the animation mid-air.
    map.once('moveend', () => entry.marker.openPopup());

    document.querySelectorAll('.store-card').forEach((c) => c.classList.remove('ring-2', 'ring-brand'));
    const card = document.querySelector(`.store-card[data-store-id="${storeId}"]`);
    if (card) card.classList.add('ring-2', 'ring-brand');
}

// --- Geolocation + nearest city -------------------------------------------

function haversine(lat1, lng1, lat2, lng2) {
    const toRad = (d) => (d * Math.PI) / 180;
    const R = 6371; // km
    const dLat = toRad(lat2 - lat1);
    const dLng = toRad(lng2 - lng1);
    const a =
        Math.sin(dLat / 2) ** 2 +
        Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLng / 2) ** 2;
    return 2 * R * Math.asin(Math.sqrt(a));
}

/** Nearest city (that has dealers) to the given coordinates. */
function nearestCity(lat, lng) {
    let best = null;
    let bestDist = Infinity;
    cities.forEach((city) => {
        const d = haversine(lat, lng, city.lat, city.lng);
        if (d < bestDist) {
            bestDist = d;
            best = city;
        }
    });
    return best;
}

/** Move the user's city group to the top of the list. */
function reorderCity(cityId) {
    const container = document.getElementById('stores-container');
    const group = container?.querySelector(`.city-group[data-city-id="${cityId}"]`);
    if (container && group) {
        container.prepend(group);
        container.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

/** Highlight the user's city: cards, header, and map markers. */
function highlightCity(cityId) {
    // Reset all cards/markers to default.
    document.querySelectorAll('.store-card').forEach((c) => {
        c.classList.remove('store-card--near');
    });
    storeIndex.forEach((entry) => entry.marker.setIcon(greenPin));

    // Emphasise the chosen city (tinted green card, see app.css).
    document.querySelectorAll(`.city-group[data-city-id="${cityId}"] .store-card`).forEach((c) => {
        c.classList.add('store-card--near');
    });
    storeIndex.forEach((entry) => {
        if (entry.cityId === cityId) entry.marker.setIcon(orangePin);
    });

    const city = cities.find((c) => c.id === cityId);
    if (city && map) {
        // Fit every outlet of the city on screen. The fixed per-city zoom was
        // tuned for the large desktop map and cuts markers off on the small
        // mobile card; bounds adapt to whatever size the map currently is.
        // The city zoom still caps it so one-outlet cities don't zoom to
        // rooftop level.
        const points = city.stores.map((s) => [s.lat, s.lng]);
        if (points.length) {
            // flyToBounds glides smoothly even across big jumps (country view
            // -> one city), where fitBounds just snaps.
            map.flyToBounds(points, { padding: [30, 30], maxZoom: city.zoom || 14, duration: 1.1 });
        } else {
            map.flyTo([city.lat, city.lng], city.zoom, { duration: 1.1 });
        }
    }
}

function setButtonLoading(btn, loading) {
    if (!btn) return;
    btn.disabled = loading;
    btn.classList.toggle('opacity-70', loading);
    btn.querySelector('span').textContent = loading ? 'Locating…' : 'Get my location';
}

function locateUser(userInitiated) {
    const btn = document.getElementById('get-location-btn');
    if (!navigator.geolocation) {
        if (userInitiated) alert('Geolocation is not supported by your browser.');
        return;
    }

    setButtonLoading(btn, true);

    navigator.geolocation.getCurrentPosition(
        (pos) => {
            setButtonLoading(btn, false);
            const city = nearestCity(pos.coords.latitude, pos.coords.longitude);
            if (city) {
                reorderCity(city.id);
                highlightCity(city.id);
            }
        },
        (err) => {
            setButtonLoading(btn, false);
            // Only surface an error when the user explicitly asked; the silent
            // auto-attempt just leaves the map at its default view.
            if (userInitiated && err.code === err.PERMISSION_DENIED) {
                alert('Location access was denied. Allow it in your browser to see dealers near you.');
            }
        },
        { enableHighAccuracy: false, timeout: 8000, maximumAge: 300000 }
    );
}

// --- Wire up ---------------------------------------------------------------

document.addEventListener('DOMContentLoaded', () => {
    initMap();

    document.querySelectorAll('.store-card').forEach((card) => {
        card.addEventListener('click', () => {
            focusStore(Number(card.dataset.storeId));
        });
    });

    document.getElementById('get-location-btn')?.addEventListener('click', () => locateUser(true));

    // Scroll-reveal: cards and city headers fade up (staggered) as they enter
    // the viewport. The hiding class is added here, not in the markup, so
    // content is never invisible if scripts fail. Classes are removed after
    // the animation so hover transforms take over cleanly.
    const revealEls = document.querySelectorAll('.store-card, .city-header');
    if (revealEls.length && 'IntersectionObserver' in window) {
        const io = new IntersectionObserver(
            (entries) => {
                entries
                    .filter((e) => e.isIntersecting)
                    .forEach((entry, i) => {
                        const el = entry.target;
                        el.style.transitionDelay = `${i * 45}ms`;
                        el.classList.add('is-visible');
                        io.unobserve(el);
                        setTimeout(() => {
                            el.classList.remove('wtb-reveal', 'is-visible');
                            el.style.transitionDelay = '';
                        }, 650 + i * 45);
                    });
            },
            { rootMargin: '0px 0px -8% 0px', threshold: 0.05 }
        );

        revealEls.forEach((el) => {
            el.classList.add('wtb-reveal');
            io.observe(el);
        });
    }

    // Auto-request on load (silent fallback if denied).
    setTimeout(() => locateUser(false), 600);
});
