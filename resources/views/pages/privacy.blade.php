@extends('layouts.app')

@section('title', 'Privasi — Joinara')
@section('content')
    <div class="card">
        <h1>Privasi &amp; data Anda</h1>

        <h2>Data apa yang disimpan</h2>
        <ul>
            <li>Nama tamu (dan telepon/email bila diisi) untuk keperluan RSVP.</li>
            <li>Status kehadiran, jumlah pendamping, dan catatan dari form RSVP.</li>
            <li>Foto yang Anda unggah lewat tautan undangan.</li>
            <li>Waktu check-in saat QR dipindai.</li>
        </ul>

        <h2>Siapa yang bisa mengakses</h2>
        <p>Hanya pemilik acara (sepasang pengantin). Foto tamu berstatus <em>pending</em> tidak tampil di galeri sampai disetujui. Data tidak dibagikan ke pihak lain dan aplikasi berjalan di komputer pemilik acara.</p>

        <h2>Cara meminta penghapusan</h2>
        <p>Hubungi pemilik acara melalui kontak yang tertera di undangan, minta data Anda dihapus. Pemilik acara dapat menghapus data tamu dan foto kapan pun dari dashboard, atau mengikuti prosedur di <code>docs/privacy-delete-procedure.md</code>.</p>

        <p class="footer"><a href="/">Kembali</a></p>
    </div>
@endsection
