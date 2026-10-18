@extends('layouts.dashboard', ['breadcrumb' => 'Pembaruan Logistik', 'title' => 'Pengiriman ' . $shipment->shipment_number])

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
        <p class="page-subtitle">Rute: <strong>{{ $shipment->origin }}</strong> → <strong>{{ $shipment->destination }}</strong> | Operator Kapal: {{ $shipment->carrier ?? 'TBD' }}</p>
    </div>
    <div>
        <a href="{{ route('admin.shipments.index') }}" class="btn btn-secondary">← Kembali ke Pengiriman</a>
    </div>
</div>

<div class="grid-2" style="align-items: flex-start">
    <!-- Left: Timeline list -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">Catatan Riwayat Logistik yang Terekam</span>
        </div>
        <div class="card-body">
            @forelse($shipment->updates as $update)
                <div style="display:flex; gap:14px; margin-bottom:20px">
                    <div style="width:10px; height:10px; border-radius:50%; background:var(--green); margin-top:5px; flex-shrink:0"></div>
                    <div style="flex:1">
                        <div style="display:flex; justify-content:space-between">
                            <strong style="color:var(--forest)">{{ $update->title }}</strong>
                            <small style="color:var(--muted)">{{ $update->event_date->format('d M Y, H:i') }}</small>
                        </div>
                        <div style="font-size:12px; color:var(--brass); margin-top:2px">
                            {{ $update->location ?? 'Indonesia' }} · <span style="text-transform:uppercase">{{ $update->status }}</span>
                        </div>
                        @if($update->description)
                            <div style="font-size:13px; color:var(--ink); margin-top:4px">{{ $update->description }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <p style="color:var(--muted); text-align:center; padding:16px 0">Belum ada catatan milestone yang dicatat.</p>
            @endforelse
        </div>
    </div>

    <!-- Right: Add Milestone Event -->
    <div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Catat Milestone Pelayaran Baru</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.shipments.status', $shipment) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Status Milestone Baru *</label>
                        <select name="status" class="form-select" required>
                            <option value="preparing" {{ $shipment->status === 'preparing' ? 'selected' : '' }}>Persiapan Terminal</option>
                            <option value="ready_to_ship" {{ $shipment->status === 'ready_to_ship' ? 'selected' : '' }}>Siap Berlayar (Bea Cukai & Dokumen Ekspor Selesai)</option>
                            <option value="in_transit" {{ $shipment->status === 'in_transit' ? 'selected' : '' }}>Dalam Pelayaran (Di Laut Lepas)</option>
                            <option value="arrived" {{ $shipment->status === 'arrived' ? 'selected' : '' }}>Tiba di Pelabuhan Tujuan (Discharge Port)</option>
                            <option value="delivered" {{ $shipment->status === 'delivered' ? 'selected' : '' }}>Selesai / Kargo Diserahkan ke Consignee</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Judul Peristiwa Milestone *</label>
                        <input type="text" name="title" class="form-input" placeholder="Contoh: Kapal Bertolak dari Belawan, Bea Cukai Rotterdam Selesai" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lokasi Geografis Saat Ini</label>
                        <input type="text" name="location" class="form-input" placeholder="Contoh: Selat Malaka, Pelabuhan Singapura, Rotterdam">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Catatan Pembaruan Kargo</label>
                        <textarea name="description" class="form-textarea" rows="2" placeholder="Contoh: Dokumen Certificate of Analysis ditandatangani, bunker pengisian bahan bakar selesai..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%">Terbitkan Milestone Logistik</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
