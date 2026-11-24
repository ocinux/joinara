# Joinara Founder Questions

Status: Q1–10 TERJAWAB founder. Q1–4 = arahan awal/hipotesis; Q5–10 = keputusan final 2026-11-23. Detail terikat di `decision-log.md`.

## Terjawab

1. **ICP:** pasangan menyiapkan pernikahan di Indonesia; WO/vendor sekunder, perlu validasi. (Arahan awal)
2. **Masalah diuji:** kumpul/kelola foto-video tamu tanpa instal app + undangan/RSVP/guest/check-in. Status: hipotesis — bukan masalah terbukti termahal.
3. **Fokus event:** wedding saja untuk MVP; diperluas kemudian.
4. **Sukses validasi:** DISSETUJUJI sebagai target eksperimen awal (5 pasangan, 3 WO, ≥1 event nyata, RSVP ≥50%, upload ≥20%). Bukan klaim validasi, bukan syarat produk final.
5. **Hosted:** A — ditunda. Self-hosted/local pilot saja; tunnel boleh diuji sebagai PoC, bukan jaminan hosting gratis/permanen.
6. **Privasi media:** A dengan persetujuan owner. Galeri tidak publik; akses tamu via tautan/token event. Media baru = pending sampai owner approve/hide. Pemegang tautan TIDAK otomatis melihat media belum disetujui. Owner dapat menghapus; permintaan penghapusan manual di MVP. Retensi: sampai owner menghapus.
7. **QR check-in:** A — online-only untuk MVP. PWA hanya cache aset; tidak mengklaim check-in/sync offline. Fallback pencarian nama/manual wajib bila scan/kamera gagal.
8. **DB/storage:** SQLite + disk lokal disetujui SEMENTARA untuk MVP lokal 7 hari, via Eloquent + migrasi agar pindah MySQL mungkin. Bukan keputusan produksi/hosted multi-user; batas SQLite dokumentasi + evaluasi ulang sebelum skenario itu. Batas 25 MB/file dan video ≤2 menit dipertahankan hanya jika realistis diuji dalam jadwal. Kapasitas disk, backup, penghapusan harus dijelaskan. Google Drive + storage hosted ditunda.
9. **Waktu/budget:** maksimal 7 hari sejak implementasi disetujui+dimulai; tanggal kalender dicatat saat issue disetujui. Budget tambahan Rp0 tanpa persetujuan eksplisit; pakai kuota/tooling yang ada; minta persetujuan sebelum biaya.
10. **Approval/pilot:** C — founder tes manual happy path + test report + 1 event pilot sebelum siap pilot. Founder satu-satunya approver merge ke main, scope, dan biaya.
