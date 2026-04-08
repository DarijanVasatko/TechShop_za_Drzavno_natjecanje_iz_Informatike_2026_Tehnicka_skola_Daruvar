# TechShop

Web trgovina za prodaju tehničke opreme i PC komponenti s integriranim PC konfiguratorom.

Izrađen kao završni rad — Laravel 12, Bootstrap 5, MySQL.

---

## Preduvjeti

| Alat | Minimalna verzija | Napomena |
|------|-------------------|----------|
| PHP | 8.2+ | S ekstenzijama: `mbstring`, `xml`, `curl`, `mysql`, `zip`, `gd` |
| Composer | 2.x | [getcomposer.org](https://getcomposer.org) |
| MySQL | 8.0+ (ili MariaDB 10.6+) | |
| Node.js | 18+ | Za Vite build (frontend) |
| npm | 9+ | Dolazi s Node.js |

> **XAMPP korisnici:** XAMPP 8.2+ dolazi s PHP-om i MySQL-om. Trebat ćete samo instalirati Composer i Node.js zasebno.

---

## Instalacija (korak po korak)

### 1. Kloniranje repozitorija

```bash
git clone <URL-repozitorija>
cd <ime-repozitorija>/laravel
```

Svi sljedeći koraci izvršavaju se iz `laravel/` direktorija.

---

### 2. Instalacija PHP dependencija

```bash
composer install
```

---

### 3. Konfiguracija okruženja (.env)

```bash
cp .env.example .env
php artisan key:generate
```

Zatim otvori `.env` i prilagodi sljedeće vrijednosti:

```dotenv
# Baza podataka
DB_DATABASE=web_trgovina
DB_USERNAME=root
DB_PASSWORD=              # Tvoja MySQL lozinka

# URL aplikacije (prilagodi za server)
APP_URL=http://127.0.0.1:8000

# Mail (opcionalno, za slanje mailova)
MAIL_USERNAME=
MAIL_PASSWORD=

# Google OAuth (opcionalno, za Google login)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# Looker Studio (opcionalno, za analitiku)
LOOKER_STUDIO_EMBED_URL=
LOOKER_STUDIO_OPEN_URL=
```

---

### 4. Baza podataka

Stvori praznu bazu u MySQL-u:

```sql
CREATE DATABASE web_trgovina CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Zatim imaš **dva načina** za popunjavanje baze:

#### Opcija A: SQL dump (preporučeno za brzi start)

Uvezi SQL dump koji se nalazi u root direktoriju projekta:

```bash
mysql -u root -p web_trgovina < ../web_trgovina.sql
```

Ova opcija uvozi kompletnu strukturu + podatke (proizvodi, kategorije, brendovi, PC komponente, itd.)

#### Opcija B: Migracije + seederi (čista instalacija)

```bash
php artisan migrate
php artisan db:seed
```

Ovo stvara strukturu tablica i osnove podatke (kategorije, brendove, PC komponente, admin korisnika).

> **Napomena:** SQL dump sadrži više podataka od seedera (uključujući proizvode s opisima i cijenama). Za najpotpuniji start preporučena je Opcija A.

---

### 5. Storage link (slike proizvoda)

Ovaj korak je **obavezan** — bez njega slike proizvoda neće biti vidljive:

```bash
php artisan storage:link
```

Ova naredba stvara simbolički link:
```
public/storage/  -->  storage/app/public/
```

Slike proizvoda i PC komponenti su uključene u repozitorij (`storage/app/public/uploads/`) i bit će dostupne odmah nakon kreiranja linka.

---

### 6. Frontend (Vite + Bootstrap)

```bash
npm install
npm run build
```

Za razvoj s hot-reloadom:
```bash
npm run dev
```

---

### 7. Pokretanje aplikacije

#### Lokalni razvoj (PHP dev server):

```bash
php artisan serve
```

Aplikacija je dostupna na: **http://127.0.0.1:8000**

#### XAMPP:

Ako koristiš XAMPP, konfiguriraj Apache Virtual Host da pokazuje na `laravel/public/` direktorij.

---

## Admin pristup

Ako si koristio SQL dump ili seedere, admin korisnik je već kreiran:

| Polje | Vrijednost |
|-------|-----------|
| URL | `/admin` |
| Email | `admin@techshop.tsd` |

> Lozinka ovisi o tome koji si način postavljanja baze koristio. Ako koristiš seedere, lozinka se nalazi u `database/seeders/DatabaseSeeder.php`.

---

## Struktura projekta

```
laravel/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/              # Admin sučelje (proizvodi, komponente)
│   │   ├── CartController.php  # Košarica
│   │   └── CheckoutController.php
│   └── Models/                 # Eloquent modeli
├── config/
│   └── shop.php                # PDV stopa, valuta, plaćanje
├── database/
│   ├── migrations/             # 44 migracije
│   └── seeders/                # Kategorije, brendovi, PC komponente
├── resources/views/
│   ├── admin/                  # Admin blade templatei
│   ├── layouts/                # App + admin layouti
│   ├── partials/               # Product cards, grid
│   └── pc-builder/             # PC konfigurator
├── public/
│   ├── uploads/icons/          # Statičke ikone (payment metode)
│   └── storage -> ../storage/app/public/  # Symlink (storage:link)
├── storage/app/public/
│   └── uploads/                # Slike proizvoda i PC komponenti
│       ├── products/
│       └── pc-components/
│           ├── cpu/
│           ├── gpu/
│           ├── motherboard/
│           ├── ram/
│           ├── storage/
│           ├── psu/
│           └── case/
└── web_trgovina.sql            # SQL dump baze (u root direktoriju)
```

---

## Deploy na produkcijski server

### Dodatne .env postavke za produkciju:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tvoja-domena.hr
```

### Naredbe nakon deploya:

```bash
composer install --no-dev --optimize-autoloader
npm install && npm run build
php artisan key:generate          # Samo pri prvom deployu
php artisan storage:link          # Obavezno!
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Apache konfiguracija

DocumentRoot treba pokazivati na `laravel/public/`. Primjer Virtual Host-a:

```apache
<VirtualHost *:80>
    ServerName tvoja-domena.hr
    DocumentRoot /var/www/techshop/laravel/public

    <Directory /var/www/techshop/laravel/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Obavezno omogući `mod_rewrite`:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Dozvole (Linux server):

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Brzi pregled naredbi (copy-paste)

```bash
# Kompletna instalacija od nule:
git clone <URL> && cd <repo>/laravel
composer install
cp .env.example .env
php artisan key:generate
# stvori bazu web_trgovina u MySQL-u, zatim:
mysql -u root -p web_trgovina < ../web_trgovina.sql
php artisan storage:link
npm install && npm run build
php artisan serve
```
