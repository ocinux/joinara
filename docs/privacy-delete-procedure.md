# Prosedur hapus data tamu / event (manual)

Owner pemegang akses dashboard Joinara yang mengeksekusi penghapusan. Semua
operasi manual — belum ada jalur penghapusan via undangan.

Permintaan tamu masuk melalui kontak owner di halaman undangan (lihat
`/privasi`). Owner menerima permintaan, lalu ikuti salah satu prosedur di bawah.

## Hapus satu event (semua tamu + RSVP + check-in ikut terhapus)

1. Dashboard → buka event → **Hapus acara**.
2. Database: baris `events`, `guests`, `rsvps`, `check_ins`, `media` terhapus
   kaskade (foreign key `cascadeOnDelete`).
3. File foto TIDAK ikut terhapus — bersihkan manual:

```bash
# dari root aplikasi Laravel; sesuaikan event-{id}
rm -rf storage/app/private/media/event-1
```

## Hapus satu tamu saja

```bash
php artisan tinker
```

```php
$guest = App\Models\Guest::where('event_id', 1)->where('name', '<Nama tamu>')->first();

// hapus foto milik tamu: baris DB + file fisik
foreach (App\Models\Media::where('guest_id', $guest->id)->get() as $m) {
    Storage::disk('local')->delete($m->path);
    $m->delete();
}

// RSVP & check-in tamu terhapus kaskade bersama baris guest
$guest->delete();
```

## Hapus hanya RSVP (tamunya tetap)

```php
App\Models\Rsvp::where('guest_id', $guest->id)->delete();
```

## Retensi

Data disimpan sampai owner menghapusnya — tidak ada retensi otomatis di MVP.
Backup berkala: salin file database SQLite + folder `storage/app/private`.
