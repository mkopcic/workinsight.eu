# ERD i shema baze podataka
### Platforma za upravljanje dostavom hrane (MySQL 8 / Laravel 12)

> Dizajn s naglaskom na čistu domenu i **spremnost za scale**. Konvencije, tablice po bounded contextima, indeksi te zasebna sekcija o skaliranju (particioniranje, materijalizirani sažeci, read replike).

---

## 1. Konvencije

- **PK:** `id` `BIGINT UNSIGNED AUTO_INCREMENT`. (Bigint zbog lokalnosti indeksa i performansi; UUID/ULID se izbjegava kao PK.)
- **Javni identifikatori:** tamo gdje se ID izlaže u URL-u ili vanjski (narudžbe, ugovori) dodaje se `public_id` (ULID, `CHAR(26)`, unique) — nepredvidiv, ali ne kvari indekse.
- **FK:** `*_id`, `BIGINT UNSIGNED`, s eksplicitnim `ON DELETE` pravilom (RESTRICT za financijske/povijesne veze, CASCADE za prave djecu).
- **Novac:** `DECIMAL(10,2)`, nikad `FLOAT`. Valuta EUR (konstanta; `currency CHAR(3)` samo ako zatreba).
- **Vrijeme:** `created_at`, `updated_at` na svim tablicama (podrazumijeva se, ne ponavlja se dolje). UTC u bazi.
- **Soft delete:** `deleted_at` SAMO gdje poslovno treba (korisnici, tvrtke, ugovori). Logovi i transakcijske stavke se ne brišu — koriste `status`.
- **Enumi:** kao `VARCHAR` + check/app-validacija ili MySQL `ENUM` (ovdje pišem kao enum radi čitljivosti; u migracijama preferiramo string + validaciju zbog lakšeg proširenja).
- **JSON:** za fleksibilne/rijetko-upitivane podatke (alergeni, snapshot adrese, payload). Indeksira se preko generated columns ako zatreba.
- **Naming:** tablice množina snake_case; pivoti abecedno (`company_user`).

---

## 2. Identitet i pristup

### `users` *(soft delete)*
| Kolona | Tip | Napomena |
|---|---|---|
| id | bigint PK | |
| name | varchar(150) | |
| email | varchar(190) | unique |
| phone | varchar(30) | null |
| password | varchar(255) | null (ako samo Google) |
| google_id | varchar(64) | null, unique |
| status | enum(active,inactive,blocked) | |
| email_verified_at | timestamp | null |
| last_login_at | timestamp | null |

Role/permisije preko **spatie/laravel-permission**: `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`. 5 rola: admin, customer, company, driver, kitchen.

### `staff_profiles` *(za vozače/kuhinju/admin)*
| Kolona | Tip | Napomena |
|---|---|---|
| id | bigint PK | |
| user_id | bigint FK→users | unique |
| employee_code | varchar(30) | null |
| vehicle_label | varchar(60) | null (vozač) |
| default_line_id | bigint FK→delivery_lines | null |
| active | boolean | |

---

## 3. Profili i adrese

### `customers` *(fizičke osobe, soft delete)*
| Kolona | Tip | Napomena |
|---|---|---|
| id | bigint PK | |
| user_id | bigint FK→users | unique, null (ako bez logina) |
| public_id | char(26) | ulid, unique |
| first_name / last_name | varchar(100) | |
| oib | char(11) | null, index |
| phone | varchar(30) | |
| default_address_id | bigint FK→addresses | null |
| status | enum(active,paused,inactive) | |
| notes | text | null |

### `companies` *(pravne osobe, soft delete)*
| Kolona | Tip | Napomena |
|---|---|---|
| id | bigint PK | |
| owner_user_id | bigint FK→users | null (glavni kontakt) |
| public_id | char(26) | ulid, unique |
| legal_name | varchar(200) | |
| oib | char(11) | unique |
| vat_id | varchar(20) | null |
| hq_address_id | bigint FK→addresses | null |
| billing_email | varchar(190) | |
| employee_count | smallint unsigned | |
| status | enum(active,paused,inactive) | |

### `company_contacts`
| id | bigint PK | |
| company_id | bigint FK→companies | cascade |
| name / email / phone | varchar | |
| role_label | varchar(60) | npr. „HR", „računovodstvo" |

### `pensioners` *(sinkronizirani iz Para programa)*
| Kolona | Tip | Napomena |
|---|---|---|
| id | bigint PK | |
| external_para_id | varchar(64) | unique, index |
| first_name / last_name | varchar(100) | |
| address_id | bigint FK→addresses | null |
| phone | varchar(30) | null |
| delivery_line_id | bigint FK→delivery_lines | null, index |
| para_synced_at | timestamp | null |
| status | enum(active,inactive,suspended) | |
| notes | text | null |

### `addresses` *(polimorfno vlasništvo)*
| Kolona | Tip | Napomena |
|---|---|---|
| id | bigint PK | |
| owner_type / owner_id | morphs | index (owner_type, owner_id) |
| label | varchar(60) | null („kuća", „ured") |
| street / house_number | varchar | |
| city / postal_code | varchar | |
| latitude | decimal(10,7) | null |
| longitude | decimal(10,7) | null |
| geocoded_at | timestamp | null |
| is_default | boolean | |

> Koordinate se geokodiraju jednom pri unosu (Google Geocoding) i keširaju; ne geokodira se na svaku narudžbu.

---

## 4. Meniji

### `meal_categories`
`id`, `name`, `sort_order`.

### `meals`
| Kolona | Tip | Napomena |
|---|---|---|
| id | bigint PK | |
| meal_category_id | bigint FK | null |
| name | varchar(150) | |
| description | text | null |
| allergens | json | lista |
| base_price | decimal(10,2) | |
| active | boolean | |

### `menus` *(tjedni meni)*
| Kolona | Tip | Napomena |
|---|---|---|
| id | bigint PK | |
| menu_type | enum(pensioner,standard) | razdvaja umirovljenike od tvrtki/individualnih |
| week_start | date | index |
| status | enum(draft,published,archived) | |
| published_at | timestamp | null |
| created_by | bigint FK→users | |

unique(`menu_type`,`week_start`).

### `menu_items` *(jelo ponuđeno na određeni datum u meniju)*
| Kolona | Tip | Napomena |
|---|---|---|
| id | bigint PK | |
| menu_id | bigint FK→menus | cascade |
| delivery_date | date | index |
| meal_id | bigint FK→meals | |
| slot | enum(soup,main,side,dessert) | |
| price | decimal(10,2) | snapshot cijene |
| capacity | int unsigned | null (limit po danu) |

index(`menu_id`,`delivery_date`).

---

## 5. Narudžbe i pretplate

### `subscriptions` *(ponavljajuće narudžbe)*
| Kolona | Tip | Napomena |
|---|---|---|
| id | bigint PK | |
| subscriber_type / subscriber_id | morphs (customer/company) | index |
| menu_type | enum(pensioner,standard) | |
| plan | enum(daily,monthly) | |
| weekday_pattern | json | npr. [1,2,3,4,5] |
| default_quantity | smallint unsigned | |
| start_date | date | |
| end_date | date | null |
| status | enum(active,paused,ended) | index |

### `orders` *(zaglavlje narudžbe)*
| Kolona | Tip | Napomena |
|---|---|---|
| id | bigint PK | |
| public_id | char(26) | ulid, unique |
| order_number | varchar(20) | unique, čitljiv |
| subscriber_type / subscriber_id | morphs | index |
| subscription_id | bigint FK | null |
| order_type | enum(daily,weekly,monthly) | |
| status | enum(draft,confirmed,partially_delivered,completed,cancelled) | index |
| placed_at | timestamp | |
| ordered_by_user_id | bigint FK→users | |
| total_amount | decimal(10,2) | |
| notes | text | null |

### `order_lines` *(stavka po danu i jelu)*
| Kolona | Tip | Napomena |
|---|---|---|
| id | bigint PK | |
| order_id | bigint FK→orders | cascade |
| delivery_date | date | index |
| menu_item_id | bigint FK | null |
| meal_id | bigint FK→meals | |
| beneficiary_type / beneficiary_id | morphs | null (zaposlenik/umirovljenik) |
| quantity | smallint unsigned | |
| unit_price | decimal(10,2) | |
| line_total | decimal(10,2) | |
| status | enum(pending,locked,delivered,cancelled) | |

> Pravilo **min. 7 dana unaprijed** i **cutoff za izmjene** validira se u domenskom servisu na temelju `delivery_date` vs `now()`. Nakon cutoffa `status=locked`.
> index(`delivery_date`,`status`) — ključno za noćno generiranje dostava.

---

## 6. Dostava

### `delivery_lines` *(rute/linije)*
| id | bigint PK | |
| name | varchar(120) | |
| code | varchar(20) | unique |
| zone | varchar(60) | null |
| color | char(7) | UI |
| default_driver_id | bigint FK→users | null |
| active | boolean | |

### `line_assignments` *(vozač ↔ linija ↔ dan)*
| id | bigint PK | |
| delivery_line_id | bigint FK | |
| driver_user_id | bigint FK→users | |
| service_date | date | |
| status | enum(planned,in_progress,completed) | |

unique(`delivery_line_id`,`service_date`); index(`driver_user_id`,`service_date`).

### `deliveries` *(dnevna dostavna stavka / stop)*
| Kolona | Tip | Napomena |
|---|---|---|
| id | bigint PK | |
| service_date | date | **partition key kandidat**, index |
| delivery_line_id | bigint FK | index |
| line_assignment_id | bigint FK | null |
| recipient_type / recipient_id | morphs (customer/company/pensioner) | index |
| address_snapshot | json | adresa „zamrznuta" na dan dostave |
| latitude / longitude | decimal(10,7) | snapshot za kartu |
| stop_sequence | smallint unsigned | redoslijed na ruti |
| status | enum(pending,delivered,canister_collected,no_answer,rescheduled,failed) | index |
| carried_over_from_id | bigint FK→deliveries | null (kantica → sljedeći dan) |
| note | text | null |
| delivered_at | timestamp | null |

composite index(`service_date`,`delivery_line_id`,`status`).

### `delivery_items` *(što se dostavlja)*
`id`, `delivery_id` FK cascade, `order_line_id` FK, `meal_id` FK, `quantity`.

### `delivery_status_logs` *(append-only audit — VISOK VOLUMEN)*
| id | bigint PK | |
| delivery_id | bigint FK | index |
| from_status / to_status | varchar(30) | |
| driver_user_id | bigint FK→users | |
| latitude / longitude | decimal(10,7) | null |
| note | text | null |
| created_at | timestamp | index |

> **Particionirati po mjesecu (`created_at`)**; stare particije arhivirati/prunati.

### `driver_locations` *(GPS pingovi — VRLO VISOK VOLUMEN)*
| id | bigint PK | |
| driver_user_id | bigint FK | |
| service_date | date | |
| latitude / longitude | decimal(10,7) | |
| recorded_at | timestamp(3) | |

> **Primarno u Redisu** (live pozicija) preko Reverba. U MySQL ide samo **downsamplirana** povijest (npr. 1 točka / 30 s) i to **particionirano po danu**, s automatskim pruningom (npr. 30–90 dana).

---

## 7. Ugovori

### `contracts` *(soft delete)*
| Kolona | Tip | Napomena |
|---|---|---|
| id | bigint PK | |
| public_id | char(26) | ulid |
| contractable_type / contractable_id | morphs (customer/company) | index |
| contract_number | varchar(30) | unique |
| status | enum(draft,generated,sent,signed,expired,terminated) | index |
| generated_pdf_path | varchar(255) | object storage |
| signed_pdf_path | varchar(255) | null |
| valid_from | date | |
| valid_until | date | index (za podsjetnike) |
| signed_at / uploaded_at | timestamp | null |
| reminder_sent_at | timestamp | null |

> Scheduled job traži `valid_until = today()+7` i `reminder_sent_at IS NULL` → šalje podsjetnik.

---

## 8. Naplata (eRačuni) i izvještaji

### `invoice_exports` *(mjesečni B2B payload prema eRačunima)*
| Kolona | Tip | Napomena |
|---|---|---|
| id | bigint PK | |
| billable_type / billable_id | morphs (company/customer) | index |
| period_year | smallint | |
| period_month | tinyint | |
| idempotency_key | varchar(80) | **unique** (billable+period) |
| payload | json | generirani podaci |
| external_invoice_id | varchar(64) | null (vraćeni ID) |
| status | enum(pending,sent,failed,acknowledged) | index |
| sent_at | timestamp | null |
| error | text | null |

> Unique `idempotency_key` sprječava duple račune kod retryja.

### `invoice_export_lines`
`id`, `invoice_export_id` FK cascade, `description`, `quantity`, `unit_price`, `amount`.

### `monthly_reports`
`id`, `period_year`, `period_month`, `type`, `file_path`, `generated_at`, `emailed_at`.

---

## 9. Integracije (Para) i postavke

### `para_sync_runs` *(audit sinkronizacije)*
`id`, `source` enum(api,file), `started_at`, `finished_at`, `records_processed`, `records_created`, `records_updated`, `status` enum(running,success,failed), `error`.

### `settings`
`id`, `group`, `key`, `value` (json/encrypted), unique(`group`,`key`).

---

## 10. Notifikacije i logovi

- **`notifications`** — standardna Laravel tablica (uuid PK, `type`, `notifiable`, `data` json, `read_at`).
- **`sms_logs`** *(append-only)* — `id`, `to`, `body`, `provider_sid`, `status`, `related_type/related_id` morphs, `created_at` (index). Particionirati po mjesecu pri rastu.
- **`mail_logs`** — `id`, `to`, `subject`, `mailable`, `status`, `created_at`.
- **`activity_log`** (spatie, opcionalno) — audit promjena nad ključnim entitetima.
- **`jobs` / `failed_jobs` / `job_batches`** — Laravel queue infrastruktura.

---

## 11. Skalabilnost (ključno)

**11.1 Particioniranje visokovolumnih tablica.** `driver_locations`, `delivery_status_logs`, `sms_logs`, `notifications` rastu linearno s prometom. Koristiti **MySQL RANGE particioniranje po datumu** (mjesečno za logove, dnevno za GPS) + scheduled pruning starih particija. Tako upiti i brisanje ostaju jeftini.

**11.2 GPS izvan vrućeg puta.** Live pozicije idu u **Redis** (Reverb presence/keyspace), ne u MySQL na svaki ping. U MySQL se sprema samo downsamplirana povijest, i to opcionalno. Throttle na klijentu (5–10 s) + batch insert.

**11.3 Materijalizirani sažeci za analitiku.** Dashboard ne smije skenirati `deliveries`/`order_lines` uživo. Scheduled job puni **`delivery_daily_summaries`** (po danu/liniji/tipu korisnika: broj obroka, dostavljeno, kantice, no-answer) i **`billing_monthly_summaries`**. Analitika čita sažetke → konstantno brzo bez obzira na volumen.

**11.4 Razdvajanje čitanja/pisanja.** Laravel `read`/`write` konekcije; kad promet naraste, dodati **MySQL read replicu** i usmjeriti izvještaje/analitiku na nju bez promjene koda.

**11.5 Indeksi ciljani na stvarne upite.**
- `deliveries(service_date, delivery_line_id, status)` — dnevni pregled rute/admina.
- `order_lines(delivery_date, status)` — noćno generiranje + kuhinjske količine.
- `orders(subscriber_type, subscriber_id, status)` — povijest korisnika.
- `contracts(valid_until, status)` — podsjetnici.
- `invoice_exports(billable_type, billable_id, period_year, period_month)` unique.
Izbjegavati over-indeksiranje na write-heavy tablicama (GPS/logovi) — samo nužno.

**11.6 Vitke jezgrene tablice.** Rijetko-upitivani i fleksibilni podaci u `json` (alergeni, snapshotovi, payloadi) da se redovi ne napuhuju; snapshot adrese/cijene u dostavi/narudžbi čuva povijesnu točnost bez JOIN-ova na žive profile.

**11.7 Spremnost za multitenancy.** `companies` su prirodna granica. Ako jednom zatreba tvrda izolacija (npr. franšize/gradovi), uvodi se `tenant_id` na jezgrenim tablicama + global scope — shema je već agregirana tako da je to aditivna promjena.

**11.8 Keširanje.** Meniji se rijetko mijenjaju → keširati u Redis s invalidacijom na objavu. Postavke i linije također.

**11.9 Arhiviranje.** Politika zadržavanja: GPS 30–90 dana, status logovi 12–24 mj u vrućoj bazi, starije u cold storage (dump/parquet) — bez opterećivanja produkcije.

---

## 12. Sažetak relacija (tekstualno)

- `users` 1–1 `customers` / `companies` (opcionalno) i 1–1 `staff_profiles`.
- `customers`/`companies`/`pensioners` 1–N `addresses` (polimorfno).
- `menus` 1–N `menu_items`; `meals` 1–N `menu_items`.
- `subscriptions` 1–N `orders`; `orders` 1–N `order_lines`; `meals`/`menu_items` 1–N `order_lines`.
- `delivery_lines` 1–N `line_assignments`; `line_assignments` 1–N `deliveries`.
- `deliveries` 1–N `delivery_items` i 1–N `delivery_status_logs`; `order_lines` 1–N `delivery_items`.
- `customers`/`companies` 1–N `contracts`, 1–N `invoice_exports`.
- `users` (vozač) 1–N `deliveries`, `driver_locations`, `delivery_status_logs`.
