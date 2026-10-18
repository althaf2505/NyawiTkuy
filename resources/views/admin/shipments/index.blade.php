@extends('layouts.dashboard', ['breadcrumb' => 'Manajemen Pengiriman', 'title' => 'Logistik Ekspor Kapal'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Pengiriman Kargo & Logistik Pelayaran</h1>
        <p class="page-subtitle">Tetapkan kapal pengangkut, catat nomor Bill of Lading, dan perbarui milestone bongkar muat pelabuhan.</p>
    </div>
    <div>
        <a href="{{ route('admin.shipments.create') }}" class="btn btn-primary">+ Terbitkan Pengiriman Baru</a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nomor Pengiriman</th>
                    <th>Ref. Pesanan</th>
                    <th>Pembeli & Negara</th>
                    <th>Rute Pelayaran</th>
                    <th>Perusahaan Kapal / B/L</th>
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
                        <td>
                            <strong>{{ $shp->order && $shp->order->user ? $shp->order->user->name : 'N/A' }}</strong>
                            <div style="font-size:11px; color:var(--muted)">{{ $shp->order && $shp->order->user ? $shp->order->user->company_name : '-' }}</div>
                        </td>
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
                        <td>{{ $shp->estimated_arrival ? $shp->estimated_arrival->format('d M Y') : 'Pending' }}</td>
                        <td>
                            <a href="{{ route('admin.shipments.show', $shp) }}" class="btn btn-secondary btn-sm">
                                Perbarui Milestone →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:40px; color:var(--muted)">
                            Belum ada jadwal pelayaran kapal yang diterbitkan.
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
