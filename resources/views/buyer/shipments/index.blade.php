@extends('layouts.dashboard', ['breadcrumb' => 'Pelacakan Pengiriman', 'title' => 'Logistik Ekspor'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Pelacakan Pengiriman & Pelayaran Kapal</h1>
        <p class="page-subtitle">Pantau posisi kapal pengangkut kargo, status bea cukai ekspor, dan pelabuhan bongkar.</p>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nomor Pengiriman (B/L)</th>
                    <th>Nomor Pesanan</th>
                    <th>Rute Pelayaran</th>
                    <th>Perusahaan Pelayaran / Tracking</th>
                    <th>Status</th>
                    <th>Perkiraan Tiba (ETA)</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shipments as $shp)
                    <tr>
                        <td><strong>{{ $shp->shipment_number }}</strong></td>
                        <td>{{ $shp->order ? $shp->order->order_number : '-' }}</td>
                        <td>{{ $shp->origin }} → {{ $shp->destination }}</td>
                        <td>
                            <div>{{ $shp->carrier ?? 'Kapal Menunggu Nominasi' }}</div>
                            <small style="color:var(--muted)">{{ $shp->tracking_reference ?? 'Nomor B/L belum terbit' }}</small>
                        </td>
                        <td>
                            @php
                                $shipStatusIndo = match($shp->status) {
                                    'preparing' => 'Persiapan Terminal',
                                    'ready_to_ship' => 'Bea Cukai Selesai',
                                    'in_transit' => 'Dalam Pelayaran',
                                    'arrived' => 'Tiba di Pelabuhan',
                                    'delivered' => 'Diserahkan / Bongkar',
                                    default => str_replace('_', ' ', $shp->status),
                                };
                            @endphp
                            <span class="badge badge-{{ $shp->status }}">{{ $shipStatusIndo }}</span>
                        </td>
                        <td>{{ $shp->estimated_arrival ? $shp->estimated_arrival->format('d M Y') : 'Menunggu jadwal berlayar' }}</td>
                        <td>
                            <a href="{{ route('buyer.shipments.show', $shp) }}" class="btn btn-secondary btn-sm">
                                Linimasa Pelacakan →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding: 40px; color:var(--muted)">
                            Belum ada jadwal pelayaran aktif. Setelah pembayaran pesanan terverifikasi dan kargo dijadwalkan berlayar, data pelacakan akan otomatis tersedia di sini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($shipments->hasPages())
        <div style="padding:16px 24px; border-top:1px solid var(--line)">
            {{ $shipments->links() }}
        </div>
    @endif
</div>
@endsection
