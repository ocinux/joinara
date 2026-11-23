# Joinara Decision Log

Sumber arahan lanjutan: komentar founder `ocinux` di https://github.com/ocinux/joinara/pull/1. Tanggal pencatatan: 2026-11-20.

| Keputusan | Status | Sumber |
|---|---|---|
| MIT lisensi final; hak mengikuti teks LICENSE, bukan klaim hak tambahan. | Disetujui | Konfirmasi chat founder |
| Operating Plan disetujui secara prinsip dengan koreksi di PR #1. | Disetujui prinsip | PR #1 |
| Laravel frontend dan backend, satu aplikasi. | Disetujui | PR #1 |
| Target MVP maksimal 7 hari; mulai kalender dihitung sejak issue implementasi disetujui dan dimulai. | Disetujui target | PR #1; review 2026-11-23 Q9 |
| Scope inti: landing, halaman undangan, RSVP, guest list, QR check-in dasar, upload foto/video + galeri privat bermoderasi. | Scope disetujui | PR #1 |
| Mobile-first, responsif, PWA manifest + SW dasar; tidak ada klaim offline fungsional. | Disetujui | PR #1 |
| madbox.io referensi visual/interaksi saja; anti-copy; animasi 2D ringan; aksesibilitas + prefers-reduced-motion. | Disetujui | PR #1 |
| Tunda Google Drive, custom domain, seating, slideshow, analytics lanjut, pembayaran, 3D/Three.js. | Disetujui | PR #1 |
| ICP pasangan Indonesia; WO/vendor sekunder. | Arahan awal; perlu validasi | PR #1, Q1 |
| Foto-video tanpa instal app = masalah yang diuji. | Hipotesis produk; bukan terbukti | PR #1 Q2; ditegaskan review 2026-11-23 |
| Wedding saja untuk MVP. | Disetujui | PR #1, Q3 |
| Merge ke main/PR hanya dengan approval eksplisit founder. | Disetujui | PR #1; Q10 |
| Hosted service ditunda; self-host/local pilot saja; tunnel = PoC opsional, bukan jaminan hosting. | Disetujui | Review 2026-11-23, Q5 |
| Privasi: galeri privat; akses via token event; media baru pending; approve/hide/hapus oleh owner; pemegang tautan TIDAK melihat media pending; permintaan hapus manual; retensi sampai owner menghapus. | Disetujui | Review 2026-11-23, Q6 |
| QR check-in online-only; PWA cache aset saja; fallback pencarian nama/manual wajib. | Disetujui | Review 2026-11-23, Q7 |
| SQLite + disk lokal disetujui SEMENTARA untuk MVP lokal via Eloquent/migrasi; bukan keputusan produksi/hosted; batas SQLite didokumentasikan; evaluasi ulang sebelum skenario multi-user. Batas upload 25 MB/file + video ≤2 menit dipertahankan hanya jika realistis diuji. Kapasitas/backup/penghapusan wajib didokumentasikan. | Disetujui sementara | Review 2026-11-23, Q8 |
| Waktu: 7 hari sejak implementasi disetujui; budget tambahan Rp0 tanpa persetujuan; pakai tooling tersedia. | Disetujui | Review 2026-11-23, Q9 |
| Siap pilot = founder tes manual happy path + test report + 1 event pilot. Founder satu-satunya approver merge/scope/biaya. | Disetujui | Review 2026-11-23, Q10 |
| Angka validasi Q4 (5 pasangan, 3 WO, ≥1 event nyata, RSVP ≥50%, upload ≥20%). | Disetujui sebagai target eksperimen awal; bukan klaim validasi / syarat final | Review 2026-11-23 |
| Buat GitHub Issues setelah backlog diperbarui. Issues saja, belum coding; item tunnel/fitur lanjutan dipisah dari jalur inti. | Disetujui | Review 2026-11-23 |
| Privasi/keamanan minimum wajib ada sebelum upload yang dapat diakses publik diuji. | Disetujui | Review 2026-11-23 |

| Klarifikasi jalur inti disetujui: urut #2→#10 lalu #11; #12–#15 non-blocking; jalur inti diuji lokal/LAN tanpa tunnel; #2 requirement PHP/Composer + tanpa kredensial default dikomit + PWA tidak cache login/privat; #3–#4 token tidak masuk log + usul angka rate limit + bukti uji; #5–#6 aturan RSVP anti-duplikat ditetapkan + guest list/CSV tetap sederhana; #8 JPEG/PNG minimum, HEIC/video/EXIF/ZIP hanya jika realistis; #9 pending/hidden harus 404/403 via URL langsung; #10 backup DB+media, restore diuji. Klarifikasi ditulis di body Issues. | Disetujui | Review founder 2026-11-23 |
| Setelah klarifikasi diperbarui: agen boleh mulai jalur inti; tidak ada coding di luar scope issue + acceptance criteria. | Disetujui | Review founder 2026-11-23 |

## Belum disetujui / terbuka

- Batas upload final (25 MB, video ≤2 menit) dikunci hanya setelah terbukti realistis diuji dalam jadwal.
- Tanggal kalender mulai/done: dihitung saat issue implementasi disetujui.
- Keputusan produksi/hosted (DB final, storage hosted, multi-user) — dievaluasi ulang pasca-MVP.
