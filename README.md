# 🍽️ WorkInsight — Platforma za upravljanje dostavom hrane

> Pametna platforma za organizaciju, narudžbe i dostavu obroka — za tvrtke, umirovljenike i individualne korisnike.

🌐 **[workinsight.eu](https://workinsight.eu/)**

---

## 📖 O projektu

**WorkInsight** je modularni Laravel monolit dizajniran za upravljanje cjelokupnim procesom dostave hrane — od tjednih menija i narudžbi do live praćenja vozača i automatske naplate.

Sustav pokriva četiri korisničke role:

| Sučelje | Uloga | Tehnologija |
|---|---|---|
| 🛡️ **Admin / Kuhinja** | Upravljanje svim entitetima, kuhinjski pregled, izvještaji | Filament v4 + Tailwind 4 |
| 👤 **Klijent (fizička osoba)** | Tjedni meni, narudžbe, pretplate | Livewire + Blade + Tabler.io |
| 🏢 **Tvrtka** | Narudžbe po danima/tjednu, izmjena količina | Livewire + Blade + Tabler.io |
| 🚗 **Vozač** | Ruta, statusi dostave, live GPS (mobile-first PWA) | Livewire + Blade + Tabler.io |

---

## ⚙️ Tech stack

**Backend**
- 🐘 PHP 8.3+ / **Laravel 13**
- 🗄️ MySQL 8
- ⚡ Redis — queue, cache, sesije, presence za broadcasting
- 🔄 Laravel Horizon — nadzor queue workera
- 📡 Laravel Reverb — self-hosted WebSocket (live GPS vozača → admin karta)

**Frontend**
- 🎛️ Filament v4 (admin panel)
- 🌊 Livewire + Alpine.js + Tabler.io (Bootstrap 5 — kupljeni layout)
- ⚡ Vite — dva odvojena bundlea (Filament/Tailwind + Tabler/Bootstrap)

**Auth & ovlasti**
- 🔐 Laravel Fortify + Socialite (Google OAuth)
- 🎭 spatie/laravel-permission — 5 rola: `admin`, `customer`, `company`, `driver`, `kitchen`

**Integracije**
- 🗺️ Google Maps JS API + Geocoding API (karte i geokodiranje adresa)
- 📱 Browser Geolocation API (`watchPosition`) za GPS vozača
- 💬 Twilio — SMS notifikacije (queue, s retry)
- 📄 eRačuni — automatski B2B export (idempotentni queued job)
- 🔗 Para program — sync umirovljenika (anti-corruption adapter, API ili periodični import)
- 📝 PDF ugovori (Spatie Laravel-PDF)

**Kvaliteta**
- 🧪 PHPUnit / Pest testovi
- 🔍 Larastan / PHPStan statička analiza
- 💅 Laravel Pint formatiranje
- 🤖 Laravel Boost — AI agent alati

---

## 🗃️ Shema baze podataka

Baza je dizajnirana s naglaskom na **clean domain** i **scale-ready arhitekturu**:

- **Identitet:** `users`, `staff_profiles`, `customers`, `companies`, `pensioners`
- **Meniji:** `meals`, `menus`, `menu_items`, `meal_categories`
- **Narudžbe:** `orders`, `order_lines`, `subscriptions`
- **Dostava:** `delivery_lines`, `line_assignments`, `deliveries`, `delivery_items`, `delivery_status_logs`, `driver_locations`
- **Ugovori:** `contracts`
- **Naplata:** `invoice_exports`, `invoice_export_lines`, `monthly_reports`
- **Integracije:** `para_sync_runs`, `settings`
- **Komunikacija:** `notifications`, `sms_logs`, `mail_logs`

Visokovolumne tablice (`driver_locations`, `delivery_status_logs`) su **particionirane** i koriste Redis za live podatke.

Detaljna ERD dokumentacija dostupna je u [`docs/ERD_shema_baze.md`](docs/ERD_shema_baze.md).

---

## 🚀 Instalacija (lokalno)

### Preduvjeti

- PHP 8.3+
- Composer
- Node.js 20+
- MySQL 8 ili SQLite (dev)
- Redis

### Koraci

```bash
# Kloniraj repozitorij
git clone https://github.com/mkopcic/workinsight.eu.git
cd workinsight.eu

# Instaliraj dependencies i postavi aplikaciju
composer run setup
```

Skripta `setup` automatski:
1. Instalira PHP dependencies (`composer install`)
2. Kopira `.env.example` u `.env` i generira ključ
3. Pokreće migracije
4. Instalira Node dependencies i builda assete

### Ručno postavljanje

```bash
composer install
cp .env.example .env
php artisan key:generate

# Konfiguracija baze u .env, zatim:
php artisan migrate --seed

npm install
npm run build
```

### Lokalni development server

```bash
composer run dev
```

Pokreće paralelno: Laravel server, queue worker, Pail log viewer i Vite dev server.

---

## 🧪 Testovi

```bash
# Svi testovi
php artisan test --compact

# Samo određeni test
php artisan test --compact --filter=testName
```

---

## 📐 Arhitektura

Detaljna tehnička dokumentacija dostupna u [`docs/Arhitektura_i_workflow.md`](docs/Arhitektura_i_workflow.md).

### Ključne arhitekturalne odluke

- **Modularni monolit** — jedna baza, domenski servisi (`App\Domain\Orders`, `App\Domain\Delivery`...)
- **Dva CSS frameworka** bez konflikta — stroga granica po route grupama (`/admin` = Tailwind, ostalo = Bootstrap)
- **Live GPS bez native appa** — Browser Geolocation → Reverb WebSocket → admin karta
- **Anti-corruption layer** za Para i eRačune — vanjske sheme ne „cure" u domenu
- **Materijalizirani sažeci** za analitiku — dashboard ne skenira žive tablice

### Faze razvoja

| Faza | Opis |
|---|---|
| 1️⃣ Analiza | Zaključavanje verzija, ERD, izvedivost integracija |
| 2️⃣ Skelet | Projekt, Vite, Fortify+RBAC, Filament shell, profili |
| 3️⃣ Meniji i narudžbe | Tjedni meniji, narudžbe FO/tvrtki, kuhinjski pregled |
| 4️⃣ Dostava | Linije, statusi, kantica logika, Maps, Reverb GPS, Twilio |
| 5️⃣ Ugovori / Računi | PDF, eRačuni, analitika |
| 6️⃣ Stabilizacija | Testovi, Hetzner deploy, backup, primopredaja |

---

## 🏗️ Infrastruktura

- **Server:** Hetzner (Ubuntu LTS, Nginx + PHP-FPM, Webmin)
- **Deploy:** GitHub Actions → SSH/Deployer (zero-downtime, `releases/` + symlink)
- **Storage:** Hetzner Object Storage (S3-kompatibilni) za ugovore
- **CI/CD:** Pint → Larastan → PHPUnit na svakom PR-u

---

## 📄 Licenca

Privatni projekt — sva prava pridržana. © 2025–2026 [workinsight.eu](https://workinsight.eu/)
