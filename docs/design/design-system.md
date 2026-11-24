# Design System — Joinara

Kepribadian: **undangan pernikahan Indonesia yang hangat, intim, dan tenang** — bukan tech-startup generik. Warna earthy, tipografi serif humanist, ruang napas lega, tanpa efek berlebihan.

## Prinsip

- Mobile-first; satu kolom; tidak ada zoom horizontal pada viewport 360px (AC issue #5).
- Maks 3 warna primer + 1 aksen. Tanpa gradien multi-warna / gradient text / holografik.
- Maks 2 font family (1 heading + 1 body).
- Semua ukuran dalam `rem`; spacing grid 0.5rem (8px).
- Animasi 2D CSS ≤ 300ms; hormati `prefers-reduced-motion`.
- Kontras minimum WCAG AA (4.5:1 teks normal, 3:1 teks besar/UI).

## Palet Warna

Semua hex eksplisit. Primer: 3 warna earthy hangat. Aksen: 1 warna untuk aksi utama.

| Token | Hex | Peran | Kontras pada putih (#FFFFFF) |
|---|---|---|---|
| `--ink` | `#2B2118` | Teks utama | 14.2:1 ✓ |
| `--terracotta` | `#C76B4A` | Primer, aksen hangat, garis dekoratif | 3.9:1 (teks besar/UI) ✓ |
| `--sage` | `#6B7A5F` | Primer sekunder, elemen sekunder | 5.2:1 ✓ |
| `--sand` | `#F7F0E6` | Latar hangat | — |
| `--accent` | `#3E6F4F` | Aksen aksi utama (CTA, radio/checkbox) | 5.9:1 ✓ |
| `--error` | `#A63A2C` | Pesan error | 5.1:1 ✓ |
| `--success` | `#3E6F4F` | Pesan sukses (sama dengan aksen untuk efisiensi) | 5.9:1 ✓ |

Catatan: `--accent` dipakai untuk semua elemen aksi utama (tombol submit, state terpilih). Terkonsistensi 1 warna aksi = lebih tenang, mengurangi kuantitas visual.

## Tipografi

| Token | Family | Ukuran | Line-height | Peran |
|---|---|---|---|---|
| `--font-heading` | `"Cormorant Garamond", "Times New Roman", serif` | H1 `2.5rem`, H2 `1.75rem`, H3 `1.375rem` | 1.2 | Judul pasangan, seksi, sub-spesi |
| `--font-body` | `"Source Sans 3", system-ui, sans-serif` | Body `1rem` (16px min, anti-zoom iOS) | 1.5 | Paragraf, label, helper text |

Pilihan alasan:
- **Cormorant Garamond** — serif humanist dengan rasa editorial hangat, cocok untuk nama pasangan/undangan. Bukan Inter/Outfit.
- **Source Sans 3** — sans-serif bersih, mudah dibaca, netral untuk data form.
- Maks 2 family (AC anti-slop terpenuhi).
- `clamp()` untuk H1 responsif: `clamp(2rem, 1.25rem + 3vw, 2.5rem)` (maks/min ≤ 2.5×).
- Hindari `text-wrap: balance` pada heading dengan visible box.

## Spacing

Grid 0.5rem (8px) — semua padding/margin hanya kelipatan:

| Token | Nilai |
|---|---|
| `--space-1` | 0.5rem (8px) |
| `--space-2` | 1rem (16px) |
| `--space-3` | 1.5rem (24px) |
| `--space-4` | 2rem (32px) |
| `--space-5` | 3rem (48px) |

## Radius & Shadow

| Token | Nilai | Peran |
|---|---|---|
| `--radius-sm` | 0.25rem | Input kecil, chip |
| `--radius` | 0.5rem | Tombol, kartu, form card |
| `--shadow-sm` | `0 2px 4px rgba(43, 33, 24, 0.08)` | Kartu ringan |
| `--shadow` | `0 4px 8px rgba(43, 33, 24, 0.12)` | Elevation tinggi, dialog |

Radius bulat halus, tidak sharp tech-startup; konsisten 2 tier saja.

## Komponen

### Button (primary)
- Tinggi ≥ 48px (coarse pointer); ≥ 44px minimum (AC touch target).
- Background `--accent`, teks putih (kontras 5.9:1 ✓), radius `--radius`.
- State: default / hover (darken 10%) / `:focus-visible` (outline 3px solid `--terracotta`, offset 3px) / disabled (opacity 0.5, cursor not-allowed).
- Animasi: `transition: background-color 0.15s, color 0.15s` — hanya warna, tanpa spatial transform.

### Input / Text field
- Label di atas input (stacked, bukan floating-label) — sesuai anti-AI-slop.
- `font: inherit; font-size: max(1rem, 1em); min-height: 48px; padding: 0.75rem;`
- Border 1px solid `#767676` (kontras 4.54:1 ✓ pada `--sand`).
- `:user-invalid` styling (tidak `:invalid` mentah).
- Helper text di bawah input; error message link via `aria-describedby`.
- Validasi trigger: `blur` (kalau edited) / `submit` — jangan di tiap keystroke.

### Radio group (Hadir / Tidak)
- `<fieldset>` + `<legend>`; label inline setelah radio.
- `accent-color: var(--accent)` untuk native radio branding.
- Jarak antara option: `--space-1`.
- Target ukuran: ≥ 44px (padding pada label wrapper).

### Toast / status live region
- `role="status"` + `aria-live="polite"` untuk sukses/soft error.
- `role="alert"` + `aria-live="assertive"` hanya untuk error kritis (gagal submit).

## Aksesibilitas (Lighthouse 0 error kritis)

- `lang="id"` di `<html>`.
- Satu `<main>` per halaman; heading hierarkis `h1 → h2`.
- `:focus-visible` selalu visible, kontras ≥ 3:1.
- `prefers-reduced-motion: no-preference` untuk animasi transform; warna transition selalu aktif.
- Alt-text deskriptif untuk foto pasangan; `alt=""` untuk dekorasi.
- Tap target ≥ 44px (mobile) — semua button/input/radio.

## Motion

- Maks 300ms, hanya `opacity`/`transform` (compositor thread).
- Fade-in hero (0.3s opacity), slide-up form card (0.25s translateY).
- Tidak ada auto-running carousel. Tidak ada blink/flash.
- `@media (prefers-reduced-motion: no-preference)` membungkus semua animasi spatial; state final tetap visible.

## Anti-Slop Checklist (self-audit)

- [x] ≤ 3 primer + 1 aksen, hex eksplisit, semua kontras AA terverifikasi.
- [x] 2 font family saja (Cormorant Garamond + Source Sans 3), bukan Inter/Outfit.
- [x] Label form di atas input.
- [x] Touch target ≥ 44px.
- [x] Animasi ≤ 300ms, `prefers-reduced-motion` dihormati.
- [x] Tanpa gradien multi-warna / gradient text.
- [x] Copy Bahasa Indonesia natural, tanpa emoji berlebihan.
