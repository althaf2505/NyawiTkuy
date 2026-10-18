@extends('layouts.dashboard', ['breadcrumb' => 'Daftar Pesanan', 'title' => 'Pesanan Saya'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Pesanan Ekspor Saya</h1>
        <p class="page-subtitle">Pantau progres kontrak dagang, verifikasi pembayaran kargo, dan jadwal pengiriman kapal.</p>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nomor Pesanan (Order)</th>
                    <th>Ref. Penawaran</th>
                    <th>Total Nilai Kontrak</th>
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
                        <td>{{ $order->quotation ? $order->quotation->quotation_number : '-' }}</td>
                        <td><strong>{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</strong></td>
                        <td>{{ $order->trade_terms ?? 'FOB' }}</td>
                        <td>
                            @php
                                $statusIndo = match($order->status) {
                                    'awaiting_payment' => 'Menunggu Pembayaran',
                                    'processing' => 'Sedang Diproses',
                                    'shipped' => 'Dalam Pengiriman',
                                    'completed' => 'Selesai / Diterima',
                                    'cancelled' => 'Dibatalkan',
                                    default => str_replace('_', ' ', $order->status),
                                };
                            @endphp
                            <span class="badge badge-{{ $order->status }}">{{ $statusIndo }}</span>
                        </td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('buyer.orders.show', $order) }}" class="btn btn-secondary btn-sm">
                                Rincian & Pembayaran →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding: 40px; color:var(--muted)">
                            Belum ada pesanan aktif. Pesanan akan otomatis terbentuk ketika Anda menyetujui penawaran harga resmi (Quotation).
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
