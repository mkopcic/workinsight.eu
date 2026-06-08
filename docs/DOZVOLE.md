# Dozvole i korisnici — server setup

## Ključno pravilo

**PHP-FPM i web server rade kao korisnik `workinsight`**, ne kao `root`.

Sve datoteke koje aplikacija treba pisati (`storage/`, `bootstrap/cache/`) moraju biti u vlasništvu korisnika `workinsight`. Kada AI agent ili administrator pokreće `artisan`, `composer`, `npm` ili druge komande **kao root**, generirane datoteke dobivaju vlasnika `root` i PHP ih ne može pisati → 500 greška.

---

## Server info

- **Hosting:** Hetzner + Virtualmin
- **Virtualmin korisnik:** `workinsight`
- **PHP-FPM pool:** radi kao `workinsight:workinsight`
- **PHP verzija:** 8.3
- **Web root:** `/home/workinsight/public_html/app/`

---

## Direktoriji koje PHP mora moći pisati

```
storage/
storage/logs/
storage/framework/cache/
storage/framework/sessions/
storage/framework/views/
storage/app/
bootstrap/cache/
```

### Ispravne dozvole

```bash
chown -R workinsight:workinsight /home/workinsight/public_html/app/storage
chown -R workinsight:workinsight /home/workinsight/public_html/app/bootstrap/cache
chmod -R 775 /home/workinsight/public_html/app/storage
chmod -R 775 /home/workinsight/public_html/app/bootstrap/cache
```

---

## Problem s pokretanjem komandi kao root

Kada se `php artisan`, `composer install` ili slično pokreće kao `root`, novonastale datoteke (logovi, cache, compiled views) dobivaju vlasnika `root:root`. PHP-FPM koji radi kao `workinsight` ih ne može ni pisati ni brisati.

### Simptom

```
UnexpectedValueException: The stream or file ".../storage/logs/laravel.log"
could not be opened in append mode: Permission denied
```

### Rješenje — pokretanje komandi kao workinsight

```bash
# Pokretanje kao workinsight korisnik
su - workinsight -s /bin/bash -c "php /home/workinsight/public_html/app/artisan migrate"
su - workinsight -s /bin/bash -c "composer install --no-dev --optimize-autoloader"

# Ili ako si već u app direktoriju kao root, popravi vlasništvo nakon komande:
chown -R workinsight:workinsight /home/workinsight/public_html/app/storage
chown -R workinsight:workinsight /home/workinsight/public_html/app/bootstrap/cache
```

---

## AI agent uputa

> Kada Claude Code ili drugi AI agent pokreće shell komande na ovom serveru, radi ih kao `root`. Nakon svake komande koja može generirati datoteke u `storage/` ili `bootstrap/cache/`, potrebno je pokrenuti `chown` korekciju ili koristiti `su - workinsight`.
>
> **Migracije i artisan komande pokretati kao `workinsight`**, ne kao `root`.

---

## Provjera vlasništva

```bash
ls -la /home/workinsight/public_html/app/storage/logs/
ls -la /home/workinsight/public_html/app/bootstrap/cache/
```

Svi fajlovi trebaju imati `workinsight workinsight` kao owner/group.
