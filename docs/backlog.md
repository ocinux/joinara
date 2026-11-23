# Joinara Backlog

Draft 2026-11-20. Mengacu ke `product-context.md` dan `decision-log.md`. Urutan prioritas untuk 7 hari MVP. Nomor Issue GitHub mengikuti saat dibuat.

Legenda: P0 = wajib MVP · P1 = MVP jika muat · P2 = ditunda pasca-MVP.

## P0 — inti, urutan build
1. Scaffold Laravel: SQLite (jika founder setuju), auth owner, migrasi awal (event, guest, rsvp, media), PWA manifest + SW dasar.
2. CRUD event: buat event (nama, tanggal, deskripsi singkat), URL undangan token, pembuatan QR (link publik).
3. Halaman undangan publik mobile-first: detail event + form RSVP (nama, status, jumlah, catatan). Tanpa login.
4. Guest list + RSVP dashboard owner: tabel tamu, status RSVP, tambah tamu manual, export CSV.
5. QR check-in dasar: owner buka mode check-in, scan/cari tamu → tandai hadir + waktu.
6. Upload foto/video tamu: link token event (tanpa login), validasi tipe/ukuran, status media `pending`.
7. Galeri privat + moderasi: owner approve/hide, tampilkan approved saja, download sederhana (zip atau satu-satu).
8. Privasi dasar: halaman privasi, jalur penghapusan data manual, rate-limit upload, URL token tidak tertebak.

## P1 — aman + pilot-ready
9. Test plan E2E MVP (happy path RSVP → upload → approve → check-in).
10. PoC tunnel aman: jalankan app lokal, tunnel ber-auth, QR berfungsi; laporkan hasil (bukan klaim produksi).

## P2 — eksplisit ditunda (per founder)
11. Integrasi Google Drive (OAuth, kuota, revocability) — evaluasi riset saja, tidak build.
12. Custom domain, seating plan, slideshow live, analytics lanjutan, pembayaran, 3D.
13. Hosted multi-tenant berbayar — tergantung keputusan Q5 founder.

## Riset paralel (bukan coding)
- Riset kompetitor tersumber (undangan digital Indonesia + global) + pricing observasi — tandai semua "belum validasi" sampai disetujui untuk dilakukan.

## Definition of Done (backlog)
Setiap item selesai = Issue tertutup via PR (founder approval untuk merge ke main) + acceptance criteria Issue terpenuhi + dokumentasi terkait diperbarui + tidak ada secret/pii di repo.
