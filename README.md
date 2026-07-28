# Lahore Fan — Where to Buy (Outlets)

A small, self-contained Laravel 12 application that powers a **"Where to Buy"** page for
[lahorefan.com](https://lahorefan.com): a searchable, map-based dealer/outlet locator plus a
lightweight admin panel to manage the outlets and cities.

It is designed to run on its **own subdomain** (e.g. `outlets.lahorefan.com`) and to look and feel
like a native page of the main WordPress site. The WordPress side needs **no plugin and no
integration** — the "Where to Buy" button is simply a link to this app's URL.

---

## What's inside

| Area | Details |
|------|---------|
| **Public page** (`/`) | City-grouped outlet cards + an interactive Leaflet/OpenStreetMap map. "Get my location" highlights the nearest city (browser geolocation + local distance math — **no paid maps API, no API key**). |
| **Admin panel** (`/admin`) | Login-protected CRUD for **Outlets** and **Cities**, styled in the Lahore Fan green theme. Includes a click-to-set map picker for coordinates and an active/hidden toggle per outlet. |
| **Auth** | Laravel Breeze (login only — **public registration is disabled**). Admins are created via the seeder or `php artisan tinker`. |
| **Design** | Rebuilt header/footer chrome, colours (brand green `#008242`), and Manrope typography to match lahorefan.com. |

The public outlet data is cached and **automatically refreshed** whenever an admin adds, edits, or
deletes an outlet or city, so changes appear on the public page immediately.

---

## Requirements

- PHP **8.2+** (tested on 8.4) with `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `curl`
- Composer 2
- MySQL 5.7+/8.0
- Node 18+ / npm (only needed to build front-end assets)

---

## Local setup

```bash
# 1. Install dependencies
composer install
npm install

# 2. Environment
cp .env.example .env         # if starting fresh; this repo already has a working .env
php artisan key:generate

# 3. Configure the database in .env
#    DB_DATABASE=lahorefan_outlets   (create this database first)
#    DB_USERNAME=... DB_PASSWORD=...

# 4. Migrate + seed (creates the admin user + demo outlets)
php artisan migrate --seed

# 5. Build assets
npm run build        # or: npm run dev   (for hot reload during development)

# 6. Serve
php artisan serve
```

### Admin login

The **first** time you run `php artisan db:seed`, a random admin password is generated and printed
to the console — copy it. The account is:

- **Email:** `admin@lahorefan.com`
- **Password:** *(shown once at seed time)*

To reset it later, or create another admin:

```bash
php artisan tinker
>>> \App\Models\User::where('email','admin@lahorefan.com')->update(['password'=>bcrypt('your-new-password')]);
# or create a new admin:
>>> \App\Models\User::create(['name'=>'Name','email'=>'you@lahorefan.com','password'=>bcrypt('secret')]);
```

### Demo data

The seeder loads the **13 major Pakistan cities** (with map coordinates) and **7 placeholder
outlets** so the page works on day one. These placeholders are carried over from the reference
project — **replace them with Lahore Fan's real dealer list** through the admin panel. Deleting all
placeholder outlets is safe.

---

## Managing outlets & cities

- **Cities** define the grouping order on the public page and where the map focuses. Add a city
  first (name + centre coordinates + zoom), then add outlets to it. An outlet's *city* is chosen
  from a dropdown, so a typo can't create a stray group.
- **Outlets** have a name, address, phone, a precise map pin (lat/lng — click the map picker or
  paste coordinates from Google Maps), an optional Google Maps "See more" link, an **Active**
  toggle (hide without deleting), and a sort order.
- A city can't be deleted while it still has outlets — reassign or remove them first.

---

## Production deployment (subdomain + SSL)

The recommended setup is a dedicated subdomain such as **`outlets.lahorefan.com`**.

### 1. DNS
Add an **A record** for the subdomain pointing to the server that will host this app (the same
cPanel/hosting as the WordPress site, or a separate VPS — either works).

```
outlets   A   <server-ip>
```

### 2. Web server / document root
- **cPanel:** create the subdomain and set its **document root to this app's `public/` folder**.
  Ensure PHP is **8.2+** and create a MySQL database + user.
- **Nginx/Apache VPS:** point the vhost `root` at `.../lahorefan_outlets/public` with the standard
  Laravel rewrite (front controller `index.php`).

### 3. Deploy the code
```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force --seed
php artisan config:cache route:cache view:cache
```
Set in `.env`:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://outlets.lahorefan.com
```
Make `storage/` and `bootstrap/cache/` writable by the web server.

> If Composer/SSH isn't available on shared hosting, upload the project **including the `vendor/`
> folder**, then run the migrate/seed step via the hosting panel's terminal or a one-off script.

### 4. SSL
Issue a certificate for the subdomain via **AutoSSL / Let's Encrypt** (one click in most cPanel
setups). Force HTTPS.

---

## Linking from the WordPress site

This is the **only** change needed on the WordPress side — no plugin, no API. The WP team adds a
menu item or button that links to the app:

```
https://outlets.lahorefan.com
```

(The `/where-to-buy` path also works and permanently redirects to the root.)

---

## Tech notes

- **No external map billing:** Leaflet + OpenStreetMap tiles; marker icons and Leaflet assets are
  bundled locally via Vite (no CDN hotlinking).
- **Nearest-outlet:** browser Geolocation API + a Haversine distance calculation against the city
  list — entirely client-side, no reverse-geocoding service.
- **Caching:** the public payload is cached (`CACHE_STORE=file` by default) and invalidated by model
  observers on any store/city change.
- Brand URLs, phone, social links, and navigation live in [`config/brand.php`](config/brand.php) —
  update them there if the main site changes.
