# Joinara

Undangan digital wedding-first Indonesia. MVP: Laravel tunggal (frontend + backend), SQLite.

## Requirement

- **PHP 8.2 – 8.5** (teruji 8.5) dengan ekstensi: `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `curl`, `fileinfo`, `dom`
- **Composer 2**
- SQLite disertakan lewat `pdo_sqlite` (tanpa server DB)

> ⚠️ Larangan PHP 8.6 untuk saat ini: framework Laravel 12 (v12.73) belum menambahkan method `create_sid()` pada handler session (`SessionHandlerInterface`) yang wajib PHP 8.6. PHP 8.6 akan memicu `ErrorException` saat session start → 500. Gunakan PHP 8.5 (atau tunggu update framework / upgrade Laravel 13 setelah tersedia patch).

Install PHP (macOS, Homebrew):

```sh
brew install php@8.5 composer
# link agar jadi default
brew link php@8.5
```

Atau via apt (Ubuntu/Debian):

```sh
sudo apt install php8.5-cli php8.5-sqlite3 php8.5-mbstring php8.5-xml composer
```

## Jalankan lokal

```sh
composer install
cp .env.example .env      # DB_CONNECTION=sqlite sudah default
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve        # buka http://localhost:8000
```

## Buat akun owner

Tidak ada kredensial default yang di-commit. Buat via artisan interaktif (password ditulis hashed saja; jangan cantumkan contoh password nyata di README, issue, atau log):

```sh
php artisan joinara:owner
```

Simpan kredensial di password manager. Jangan commit file `.env` (sudah di `.gitignore`).

## Struktur MVP

| Model | Tabel | Tujuan |
| --- | --- | --- |
| `Event` | `events` | acara / undangan |
| `Guest` | `guests` | tamu + `unique_code` untuk undangan digital |
| `Rsvp` | `rsvps` | status kehadiran tamu |
| `Media` | `media` | foto galeri + status moderasi (`pending`/`approved`/`rejected`) |
| `CheckIn` | `check_ins` | log check-in tamu (metode `qr` / `manual`) |

## PWA

- `public/manifest.webmanifest` — manifest dasar
- `public/sw.js` — service worker yang **HANYA** cache aset statis berikut (audited, lihat juga komentar `sw.js`):

| URL | Jenis |
| --- | --- |
| `/manifest.webmanifest` | manifest |
| `/css/app.css` | stylesheet |

Tidak meng-cache halaman login, dashboard, atau data privat. `sw.js` tidak mendaftarkan cache untuk request HTML/POST. Cache key: `joinara-static-v1`.

## Batas SQLite (catatan dev)

- Cocok untuk MVP/dev lokal. Untuk traffic tinggi atau banyak tulisan bersamaan, pertimbangkan Postgres/MySQL.
- Tidak mendukung `ALTER TABLE ADD COLUMN` sekaligus perubahan kolom (Laravel memulihkan via create-copy).
- Concurrency menulis rendah — file DB terkunci saat transaksi aktif.
- Backup: cukup copy file `database/database.sqlite` saat server idle.

## Test

```sh
php artisan test
```

## Scope MVP (saat ini)

- Scaffold Laravel 12 + SQLite + session auth owner (1 akun)
- PWA shell dasar
- Belum ada: RSVP form, QR check-in, upload foto, galeri, moderasi
