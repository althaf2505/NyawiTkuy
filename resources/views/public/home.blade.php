@extends('layouts.public')

@section('content')
    <style>
        .hero { position:relative; min-height:620px; display:flex; align-items:end; overflow:hidden; color:white; background:var(--forest); } .hero-media { position:absolute; inset:0; background:linear-gradient(90deg,rgb(10 42 30 / .86) 0%,rgb(10 42 30 / .68) 48%,rgb(10 42 30 / .18) 100%),url('https://images.unsplash.com/photo-1597318181409-cf64d0b5d8a2?auto=format&fit=crop&w=1900&q=85') center/cover; } .hero .container { position:relative; padding-bottom:80px; } .hero-label { display:inline-flex; gap:9px; align-items:center; padding:8px 11px; border:1px solid rgb(255 255 255 / .28); color:rgb(255 255 255 / .85); font-size:10px; letter-spacing:.12em; text-transform:uppercase; } .hero-label i { width:6px; height:6px; border-radius:50%; background:var(--brass); } .hero h1 { max-width:780px; margin:20px 0; font-family:var(--serif); font-size:clamp(50px,7vw,88px); font-weight:400; letter-spacing:-.065em; line-height:.94; } .hero-copy { max-width:570px; margin:0 0 29px; color:rgb(255 255 255 / .8); font-size:16px; line-height:1.8; } .hero-actions { display:flex; flex-wrap:wrap; gap:11px; } .overview { display:grid; grid-template-columns:1.05fr 1.25fr; gap:75px; padding:105px 0; } .overview-copy { color:var(--muted); font-size:15px; line-height:1.85; } .capabilities { display:grid; grid-template-columns:1fr 1fr; gap:0; border-top:1px solid var(--line); border-left:1px solid var(--line); } .capability { min-height:145px; padding:24px; border-right:1px solid var(--line); border-bottom:1px solid var(--line); background:white; } .capability svg { width:23px; height:23px; color:var(--green); } .capability h3 { margin:20px 0 5px; color:var(--forest); font-size:14px; } .capability p { margin:0; color:var(--muted); font-size:12px; line-height:1.6; } .product-section { background:#eff2ec; } .product-section .section-top { margin-bottom:35px; } .why-grid { display:grid; grid-template-columns:1fr 1.2fr; gap:70px; align-items:center; } .why-photo { min-height:520px; background:url('https://images.unsplash.com/photo-1515823662972-da6cf2d2d6e4?auto=format&fit=crop&w=1000&q=85') center/cover; } .why-list { display:grid; gap:0; margin-top:28px; border-top:1px solid var(--line); } .why-item { display:grid; grid-template-columns:42px 1fr; gap:15px; padding:18px 0; border-bottom:1px solid var(--line); } .why-number { color:var(--brass); font-family:var(--serif); font-size:19px; } .why-item h3 { margin:0 0 4px; color:var(--forest); font-size:14px; } .why-item p { margin:0; color:var(--muted); font-size:12px; line-height:1.65; } .steps { display:grid; grid-template-columns:repeat(5,1fr); gap:0; margin-top:41px; border-top:1px solid var(--line); } .step { position:relative; padding:22px 25px 0 0; } .step::before { position:absolute; top:-5px; left:0; width:9px; height:9px; border-radius:50%; background:var(--brass); content:''; } .step:not(:last-child)::after { position:absolute; top:-1px; right:0; width:calc(100% - 9px); height:1px; background:var(--line); content:''; } .step-num { margin:0 0 35px; color:var(--brass); font-size:10px; font-weight:800; letter-spacing:.12em; } .step h3 { margin:0 0 8px; color:var(--forest); font-size:14px; } .step p { margin:0; color:var(--muted); font-size:12px; line-height:1.65; } .process-panel { display:grid; grid-template-columns:1.2fr .8fr; overflow:hidden; color:white; background:var(--forest); } .process-panel .panel-photo { min-height:390px; background:linear-gradient(90deg,rgb(18 60 46 / .2),rgb(18 60 46 / .05)),url('https://images.unsplash.com/photo-1566576721346-d4a3b4eaeb55?auto=format&fit=crop&w=1000&q=85') center/cover; } .process-panel .panel-copy { display:flex; flex-direction:column; justify-content:center; padding:52px; } .process-panel h2 { margin:14px 0; font-family:var(--serif); font-size:43px; font-weight:400; letter-spacing:-.045em; line-height:1; } .process-panel p { margin:0 0 26px; color:rgb(255 255 255 / .7); font-size:13px; line-height:1.8; } .final-cta { margin-top:95px; padding:73px 65px; color:white; background:var(--brass); } .final-cta h2 { max-width:650px; margin:14px 0; font-family:var(--serif); font-size:clamp(38px,5vw,62px); font-weight:400; letter-spacing:-.05em; line-height:.98; } .final-cta p { max-width:580px; margin:0 0 28px; color:rgb(255 255 255 / .84); font-size:14px; line-height:1.8; } .final-cta .button { color:var(--forest); background:white; }
        @media (max-width:780px) { .hero { min-height:590px; } .hero .container { padding-bottom:55px; } .hero h1 { font-size:clamp(47px,13vw,69px); } .overview,.why-grid,.process-panel { grid-template-columns:1fr; gap:37px; } .overview { padding:65px 0; } .why-photo { min-height:310px; } .steps { grid-template-columns:1fr; margin-top:25px; border-left:1px solid var(--line); border-top:0; } .step { padding:0 0 24px 27px; } .step::before { top:5px; left:-5px; } .step:not(:last-child)::after { top:8px; left:-1px; width:1px; height:calc(100% - 9px); } .step-num { margin:0 0 10px; } .process-panel .panel-photo { min-height:260px; } .process-panel .panel-copy { padding:40px 25px; } .final-cta { margin-top:65px; padding:48px 25px; } } @media (max-width:470px) { .capabilities { grid-template-columns:1fr; } }
    </style>

    <main>
        <section class="hero">
            <div class="hero-media"></div>
            <div class="container reveal">
                <span class="hero-label"><i></i> Pasokan Ekspor Komoditas Berbasis Indonesia</span>
                <h1>Minyak Sawit Indonesia.<br>Terhubung ke Pasar Global.</h1>
                <p class="hero-copy">Dapatkan produk kelapa sawit berkualitas dari Indonesia melalui proses ekspor yang transparan, terpercaya, dan berorientasi bisnis internasional.</p>
                <div class="hero-actions">
                    <a class="button button-brass" href="{{ route('products.index') }}">Jelajahi Produk <span aria-hidden="true">↓</span></a>
                    <a class="button button-ghost" href="{{ route('export-process') }}">Pelajari Alur Ekspor</a>
                </div>
            </div>
        </section>

        <section class="container overview reveal">
            <div>
                <p class="eyebrow">Dibangun untuk Perdagangan B2B</p>
                <h2 class="section-title">Alur terstruktur dari permintaan penawaran hingga pengiriman.</h2>
                <p class="overview-copy">PalmTrade mengintegrasikan informasi spesifikasi produk, pengajuan RFQ, negosiasi harga bertingkat, konfirmasi pesanan, dan pemantauan pelayaran kapal dalam satu platform terpadu.</p>
            </div>
            <div class="capabilities">
                <article class="capability">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 19.5V5.8a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v13.7"/><path d="M8 9h8M8 13h5M3 19.5h18"/></svg>
                    <h3>Transparansi Spesifikasi Teknis</h3>
                    <p>Parameter produk yang jelas menjadi acuan awal dalam pengajuan enquiry komersial.</p>
                </article>
                <article class="capability">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 19V5h16v14H4Z"/><path d="m8 9 2 2 4-4M8 15h8"/></svg>
                    <h3>Penawaran Harga B2B Sesuai Kebutuhan</h3>
                    <p>Kalkulasi harga resmi dirumuskan berdasarkan volume pesanan dan pelabuhan tujuan.</p>
                </article>
                <article class="capability">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M5 4h14v16H5z"/><path d="M8 8h8M8 12h8M8 16h5"/></svg>
                    <h3>Manajemen Pesanan Terstruktur</h3>
                    <p>Kesepakatan harga yang disetujui langsung diterbitkan menjadi kontrak pesanan resmi.</p>
                </article>
                <article class="capability">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 12h16M12 4v16"/><circle cx="12" cy="12" r="8"/></svg>
                    <h3>Visibilitas Pengiriman & Logistik</h3>
                    <p>Status persiapan kargo, bea cukai, dan pelayaran kapal dapat dipantau langsung.</p>
                </article>
            </div>
        </section>

        <section class="section product-section">
            <div class="container reveal">
                <div class="section-top">
                    <div>
                        <p class="eyebrow">Katalog Komoditas</p>
                        <h2 class="section-title">Produk kelapa sawit untuk rantai pasok industri global.</h2>
                    </div>
                    <a class="text-link" href="{{ route('products.index') }}">Lihat Katalog Lengkap →</a>
                </div>
                <div class="product-grid">
                    @forelse($featuredProducts as $product)
                        <article class="product-card">
                            <a class="product-image" href="{{ route('products.show', $product) }}">
                                <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset($product->image)) : 'https://images.unsplash.com/photo-1600932713394-7e335f8b9061?auto=format&fit=crop&w=900&q=85' }}" alt="{{ $product->name }}">
                            </a>
                            <div class="product-body">
                                <p class="product-category">{{ $product->category }}</p>
                                <h3 class="product-name">{{ $product->name }}</h3>
                                <p class="product-desc">{{ \Illuminate\Support\Str::limit($product->description, 105) }}</p>
                                <div class="product-bottom">
                                    <span class="product-meta">Minimum Order (MOQ)<strong>{{ rtrim(rtrim(number_format((float) $product->moq, 2), '0'), '.') }} {{ $product->unit }}</strong></span>
                                    <a class="text-link" href="{{ route('products.show', $product) }}">Lihat Detail →</a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="product-card" style="grid-column:1/-1;padding:42px">
                            <h3 class="product-name">Katalog demo sedang disiapkan.</h3>
                            <p class="product-desc">Tambahkan produk aktif melalui dashboard admin untuk menampilkannya di sini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container why-grid reveal">
                <div class="why-photo" role="img" aria-label="Perkebunan kelapa sawit di Indonesia"></div>
                <div>
                    <p class="eyebrow">Keunggulan PalmTrade</p>
                    <h2 class="section-title">Solusi pengadaan komoditas yang transparan dan terukur.</h2>
                    <div class="why-list">
                        <article class="why-item">
                            <span class="why-number">01</span>
                            <div>
                                <h3>Informasi Produk yang Terstruktur</h3>
                                <p>Akses spesifikasi mutu, parameter lab, satuan pengemasan, dan acuan harga indikatif dalam satu tempat.</p>
                            </div>
                        </article>
                        <article class="why-item">
                            <span class="why-number">02</span>
                            <div>
                                <h3>Harga Berdasarkan Penawaran Resmi (RFQ)</h3>
                                <p>Ketentuan komersial diformulasikan sesuai dengan volume pemesanan, pelabuhan muat, dan tujuan ekspor Anda.</p>
                            </div>
                        </article>
                        <article class="why-item">
                            <span class="why-number">03</span>
                            <div>
                                <h3>Riwayat Negosiasi yang Terdokumentasi</h3>
                                <p>Setiap counter-offer tersimpan dalam versi resmi sehingga pembeli dan eksportir memiliki catatan yang sinkron.</p>
                            </div>
                        </article>
                        <article class="why-item">
                            <span class="why-number">04</span>
                            <div>
                                <h3>Pemantauan Status Pesanan & Logistik</h3>
                                <p>Proses verifikasi pembayaran, persiapan dokumen ekspor, dan status kapal kontainer/curah terpantau berkala.</p>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section class="section product-section">
            <div class="container reveal">
                <p class="eyebrow">Alur Kerja Perdagangan</p>
                <h2 class="section-title">Dari pengajuan kebutuhan hingga kargo tiba di tujuan.</h2>
                <div class="steps">
                    <article class="step">
                        <p class="step-num">01</p>
                        <h3>Jelajahi Produk</h3>
                        <p>Pilih produk kelapa sawit sesuai standar industri Anda.</p>
                    </article>
                    <article class="step">
                        <p class="step-num">02</p>
                        <h3>Ajukan RFQ</h3>
                        <p>Kirimkan volume target, pelabuhan tujuan, dan spesifikasi khusus.</p>
                    </article>
                    <article class="step">
                        <p class="step-num">03</p>
                        <h3>Evaluasi & Negosiasi</h3>
                        <p>Eksportir menerbitkan penawaran komersial (harga, incoterms, L/C).</p>
                    </article>
                    <article class="step">
                        <p class="step-num">04</p>
                        <h3>Konfirmasi Kontrak</h3>
                        <p>Setujui penawaran untuk menerbitkan pesanan resmi (Order).</p>
                    </article>
                    <article class="step">
                        <p class="step-num">05</p>
                        <h3>Lacak Pemenuhan</h3>
                        <p>Pantau pembayaran, inspeksi surveyor, dan pelayaran kapal.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="container reveal">
            <div class="process-panel">
                <div class="panel-photo" role="img" aria-label="Logistik pelabuhan dan peti kemas ekspor"></div>
                <div class="panel-copy">
                    <p class="eyebrow" style="color:#d9bd8b">Manajemen Ekspor</p>
                    <h2>Diproses, didokumentasikan, dan diperbarui secara berkala.</h2>
                    <p>Setelah syarat dagang disepakati, tim eksportir mengelola verifikasi transfer bank, persiapan tangki/flexibag, dokumen BL/COO, inspeksi surveyor, hingga keberangkatan kapal.</p>
                    <div><a class="button button-ghost" href="{{ route('export-process') }}">Lihat Prosedur Ekspor</a></div>
                </div>
            </div>
        </section>

        <section class="container final-cta reveal">
            <p class="eyebrow" style="color:#fff">Mulai Kerjasama</p>
            <h2>Mencari Mitra Pasokan Minyak Sawit Terpercaya?</h2>
            <p>Sampaikan kebutuhan komoditas, estimasi volume, dan pelabuhan tujuan Anda. Tim kami akan segera meninjau dan menyiapkan penawaran resmi.</p>
            <div class="hero-actions">
                <a class="button" href="{{ route('products.index') }}">Minta Penawaran (RFQ)</a>
                <a class="button button-ghost" href="{{ route('contact') }}">Hubungi Tim Kami</a>
            </div>
        </section>
    </main>
@endsection
