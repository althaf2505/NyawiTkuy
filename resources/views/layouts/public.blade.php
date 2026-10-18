<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#123C2E">
    <title>{{ $title ?? 'PalmTrade | Ekspor Minyak Sawit Indonesia' }}</title>
    <style>
        :root { --forest:#123C2E; --green:#1D5B43; --sage:#A8B8A0; --ivory:#F7F5EF; --white:#fff; --ink:#202923; --muted:#68736B; --line:#E4E7DF; --brass:#B89455; --serif: Georgia, 'Times New Roman', serif; --sans: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        * { box-sizing:border-box; } html { scroll-behavior:smooth; } body { margin:0; color:var(--ink); background:var(--ivory); font-family:var(--sans); line-height:1.5; } a { color:inherit; text-decoration:none; } button,input,select,textarea { font:inherit; } .container { width:min(1180px, calc(100% - 48px)); margin:auto; } .sr-only { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; } .eyebrow { display:flex; gap:9px; align-items:center; color:var(--brass); font-size:11px; font-weight:800; letter-spacing:.15em; text-transform:uppercase; } .eyebrow::before { content:''; width:26px; height:1px; background:currentColor; } .button { min-height:46px; display:inline-flex; gap:8px; align-items:center; justify-content:center; padding:0 19px; border:1px solid transparent; border-radius:5px; font-size:13px; font-weight:750; transition:transform .18s ease, background .18s ease, border-color .18s ease; cursor:pointer; } .button:hover { transform:translateY(-2px); } .button-primary { color:white; background:var(--green); } .button-primary:hover { background:var(--forest); } .button-brass { color:white; background:var(--brass); } .button-ghost { border-color:rgb(255 255 255 / .4); color:white; } .button-quiet { border-color:var(--line); color:var(--forest); background:white; }
        .site-header { position:sticky; top:0; z-index:20; border-bottom:1px solid transparent; transition:background .25s ease, box-shadow .25s ease, border-color .25s ease; } .site-header.is-scrolled { border-color:var(--line); background:rgb(247 245 239 / .96); box-shadow:0 3px 18px rgb(20 45 34 / .04); backdrop-filter:blur(12px); } .nav-inner { min-height:76px; display:flex; align-items:center; justify-content:space-between; gap:28px; } .brand { display:inline-flex; gap:10px; align-items:center; color:var(--forest); font-size:17px; font-weight:800; letter-spacing:-.04em; } .brand-mark { position:relative; display:grid; width:33px; height:39px; place-items:center; overflow:hidden; color:white; background:var(--forest); clip-path:polygon(50% 0,100% 22%,100% 78%,50% 100%,0 78%,0 22%); font-size:11px; letter-spacing:-.08em; } .brand span:last-child { color:var(--brass); font-weight:500; } .nav-links { display:flex; align-items:center; gap:25px; color:#4d5d54; font-size:12px; font-weight:700; } .nav-links a:not(.button) { transition:color .18s ease; } .nav-links a:not(.button):hover,.nav-links a[aria-current=page] { color:var(--green); } .menu-toggle { display:none; width:42px; height:42px; border:1px solid var(--line); border-radius:5px; background:white; color:var(--forest); }
        .page-hero { padding:72px 0 58px; background:var(--forest); color:white; } .page-hero h1 { max-width:720px; margin:14px 0 13px; font-family:var(--serif); font-size:clamp(42px,6vw,72px); font-weight:400; letter-spacing:-.055em; line-height:.98; } .page-hero p { max-width:620px; margin:0; color:rgb(255 255 255 / .74); font-size:16px; line-height:1.75; }
        .section { padding:96px 0; } .section-top { display:flex; justify-content:space-between; align-items:end; gap:35px; margin-bottom:35px; } .section-title { max-width:700px; margin:12px 0 0; color:var(--forest); font-family:var(--serif); font-size:clamp(32px,4.2vw,52px); font-weight:400; letter-spacing:-.045em; line-height:1.04; } .section-copy { max-width:480px; margin:13px 0 0; color:var(--muted); font-size:14px; line-height:1.8; }
        .product-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:19px; } .product-card { overflow:hidden; background:white; border:1px solid var(--line); transition:transform .25s ease,box-shadow .25s ease; } .product-card:hover { transform:translateY(-5px); box-shadow:0 17px 35px rgb(18 60 46 / .1); } .product-image { position:relative; display:block; height:275px; overflow:hidden; background:#dfe7dc; } .product-image img { width:100%; height:100%; object-fit:cover; transition:transform .45s ease; } .product-card:hover .product-image img { transform:scale(1.04); } .product-image::after { content:''; position:absolute; inset:0; background:linear-gradient(180deg,transparent 55%,rgb(15 48 36 / .2)); } .product-body { padding:23px; } .product-category { margin:0 0 8px; color:var(--brass); font-size:10px; font-weight:800; letter-spacing:.13em; text-transform:uppercase; } .product-name { margin:0; color:var(--forest); font-family:var(--serif); font-size:25px; font-weight:400; letter-spacing:-.035em; } .product-desc { min-height:43px; margin:10px 0 20px; color:var(--muted); font-size:13px; line-height:1.65; } .product-bottom { display:flex; align-items:center; justify-content:space-between; gap:15px; padding-top:16px; border-top:1px solid var(--line); } .product-meta { color:var(--muted); font-size:11px; } .product-meta strong { display:block; margin-top:2px; color:var(--ink); font-size:12px; } .text-link { color:var(--green); font-size:12px; font-weight:800; }
        .site-footer { margin-top:80px; color:#dae5dd; background:var(--forest); } .footer-grid { display:grid; grid-template-columns:1.6fr repeat(3,1fr); gap:42px; padding:65px 0 52px; } .footer-brand { color:white; } .footer-copy { max-width:270px; margin:17px 0 0; color:rgb(255 255 255 / .62); font-size:12px; line-height:1.75; } .footer-heading { margin:4px 0 16px; color:var(--brass); font-size:10px; letter-spacing:.14em; text-transform:uppercase; } .footer-list { display:grid; gap:10px; margin:0; padding:0; list-style:none; color:rgb(255 255 255 / .72); font-size:12px; } .footer-list a:hover { color:white; } .footer-bottom { display:flex; justify-content:space-between; gap:20px; padding:18px 0; border-top:1px solid rgb(255 255 255 / .14); color:rgb(255 255 255 / .47); font-size:10px; }
        .reveal { opacity:0; transform:translateY(20px); transition:opacity .6s ease,transform .6s ease; } .reveal.is-visible { opacity:1; transform:none; } @media (prefers-reduced-motion:reduce) { *,*::before,*::after { scroll-behavior:auto!important; transition-duration:.01ms!important; animation-duration:.01ms!important; } .reveal { opacity:1; transform:none; } }
        @media (max-width:780px) { .container { width:min(100% - 32px,1180px); } .nav-inner { min-height:67px; } .menu-toggle { display:grid; place-items:center; } .nav-links { position:absolute; top:67px; left:0; right:0; display:none; padding:16px; border-bottom:1px solid var(--line); background:var(--ivory); align-items:stretch; flex-direction:column; } .nav-links.is-open { display:flex; } .nav-links .button { width:100%; } .page-hero { padding:58px 0 45px; } .section { padding:65px 0; } .section-top { align-items:start; flex-direction:column; gap:18px; } .product-grid { grid-template-columns:1fr; } .product-image { height:235px; } .footer-grid { grid-template-columns:1fr 1fr; gap:30px; } .footer-grid > :first-child { grid-column:1/-1; } } @media (max-width:440px) { .footer-grid { grid-template-columns:1fr; } .footer-grid > :first-child { grid-column:auto; } .footer-bottom { align-items:start; flex-direction:column; } }
    </style>
    @stack('styles')
</head>
<body>
    <header class="site-header" data-header>
        <div class="container nav-inner">
            <a class="brand" href="{{ route('home') }}" aria-label="Beranda PalmTrade"><span class="brand-mark">PT</span> Palm<span>Trade</span></a>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-navigation" data-menu-toggle><span aria-hidden="true">☰</span><span class="sr-only">Buka menu navigasi</span></button>
            <nav class="nav-links" id="main-navigation" aria-label="Navigasi utama" data-menu>
                <a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Beranda</a>
                <a href="{{ route('products.index') }}" @if(request()->routeIs('products.*')) aria-current="page" @endif>Produk</a>
                <a href="{{ route('export-process') }}" @if(request()->routeIs('export-process')) aria-current="page" @endif>Proses Ekspor</a>
                <a href="{{ route('about') }}" @if(request()->routeIs('about')) aria-current="page" @endif>Tentang Kami</a>
                <a href="{{ route('contact') }}" @if(request()->routeIs('contact')) aria-current="page" @endif>Kontak</a>
                @auth
                    @if(auth()->user()->isBuyer())
                        <a class="button button-quiet" href="{{ route('buyer.dashboard') }}">Workspace Buyer</a>
                    @else
                        <a class="button button-quiet" href="{{ route('admin.dashboard') }}">Dashboard Eksportir</a>
                    @endif
                @else
                    <a class="button button-quiet" href="{{ route('login') }}">Masuk</a>
                @endauth
                <a class="button button-primary" href="{{ route('products.index') }}">Minta Penawaran <span aria-hidden="true">↗</span></a>
            </nav>
        </div>
    </header>
    @yield('content')
    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <a class="brand footer-brand" href="{{ route('home') }}"><span class="brand-mark">PT</span> Palm<span>Trade</span></a>
                <p class="footer-copy">Platform B2B terstruktur untuk penemuan produk sawit Indonesia, pengajuan penawaran harga (RFQ), negosiasi, dan visibilitas pengiriman ekspor.</p>
            </div>
            <div>
                <p class="footer-heading">Katalog Produk</p>
                <ul class="footer-list">
                    <li><a href="{{ route('products.index', ['category' => 'Crude Palm Oil']) }}">Crude Palm Oil (CPO)</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'Refined Palm Oil']) }}">Refined Palm Oil</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'Palm Kernel']) }}">Palm Kernel & CPKO</a></li>
                </ul>
            </div>
            <div>
                <p class="footer-heading">Perusahaan</p>
                <ul class="footer-list">
                    <li><a href="{{ route('about') }}">Tentang Kami</a></li>
                    <li><a href="{{ route('export-process') }}">Proses & Regulasi Ekspor</a></li>
                    <li><a href="{{ route('contact') }}">Hubungi Tim Eksportir</a></li>
                </ul>
            </div>
            <div>
                <p class="footer-heading">Hubungi Kami</p>
                <ul class="footer-list">
                    <li>Dukungan Ekspor Perdagangan B2B</li>
                    <li><a href="{{ route('contact') }}">Kirim Pertanyaan Langsung →</a></li>
                </ul>
            </div>
        </div>
        <div class="container footer-bottom">
            <span>© {{ now()->year }} PalmTrade. Platform Perdagangan Ekspor Sawit Indonesia.</span>
            <span>Kebijakan Privasi · Ketentuan Layanan</span>
        </div>
    </footer>
    <script>
        const header = document.querySelector('[data-header]'); const toggle = document.querySelector('[data-menu-toggle]'); const menu = document.querySelector('[data-menu]');
        const setHeader = () => header.classList.toggle('is-scrolled', window.scrollY > 8); setHeader(); window.addEventListener('scroll', setHeader, {passive:true});
        toggle?.addEventListener('click', () => { const open = menu.classList.toggle('is-open'); toggle.setAttribute('aria-expanded', String(open)); });
        const observer = new IntersectionObserver((entries) => entries.forEach((entry) => { if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); } }), {threshold:.12}); document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));
    </script>
    @stack('scripts')
</body>
</html>
