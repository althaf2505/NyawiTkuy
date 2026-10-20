@extends('layouts.public')

@section('content')
    <style>
        .crumb { display:flex; gap:9px; padding:24px 0; color:var(--muted); font-size:11px; } .crumb a { color:var(--green); } .product-detail { display:grid; grid-template-columns:1.1fr .9fr; gap:65px; padding:15px 0 95px; } .detail-image { min-height:550px; background:#dde8dc; } .detail-image img { width:100%; height:100%; object-fit:cover; } .detail-kicker { margin:4px 0 13px; color:var(--brass); font-size:10px; font-weight:800; letter-spacing:.14em; text-transform:uppercase; } .detail-title { margin:0; color:var(--forest); font-family:var(--serif); font-size:55px; font-weight:400; letter-spacing:-.055em; line-height:.98; } .detail-sku { margin:14px 0 25px; color:var(--muted); font-size:12px; } .detail-description { color:var(--muted); font-size:14px; line-height:1.8; } .info-grid { display:grid; grid-template-columns:1fr 1fr; margin:29px 0; border-top:1px solid var(--line); border-left:1px solid var(--line); } .info { padding:17px; border-right:1px solid var(--line); border-bottom:1px solid var(--line); background:white; } .info small { display:block; margin-bottom:5px; color:var(--muted); font-size:10px; text-transform:uppercase; letter-spacing:.07em; } .info strong { color:var(--forest); font-size:13px; } .specs { margin:26px 0 30px; padding-top:25px; border-top:1px solid var(--line); } .specs h2 { margin:0 0 14px; color:var(--forest); font-family:var(--serif); font-size:28px; font-weight:400; } .specs dl { display:grid; grid-template-columns:1fr 1fr; margin:0; } .specs div { display:contents; } .specs dt,.specs dd { margin:0; padding:11px 0; border-bottom:1px solid var(--line); font-size:12px; } .specs dt { color:var(--muted); } .specs dd { color:var(--forest); font-weight:700; text-align:right; } .inquiry { padding:25px; background:var(--forest); color:white; } .inquiry h2 { margin:0 0 8px; font-family:var(--serif); font-size:29px; font-weight:400; } .inquiry p { margin:0 0 19px; color:rgb(255 255 255 / .72); font-size:12px; line-height:1.7; } .related { padding:75px 0 0; border-top:1px solid var(--line); }
        @media(max-width:780px) { .product-detail { grid-template-columns:1fr; gap:32px; padding-bottom:65px; } .detail-image { min-height:330px; } .detail-title { font-size:44px; } }
    </style>
    <main class="container">
        <nav class="crumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Beranda</a><span>/</span>
            <a href="{{ route('products.index') }}">Produk</a><span>/</span>
            <span>{{ $product->name }}</span>
        </nav>
        <section class="product-detail">
            <div class="detail-image">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            </div>
            <div>
                <p class="detail-kicker">{{ $product->category }}</p>
                <h1 class="detail-title">{{ $product->name }}</h1>
                <p class="detail-sku">Kode Produk (SKU): {{ $product->sku }}</p>
                <p class="detail-description">{{ $product->description }}</p>
                <div class="info-grid">
                    <div class="info">
                        <small>Minimum Order (MOQ)</small>
                        <strong>{{ rtrim(rtrim(number_format((float) $product->moq, 2), '0'), '.') }} {{ $product->unit }}</strong>
                    </div>
                    <div class="info">
                        <small>Kapasitas Pasokan</small>
                        <strong>{{ $product->stock_capacity ? rtrim(rtrim(number_format((float) $product->stock_capacity, 2), '0'), '.') . ' ' . $product->unit : 'Sesuai Permintaan' }}</strong>
                    </div>
                    <div class="info">
                        <small>Harga Indikatif</small>
                        <strong>{{ $product->indicative_price ? $product->formatted_price . ' / ' . $product->unit : 'Berdasarkan Penawaran' }}</strong>
                    </div>
                    <div class="info">
                        <small>Asal Komoditas</small>
                        <strong>{{ $product->origin }}</strong>
                    </div>
                </div>
                <section class="specs">
                    <h2>Spesifikasi Teknis & Mutu</h2>
                    @if($product->specifications)
                        <dl>
                            @foreach($product->specifications as $label=>$value)
                                <div>
                                    <dt>{{ \Illuminate\Support\Str::headline($label) }}</dt>
                                    <dd>{{ is_array($value) ? implode(', ', $value) : $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @else
                        <p class="detail-description">Rincian parameter uji laboratorium lengkap dikonfirmasikan pada dokumen penawaran resmi.</p>
                    @endif
                </section>
                <section class="inquiry">
                    <h2>Ajukan Permintaan Harga (RFQ)</h2>
                    <p>Harga resmi dan syarat pembayaran (FOB/CIF, L/C, T/T) diformulasikan berdasarkan kuantitas, waktu pengiriman, dan pelabuhan tujuan Anda.</p>
                    @auth
                        @if(auth()->user()->isBuyer())
                            <a class="button button-brass" href="{{ route('buyer.quotations.create', ['product_id' => $product->id]) }}">Buat Pengajuan RFQ Sekarang <span aria-hidden="true">↗</span></a>
                        @else
                            <a class="button button-brass" href="{{ route('admin.dashboard') }}">Kelola Produk di Dashboard <span aria-hidden="true">↗</span></a>
                        @endif
                    @else
                        <a class="button button-brass" href="{{ route('login') }}">Masuk untuk Mengajukan RFQ <span aria-hidden="true">↗</span></a>
                    @endauth
                </section>
            </div>
        </section>
        @if($relatedProducts->isNotEmpty())
            <section class="related">
                <div class="section-top">
                    <div>
                        <p class="eyebrow">Produk Terkait</p>
                        <h2 class="section-title">Eksplorasi Komoditas Lain</h2>
                    </div>
                    <a class="text-link" href="{{ route('products.index') }}">Semua Produk →</a>
                </div>
                <div class="product-grid">
                    @foreach($relatedProducts as $related)
                        <article class="product-card">
                            <a class="product-image" href="{{ route('products.show',$related) }}">
                                <img src="{{ $related->image_url }}" alt="{{ $related->name }}">
                            </a>
                            <div class="product-body">
                                <p class="product-category">{{ $related->category }}</p>
                                <h3 class="product-name">{{ $related->name }}</h3>
                                <p class="product-desc">{{ \Illuminate\Support\Str::limit($related->description,90) }}</p>
                                <a class="text-link" href="{{ route('products.show',$related) }}">Lihat Detail →</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </main>
@endsection
