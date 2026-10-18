@extends('layouts.dashboard', ['breadcrumb' => 'Laporan & Analitik', 'title' => 'Laporan Ekspor'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan & Analitik Perdagangan Ekspor</h1>
        <p class="page-subtitle">Ringkasan transaksi riil, omset kontrak berjalan, status pesanan, dan ekspor laporan ke file CSV.</p>
    </div>
    <div>
        <a href="{{ route('admin.reports.export') }}" class="btn btn-primary">Unduh Rekap Pesanan (CSV) ⤓</a>
    </div>
</div>

<div class="grid-3" style="margin-bottom:28px">
    <div class="stat-card">
        <div class="lbl">Total Kontrak Pesanan</div>
        <div class="val">{{ number_format($totalOrders) }}</div>
        <div style="font-size:11px; color:var(--muted)">Semua pesanan yang pernah diterbitkan</div>
    </div>
    <div class="stat-card">
        <div class="lbl">Total Nilai Transaksi Aktif</div>
        <div class="val" style="color:var(--green)">USD {{ number_format($totalRevenue, 2) }}</div>
        <div style="font-size:11px; color:var(--muted)">Pesanan diproses, berlayar, dan terkirim</div>
    </div>
    <div class="stat-card">
        <div class="lbl">Permintaan RFQ Masuk</div>
        <div class="val" style="color:var(--brass)">{{ number_format($totalRfqs) }}</div>
        <div style="font-size:11px; color:var(--muted)">Total inquiry komersial pembeli</div>
    </div>
</div>

<div class="grid-2" style="align-items: flex-start">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Distribusi Pesanan Berdasarkan Status</span>
        </div>
        <div class="card-body">
            @forelse($ordersByStatus as $row)
                @php
                    $statusIndo = match($row->status) {
                        'awaiting_payment' => 'Menunggu Pembayaran',
                        'processing' => 'Sedang Diproses',
                        'shipped' => 'Dalam Pengiriman',
                        'completed' => 'Selesai / Terkirim',
                        'cancelled' => 'Dibatalkan',
                        default => str_replace('_', ' ', $row->status),
                    };
                @endphp
                <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid var(--line)">
                    <div>
                        <span class="badge badge-{{ $row->status }}">{{ $statusIndo }}</span>
                        <strong style="margin-left:8px">{{ $row->count }} pesanan</strong>
                    </div>
                    <strong>USD {{ number_format($row->total, 2) }}</strong>
                </div>
            @empty
                <p style="color:var(--muted); text-align:center; padding:20px 0">Belum ada data pesanan.</p>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Daftar Transaksi Terbaru</span>
        </div>
        <div class="card-body">
            @forelse($recentTransactions as $tx)
                <div style="padding:10px 0; border-bottom:1px solid var(--line); display:flex; justify-content:space-between; align-items:center; font-size:13px">
                    <div>
                        <strong>{{ $tx->order_number }}</strong>
                        <div style="font-size:11px; color:var(--muted)">{{ $tx->user ? $tx->user->company_name : '-' }} · {{ $tx->created_at->format('d M Y') }}</div>
                    </div>
                    <div style="text-align:right">
                        <strong>{{ $tx->currency }} {{ number_format($tx->total_amount, 2) }}</strong>
                        <div><span class="badge badge-{{ $tx->status }}">{{ $tx->status }}</span></div>
                    </div>
                </div>
            @empty
                <p style="color:var(--muted); text-align:center; padding:20px 0">Belum ada transaksi terekam.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
