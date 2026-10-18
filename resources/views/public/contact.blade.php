@extends('layouts.public')

@section('content')
<style>
    .contact-wrap{display:grid;grid-template-columns:.9fr 1.1fr;gap:70px;padding:85px 0}
    .contact-form{display:grid;gap:16px;padding:30px;border:1px solid var(--line);background:white}
    .contact-form label{display:grid;gap:7px;color:var(--forest);font-size:12px;font-weight:750}
    .contact-form input,.contact-form textarea{width:100%;padding:12px;border:1px solid var(--line);border-radius:3px;outline:0;color:var(--ink)}
    .contact-form textarea{min-height:120px;resize:vertical}
    .notice{padding:17px;border-left:3px solid var(--brass);background:#f1ede3;color:var(--muted);font-size:12px;line-height:1.7}
    @media(max-width:780px){.contact-wrap{grid-template-columns:1fr;gap:35px;padding:60px 0}}
</style>
<main class="container contact-wrap">
    <div>
        <p class="eyebrow">Kontak Perusahaan</p>
        <h1 class="section-title">Konsultasikan kebutuhan pasokan sawit Anda.</h1>
        <p class="section-copy">Sampaikan jenis komoditas, estimasi volume kargo, negara dan pelabuhan tujuan ekspor, serta persyaratan teknis lainnya. Tim pemasaran ekspor kami akan segera merespons permintaan Anda.</p>
        <p class="notice">Lingkungan Demo: Untuk mengajukan penawaran harga resmi (RFQ) dengan perhitungan harga dan riwayat negosiasi, disarankan mendaftar atau masuk sebagai Buyer.</p>
    </div>
    <form class="contact-form" onsubmit="event.preventDefault(); alert('Pesan pertanyaan berhasil disimulasikan. Silakan gunakan alur RFQ di akun Buyer untuk transaksi riil.');">
        <label>Nama Lengkap PIC<input required autocomplete="name" placeholder="Nama Anda"></label>
        <label>Email Bisnis Perusahaan<input required type="email" autocomplete="email" placeholder="nama@perusahaan.com"></label>
        <label>Nama Perusahaan / Importir<input required autocomplete="organization" placeholder="PT / Ltd / Corp"></label>
        <label>Pesan Pertanyaan / Kebutuhan Kargo<textarea required placeholder="Jelaskan kebutuhan produk, target volume metrik ton (MT), pelabuhan tujuan, dan pertanyaan lainnya..."></textarea></label>
        <button class="button button-primary" type="submit">Kirim Pertanyaan</button>
    </form>
</main>
@endsection
