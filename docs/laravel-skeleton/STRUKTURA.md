# Struktura projekta (Domain-driven Laravel)

Modularni monolit organiziran po **bounded contextima** umjesto klasičnog `app/Models`.
Svaki kontekst drži svoje modele (kasnije i servise, akcije, DTO-ove, evente).

```
app/
  Domain/
    Shared/
      Enums/            # backed enumi (statusi, tipovi) — dijeljeni
    Access/             # identitet i ovlasti
      Models/           # User, StaffProfile
    Customers/          # korisnici i adrese
      Models/           # Customer, Company, CompanyContact, Pensioner, Address
    Menus/
      Models/           # MealCategory, Meal, Menu, MenuItem
    Orders/
      Models/           # Subscription, Order, OrderLine
    Delivery/
      Models/           # DeliveryLine, LineAssignment, Delivery, DeliveryItem,
                        # DeliveryStatusLog, DriverLocation
    Contracts/
      Models/           # Contract
    Billing/
      Models/           # InvoiceExport, InvoiceExportLine, MonthlyReport
    Integration/
      Models/           # ParaSyncRun
database/
  migrations/           # ovdje su sve migracije iz ERD-a
```

## Namespace konvencija
- `App\Domain\<Context>\Models\<Model>`
- Kasnije se dodaju: `App\Domain\<Context>\Actions`, `\Services`, `\Data` (DTO), `\Events`.

## composer.json (PSR-4) — već pokriveno standardnim `App\` mapiranjem na `app/`.
Nije potrebna dodatna konfiguracija; `App\Domain\...` radi automatski jer je pod `app/`.

## Sljedeći slojevi (preporuka redoslijeda)
1. Migracije (ovdje) → `php artisan migrate`
2. Modeli (ovdje) → relacije, castovi, scopeovi
3. Domenski servisi/akcije: npr. `GenerateDailyDeliveries`, `PlaceOrder` (validacija 7 dana), `ExportMonthlyInvoices`
4. Filament resursi (admin/kuhinja) + Livewire komponente (klijent/tvrtka/vozač)
5. Schedule + Jobs (Horizon), Reverb broadcasting za GPS

## Napomene
- Modeli koriste `$guarded = []` radi sažetosti skeletona; u produkciji preporuka prelazak na eksplicitni `$fillable` ili Form Request validaciju.
- Polimorfne veze: addresses(owner), subscriptions/orders(subscriber), deliveries(recipient), contracts(contractable), invoice_exports(billable).
- spatie/laravel-permission objavljuje vlastitu migraciju za role/permisije (`php artisan vendor:publish`), nije uključena ovdje.
