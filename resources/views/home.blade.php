<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f7f4">
    <title>UTP Export Portal</title>
    <style>
        :root {
            color-scheme: light;
            --ink: #17251f;
            --muted: #66756d;
            --green: #176b4a;
            --green-dark: #104d36;
            --lime: #d9f278;
            --line: #e1e8e2;
            --paper: #ffffff;
            --canvas: #f5f7f4;
        }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--canvas); color: var(--ink); font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        a { color: inherit; text-decoration: none; }
        .shell { width: min(1160px, calc(100% - 40px)); margin: 0 auto; }
        .topbar { height: 76px; display: flex; align-items: center; justify-content: space-between; gap: 24px; }
        .brand { display: flex; align-items: center; gap: 12px; font-size: 15px; font-weight: 800; letter-spacing: -.03em; }
        .brand-mark { width: 38px; height: 38px; display: grid; place-items: center; border-radius: 12px; color: #fff; background: var(--green); font-size: 14px; letter-spacing: -.06em; }
        .nav { display: flex; align-items: center; gap: 28px; color: var(--muted); font-size: 13px; font-weight: 650; }
        .nav a:hover { color: var(--green); }
        .nav .nav-cta { padding: 11px 16px; border-radius: 9px; color: white; background: var(--green); }
        .hero { position: relative; overflow: hidden; margin-top: 16px; padding: 72px 64px; border-radius: 24px; color: white; background: var(--green-dark); }
        .hero:after { position: absolute; top: -150px; right: -88px; width: 520px; height: 520px; border: 1px solid rgb(255 255 255 / 13%); border-radius: 50%; box-shadow: 0 0 0 40px rgb(255 255 255 / 4%), 0 0 0 90px rgb(255 255 255 / 3%); content: ""; }
        .hero-content { position: relative; z-index: 1; max-width: 630px; }
        .eyebrow { display: inline-flex; align-items: center; gap: 8px; margin: 0 0 22px; color: var(--lime); font-size: 11px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
        .eyebrow:before { width: 7px; height: 7px; border-radius: 50%; background: var(--lime); content: ""; }
        h1 { max-width: 620px; margin: 0; font-size: clamp(38px, 5vw, 62px); line-height: 1.04; letter-spacing: -.055em; }
        .hero-copy { max-width: 510px; margin: 22px 0 30px; color: rgb(255 255 255 / 76%); font-size: 15px; line-height: 1.8; }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 12px; }
        .button { display: inline-flex; min-height: 46px; align-items: center; justify-content: center; padding: 0 18px; border: 1px solid transparent; border-radius: 9px; font-size: 13px; font-weight: 750; }
        .button-lime { color: var(--ink); background: var(--lime); }
        .button-outline { border-color: rgb(255 255 255 / 30%); color: white; }
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; overflow: hidden; margin: 22px 0 58px; border: 1px solid var(--line); border-radius: 16px; background: var(--line); }
        .stat { min-height: 102px; padding: 23px 26px; background: var(--paper); }
        .stat strong { display: block; margin-bottom: 6px; font-size: 24px; letter-spacing: -.04em; }
        .stat span { color: var(--muted); font-size: 12px; }
        .section-head { display: flex; align-items: end; justify-content: space-between; gap: 20px; margin-bottom: 20px; }
        .section-kicker { margin: 0 0 8px; color: var(--green); font-size: 10px; font-weight: 850; letter-spacing: .14em; text-transform: uppercase; }
        h2 { margin: 0; font-size: 29px; letter-spacing: -.045em; }
        .section-description { margin: 8px 0 0; color: var(--muted); font-size: 13px; }
        .search-form { display: grid; grid-template-columns: minmax(180px, 1fr) 180px auto; gap: 9px; width: min(510px, 100%); }
        .search-form input, .search-form select { min-height: 43px; width: 100%; padding: 0 12px; border: 1px solid var(--line); border-radius: 8px; outline: none; color: var(--ink); background: white; font: inherit; font-size: 12px; }
        .search-form input:focus, .search-form select:focus { border-color: var(--green); box-shadow: 0 0 0 3px rgb(23 107 74 / 10%); }
        .search-form button { min-height: 43px; padding: 0 16px; border: 0; border-radius: 8px; color: white; background: var(--green); font: inherit; font-size: 12px; font-weight: 750; cursor: pointer; }
        .product-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
        .product-card { overflow: hidden; border: 1px solid var(--line); border-radius: 14px; background: white; transition: transform .18s ease, box-shadow .18s ease; }
        .product-card:hover { transform: translateY(-3px); box-shadow: 0 14px 30px rgb(20 48 33 / 8%); }
        .product-image { display: grid; height: 170px; place-items: center; overflow: hidden; background: #edf3eb; color: #93a79a; }
        .product-image img { width: 100%; height: 100%; object-fit: cover; }
        .product-image svg { width: 48px; height: 48px; }
        .product-body { padding: 17px; }
        .product-category { margin: 0 0 8px; color: var(--green); font-size: 10px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
        .product-name { margin: 0; font-size: 15px; line-height: 1.4; letter-spacing: -.02em; }
        .product-sku { margin: 7px 0 15px; color: var(--muted); font-size: 11px; }
        .product-meta { display: flex; align-items: end; justify-content: space-between; gap: 8px; padding-top: 13px; border-top: 1px solid var(--line); }
        .product-meta small { display: block; margin-bottom: 4px; color: var(--muted); font-size: 10px; }
        .product-meta strong { font-size: 12px; }
        .empty-state { padding: 58px 22px; border: 1px dashed #cbd8ce; border-radius: 14px; text-align: center; background: rgb(255 255 255 / 65%); }
        .empty-icon { display: grid; width: 48px; height: 48px; place-items: center; margin: 0 auto 15px; border-radius: 15px; color: var(--green); background: #e9f3e8; }
        .empty-state h3 { margin: 0 0 8px; font-size: 17px; }
        .empty-state p { max-width: 430px; margin: 0 auto; color: var(--muted); font-size: 13px; line-height: 1.7; }
        footer { display: flex; justify-content: space-between; gap: 20px; margin-top: 62px; padding: 24px 0 32px; border-top: 1px solid var(--line); color: var(--muted); font-size: 11px; }
        @media (max-width: 900px) { .product-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } .section-head { align-items: stretch; flex-direction: column; } .search-form { width: 100%; } }
        @media (max-width: 620px) { .shell { width: min(100% - 28px, 1160px); } .topbar { height: 66px; } .nav { gap: 12px; font-size: 11px; } .nav a:not(.nav-cta) { display: none; } .nav .nav-cta { padding: 10px 12px; } .hero { margin-top: 10px; padding: 45px 25px; border-radius: 18px; } .hero:after { right: -340px; } .hero-copy { font-size: 14px; } .stats { margin-bottom: 43px; } .stat { min-height: 85px; padding: 17px 12px; } .stat strong { font-size: 19px; } .stat span { font-size: 10px; } h2 { font-size: 25px; } .search-form { grid-template-columns: 1fr 1fr; } .search-form input { grid-column: 1 / -1; } .search-form button { grid-column: 1 / -1; } .product-grid { gap: 10px; } .product-image { height: 125px; } .product-body { padding: 13px; } .product-name { font-size: 13px; } .product-meta { align-items: start; flex-direction: column; } footer { flex-direction: column; gap: 7px; } }
    </style>
</head>
<body>
    <header class="shell topbar">
        <a class="brand" href="{{ route('home') }}" aria-label="UTP Export Portal">
            <span class="brand-mark">UTP</span>
            <span>UTP <span style="font-weight: 500; color: var(--muted)">Export Portal</span></span>
        </a>
        <nav class="nav" aria-label="Navigasi utama">
            <a href="#catalog">Katalog</a>
            <a href="#about">Tentang</a>
            <a class="nav-cta" href="#catalog">Jelajahi produk <span aria-hidden="true">↗</span></a>
        </nav>
    </header>

    <main class="shell">
        <section class="hero" id="about">
            <div class="hero-content">
                <p class="eyebrow">Mitra dagang Indonesia</p>
                <h1>Produk berkualitas.<br>Jangkauan global.</h1>
                <p class="hero-copy">Temukan produk ekspor Indonesia dan jelajahi peluang pengadaan untuk kebutuhan bisnis Anda.</p>
                <div class="hero-actions">
                    <a class="button button-lime" href="#catalog">Lihat katalog <span aria-hidden="true" style="margin-left: 8px">↓</span></a>
                    <a class="button button-outline" href="#about">Pelajari lebih lanjut</a>
                </div>
            </div>
        </section>

        <section class="stats" aria-label="Ringkasan katalog">
            <div class="stat"><strong>{{ number_format($productCount) }}</strong><span>Produk aktif</span></div>
            <div class="stat"><strong>{{ number_format($categories->count()) }}</strong><span>Kategori produk</span></div>
            <div class="stat"><strong>Indonesia</strong><span>Sumber produk</span></div>
        </section>

        <section id="catalog">
            <div class="section-head">
                <div>
                    <p class="section-kicker">Katalog</p>
                    <h2>Produk pilihan</h2>
                    <p class="section-description">Cari berdasarkan nama produk, SKU, atau kategori.</p>
                </div>
                <form class="search-form" method="GET" action="{{ route('home') }}">
                    <input type="search" name="q" value="{{ $search }}" placeholder="Cari produk atau SKU..." aria-label="Cari produk atau SKU">
                    <select name="category" aria-label="Pilih kategori">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected($selectedCategory === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                    <button type="submit">Cari</button>
                </form>
            </div>

            @if ($products->isNotEmpty())
                <div class="product-grid">
                    @foreach ($products as $product)
                        <article class="product-card">
                            <div class="product-image">
                                @if ($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                                @else
                                    <svg viewBox="0 0 48 48" fill="none" aria-hidden="true"><path d="M8 36.5 18.5 25l7 7L34 21l7 9.5V39H8v-2.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><circle cx="17" cy="15" r="4" stroke="currentColor" stroke-width="2"/><path d="M8 9h32v30H8z" stroke="currentColor" stroke-width="2"/></svg>
                                @endif
                            </div>
                            <div class="product-body">
                                <p class="product-category">{{ $product->category }}</p>
                                <h3 class="product-name">{{ $product->name }}</h3>
                                <p class="product-sku">SKU: {{ $product->sku }}</p>
                                <div class="product-meta">
                                    <div><small>Minimum pemesanan</small><strong>{{ rtrim(rtrim(number_format((float) $product->moq, 2), '0'), '.') }} {{ $product->unit }}</strong></div>
                                    @if ($product->indicative_price)
                                        <div><small>Harga indikatif</small><strong>{{ $product->formatted_price }}</strong></div>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <span class="empty-icon" aria-hidden="true">
                        <svg width="23" height="23" viewBox="0 0 24 24" fill="none"><path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="m4.5 7.8 7.5 4.4 7.5-4.4M12 12.2V21" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                    </span>
                    @if ($search !== '' || $selectedCategory !== '')
                        <h3>Produk tidak ditemukan</h3>
                        <p>Coba kata kunci atau kategori lain untuk mencari produk.</p>
                    @else
                        <h3>Katalog sedang disiapkan</h3>
                        <p>Belum ada produk aktif di database. Tambahkan produk melalui database agar katalog tampil di halaman ini.</p>
                    @endif
                </div>
            @endif
        </section>
    </main>

    <footer class="shell">
        <span>© {{ now()->year }} UTP Export Portal</span>
        <span>Platform pengadaan dan perdagangan</span>
    </footer>
</body>
</html>
