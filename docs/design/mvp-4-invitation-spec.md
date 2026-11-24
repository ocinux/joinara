# Spesifikasi Halaman Undangan Publik + Form RSVP — MVP-4 (issue #5)

Sumber: ocinux/joinara#5. Halaman dibuka tamu via link/QR, mobile-first, tanpa login.
Design system: lihat `design-system.md`. Guide referensi: `guides-referenced.txt`.

## Tujuan halaman

Tamu membuka link undangan → lihat info acara & detail pasangan → isi form RSVP → dapat konfirmasi. Seluruh alur dalam **1 halaman** (single-page, tidak multi-step) supaya ringan dan mudah dibagikan ulang.

## Struktur konten (urutan vertical, 1 kolom)

1. **Hero** — foto pasangan (`aspect-ratio` reserved, `loading="lazy"` jika non-LCP) + nama pasangan (H1) + tanggal acara.
2. **Salam / kutipan** — teks pendek 1-2 kalimat hangat (copy di bawah).
3. **Detail acara** — `<section>` berisi:
   - Waktu (jadwal: akad, resepsi)
   - Lokasi + tombol "Lihat di Peta" (`<a target="_blank" rel="noopener">` ke Google Maps)
   - Dress code (opsional, jika host set)
4. **Form RSVP** — `<section>` + `<form>` (lihat rincian form di bawah).
5. **Footer** — "Dibuat dengan ❤ oleh Joinara" (tanpa emoji berlebihan, cukup teks) + tautan ke host/pengelola jika relevan.

## Form RSVP — rincian

Semua field dalam 1 form, satu kolom, label di atas input (stacked).

| Field | Element | `name` | Validasi | Catatan |
|---|---|---|---|---|
| Nama tamu | `<input type="text">` | `guest_name` | `required`, `maxlength=100` | Label: "Nama Anda" |
| Kehadiran | radio group `<fieldset>` | `attendance` | `required`, nilai `hadir` / `tidak` | Label group: "Apakah Anda hadir?" Opsi: "Ya, insyaallah hadir" / "Mohon maaf, tidak bisa hadir" |
| Jumlah orang | `<input type="text" inputmode="numeric" pattern="[1-9]">` | `party_size` | `required`, min 1, max 10 | Tampil hanya saat `attendance=hadir` (conditional; `fieldset` + `legend`, di-hide via CSS bukan `display:none` total — tetap dalam DOM untuk form state, pakai `inert`/`hidden` toggle yang aman) |
| Catatan | `<textarea>` | `notes` | `maxlength=300`, opsional | Label: "Pesan / catatan (opsional)" |
| (hidden) kode undangan | `<input type="hidden">` | `invitation_code` | — | Isinya token personal (lihat anti-duplikat di bawah) |

Catatan implementasi (untuk dev, bukan scope design tapi perlu disepakati):
- Jangan pakai `<input type="number">` untuk `party_size` — guide `forms` menolak `type=number` untuk nilai pendek (spinner + leading-zero issues). Pakai `type="text" inputmode="numeric" pattern`.
- Jangan auto-submit saat radio berubah — selalu ada tombol submit eksplisit (AC guide `forms`: "DON'T auto-submit... without an explicit submit action").
- Validasi `blur` (kalau field sudah edited) + `submit`; jangan setiap keystroke.
- `autocomplete="name"` pada `guest_name`; `autocorrect="off"` tidak perlu (ini free-text nama, default cukup).
- Setelah submit valid: disable tombol (cegah double-post) — sesuai guide `forms`.

## Anti-duplikat RSVP — keputusan: **kode undangan personal**

Pilihan (dari klarifikasi founder: kode personal ATAU dedup nama+event). Keputusan: **kode undangan personal**, bukan dedup nama+event.

Alasan:
- **Presisi**: dedup nama+event tidak reliable — orang Indonesia sering nama pendek umum ("Budi", "Rina"), atau tamu isi nama beda sedikit ("Andi Pratama" vs "Andi"). Dedup string-based salah-positif tinggi, dan salah-negatif (dua orang beda bernama sama) tidak terdeteksi.
- **Simpel & deterministik**: satu token unik per link; tidak perlu fuzzy matching / threshold. DB lookup `WHERE invitation_code = ?` O(1).
- **MVP 7 hari**: tidak perlu algoritma dedup nama (risque bug, time sink). Kode personal = generate random token saat host buat undangan.
- **UX tamu**: tidak perlu "pastikan nama ejaan benar" — cukup link yang sudah berisi kode.
- **Trade-off**: tamu yang share link ke orang lain tidak terdeteksi sebagai tamu berbeda — tapi untuk MVP, host yang share link personal ke tamu undangan spesifik, bukan link publik bebas. Jika nanti dibutuhkan multi-guest per link, baru tambah `party_size` dedup (out of scope MVP-4).

Implementasi (saran, bukan scope design):
- Saat host membuat undangan, sistem generate `invitation_code` (UUID/short random) dan sisipkan di URL: `/{slug}/invite/{code}` atau query `?code=...` (pilih 1 saat dev, keduanya bisa — design tidak memaksakan).
- Form `RSVP` submit membawa `invitation_code`; DB constraint UNIQUE pada `(invitation_code, attendance_session)` kalau perlu.
- Jika tamu buka link tanpa kode (fallback), tampilkan notice: "Undangan ini bersifat personal, silakan minta link dari pengundang" — jangan biarkan submit kosong.

## States form & halaman

| State | Perilaku | Visual |
|---|---|---|
| **default** | Form kosong, semua field unfilled | Sesuai desain di atas |
| **loading** | Tombol submit ber-spin, form `inert` selama fetch | Spinner CSS (compositor, `prefers-reduced-motion` dihormati — hanya opacity pulse, bukan rotasi jika reduced) |
| **error (field)** | `:user-invalid` border merah, pesan di bawah field, `aria-invalid="true"`, `aria-describedby` ke pesan; focus pindah ke field error pertama | Warna `--error` (#A63A2C), teks + ikon (non-color cue) |
| **error (submit gagal)** | Toast `role="alert"` di atas form, scroll ke sana | "Terjadi gangguan, coba lagi" |
| **empty** | Tidak ada state empty di form (bukan data list). Untuk halaman: jika event tidak ada/tidak aktif, redirect ke halaman 404/410 (di luar scope design MVP-4, flag ke cofounder) |
| **success** | Form digantikan panel sukses (tanpa reload penuh) atau halaman redirect; `aria-live="polite"` mengumumkan | "Terima kasih, [nama]! Kami sudah catat kehadiran Anda." + ringkasan: nama, jumlah, status |
| **sudah ter-isi (guest revisit)** | Tampilkan panel "Anda sudah konfirmasi kehadiran" + tombol "Ubah jawaban" (bukan form ulang dari nol) | State conditional — flag ke cofounder apakah perlu edit RSVP atau read-only (default: read-only + tombol ubah, untuk MVP bisa read-only saja) |

## Copy Bahasa Indonesia (contoh, finalisasi oleh cofounder/founder)

Hero:
- Sub-heading di atas nama: "Undangan Pernikahan"
- Nama pasangan: "Raka & Amira"
- Tanggal: "Sabtu, 12 Desember 2026"

Kutipan:
- "Kami dengan penuh rasa syukur mengundang Anda untuk hadir dalam momen bahagia kami."

RSVP:
- Judul seksi: "Konfirmasi Kehadiran"
- Helper: "Mohon diisi selambatnya 1 minggu sebelum acara."
- Label radio: "Apakah Anda hadir?"
- Opsi radio: "Ya, insyaallah hadir" / "Mohon maaf, tidak bisa hadir"
- Label jumlah: "Jumlah tamu (termasuk Anda)"
- Label catatan: "Pesan untuk kami (opsional)"
- Tombol submit: "Kirim Konfirmasi"
- Loading: "Mengirim..."
- Success: "Terima kasih, {nama}! Konfirmasi Anda sudah kami terima."
- Error submit: "Terjadi gangguan saat mengirim. Silakan coba lagi."

Catatan: copy di atas placeholder — cofounder/founder review untuk final tone (apakah "insyaallah" dipakai tergantung audience host, defaultkan netral "Ya, hadir" jika tidak diminta religius).

## Wireframe ASCII (mobile 360px)

```
┌────────────────────────────┐
│   [foto pasangan 3:4]      │
│                            │
│        Raka & Amira        │
│    Sabtu, 12 Des 2026      │
│  "Undangan Pernikahan"      │
├────────────────────────────┤
│  Kami dengan penuh rasa    │
│  syukur mengundang Anda... │
├────────────────────────────┤
│  Detail Acara              │
│  10.00 — Akad Nikah        │
│  12.00 — Resepsi           │
│  Grand Ballroom, Bandung   │
│  [ Lihat di Peta ]         │
├────────────────────────────┤
│  Konfirmasi Kehadiran       │
│                            │
│  Nama Anda                 │
│  ┌──────────────────────┐  │
│  │                      │  │
│  └──────────────────────┘  │
│                            │
│  Apakah Anda hadir?        │
│  ( ) Ya, insyaallah hadir  │
│  ( ) Mohon maaf, tidak     │
│                            │
│  Jumlah tamu (termasuk)    │
│  ┌──────────────────────┐  │
│  │ 1                    │  │
│  └──────────────────────┘  │
│                            │
│  Pesan (opsional)          │
│  ┌──────────────────────┐  │
│  │                      │  │
│  └──────────────────────┘  │
│                            │
│  [  Kirim Konfirmasi  ]    │
├────────────────────────────┤
│  Dibuat oleh Joinara       │
└────────────────────────────┘
```

## Acceptance criteria (untuk QA/dev, selaras issue #5)

- [ ] Usable di viewport 360px tanpa zoom horizontal.
- [ ] Lighthouse a11y: 0 error kritis (kontras, label, focus, lang).
- [ ] Touch target ≥ 44px.
- [ ] Form validasi muncul setelah interaksi, tidak pada load.
- [ ] `prefers-reduced-motion` dihormati.
- [ ] Copy Bahasa Indonesia, tone hangat, tanpa emoji berlebihan.
- [ ] Anti-duplikat via `invitation_code` (keputusan di atas) — flag ke cofounder untuk konfirmasi sebelum dev.

## Open items (ke cofounder/founder)

1. Finalisasi copy: apakah "insyaallah" masuk default atau netral.
2. Apakah tamu yang sudah submit boleh edit RSVP (default usul: read-only + tombol ubah untuk nanti; MVP bisa read-only saja).
3. Format URL undangan personal: path vs query param (teknis, bisa disepakati dev).
