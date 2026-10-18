@extends('layouts.dashboard', ['breadcrumb' => 'Linimasa Pengiriman', 'title' => 'Pengiriman ' . $shipment->shipment_number])

@section('content')
<div class="page-header">
    <div>
        <div style="display:flex; gap:10px; align-items:center; margin-bottom:8px">
            <h1 class="page-title">{{ $shipment->shipment_number }}</h1>
            @php
                $shipStatusIndo = match($shipment->status) {
                    'preparing' => 'Persiapan Terminal',
                    'ready_to_ship' => 'Bea Cukai Selesai',
                    'in_transit' => 'Dalam Pelayaran',
                    'arrived' => 'Tiba di Pelabuhan',
                    'delivered' => 'Diserahkan ke Consignee',
                    default => str_replace('_', ' ', $shipment->status),
                };
            @endphp
            <span class="badge badge-{{ $shipment->status }}">{{ $shipStatusIndo }}</span>
        </div>
        <p class="page-subtitle">Rute: <strong>{{ $shipment->origin }}</strong> menuju <strong>{{ $shipment->destination }}</strong> | Operator Kapal: {{ $shipment->carrier ?? 'TBD' }}</p>
    </div>
    <div>
        <a href="{{ route('buyer.shipments.index') }}" class="btn btn-secondary">← Kembali ke Pengiriman</a>
    </div>
</div>

<div class="grid-2" style="align-items: flex-start">
    <!-- Left: Timeline Events -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">Linimasa & Milestone Logistik Internasional</span>
        </div>
        <div class="card-body">
            @forelse($shipment->updates as $update)
                <div style="display:flex; gap:16px; margin-bottom:24px; position:relative">
                    <div style="width:12px; height:12px; border-radius:50%; background:var(--green); margin-top:4px; flex-shrink:0; box-shadow:0 0 0 4px #e8f5e9"></div>
                    <div style="flex:1">
                        <div style="display:flex; justify-content:space-between; align-items:baseline">
                            <strong style="font-size:14px; color:var(--forest)">{{ $update->title }}</strong>
                            <small style="color:var(--muted)">{{ $update->event_date->format('d M Y, H:i') }}</small>
                        </div>
                        <div style="font-size:12px; color:var(--brass); font-weight:600; margin-top:2px">
                            {{ $update->location ?? 'Indonesia' }} · <span style="text-transform:uppercase">{{ $update->status }}</span>
                        </div>
                        @if($update->description)
                            <p style="font-size:13px; color:var(--ink); margin-top:6px; line-height:1.5">
                                {{ $update->description }}
                            </p>
                        @endif
                    </div>
                </div>
            @empty
                <p style="color:var(--muted); text-align:center; padding:20px 0">
                    Jadwal pengiriman telah diinisiasi. Proses persiapan tangki dan inspeksi surveyor sedang berlangsung di pelabuhan muat.
                </p>
            @endforelse
        </div>
    </div>

    <!-- Right: Cargo & Summary Details -->
    <div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Informasi Kargo & Pelayaran Kapal</span>
            </div>
            <div class="card-body" style="font-size:13px; line-height:1.8">
                <div>Pelabuhan Muat (Port of Loading / POL): <strong>{{ $shipment->origin }}</strong></div>
                <div>Pelabuhan Bongkar (Port of Discharge / POD): <strong>{{ $shipment->destination }}</strong></div>
                <div>Perusahaan Pelayaran / Nama Kapal: <strong>{{ $shipment->carrier ?? 'Menunggu nominasi kapal' }}</strong></div>
                <div>Nomor Bill of Lading (B/L): <strong>{{ $shipment->tracking_reference ?? 'Diberitahukan setelah kapal berlayar' }}</strong></div>
                <div>Estimasi Tanggal Tiba (ETA): <strong>{{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y') : 'Menunggu konfirmasi pelayaran' }}</strong></div>
                
                @if($shipment->order)
                    <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--line)">
                        <div style="font-weight:700; color:var(--forest); margin-bottom:8px">Isi Kontrak Pesanan {{ $shipment->order->order_number }}:</div>
                        @foreach($shipment->order->items as $item)
                            <div>• {{ $item->product_name }} - {{ number_format($item->quantity) }} {{ $item->unit }}</div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
