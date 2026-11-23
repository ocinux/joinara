# Joinara Backlog

Diperbarui 2026-11-23 mengikuti review founder (Q5–10). Mengacu `product-context.md` + `decision-log.md`. Realistis untuk jendela MVP 7 hari sejak implementasi disetujui.

Legenda: P0 = wajib MVP · P1 = jalur terpisah/PoC · P2 = ditunda eksplisit.
Aturan: founder satu-satunya approver merge/scope/biaya; siap pilot = tes manual founder + test report + 1 event pilot (Q10).

## P0 — jalur inti (urutan build)
1. **Scaffold Laravel** — SQLite + disk lokal (sementara, Q8), auth owner session, migrasi (event, guest, rsvp, media, check-in), PWA manifest + SW cache aset statis saja. Catatan batas SQLite di README dev.
2. **Event & tautan undangan** — owner buat event (pasangan, tanggal, detail, template 2D ringan), token unik per event (tidak tertebak), QR dari URL token, halaman "buat undangan" pertama.
3. **Privasi & keamanan minimum** — WAJIB sebelum fitur upload publik diuji (catatan founder): rate limit, validasi MIME/ukuran server-side, halaman privasi singkat, jalur permintaan hapus data manual (email/form → owner hapus).
4. **Halaman undangan publik + RSVP** — mobile-first, aksesibilitas, prefers-reduced-motion; form RSVP (nama, hadir/tidak, jumlah, catatan) tanpa login.
5. **Guest list + dashboard RSVP** — tabel tamu + status RSVP, tambah tamu manual, export CSV, indikator jumlah.
6. **QR check-in dasar** — online-only (Q7); mode check-in owner: scan → tandai hadir; fallback pencarian nama/manual wajib.
7. **Upload foto/video tamu** — via tautan token, tanpa login; status media `pending` default; batas uji 25 MB/file & video ≤2 mnt (hanya jika realistis; hasil uji dicatat, Q8).
8. **Galeri privat + moderasi** — hanya media approved yang tampil; pemegang tautan TIDAK melihat pending (Q6); owner approve/hide/hapus; download sederhana (per file / zip).
9. **Storage lokal: kapasitas, backup, penghapusan** — dokumentasi + mekanisme: panduan backup (copy file DB + folder media), retensi sampai owner hapus, penghapusan media/data event.

## P1 — jalur terpisah (bukan blocking jalur inti)
10. **Test plan + laporan E2E MVP** — happy path RSVP → upload → approve → check-in → download; termasuk verifikasi batas upload; hasil uji menjadi dasar kunci/ubah angka batas (Q8).
11. **PoC tunnel akses tamu (eksperimen opsional)** — app lokal + HTTPS tunnel dengan kontrol akses; validasi upload harus sudah siap (Issue #3) sebelum diekspos; QR diuji terhadap URL stabil; dokumentasikan: komputer harus nyala + internet, upload mati saat app/tunnel down. Bukan jaminan hosting gratis/permanen (Q5).

## P2 — ditunda eksplisit (per founder)
12. Google Drive sebagai storage pengguna — riset OAuth/privasi/kuota/revocability/penghapusan, tanpa build.
13. Storage hosted, custom domain, seating plan, live slideshow, analytics lanjutan, pembayaran, 3D/Three.js, hosted multi-tenant.

## Riset paralel (non-coding)
- Riset kompetitor + harga tersumber (undangan digital ID + global); semua temuan berlabel sumber, status "belum divalidasi" sampai founder menyetujui klaim.

## Definition of Done
Issue selesai = PR (merge hanya dengan approval founder) + acceptance criteria Issue terpenuhi + test terkait lulus + dokumentasi diperbarui + tidak ada secret/PII di repo/dokumen.
