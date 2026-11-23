# Joinara Product Context

Diperbarui 2026-11-23 mengikuti review founder (Q5–10 + catatan PR #1). Status: selaras keputusan; hal yang belum terikat ditandai usulan/sementara. Keputusan terikat tercatat di `decision-log.md`.

## Satu kalimat
Undangan digital + pengumpul foto/video tamu untuk pernikahan di Indonesia: tamu buka link/QR, RSVP, lalu mengunggah momen tanpa memasang aplikasi.

## Klaim terverifikasi
- Repo `ocinux/joinara` publik; baseline hanya README + LICENSE MIT; belum ada kode produk.
- Founder menetapkan: Laravel, mobile-first + PWA dasar, 7 hari MVP, scope inti (lihat daftar di bawah), MIT final (lihat `decision-log.md`).

## Hipotesis (belum divalidasi — label jujur)
- H1: Mengumpulkan foto/video tamu tanpa instal aplikasi adalah masalah termahal dalam pernikahan Indonesia. Belum ada bukti kuantitatif; founder sendiri menandainya hipotesis.
- H2: Pasangan (self-serve) adalah pembeli pertama; WO/vendor saluran sekunder yang perlu validasi.
- H3: Model self-host (laptop organizer + tunnel HTTPS) cukup layak untuk pilot. Belum diuji; PoC wajib sebelum klaim.
- Kompetitor, harga pasar, volume demand: BELUM diriset. Jangan kutip angka dari dokumen ini sampai riset tersumber dibuat.

## Produk MVP — scope terkunci founder
Alur inti dalam 7 hari (enam fitur):
1. Landing page sederhana (jelaskan + CTA).
2. Halaman event/undangan publik mobile-first untuk tamu (dibuat owner via akun sederhana; template 2D ringan; QR + tautan token).
3. RSVP tamu (hadir/tidak, jumlah orang, catatan).
4. Guest list dasar + status RSVP (owner lihat/export CSV).
5. QR check-in dasar (tandai hadir saat event; **fallback pencarian nama/manual wajib** bila scan/kamera gagal; online-only).
6. Upload foto/video tamu via link/QR (tanpa login) + galeri privat dengan moderasi sederhana (pending default; owner approve/hide/hapus).

Ditunda: Google Drive, custom domain, seating, slideshow live, analytics lanjut, pembayaran, 3D/Three.js, offline klaim PWA.

## Non-goals MVP
- Multi-tipe event umum; hanya wedding.
- Hosted multi-user berbayar (ditunda, Q5). Self-host/local pilot saja; tunnel = PoC opsional.
- Aplikasi native.

## Keputusan teknis — disetujui / sementara / usulan
- Database: **SQLite + disk lokal DISETUJUI SEMENTARA untuk MVP lokal** (Q8), via Eloquent + migrasi agar pindah MySQL mungkin. BUKAN keputusan produksi/hosted multi-user; batas SQLite didokumentasikan dan dievaluasi ulang sebelum skenario itu.
- Storage MVP: disk lokal. Batas upload **usulan** 25 MB/file dan video ≤2 menit — dipertahankan HANYA jika realistis diuji dalam jadwal; belum dikunci. Retensi default sampai owner menghapus (disetujui, Q6).
- Auth: session login owner sederhana; akses tamu via token unik di URL/QR, bukan akun. Rekomendasi, belum dikunci.
- PWA: manifest + service worker cache aset statis saja; tidak ada klaim offline fungsional (Q7).
- Moderasi: status `pending/approved/hidden`, default pending; pemegang tautan TIDAK melihat media pending (Q6).
- Batas SQLite: SQLite bekerja untuk satu app lokal; keterbatasan: tulis bersamaan terbatas, backup = copy file saat app idle, tidak cocok untuk banyak owner bersamaan → wajib evaluasi sebelum hosted.

## Privasi & keamanan (minimum — WAJIB sebelum upload yang dapat diakses publik diuji)
- Data tamu (nama, jumlah, foto) = data pribadi. Butuh: halaman privasi singkat, jalur permintaan penghapusan data manual, akses galeri via URL token tidak tertebak, rate limit upload, validasi tipe/ukuran.
- Kontrol akses + validasi upload minimum harus siap SEBELUM mode tunnel membuka aplikasi lokal ke publik (catatan review founder).
- Jangan simpan secret di repo/dokumen.

## Definisi sukses validasi — DISETUJUI sebagai target eksperimen awal (Q4)
Bukan klaim validasi, bukan syarat produk final. Eksperimen 7–10 hari setelah prototype bisa dipakai:
1. 5 pasangan bertunangan / 3 WO mencoba prototipe untuk event nyata.
2. Ukur: % tamu yang berhasil RSVP (target ≥50% dari tamu diundang), % yang upload ≥1 foto (target ≥20%), event end-to-end tanpa pendampingan teknis (target ≥1).
3. Sinyal kesediaan: minimal 1 event nyata memakai Joinara + wawancara singkat "apa yang mahal/merepotkan tanpa ini".

## Roadmap kasar
- Fase 0 (sekarang): docs + issues + jawaban founder Q5–10.
- Fase 1: build MVP 7 hari (lihat `backlog.md`).
- Fase 2: pilot event nyata + validasi H1–H3; PoC tunnel aman paralel.
- Fase 3: keputusan hosted vs self-host, monetisasi, perluasan event type — semua butuh keputusan founder.
