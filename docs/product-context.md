# Joinara Product Context

Diperbarui 2026-11-20 dari arahan founder di PR #1. Status dokumen: draft untuk review founder. Keputusan terikat hanya jika tercatat di `decision-log.md`.

## Satu kalimat
Undangan digital + pengumpul foto/video tamu untuk pernikahan di Indonesia: tamu buka link/QR, RSVP, lalu mengunggah momen tanpa memasang aplikasi.

## Klaim terverifikasi
- Repo `ocinux/joinara` publik; baseline hanya README + LICENSE MIT; belum ada kode produk.
- Founder menetapkan: Laravel, mobile-first + PWA dasar, 7 hari MVP, scope enam fitur inti, MIT final (lihat `decision-log.md`).

## Hipotesis (belum divalidasi — label jujur)
- H1: Mengumpulkan foto/video tamu tanpa instal aplikasi adalah masalah termahal dalam pernikahan Indonesia. Belum ada bukti kuantitatif; founder sendiri menandainya hipotesis.
- H2: Pasangan (self-serve) adalah pembeli pertama; WO/vendor saluran sekunder yang perlu validasi.
- H3: Model self-host (laptop organizer + tunnel HTTPS) cukup layak untuk pilot. Belum diuji; PoC wajib sebelum klaim.
- Kompetitor, harga pasar, volume demand: BELUM diriset. Jangan kutip angka dari dokumen ini sampai riset tersumber dibuat.

## Produk MVP — scope terkunci founder
Alur inti dalam 7 hari:
1. Landing page sederhana (jelaskan + CTA).
2. Buat event/undangan (owner buat via akun sederhana; template 2D ringan).
3. Halaman undangan publik mobile-first untuk tamu.
4. RSVP tamu (hadir/tidak, jumlah orang, catatan).
5. Guest list dasar + status RSVP (owner lihat/export CSV).
6. QR check-in dasar (tandai hadir saat event).
7. Upload foto/video tamu via link/QR (tanpa login app) + galeri privat dengan moderasi sederhana (approve/hide oleh owner).

Ditunda: Google Drive, custom domain, seating, slideshow live, analytics lanjut, pembayaran, 3D/Three.js, offline klaim PWA.

## Non-goals MVP
- Multi-tipe event umum; hanya wedding.
- Multi-tenant hosting berbayar (kecuali founder menyetujui di Q5).
- Aplikasi native.

## Keputusan teknis — usulan sebelum dikunci
- Database: **SQLite untuk MVP/self-host, abstraksi via Eloquent sehingga pindah MySQL/Postgres nanti murah.** Alasan: nol-ops untuk organizer yang menjalankan di laptop, backup = copy file, migrasi Laravel standar. Alternatif: MySQL (lebih lazim di shared hosting Indonesia, tapi menambah langkah instalasi). Rekomendasi: SQLite dulu. MENUNGGU persetujuan founder (Q8).
- Storage MVP: disk lokal + batas upload per file (usulan 25 MB, JPEG/HEIC→konversi, MP4/WebM ≤2 menit usulan) + retensi default sampai owner hapus (usulan). Hosting gambar tanpa CDN membatasi jumlah foto tamu; angka aman perlu diuji di PoC. MENUNGGU Q6.
- Auth: session login owner sederhana; akses tamu via token unik di URL/QR, bukan akun. Rekomendasi, belum dikunci.
- PWA: manifest + service worker cache aset statis saja; tidak ada klaim offline fungsional.
- Moderasi: status `pending/approved/hidden`, default pending, owner approve dari guest list page.

## Privasi & keamanan (minimum)
- Data tamu (nama, jumlah, foto) = data pribadi. Butuh: halaman privasi singkat, mekanisme hapus (permintaan hapus data peserta → manual MVP), akses galeri via URL token yang tidak tertebak, rate limit upload.
- Jangan simpan secret di repo/dokumen. Jangan ekspos app lokal tanpa tunnel ber-AUTH (mis. tunnel dengan password) + validasi tipe/ukuran upload — sesuai arahan founder.

## Definisi sukses validasi (USULAN — founder yang menetapkan angka)
Eksperimen 7–10 hari setelah prototype bisa dipakai:
1. 5 pasangan bertunangan / 3 WO mencoba prototipe untuk event nyata.
2. Ukur: % tamu yang berhasil RSVP (target ≥50% dari tamu diundang), % yang upload ≥1 foto (target ≥20%), event end-to-end tanpa pendampingan teknis (target ≥1).
3. Sinyal kesediaan: minimal 1 event nyata memakai Joinara + wawancara singkat "apa yang mahal/merepotkan tanpa ini".
Angka di atas = proposal, bukan keputusan.

## Roadmap kasar
- Fase 0 (sekarang): docs + issues + jawaban founder Q5–10.
- Fase 1: build MVP 7 hari (lihat `backlog.md`).
- Fase 2: pilot event nyata + validasi H1–H3; PoC tunnel aman paralel.
- Fase 3: keputusan hosted vs self-host, monetisasi, perluasan event type — semua butuh keputusan founder.
