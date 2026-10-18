@extends('layouts.dashboard', ['breadcrumb' => 'Dashboard', 'title' => $title])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $workspace === 'buyer' ? 'Dashboard Pembeli' : 'Dashboard Eksportir' }}</h1>
        <p class="page-subtitle">Selamat datang kembali, {{ auth()->user()->name }} ({{ auth()->user()->company_name ?? auth()->user()->email }})</p>
    </div>
    <div>
        @if($workspace === 'buyer')
            <a href="{{ route('buyer.quotations.create') }}" class="btn btn-primary">+ Ajukan RFQ Baru</a>
        @else
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Tambah Produk</a>
        @endif
    </div>
</div>

<div class="grid-3" style="margin-bottom: 28px">
    @foreach($metrics as $metric)
        @php
            $indonesianLabel = match($metric['label']) {
                'Active RFQs' => 'RFQ / Penawaran Aktif',
                'Orders in progress' => 'Pesanan Sedang Diproses',
                'Shipments' => 'Pengiriman Pelayaran',
                'Active products' => 'Produk Sawit Aktif',
                'New RFQs' => 'Permintaan RFQ Baru',
                default => $metric['label'],
            };
        @endphp
        <div class="stat-card">
            <div class="lbl">{{ $indonesianLabel }}</div>
            <div class="val">{{ $metric['value'] }}</div>
        </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">{{ $workspace === 'buyer' ? 'Pengajuan Penawaran (RFQ) Terkini' : 'Permintaan Masuk dari Pembeli Terkini' }}</span>
        @if($workspace === 'buyer')
            <a href="{{ route('buyer.quotations.index') }}" class="btn btn-secondary btn-sm">Lihat Semua RFQ →</a>
        @else
            <a href="{{ route('admin.quotations.index') }}" class="btn btn-secondary btn-sm">Lihat Semua Permintaan →</a>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nomor Referensi</th>
                    <th>{{ $workspace === 'buyer' ? 'Tujuan Pengiriman' : 'Pembeli & Perusahaan' }}</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td><strong>{{ $item->quotation_number }}</strong></td>
                        <td>
                            @if($workspace === 'buyer')
                                {{ $item->destination_country }} {{ $item->destination_port ? '('.$item->destination_port.')' : '' }}
                            @else
                                <strong>{{ $item->user ? $item->user->name : 'N/A' }}</strong>
                                <div style="font-size:11px; color:var(--muted)">{{ $item->user ? $item->user->company_name : '-' }}</div>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusIndo = match($item->status) {
                                    'submitted' => 'Diajukan',
                                    'under_review' => 'Dalam Peninjauan',
                                    'quoted' => 'Diberi Penawaran',
                                    'accepted' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    'cancelled' => 'Dibatalkan',
                                    'expired' => 'Kadaluarsa',
                                    default => str_replace('_', ' ', $item->status),
                                };
                            @endphp
                            <span class="badge badge-{{ $item->status }}">{{ $statusIndo }}</span>
                        </td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            @if($workspace === 'buyer')
                                <a href="{{ route('buyer.quotations.show', $item) }}" class="btn btn-secondary btn-sm">Lihat →</a>
                            @else
                                <a href="{{ route('admin.quotations.show', $item) }}" class="btn btn-secondary btn-sm">Tinjau →</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 40px; color:var(--muted)">
                            Belum ada aktivitas transaksi terkini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
