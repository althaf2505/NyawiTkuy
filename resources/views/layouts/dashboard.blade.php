<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Workspace' }} | PalmTrade</title>
    <style>
        :root {
            --forest:#123C2E; --green:#1D5B43; --sage:#A8B8A0; --ivory:#F7F5EF; --white:#fff;
            --ink:#202923; --muted:#68736B; --line:#E4E7DF; --brass:#B89455;
            --serif: Georgia, 'Times New Roman', serif;
            --sans: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: var(--sans); background: var(--ivory); color: var(--ink); line-height: 1.5; font-size: 14px; }
        a { color: inherit; text-decoration: none; }
        .dashboard-container { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
        
        /* Sidebar */
        .sidebar { background: var(--forest); color: white; display: flex; flex-direction: column; border-right: 1px solid rgba(255,255,255,0.08); }
        .sidebar-brand { padding: 24px; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: space-between; }
        .brand-link { display: inline-flex; gap: 8px; align-items: center; font-size: 18px; font-weight: 800; color: white; }
        .brand-link span { color: var(--brass); font-weight: 400; }
        .brand-pill { font-size: 10px; background: rgba(184, 148, 85, 0.25); color: var(--brass); padding: 2px 8px; border-radius: 9999px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; }
        .sidebar-nav { padding: 24px 16px; flex: 1; display: flex; flex-direction: column; gap: 6px; }
        .nav-section-title { font-size: 10px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: var(--sage); padding: 12px 12px 6px; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; color: rgba(255,255,255,0.75); transition: all 0.15s ease; }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: white; }
        .nav-item.active { background: var(--green); color: white; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
        .sidebar-user { padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: space-between; }
        .user-meta strong { display: block; font-size: 13px; color: white; }
        .user-meta span { font-size: 11px; color: var(--sage); }

        /* Main Workspace */
        .main-content { display: flex; flex-direction: column; overflow-y: auto; }
        .topbar { background: white; border-bottom: 1px solid var(--line); padding: 16px 36px; display: flex; align-items: center; justify-content: space-between; }
        .breadcrumb { font-size: 12px; color: var(--muted); display: flex; gap: 8px; align-items: center; }
        .breadcrumb a:hover { color: var(--green); }
        .content-body { padding: 36px; flex: 1; }

        /* UI Helpers */
        .page-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 28px; }
        .page-title { font-family: var(--serif); font-size: 32px; font-weight: 400; color: var(--forest); line-height: 1.1; }
        .page-subtitle { color: var(--muted); font-size: 13px; margin-top: 6px; }
        
        .alert { padding: 14px 18px; border-radius: 6px; margin-bottom: 24px; font-size: 13px; display: flex; align-items: center; justify-content: space-between; }
        .alert-success { background: #e8f5e9; border: 1px solid #c8e6c9; color: #2e7d32; }
        .alert-danger { background: #ffebee; border: 1px solid #ffcdd2; color: #c62828; }

        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 8px 18px; border-radius: 5px; font-size: 13px; font-weight: 700; cursor: pointer; border: 1px solid transparent; transition: all 0.15s ease; text-decoration: none; }
        .btn-primary { background: var(--green); color: white; }
        .btn-primary:hover { background: var(--forest); }
        .btn-secondary { background: white; border-color: var(--line); color: var(--forest); }
        .btn-secondary:hover { background: #f9f9f9; }
        .btn-brass { background: var(--brass); color: white; }
        .btn-danger { background: #d32f2f; color: white; }
        .btn-sm { padding: 5px 12px; font-size: 12px; }

        .card { background: white; border: 1px solid var(--line); border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); margin-bottom: 24px; }
        .card-header { padding: 18px 24px; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; }
        .card-title { font-size: 15px; font-weight: 700; color: var(--forest); }
        .card-body { padding: 24px; }

        .table-responsive { width: 100%; overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; }
        .table th, .table td { padding: 14px 20px; border-bottom: 1px solid var(--line); }
        .table th { background: #fafafa; font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); font-weight: 700; }
        .table tbody tr:hover { background: #fdfdfc; }

        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
        .badge-submitted, .badge-pending { background: #e3f2fd; color: #1565c0; }
        .badge-under_review, .badge-processing { background: #fff8e1; color: #f57f17; }
        .badge-quoted { background: #f3e5f5; color: #7b1fa2; }
        .badge-accepted, .badge-verified, .badge-delivered, .badge-completed { background: #e8f5e9; color: #2e7d32; }
        .badge-rejected, .badge-cancelled { background: #ffebee; color: #c62828; }
        .badge-awaiting_payment { background: #fff3e0; color: #e65100; }
        .badge-shipped, .badge-in_transit { background: #e0f2f1; color: #00695c; }

        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 12px; font-weight: 700; color: var(--forest); margin-bottom: 6px; }
        .form-input, .form-select, .form-textarea { width: 100%; padding: 10px 14px; border: 1px solid var(--line); border-radius: 4px; font-size: 13px; outline: none; background: white; }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--green); box-shadow: 0 0 0 3px rgba(29, 91, 67, 0.1); }
        .form-error { font-size: 11px; color: #c62828; margin-top: 4px; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }

        .stat-card { background: white; border: 1px solid var(--line); border-radius: 8px; padding: 20px; }
        .stat-card .val { font-size: 32px; font-family: var(--serif); color: var(--forest); margin: 6px 0; font-weight: 400; }
        .stat-card .lbl { font-size: 12px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; }

        @media (max-width: 900px) {
            .dashboard-container { grid-template-columns: 1fr; }
            .sidebar { display: none; }
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
            .content-body { padding: 20px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('home') }}" class="brand-link">Palm<span>Trade</span></a>
                <span class="brand-pill">{{ auth()->user()->role === 'admin' ? 'Eksportir' : 'Buyer' }}</span>
            </div>
            
            <nav class="sidebar-nav">
                @if(auth()->user()->isBuyer())
                    <span class="nav-section-title">Workspace Pembeli</span>
                    <a href="{{ route('buyer.dashboard') }}" class="nav-item {{ request()->routeIs('buyer.dashboard') ? 'active' : '' }}">Dashboard Utama</a>
                    <a href="{{ route('buyer.quotations.index') }}" class="nav-item {{ request()->routeIs('buyer.quotations.*') ? 'active' : '' }}">Penawaran Saya (RFQ)</a>
                    <a href="{{ route('buyer.orders.index') }}" class="nav-item {{ request()->routeIs('buyer.orders.*') ? 'active' : '' }}">Pesanan Saya (Orders)</a>
                    <a href="{{ route('buyer.shipments.index') }}" class="nav-item {{ request()->routeIs('buyer.shipments.*') ? 'active' : '' }}">Lacak Pengiriman Kapal</a>
                    <a href="{{ route('buyer.profile') }}" class="nav-item {{ request()->routeIs('buyer.profile') ? 'active' : '' }}">Profil Perusahaan</a>
                @else
                    <span class="nav-section-title">Workspace Eksportir</span>
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard Utama</a>
                    <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Katalog Produk</a>
                    <a href="{{ route('admin.quotations.index') }}" class="nav-item {{ request()->routeIs('admin.quotations.*') ? 'active' : '' }}">Permintaan RFQ Masuk</a>
                    <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Manajemen Pesanan</a>
                    <a href="{{ route('admin.payments.index') }}" class="nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">Verifikasi Pembayaran</a>
                    <a href="{{ route('admin.shipments.index') }}" class="nav-item {{ request()->routeIs('admin.shipments.*') ? 'active' : '' }}">Logistik & Pelayaran</a>
                    <a href="{{ route('admin.buyers.index') }}" class="nav-item {{ request()->routeIs('admin.buyers.*') ? 'active' : '' }}">Daftar Buyer</a>
                    <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">Laporan & Analitik</a>
                @endif
                <div style="margin-top:auto"></div>
                <a href="{{ route('products.index') }}" class="nav-item" target="_blank">Lihat Web Publik ↗</a>
            </nav>

            <div class="sidebar-user">
                <div class="user-meta">
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>{{ auth()->user()->company_name ?? auth()->user()->email }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm" style="background:transparent;border-color:rgba(255,255,255,0.2);color:white">Keluar</button>
                </form>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="main-content">
            <header class="topbar">
                <div class="breadcrumb">
                    <a href="{{ route('home') }}">Beranda</a>
                    <span>/</span>
                    <span>{{ $breadcrumb ?? 'Workspace' }}</span>
                </div>
                <div>
                    <span style="font-size: 12px; color: var(--muted)">Sesi Perdagangan: <strong>Aktif</strong></span>
                </div>
            </header>

            <div class="content-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error') || $errors->any())
                    <div class="alert alert-danger">
                        <span>{{ session('error') ?? $errors->first() }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
