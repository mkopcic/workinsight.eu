# Autentikacija i RBAC

Auth je riješen preko **Laravel Fortify** (headless), s **spatie/laravel-permission** za role. UI je **Tabler (Bootstrap 5)**, usklađen s landing stranicom. **Registracija je namjerno isključena** — korisnike kreira admin (seed / kasnije Filament).

## Stack i odluke

- **laravel/fortify** — backend auth (login, reset lozinke, update profila/lozinke). Bez Tailwinda → čuva Bootstrap/Tabler odluku.
- **spatie/laravel-permission** — 5 rola: `admin`, `customer`, `company`, `driver`, `kitchen` (guard `web`).
- Uključene Fortify značajke (`config/fortify.php`): `resetPasswords`, `updateProfileInformation`, `updatePasswords`.
- Isključeno: **registracija**, 2FA, passkeys, email verifikacija. (Migracije `two_factor` stupaca i `passkeys` tablice ostaju od scaffolda, ali značajke su off — re-enable je samo izmjena `features` arraya.)
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
- `app/Models/User.php` — traitovi `HasRoles`, `SoftDeletes`; `#[Fillable]` proširen (name, email, phone, password, google_id, status).
- `app/Http/Controllers/DashboardController.php` — invokable, renderira docs + ERD na `/dashboard`.
- `resources/views/layouts/guest.blade.php` — Tabler guest layout.
- `resources/views/auth/{login,forgot-password,reset-password}.blade.php`.
- `resources/views/welcome.blade.php` — javni landing (Prijava / Dashboard ako je ulogiran).
- `resources/views/dashboard.blade.php` — interni pregled (docs + domena) + top-bar s imenom/rolom i Odjavom.
- `database/seeders/RolesAndAdminSeeder.php` — 5 rola + admin user.
- `public/tabler/` — Tabler 1.4.0 compiled CSS/JS (kopirano iz `docs/tabler-1.4.0/dashboard/dist/`).

## Dev admin (seed)

```
email:    admin@workinsight.eu
password: Admin1234!
rola:     admin
```

Seed: `php artisan db:seed --class=RolesAndAdminSeeder`.

## Smoke (provjereno)

`/`→200, `/login`→200, `/forgot-password`→200, `/register`→404, `/dashboard` (gost)→302 `/login`, `/dashboard` (ulogiran)→200, Tabler CSS→200, login admina radi.

## TODO (sljedeće)

- Redirect po roli nakon logina (admin → Filament `/admin` kad se postavi; ostali → `/dashboard`) preko custom `LoginResponse`.
- Email verifikacija + pravi SMTP (sad `MAIL_MAILER=log`).
- Filament admin panel (Livewire↔Filament verzijski gate iz arhitekture).
- Tabler preko Vite ulaza umjesto kopije u `public/` (ako se želi build pipeline).
- Feature testovi za auth (namjerno preskočeni u ovom krugu).
