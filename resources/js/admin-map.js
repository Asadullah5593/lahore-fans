import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

// Coordinate picker for the store form. Plain DOM wiring (no Alpine) so it works
// regardless of when this entry loads relative to the app's Alpine bootstrap.

const BRAND_GREEN = '#008242';

function pin() {
    return L.divIcon({
        className: 'wtb-pin',
        html: `<svg width="28" height="40" viewBox="0 0 24 36" xmlns="http://www.w3.org/2000/svg">
                 <path d="M12 0C5.4 0 0 5.4 0 12c0 9 12 24 12 24s12-15 12-24C24 5.4 18.6 0 12 0z" fill="${BRAND_GREEN}"/>
                 <circle cx="12" cy="12" r="5" fill="#ffffff"/>
               </svg>`,
        iconSize: [28, 40],
        iconAnchor: [14, 40],
    });
}

function initPicker() {
    const el = document.getElementById('store-map');
    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');
    if (!el || !latInput || !lngInput) return;

    let marker = null;

    const startLat = parseFloat(latInput.value);
    const startLng = parseFloat(lngInput.value);
    const hasPoint = Number.isFinite(startLat) && Number.isFinite(startLng);

    const map = L.map(el).setView(
        hasPoint ? [startLat, startLng] : [30.3753, 69.3451],
        hasPoint ? 14 : 5
    );
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    function placeMarker(lat, lng) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { icon: pin() }).addTo(map);
        }
    }

    if (hasPoint) placeMarker(startLat, startLng);

    // Click the map -> set the inputs and drop/move the pin. wrap() keeps the
    // longitude within ±180 even if the map has been panned around the world.
    map.on('click', (e) => {
        const point = e.latlng.wrap();
        const lat = Number(point.lat.toFixed(7));
        const lng = Number(point.lng.toFixed(7));
        latInput.value = lat;
        lngInput.value = lng;
        placeMarker(lat, lng);
    });

    /**
     * Extract a decimal "lat, lng" pair from text such as Google Maps'
     * "31.34351435477714, 74.15432451234". Exactly two numbers are required
     * (so DMS strings like 31°20'36.7"N are rejected rather than mis-read),
     * and both must be in range.
     */
    function parsePair(text) {
        const nums = String(text).match(/-?\d+(?:\.\d+)?/g) || [];
        if (nums.length !== 2) return null;
        const lat = parseFloat(nums[0]);
        const lng = parseFloat(nums[1]);
        if (!Number.isFinite(lat) || !Number.isFinite(lng)) return null;
        if (Math.abs(lat) > 90 || Math.abs(lng) > 180) return null;
        return [Number(lat.toFixed(7)), Number(lng.toFixed(7))];
    }

    function setPair([lat, lng]) {
        latInput.value = lat;
        lngInput.value = lng;
        placeMarker(lat, lng);
        map.setView([lat, lng], Math.max(map.getZoom(), 14));
    }

    // Pasting the whole "lat, lng" pair into either box fills both correctly.
    function handlePaste(e) {
        const pair = parsePair(e.clipboardData?.getData('text') ?? '');
        if (!pair) return; // single number or unrecognised text: paste as-is
        e.preventDefault();
        setPair(pair);
    }
    latInput.addEventListener('paste', handlePaste);
    lngInput.addEventListener('paste', handlePaste);

    // Typing coordinates -> move the pin. Also splits a full pair that ended
    // up in one box via autofill/drag-drop rather than paste.
    function syncFromInputs() {
        for (const input of [latInput, lngInput]) {
            const pair = parsePair(input.value);
            if (pair) {
                setPair(pair);
                return;
            }
        }
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        if (Number.isFinite(lat) && Number.isFinite(lng)) {
            placeMarker(lat, lng);
            map.panTo([lat, lng]);
        }
    }
    latInput.addEventListener('change', syncFromInputs);
    lngInput.addEventListener('change', syncFromInputs);
}

document.addEventListener('DOMContentLoaded', initPicker);
