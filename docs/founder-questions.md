# Joinara Founder Questions

Status: Q1–4 dijawab sebagai arahan awal/hipotesis. Q5–10 menunggu jawaban founder.

## Terjawab

1. **ICP awal:** pasangan yang sedang menyiapkan pernikahan di Indonesia. WO/vendor sekunder, perlu validasi.
2. **Masalah yang diuji:** kumpul/kelola foto-video tamu tanpa instal app, sekaligus undangan, RSVP, guest list, check-in. Masih hipotesis; belum terbukti paling mahal.
3. **Fokus event:** wedding saja untuk MVP; rancang agar bisa diperluas nanti.
4. **Sukses validasi:** belum ditetapkan. Usulan awal: 5 pasangan + 3 WO mencoba; ≥1 event nyata; ukur RSVP ≥50% dan upload ≥20%. Angka bukan keputusan sampai founder menyetujui.

## Menunggu jawaban founder

5. **Hosted service MVP:** pilih satu: (A) tunda; self-host/local pilot saja, (B) hosted masuk MVP, founder/operator awal siapa? **Rekomendasi:** tunda hosted agar target 7 hari fokus.

6. **Privasi media:** pilih/ubah: (A) galeri hanya owner + tamu dengan token event, (B) owner saja; default media pending; retensi (mis. sampai owner hapus / 30 hari setelah event); deletion/export wajib sejak MVP? **Rekomendasi:** approved media dapat dilihat pemegang token event; owner approve/hide; owner menghapus; permintaan hapus manual.

7. **QR check-in offline:** pilih: (A) online-only diterima, (B) offline wajib. **Rekomendasi:** online-only untuk MVP; PWA offline hanya cache aset.

8. **Database/storage:** setujui atau ubah usulan: SQLite + disk lokal untuk self-host MVP; batas usulan 25 MB/file dan video ≤2 menit; retensi sampai owner hapus. Alternatif DB: MySQL/Postgres. **Rekomendasi:** SQLite dulu, Eloquent menjaga migrasi.

9. **Waktu/budget/kapasitas:** konfirmasi tanggal mulai dan hari ke-7; budget infrastruktur/tool (usulan: Rp0 untuk MVP lokal); agent/model yang disetujui. **Rekomendasi:** tanpa biaya berbayar sebelum persetujuan eksplisit.

10. **Approval/pilot:** siapa selain founder boleh review PR? Syarat siap pilot: (A) founder manual test happy path, (B) test report + 1 event pilot, (C) keduanya. **Rekomendasi:** C; founder tetap satu-satunya approver merge/scope.
