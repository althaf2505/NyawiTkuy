@extends('layouts.dashboard', ['breadcrumb' => 'Kelola Pesanan', 'title' => 'Pesanan ' . $order->order_number])

@section('content')
<div class="page-header">
    <div>
        <div style="display:flex; gap:10px; align-items:center; margin-bottom:8px">
            <h1 class="page-title">{{ $order->order_number }}</h1>
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
        </div>
        <p class="page-subtitle">Pembeli: <strong>{{ $order->user ? $order->user->name : 'N/A' }}</strong> ({{ $order->user ? $order->user->company_name : '-' }}, {{ $order->user ? $order->user->country : '-' }}) · Tanggal Terbit: {{ $order->created_at->format('d M Y, H:i') }}</p>
    </div>
    <div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">← Kembali ke Daftar Pesanan</a>
    </div>
</div>

<div class="grid-2" style="align-items: flex-start">
    <div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Snapshot Komoditas Kontrak</span>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Volume</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product_name }}</strong>
                                    <div style="font-size:11px; color:var(--muted)">SKU: {{ $item->product_sku }}</div>
                                </td>
                                <td>{{ number_format($item->quantity) }} {{ $item->unit }}</td>
                                <td>{{ $order->currency }} {{ number_format($item->unit_price, 2) }}</td>
                                <td><strong>{{ $order->currency }} {{ number_format($item->total_price, 2) }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#fafafa">
                            <td colspan="3" style="text-align:right"><strong>Total Nilai Kontrak:</strong></td>
                            <td><strong style="font-size:16px; color:var(--forest)">{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div style="padding:14px 20px; font-size:12px; color:var(--muted); border-top:1px solid var(--line)">
                Incoterms: <strong>{{ $order->trade_terms ?? 'FOB' }}</strong> | Ketentuan Pembayaran: <strong>{{ $order->payment_terms ?? 'L/C' }}</strong>
            </div>
        </div>

        <!-- Payments associated -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">Bukti Transfer & Pembayaran dari Buyer</span>
            </div>
            <div class="card-body">
                @forelse($order->payments as $payment)
                    <div style="padding:12px; border:1px solid var(--line); border-radius:6px; margin-bottom:8px; font-size:13px">
                        <div style="display:flex; justify-content:space-between">
                            <strong>{{ $payment->payment_number }} - {{ $payment->currency }} {{ number_format($payment->amount, 2) }}</strong>
                            <span class="badge badge-{{ $payment->status }}">{{ $payment->status }}</span>
                        </div>
                        <div style="color:var(--muted); font-size:12px; margin-top:4px">Metode: {{ $payment->method }} · No Ref: {{ $payment->reference }}</div>
                        <div style="margin-top:8px">
                            <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-secondary btn-sm">Periksa & Verifikasi Bukti →</a>
                        </div>
                    </div>
                @empty
                    <p style="color:var(--muted); text-align:center; padding:16px 0">
                        Belum ada bukti pembayaran yang diunggah oleh pembeli.
                    </p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right: Status Update & Shipment Creation -->
    <div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Pembaruan Tahapan Pesanan</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Status Operasional Pesanan</label>
                        <select name="status" class="form-select">
                            <option value="awaiting_payment" {{ $order->status === 'awaiting_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Sedang Diproses (Persiapan Tangki/Kargo)</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Dalam Pelayaran (On Board Vessel)</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai (Kargo Terbongkar & Diterima)</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%">Simpan Perubahan Status</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Jadwal Pengiriman Kapal (Shipment)</span>
            </div>
            <div class="card-body">
                @if($order->shipments->isNotEmpty())
                    <p style="font-size:13px; color:var(--muted); margin-bottom:12px">Pesanan ini memiliki {{ $order->shipments->count() }} pengiriman kargo laut aktif.</p>
                    @foreach($order->shipments as $s)
                        <div style="padding:10px; border:1px solid var(--line); border-radius:4px; margin-bottom:8px; display:flex; justify-content:space-between; align-items:center">
                            <span>{{ $s->shipment_number }} ({{ $s->status }})</span>
                            <a href="{{ route('admin.shipments.show', $s) }}" class="btn btn-secondary btn-sm">Kelola Rute →</a>
                        </div>
                    @endforeach
                @else
                    <p style="font-size:13px; color:var(--muted); margin-bottom:16px">
                        Kargo siap diberangkatkan? Tentukan operator pelayaran, pelabuhan muat, dan nomor Bill of Lading.
                    </p>
                    <a href="{{ route('admin.shipments.create', ['order_id' => $order->id]) }}" class="btn btn-brass" style="width:100%">
                        + Terbitkan Jadwal Pengiriman (Shipment)
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
