# Tehnička arhitektura i projektni workflow
### Platforma za upravljanje dostavom hrane (Laravel)

> Dokument za interni razvojni tim. Uloga autora: glavni razvojni arhitekt.
> Zadana ograničenja: Laravel, MySQL, Blade, Livewire, Alpine, vanilla JS, kupljeni **Tabler.io** layout (Bootstrap 5). Otvoreno za Filament + Tailwind 4 gdje ima smisla.

---

## 1. Glavna arhitektonska odluka (i zamka koju rješavamo)

Imamo dvije „istine" koje se na prvi pogled sukobljavaju:

- **Tabler.io = Bootstrap 5** (kupljen, želimo ga iskoristiti).
- **Filament = Tailwind** (Filament v3 → Tailwind 3, Filament v4 → Tailwind 4). Filament je daleko najbrži način da se izgradi admin (tablice, filteri, forme, RBAC, CRUD nad ~15 entiteta).

Ne želimo voditi rat protiv Filamenta i tjerati ga na Bootstrap (to je tjedni izgubljenog rada), ni odbaciti Tabler koji je plaćen. **Rješenje: hibridni pristup s jasnom granicom po rolama/route grupama.**

| Sloj / sučelje | Tehnologija | Zašto |
|---|---|---|
| **Admin + Kuhinja** (back-office) | **Filament v4 (Tailwind 4)** | CRUD-teško, puno entiteta, tablice/filteri/izvještaji „iz kutije". Filament ovdje štedi tjedne. |
| **Klijent (fizička osoba)** | **Livewire + Blade + Tabler + Alpine** | Brendirano, custom UX (tjedni meni, odabir obroka, 7 dana unaprijed). |
| **Tvrtka (pravna osoba)** | **Livewire + Blade + Tabler + Alpine** | Custom narudžbe po danima/tjednu, izmjene količina. |
| **Vozač** | **Livewire + Blade + Tabler + Alpine, mobile-first (PWA)** | Terenski rad, velike tipke, Google karta, GPS, offline-friendly. |

Dva CSS frameworka koegzistiraju **bez sukoba** jer su strogo razdvojena po route grupama i nikad nisu na istoj stranici: Filament učitava svoj (Tailwind) bundle samo na `/admin`, Tabler (Bootstrap) se učitava na svim ostalim sučeljima. Vite gradi zasebne bundleove. Time **maksimalno iskorištavamo i kupljeni Tabler i Filamentovu brzinu**.

### 1.1 KRITIČNA kompatibilnost — Livewire je jedna verzija na cijeloj aplikaciji

Livewire je **jedan paket s jednom major verzijom za cijelu aplikaciju** — ne može se istovremeno vrtjeti Livewire 3 za Filament i Livewire 4 za custom panele. Filament „pinned"-a koju Livewire verziju zahtijeva.

**Odluka za Fazu 1 (analiza):** zaključati matricu verzija — odabrati Filament release koji podržava ciljani Livewire, i cijela app ide na tu Livewire verziju. Ako Filament v4 u trenutku starta ne podržava Livewire 4, biramo: (a) Livewire verziju koju Filament podržava (preporuka), ili (b) admin bez Filamenta na Livewire 4 (skuplje, ne preporučam). **Ovo je prvi tehnički gate i mora se potvrditi prije pisanja koda.**

---

## 2. Preporučeni stack (cjelovito)

**Jezgra**
- **PHP 8.3+**, **Laravel 12** (modularni monolit — jedan codebase, jedna baza)
- **MySQL 8**
- **Redis** — queue, cache, sessions, presence za broadcasting
- **Laravel Horizon** — nadzor redova (queue workeri)
- **Laravel Reverb** — self-hosted WebSocket server za **live GPS** vozača → admin karta (first-party, sjeda na Laravel/Livewire, ide na Hetzner bez vanjskog Pushera)

**Sučelja**
- **Filament v4** (admin, kuhinja)
- **Livewire + Blade + Tabler.io + Alpine.js** (klijent, tvrtka, vozač)
- **Vite** za asset bundling (dva odvojena ulaza: Filament/Tailwind i Tabler/Bootstrap)

**Auth & ovlasti**
- **Laravel Fortify** (registracija/login) + **Socialite** (Google — opcionalno)
- **spatie/laravel-permission** (RBAC, 5 rola, policies/gates)

**Integracije**
- **Google Maps JS API** (admin live karta, ruta vozača), **Geocoding API** (adresa → lat/lng pri unosu), opcionalno **Directions API** (redoslijed/optimizacija ruta)
- **Browser Geolocation API** (`watchPosition`) u vozačevom PWA → POST na endpoint / Reverb → admin
- **Twilio** kroz Laravel Notifications (SMS kanal, queued)
- **eRačuni** — izlazni queued job koji gradi mjesečni payload (idempotentno, retryable)
- **Para program** — sync servis kroz anti-corruption layer (API ili periodični import datoteke)
- **PDF ugovori** — Spatie Laravel-PDF (Browsershot/Chromium, bolja vjernost) ili `barryvdh/laravel-dompdf` (lakši, bez Chromiuma)
- **Mail** (SMTP) — obavijesti i mjesečni izvještaji

**Kvaliteta koda**
- **Pest** (testovi), **Larastan/PHPStan** (statička analiza), **Laravel Pint** (formatiranje)

---

## 3. Domenski model (glavni agregati)

Modularni monolit, jedna baza. Ključne grupe tablica:

**Identitet i ovlasti**
- `users` (jedinstvena auth tablica), `roles`, `permissions`, `model_has_roles`
- Profili: `customers` (fizičke osobe), `companies` (+ `company_seats`/zaposlenici), `pensioners` (sinkronizirani iz Para)

**Meniji**
- `meals`, `menus` (tjedni), `menu_items`, `menu_types` (umirovljenici vs tvrtke/individualni)

**Narudžbe**
- `orders`, `order_items`, `order_days` (dnevna materijalizacija), `subscriptions` (mjesečne/ponavljajuće)
- Pravilo „min. 7 dana unaprijed" živi u domenskom servisu, ne u kontroleru

**Dostava**
- `delivery_lines` (linije/rute), `line_driver_assignments` (vozač ↔ linija ↔ datum)
- `deliveries` / `delivery_stops` (dnevno generirane stavke po adresi)
- `delivery_status_logs` (dostavljeno / pokupljena kantica / ne javlja se + napomena)
- `driver_locations` (GPS pingovi — kratkotrajni, primarno u Redisu; u MySQL samo ako treba povijest)

**Ugovori / računi / izvještaji**
- `contracts` (generirani PDF, upload potpisanog, datum isteka), `contract_reminders`
- `invoice_exports` (eRačuni payloadi + status), `monthly_reports`

**Komunikacija**
- `notifications` (Laravel), `sms_logs`, `mail_logs`

> Logika kantice: ako status = „pokupljena kantica" izostane, scheduled job prebacuje zapis na sljedeći dan. Status „ne javlja se" okida Twilio SMS voditelju kroz queued notifikaciju.

---

## 4. Ključni tehnički obrasci (rationale)

**4.1 Modularni monolit, ne mikroservisi.** Tim 2–3 ljudi, jedna domena, fiksni rok. Monolit = brža isporuka, jeftiniji deploy, lakši debugging. Modularnost držimo kroz domenske servise i jasne granice (npr. `App\Domain\Orders`, `App\Domain\Delivery`).

**4.2 Generiranje dnevnih dostava schedulerom.** Noćni `php artisan deliveries:generate-daily` materijalizira sutrašnje dostave iz aktivnih narudžbi/pretplata, raspoređuje po linijama i vozačima. Idempotentno (ponovni run ne duplicira). Sva teška obrada ide kroz **queue jobove**, ne u request ciklusu.

**4.3 Anti-corruption layer za Para i eRačuni.** Vanjske sheme nikad ne „cure" u domenu. Adapter mapira Para/eRačuni format na naše modele. Ako Para nema API → isti adapter čita periodični import (CSV/XML). Time vanjski sustav možemo mijenjati bez diranja jezgre.

**4.4 Live GPS kroz web (bez native app).** Vozačev preglednik šalje lokaciju (`navigator.geolocation.watchPosition`) → naš endpoint → Reverb broadcast na admin kartu. Zadovoljava uvjet „GPS kroz web aplikaciju". PWA daje „add to home screen", push i osnovni offline.

**4.5 Notifikacije kao kanali.** SMS (Twilio) i email idu kroz Laravel Notifications, uvijek queued i logirani, s retry/backoff.

**4.6 Idempotentni eRačuni export.** Mjesečni job gradi payload po klijentu, šalje, bilježi status; retry ne stvara duple račune (idempotency key po razdoblju+klijentu).

---

## 5. Infrastruktura (Hetzner)

- **OS:** Ubuntu LTS; **Nginx + PHP-FPM**
- **Servisi:** MySQL 8, Redis, **Supervisor** (drži Horizon queue workere + Reverb živima)
- **Webmin** za administraciju servera (zahtjev klijenta)
- **Storage:** potpisani ugovori → S3-kompatibilni (Hetzner Object Storage) ili Storage Box; ne u git/app folderu
- **Backup:** dnevni dump MySQL + rotacija; provjera restorea
- **Deploy:** GitHub Actions → SSH/Deployer (zero-downtime: `releases/` + symlink), migracije u deploy koraku
- **Sigurnost:** firewall, fail2ban, HTTPS (Let's Encrypt), `.env` izvan repo-a, najmanje privilegije za DB usera

---

## 6. Projektni workflow — razvojni proces

**6.1 Okruženja**
`local` (Laravel Herd/Sail) → `staging` (Hetzner) → `production` (Hetzner). Svaka faza se demonstrira na staging okruženju na kraju (milestone).

**6.2 Git workflow (trunk-based, lagani)**
- `main` je uvijek deployable. Kratkoživuće `feature/*` grane po zadatku.
- Svaki PR ide na **review (senior)** prije merge-a. Bez direktnog pusha na `main`.
- Konvencionalni commitovi (`feat:`, `fix:`, `chore:`) radi čitljive povijesti.

**6.3 CI/CD (GitHub Actions)**
Na svaki PR: **Pint** (format) → **Larastan** (statička analiza) → **Pest** (testovi). Zeleni pipeline = uvjet za merge. Merge na `main` → auto-deploy na staging; produkcija ručnim promote-om.

**6.4 Testiranje**
- **Pest feature testovi** za kritične tokove: narudžba (7 dana unaprijed), noćno generiranje dostava, promjene statusa + kantica logika, eRačuni export, podsjetnici ugovora.
- **Unit testovi** za domensku logiku (cijene, pravila narudžbi).
- Cilj: pokriti rizik, ne 100% coverage radi coveragea.

**6.5 Definition of Done (po zadatku)**
Kod + testovi prolaze, Pint/Larastan čisti, PR review odobren, migracije reverzibilne, demo na staging-u, kratka bilješka u dokumentaciji.

**6.6 Redoslijed gradnje (mapiran na faze ponude)**
1. **Faza 1 — Analiza:** zaključati verzije (Livewire↔Filament), ERD, izvedivost Para/eRačuni, definicija linija. Izlaz: specifikacija + potvrda cijene.
2. **Faza 2 — Skelet:** projekt, Vite dva ulaza, Fortify+RBAC, Filament admin shell, profili (klijent/tvrtka/umirovljenici).
3. **Faza 3 — Meniji i narudžbe:** tjedni meniji, narudžbe FO/tvrtki, kuhinjski pregled.
4. **Faza 4 — Dostava:** linije, statusi, kantica logika, Google Maps, Reverb GPS, Twilio.
5. **Faza 5 — Ugovori/računi/izvještaji:** PDF, upload, podsjetnici, eRačuni, analitika.
6. **Faza 6 — Stabilizacija:** testovi svih rola, Hetzner/Webmin, deploy, backup, primopredaja.

---

## 7. Projektni workflow — operativni (kako sustav radi u produkciji)

1. **Noć (scheduler):** generiranje sutrašnjih dostava iz aktivnih narudžbi → raspored po linijama/vozačima.
2. **Jutro:** kuhinja vidi proizvodne liste i ukupne količine po tipu korisnika; vozači vide svoju liniju i rutu na karti.
3. **Tijekom dana:** vozač označava statuse (dostavljeno / pokupljena kantica / ne javlja se), dodaje napomene; GPS se streama na admin kartu (Reverb).
4. **Okidači:** „ne javlja se" → Twilio SMS voditelju; nepokupljena kantica → automatski prijenos na sljedeći dan.
5. **Kraj mjeseca:** eRačuni export po klijentu + mjesečni izvještaji na email; podsjetnici za ugovore koji ističu (T-7 dana).

---

## 8. Rizici i mitigacije

| Rizik | Utjecaj | Mitigacija |
|---|---|---|
| Para nema API | Rok/cijena | Anti-corruption adapter podržava i periodični import; potvrditi u Fazi 1 |
| eRačuni slaba dokumentacija | Rok | Izolirati iza adaptera; rani spike u Fazi 1 |
| Livewire ↔ Filament verzije | Blokira start | Zaključati matricu verzija prije koda (gate u Fazi 1) |
| Dva CSS frameworka | Zabuna | Stroga granica po route grupama; zasebni Vite bundleovi |
| Google Maps troškovi | Operativni trošak klijenta | Geokodiranje keširati; Directions samo gdje treba |
| Realtime GPS opterećenje | Performanse | Throttle pingova (npr. 5–10 s), Redis za live, MySQL samo povijest po potrebi |

---

## 9. Sažetak preporuke (TL;DR)

Modularni **Laravel 12** monolit. **Filament v4** za admin/kuhinju, **Livewire + Tabler + Alpine** za klijenta/tvrtku/vozača (mobile-first PWA). **Redis + Horizon** za pozadinske procese, **Reverb** za live GPS, **Google Maps** za karte/rute, **Twilio/eRačuni/Para** iza anti-corruption adaptera, **Hetzner + Webmin** za produkciju. Prvi i najvažniji korak je u Fazi 1 zaključati **Livewire↔Filament verzije** i **izvedivost Para/eRačuni** — to skida 80% rizika projekta.
