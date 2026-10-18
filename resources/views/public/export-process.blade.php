@extends('layouts.public')

@section('content')
<style>
    .process-list{display:grid;grid-template-columns:repeat(2,1fr);gap:1px;background:var(--line);border:1px solid var(--line)}
    .process-list article{padding:31px;background:white}
    .process-list strong{display:block;margin-bottom:13px;color:var(--brass);font:400 32px var(--serif)}
    .process-list h2{margin:0 0 8px;color:var(--forest);font-size:17px}
    .process-list p{margin:0;color:var(--muted);font-size:13px;line-height:1.7}
    @media(max-width:620px){.process-list{grid-template-columns:1fr}}
</style>
<main>
    <section class="page-hero">
        <div class="container">
            <p class="eyebrow" style="color:#d9bd8b">Alur & Prosedur Ekspor</p>
            <h1>Tahapan komersial ekspor, tercatat dengan transparan.</h1>
            <p>Setiap transaksi ekspor kelapa sawit mengikuti alur tata kelola yang teratur. PalmTrade mencatat tahapan mulai dari penawaran harga hingga tracking pengiriman pelayaran internasional.</p>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="process-list">
                <article>
                    <strong>01</strong>
                    <h2>Konfirmasi Penawaran Harga (Offer)</h2>
                    <p>Pembeli dan tim eksportir menyepakati kuantitas, masa berlaku penawaran, serta harga per metrik ton.</p>
                </article>
                <article>
                    <strong>02</strong>
                    <h2>Persetujuan Ketentuan Perdagangan (Trade Terms)</h2>
                    <p>Incoterms (FOB Belawan/Dumai atau CIF pelabuhan tujuan) dan metode pembayaran disepakati dalam kontrak.</p>
                </article>
                <article>
                    <strong>03</strong>
                    <h2>Proses & Verifikasi Pembayaran</h2>
                    <p>Pembeli mengunggah bukti penerbitan Letter of Credit (L/C) atau konfirmasi transfer bank (T/T Swift MT103).</p>
                </article>
                <article>
                    <strong>04</strong>
                    <h2>Persiapan Produk & Dokumen Ekspor</h2>
                    <p>Penyediaan tangki kargo/flexibag, sertifikat mutu surveyor independen, Phytosanitary, dan perizinan bea cukai (PEB).</p>
                </article>
                <article>
                    <strong>05</strong>
                    <h2>Pemuatan Kapal & Pengiriman (Shipment)</h2>
                    <p>Kargo dimuat ke kapal, penerbitan Bill of Lading (B/L), nomor pelayaran, dan estimasi waktu tiba (ETA).</p>
                </article>
                <article>
                    <strong>06</strong>
                    <h2>Pembaruan Status & Linimasa Logistik</h2>
                    <p>Setiap pergerakan kargo di laut dan pelabuhan bongkar diperbarui secara real-time di sistem pelacakan.</p>
                </article>
            </div>
        </div>
    </section>
</main>
@endsection
