@extends('layouts.public')

@section('content')
    <style>
        .catalog { padding:55px 0 95px; } .catalog-top { display:flex; align-items:end; justify-content:space-between; gap:24px; margin-bottom:34px; } .catalog-title { margin:11px 0 0; color:var(--forest); font-family:var(--serif); font-size:55px; font-weight:400; letter-spacing:-.055em; } .catalog-copy { max-width:390px; margin:0; color:var(--muted); font-size:13px; line-height:1.75; } .filters { display:grid; grid-template-columns:1.6fr 1fr 1fr 1fr auto; gap:9px; padding:15px; margin-bottom:31px; border:1px solid var(--line); background:white; } .filters input,.filters select { min-width:0; height:44px; padding:0 12px; border:1px solid var(--line); border-radius:3px; outline:0; color:var(--ink); background:white; font-size:12px; } .filters input:focus,.filters select:focus { border-color:var(--green); box-shadow:0 0 0 3px rgb(29 91 67 / .09); } .filters button { height:44px; border:0; border-radius:3px; color:white; background:var(--green); font-size:12px; font-weight:800; cursor:pointer; } .catalog-result { display:flex; align-items:center; justify-content:space-between; margin-bottom:18px; color:var(--muted); font-size:12px; } .clear { color:var(--green); font-weight:800; } .pagination { display:flex; flex-wrap:wrap; gap:6px; justify-content:center; margin-top:42px; } .pagination a,.pagination span { display:grid; min-width:38px; height:38px; place-items:center; border:1px solid var(--line); background:white; color:var(--forest); font-size:12px; } .pagination .active { color:white; background:var(--green); border-color:var(--green); } .empty { padding:62px 25px; border:1px dashed #c8d1c8; color:var(--muted); text-align:center; background:white; } .empty h2 { margin:0 0 10px; color:var(--forest); font-family:var(--serif); font-size:30px; font-weight:400; }
        @media(max-width:900px) { .filters { grid-template-columns:1fr 1fr; } .filters input { grid-column:1/-1; } } @media(max-width:540px) { .catalog { padding-top:38px; } .catalog-top { align-items:start; flex-direction:column; } .catalog-title { font-size:46px; } .filters { grid-template-columns:1fr; } .filters input { grid-column:auto; } }
    </style>
    <main class="container catalog">
        <div class="catalog-top reveal">
            <div>
                <p class="eyebrow">Katalog Komoditas Ekspor</p>
                <h1 class="catalog-title">Produk Minyak Sawit</h1>
            </div>
            <p class="catalog-copy">Informasi produk disajikan untuk enquiry perdagangan B2B. Harga indikatif merupakan perkiraan pasar, bukan harga kontrak final.</p>
        </div>
        <form class="filters reveal" method="GET" action="{{ route('products.index') }}">
            <input type="search" name="q" value="{{ $filters['search'] }}" placeholder="Cari nama atau kode SKU..." aria-label="Cari nama atau SKU produk">
            <select name="category" aria-label="Kategori produk">
                <option value="">Semua kategori</option>
                @foreach($categories as $item)
                    <option value="{{ $item }}" @selected($filters['category'] === $item)>{{ $item }}</option>
                @endforeach
            </select>
            <select name="availability" aria-label="Ketersediaan">
                <option value="">Semua ketersediaan</option>
                <option value="available" @selected($filters['availability'] === 'available')>Kapasitas tersedia</option>
                <option value="on-request" @selected($filters['availability'] === 'on-request')>Sesuai permintaan (On-request)</option>
            </select>
            <select name="sort" aria-label="Urutkan produk">
                <option value="newest" @selected($filters['sort'] === 'newest')>Terbaru</option>
                <option value="name" @selected($filters['sort'] === 'name')>Nama A–Z</option>
                <option value="price_asc" @selected($filters['sort'] === 'price_asc')>Harga indikatif: Terendah</option>
                <option value="price_desc" @selected($filters['sort'] === 'price_desc')>Harga indikatif: Tertinggi</option>
            </select>
            <button type="submit">Terapkan Filter</button>
        </form>
        <div class="catalog-result">
            <span>Ditemukan {{ $products->total() }} produk</span>
            @if(collect($filters)->filter(fn($value, $key) => $key !== 'sort' && filled($value))->isNotEmpty())
                <a class="clear" href="{{ route('products.index') }}">Hapus semua filter</a>
            @endif
        </div>
        @if($products->isNotEmpty())
            <div class="product-grid">
                @foreach($products as $product)
                    <article class="product-card reveal">
                        <a class="product-image" href="{{ route('products.show',$product) }}">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                        </a>
                        <div class="product-body">
                            <p class="product-category">{{ $product->category }}</p>
                            <h2 class="product-name">{{ $product->name }}</h2>
                            <p class="product-desc">{{ \Illuminate\Support\Str::limit($product->description, 105) }}</p>
                            <div class="product-bottom">
                                <span class="product-meta">Minimum Order (MOQ)<strong>{{ rtrim(rtrim(number_format((float) $product->moq, 2), '0'), '.') }} {{ $product->unit }}</strong></span>
                                <a class="text-link" href="{{ route('products.show',$product) }}">Lihat Detail →</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="empty">
                <h2>Produk tidak ditemukan</h2>
                <p>Silakan coba kata kunci lain atau bersihkan filter pencarian Anda.</p>
                <p style="margin:20px 0 0"><a class="button button-primary" href="{{ route('products.index') }}">Lihat semua produk</a></p>
            </div>
        @endif
        @if($products->hasPages())
            <nav class="pagination" aria-label="Halaman katalog">
                @if($products->onFirstPage())
                    <span>‹</span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" aria-label="Halaman sebelumnya">‹</a>
                @endif
                @foreach($products->getUrlRange(max(1,$products->currentPage()-2),min($products->lastPage(),$products->currentPage()+2)) as $page=>$url)
                    <a @class(['active'=>$page===$products->currentPage()]) href="{{ $url }}">{{ $page }}</a>
                @endforeach
                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" aria-label="Halaman selanjutnya">›</a>
                @else
                    <span>›</span>
                @endif
            </nav>
        @endif
    </main>
@endsection
