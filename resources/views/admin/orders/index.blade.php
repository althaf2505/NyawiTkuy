@extends('layouts.dashboard', ['breadcrumb' => 'Manajemen Pesanan', 'title' => 'Pesanan Ekspor'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Pesanan & Kontrak Ekspor</h1>
        <p class="page-subtitle">Pantau seluruh kontrak perdagangan yang telah disepakati, verifikasi pembayaran, dan atur kargo kapal.</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('admin.orders.index') }}" style="display:flex; gap:12px; align-items:center; width:100%">
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari nomor pesanan, nama pembeli, perusahaan..." class="form-input" style="max-width:300px">
            <select name="status" class="form-select" style="max-width:180px" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="awaiting_payment" {{ $status === 'awaiting_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                <option value="processing" {{ $status === 'processing' ? 'selected' : '' }}>Sedang Diproses (Kargo Siap)</option>
                <option value="shipped" {{ $status === 'shipped' ? 'selected' : '' }}>Dalam Pelayaran (Shipped)</option>
                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai (Terkirim)</option>
                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            @if($search || $status)
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm" style="color:var(--muted)">Reset</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nomor Pesanan</th>
                    <th>Pembeli & Negara</th>
                    <th>Nilai Kontrak</th>
                    <th>Incoterms</th>
                    <th>Status</th>
                    <th>Tanggal Terbit</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>
                            <strong>{{ $order->user ? $order->user->name : 'N/A' }}</strong>
                            <div style="font-size:11px; color:var(--muted)">{{ $order->user ? $order->user->company_name : '-' }} ({{ $order->user ? $order->user->country : '-' }})</div>
                        </td>
                        <td><strong>{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</strong></td>
                        <td>{{ $order->trade_terms ?? 'FOB' }}</td>
                        <td>
                            @php
                                $statusIndo = match($order->status) {
                                    'awaiting_payment' => 'Menunggu Pembayaran',
                                    'processing' => 'Sedang Diproses',
                                    'shipped' => 'Dalam Pengiriman',
                                    'completed' => 'Selesai / Terkirim',
                                    'cancelled' => 'Dibatalkan',
                                    default => str_replace('_', ' ', $order->status),
                                };
                            @endphp
                            <span class="badge badge-{{ $order->status }}">{{ $statusIndo }}</span>
                        </td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-sm">
                                Kelola Pesanan →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:40px; color:var(--muted)">
                            Belum ada pesanan yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div style="padding:16px 24px; border-top:1px solid var(--line)">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
