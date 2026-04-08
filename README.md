# TechShop_za_Drzavno_natjecanje_iz_Informatike_2026_Tehnicka_skola_Daruvar


> Projekt izrađen za Natjecanje iz informatike 2026. · Razvoj softvera SŠ  
> Autori: **Darijan Vašatko** i **Dominik Dušek** · Tehnička škola Daruvar  
> Mentorica: Ivana Milić, mag.inf.

---

## Sadržaj

- [O projektu](#o-projektu)
- [Tehnički stack](#tehnički-stack)
- [Preduvjeti](#preduvjeti)
- [Instalacija web aplikacije](#instalacija-web-aplikacije)
- [Instalacija Android aplikacije](#instalacija-android-aplikacije)
- [Demo pristup](#demo-pristup)
- [REST API endpointi](#rest-api-endpointi)
- [Poznati problemi](#poznati-problemi)
- [Produkcijsko okruženje](#produkcijsko-okruženje)

---

## O projektu

TechShop je full-stack e-commerce platforma koja se sastoji od:

- **Laravel web aplikacije** — katalog računalne opreme, PC konfigurator s provjera kompatibilnosti, košarica, checkout, sustav recenzija, promo kodovi, admin panel
- **Android aplikacije za dostavljače** — pregled narudžbi, potvrda dostave digitalnim potpisom

Aplikacija je javno dostupna na: **http://techshop-daruvar.xyz**

---

## Tehnički stack

| Tehnologija | Verzija | Uloga |
|---|---|---|
| PHP | 8.2+ | Backend logika |
| Laravel | 12.x | MVC framework, Eloquent ORM, routing, middleware |
| MySQL | 8.0+ | Relacijska baza podataka (30+ tablica) |
| Bootstrap | 5.2 | Responzivni CSS okvir |
| Tailwind CSS | 3.1 | Utility-first CSS stilovi |
| AlpineJS | 3.4 | Lagani JavaScript za interaktivne komponente |
| Vite | 7.x | Frontend build tool |
| Laravel Sanctum | 4.0 | API token autentikacija za Android |
| Laravel Socialite | 5.25 | Google OAuth 2.0 integracija |
| Laravel Breeze | 2.3 | Scaffolding za autentikaciju |
| Anthropic Claude API | Haiku 4.5 | AI preporuka PC konfiguracije |
| Java (Android SDK) | — | Android aplikacija za dostavljače |
| Looker Studio | — | Vizualna analitika u admin dashboardu |
| GitHub Actions | — | CI/CD pipeline za automatski deploy |
| Hetzner Cloud | CX22 | VPS hosting (Ubuntu 24.04, Nginx, PHP-FPM) |

---

## Preduvjeti

Prije pokretanja provjerite imate li instalirano sve navedeno:

| Softver | Verzija | Napomena |
|---|---|---|
| PHP | 8.2+ | Potrebne ekstenzije: `mbstring`, `xml`, `curl`, `mysql`, `zip`, `gd` |
| Composer | 2.x | PHP dependency manager |
| MySQL | 8.0+ | Ili MariaDB 10.6+ |
| Node.js | 18.x+ | Za Vite build pipeline |
| npm | 9.x+ | Dolazi s Node.js-om |
| Git | 2.x | Za kloniranje repozitorija |
| XAMPP *(opcionalno)* | 8.2+ | Uključuje PHP, MySQL, Apache — najlakši lokalni setup |
| Android Studio *(opcionalno)* | 2024.x | Samo za pokretanje Android aplikacije |

> **Preporuka za početnike:** Koristite XAMPP 8.2+ koji automatski uključuje PHP, MySQL i sve potrebne ekstenzije.

---

## Instalacija web aplikacije

### 1. Kloniranje repozitorija

```bash
git clone https://github.com/DarijanVasatko/TechShop_za_Drzavno_natjecanje_iz_Informatike_2026_Tehnicka_skola_Daruvar.git

cd TechShop_za_Drzavno_natjecanje_iz_Informatike_2026_Tehnicka_skola_Daruvar/laravel
```

### 2. Instalacija PHP ovisnosti

```bash
composer install
```

### 3. Konfiguracija okruženja

Kopirajte `.env.example` u `.env` i generirajte aplikacijski ključ:

```bash
cp .env.example .env
php artisan key:generate
```

Otvorite `.env` datoteku i postavite parametre baze podataka:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web_trgovina
DB_USERNAME=root
DB_PASSWORD=
```

Opcionalno — postavite Google OAuth i Anthropic API ključ (potrebno samo za Google prijavu i AI preporuku konfiguracije):

```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback

ANTHROPIC_API_KEY=your_anthropic_api_key
```

> Bez `ANTHROPIC_API_KEY` AI preporuka automatski pada na lokalni rule-based algoritam. Aplikacija radi normalno.

### 4. Kreiranje baze podataka

#### Opcija A — SQL dump 

Putem terminala:

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS web_trgovina CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root web_trgovina < ../web_trgovina.sql
```

Ili putem phpMyAdmina:
1. Otvorite `http://localhost/phpmyadmin`
2. Kreirajte novu bazu podataka pod nazivom `web_trgovina`
3. Kliknite **Import** → odaberite datoteku `web_trgovina.sql` → kliknite **Go**

#### Opcija B — Čiste migracije (prazna baza s osnovnim podacima)

```bash
php artisan migrate
php artisan db:seed
```

### 5. Instalacija frontend ovisnosti i build

```bash
npm install
npm run build
```

Za razvoj s hot-reload-om (automatsko osvježavanje pri promjenama):

```bash
npm run dev
```

### 6. Simbolička veza za slike

```bash
php artisan storage:link
```

### 7. Pokretanje aplikacije

```bash
php artisan serve
```

Aplikacija je dostupna na: **http://127.0.0.1:8000**

Za pristup s mobilnog uređaja na istoj mreži (potrebno za testiranje Android aplikacije):

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

---

## Instalacija Android aplikacije

### Minimalni zahtjevi

- Android 8.0 (Oreo, API level 26) ili novija verzija
- Minimalno 50 MB slobodnog prostora
- Aktivna internetska veza (Wi-Fi ili mobilni podaci)
- Dozvola za pristup kameri (za QR kod i potpis)
- Dozvola za pristup pohrani (za spremanje potpisa)

### Instalacija APK-a

1. Preuzmite APK datoteku na Android uređaj
2. U postavkama uređaja omogućite **"Instalacija iz nepoznatih izvora"**
3. Pokrenite APK datoteku i slijedite instalacijske upute
4. Nakon instalacije, u postavkama aplikacije konfigurirajte **API URL** koji pokazuje na Laravel backend (npr. `http://192.168.x.x:8000` za lokalnu mrežu ili `http://10.0.2.2:8000` za Android emulator)

### Pokretanje putem Android Studija

1. Otvorite Android Studio
2. Uvezite Android projekt iz repozitorija
3. Podesite API URL u konfiguraciji na adresu vašeg Laravel backenda
4. Pokrenite na emulatoru ili fizičkom uređaju

---

## Demo pristup

| Uloga | URL | Pristupni podaci |
|---|---|---|
| Administrator | `http://127.0.0.1:8000/adminlogin` | `admin@techshop.tsd` / lozinka u `.env` |
| Korisnik (test) | `http://127.0.0.1:8000/register` | Registracija ili Google OAuth |
| FakePay test kartica | — | `4111 1111 1111 1111` |

---

## REST API endpointi

Android aplikacija komunicira s backendom putem sljedećih API endpointa. Svi zahtjevi zahtijevaju Sanctum Bearer token i vraćaju odgovore u JSON formatu.

| Metoda | Endpoint | Opis |
|---|---|---|
| `GET` | `/api/orders` | Dohvaća sve narudžbe dodijeljene dostavljaču |
| `GET` | `/api/orders/{id}` | Dohvaća detalje pojedine narudžbe |
| `POST` | `/api/orders/{id}/confirm` | Potvrđuje dostavu narudžbe |
| `POST` | `/api/orders/{id}/signature` | Sprema digitalni potpis primatelja |

---

## Poznati problemi

| Problem | Rješenje |
|---|---|
| `SQLSTATE[42S01]: Base table already exists` | Koristite SQL dump (Opcija A) ili pokrenite: `php artisan migrate:fresh --seed` |
| Slike se ne prikazuju | Pokrenite `php artisan storage:link`; provjerite postoji li direktorij `storage/app/public/uploads` |
| `Class 'Laravel\Socialite\...' not found` | Pokrenite `composer install` — Socialite je PHP ovisnost |
| `Vite manifest not found` | Pokrenite `npm install && npm run build` |
| Google login ne radi | Provjerite `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET` i `GOOGLE_REDIRECT_URI` u `.env` |
| CSRF token mismatch | Očistite cache: `php artisan config:clear && php artisan cache:clear` |
| Android app ne dostiže server | Pokrenite Laravel s `--host=0.0.0.0`; za emulator koristite `10.0.2.2` umjesto `localhost` |
| Permission denied na storageu (Linux/Mac) | `chmod -R 775 storage bootstrap/cache` |

---

## Produkcijsko okruženje

TechShop je deployiran na **Hetzner Cloud CX22** VPS serveru:

- **OS:** Ubuntu 24.04 LTS
- **Web server:** Nginx 1.24 + PHP-FPM 8.2
- **Baza:** MySQL 8.0.45
- **Runtime:** PHP 8.2, Node.js 20.x, Composer 2.x
- **CI/CD:** GitHub Actions — automatski deploy na svaki push na `main` granu (~2 minute)
- **Domena:** registrirana putem Namecheap, DNS A zapis pokazuje na IP servera

Svaki push na `main` granu automatski pokreće pipeline koji:
1. Preuzima najnoviji kod
2. Instalira PHP i Node.js ovisnosti
3. Kompajlira frontend resurse (Vite)
4. Prenosi datoteke na server putem SCP protokola
5. SSH spajanjem ažurira kod, osvježava cache i postavlja dozvole

---

*TechShop · Tehnička škola Daruvar · travanj 2026.*
