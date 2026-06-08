# Autentikacija i RBAC

Auth je riješen preko **Laravel Fortify** (headless), s **spatie/laravel-permission** za role. UI je **Tabler (Bootstrap 5)**, usklađen s landing stranicom. **Registracija je namjerno isključena** — korisnike kreira admin (seed / kasnije Filament).

## Stack i odluke

- **laravel/fortify** — backend auth (login, reset lozinke, update profila/lozinke). Bez Tailwinda → čuva Bootstrap/Tabler odluku.
- **spatie/laravel-permission** — 5 rola: `admin`, `customer`, `company`, `driver`, `kitchen` (guard `web`).
- Uključene Fortify značajke (`config/fortify.php`): `resetPasswords`, `updateProfileInformation`, `updatePasswords`.
- **Email verifikacija UKLJUČENA** (`verified` middleware na `/dashboard`). Isključeno: **registracija**, 2FA, passkeys. (Migracije `two_factor`/`passkeys` ostaju od scaffolda — re-enable je samo izmjena `features` arraya.)
- `home` → `/dashboard`.

## Rute (Fortify + app)

| Metoda | URI | Ime | Pristup |
|---|---|---|---|
| GET | `/` | — | javno (landing `welcome.blade.php`) |
| GET | `/login` | `login` | gost |
| POST | `/login` | `login.store` | gost |
| POST | `/logout` | `logout` | auth |
| GET | `/forgot-password` | `password.request` | gost |
| POST | `/forgot-password` | `password.email` | gost |
| GET | `/reset-password/{token}` | `password.reset` | gost |
| POST | `/reset-password` | `password.update` | gost |
| GET | `/dashboard` | `dashboard` | **auth** |
| — | `/register` | — | **404 (isključeno)** |

Gost na `/dashboard` → redirect na `/login`. Dokumentacija (markdown + ERD) sada je **samo iza logina** (renderira ju `DashboardController`).

## Datoteke

- `config/fortify.php` — features, `home`, rate limiteri.
- `app/Providers/FortifyServiceProvider.php` — view binding (`loginView`, `requestPasswordResetLinkView`, `resetPasswordView`) + rate limiteri.
- `app/Domain/Access/Models/User.php` — DDD lokacija (NE `app/Models`); `HasRoles`, `SoftDeletes`, `MustVerifyEmail`, `FilamentUser::canAccessPanel` (rola admin), `status` cast u `UserStatus` enum. `config/auth.php` pokazuje na ovu klasu.
- `app/Http/Controllers/DashboardController.php` — invokable, renderira docs + ERD na `/dashboard`.
- `resources/views/layouts/guest.blade.php` — Tabler guest layout.
- `resources/views/auth/{login,forgot-password,reset-password}.blade.php`.
- `resources/views/welcome.blade.php` — javni landing (Prijava / Dashboard ako je ulogiran).
- `resources/views/dashboard.blade.php` — interni pregled (docs + domena) + top-bar s imenom/rolom i Odjavom.
- `database/seeders/RolesAndAdminSeeder.php` — 5 rola + **5 login usera** (po jedan po roli).
- `public/tabler/` — **kupljeni** Tabler dist (css+js) iz `docs/tabler-1.4.0/dashboard/dist/`.

## Dev login korisnici (seed)

Svi: lozinka **`Admin1234!`**, verificirani, status active.

| Email | Rola | Redirect nakon logina |
|---|---|---|
| admin@workinsight.eu | admin | `/admin` (Filament) |
| customer@workinsight.eu | customer | `/dashboard` |
| company@workinsight.eu | company | `/dashboard` |
| driver@workinsight.eu | driver | `/dashboard` |
| kitchen@workinsight.eu | kitchen | `/dashboard` |

Seed: `php artisan db:seed --class=RolesAndAdminSeeder`.

## Smoke (provjereno)

`/`→200, `/login`→200, `/forgot-password`→200, `/register`→404, `/dashboard` (gost)→302 `/login`, `/dashboard` (ulogiran)→200, Tabler CSS→200, login admina radi.

## Filament admin (gotovo)

- **filament/filament v5.6** instaliran (Laravel 13 + Livewire 3 — verzijski gate prošao; novije od v4 iz arhitekture).
- Panel na **`/admin`** (`app/Providers/Filament/AdminPanelProvider.php`), login `/admin/login`.
- Pristup: `User implements FilamentUser`, `canAccessPanel()` → samo rola `admin`.

## SMTP (gotovo)

- `.env`: `MAIL_MAILER=smtp`, host **`workinsight.eu`** (ne `mail.workinsight.eu` — TLS cert CN je `workinsight.eu`), port 587 STARTTLS, user `workinsight`, from `workinsight@workinsight.eu`. Test slanja prošao.
- Email verifikacija uključena (`verified` middleware na `/dashboard`).

## Redirect po roli (gotovo)

- `app/Http/Responses/LoginResponse.php` (bind u `FortifyServiceProvider::register`): admin → `/admin`, ostali → `/dashboard`.

## Frontend (Tabler)

- Koristi se **KUPLJENI Tabler dist** u `public/tabler/` (css+js, iz `docs/tabler-1.4.0/dashboard/dist/`). Guest layout linka direktno (`asset('tabler/css/tabler.min.css')`), **ne preko Vite**. npm `@tabler/core` je maknut.
- `public/tabler/` je u gitu (maknut iz `.gitignore`); `libs/` i `img/` izostavljeni dok ne zatrebaju za premium stranice.
- `public/build` (Filament/Tailwind `app.css`/`app.js`) je gitignoriran → na deployu `npm install && npm run build`.

## Struktura modela (DDD)

- Modeli: `App\Domain\<Kontekst>\Models\` (Access, Customers, Menus, Orders, Delivery, Contracts, Billing, Integration). Enumi: `App\Domain\Shared\Enums\`. Flat `app/Models/` se **ne koristi**.
- ⚠️ spatie role/morph pivoti pamte puni FQCN klase — pri preseljenju modela treba re-seedati role ili uvesti `Relation::enforceMorphMap`.

## Dodatni spatie paketi (instalirani, configi pushani, rade)

`activitylog`, `laravel-backup`, `laravel-health`, `laravel-medialibrary`.
**TODO konfiguracija:** pregledati `config/{activitylog,backup,health,media-library}.php` i posložiti da **notifikacije/mailovi** (backup uspjeh/greška, health) idu kroz SMTP iz `.env` (`MAIL_FROM_ADDRESS=workinsight@workinsight.eu`); odrediti backup destinaciju/raspored i health checkove.

## TODO (sljedeće)

- Config + notifikacije za 4 nova paketa (gore).
- **Filament Resources (brzi CRUD-ovi)** nad Domain modelima.
- Domenski servisi (cijene, „7 dana unaprijed", noćno generiranje dostava).
- Feature testovi (na kraju, prije/uz CRUD-ove).
