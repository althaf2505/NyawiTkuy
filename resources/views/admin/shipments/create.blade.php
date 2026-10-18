@extends('layouts.dashboard', ['breadcrumb' => 'Terbitkan Pengiriman', 'title' => 'Inisiasi Pengiriman Kargo'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Inisiasi Jadwal Pengiriman Kargo (Shipment)</h1>
        <p class="page-subtitle">Hubungkan detail transportasi kapal laut dan pelabuhan bongkar ke pesanan ekspor terverifikasi.</p>
    </div>
    <div>
        <a href="{{ route('admin.shipments.index') }}" class="btn btn-secondary">← Kembali ke Pengiriman</a>
    </div>
</div>

<div class="card" style="max-width: 760px">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.shipments.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Pilih Pesanan Ekspor Terverifikasi *</label>
                <select name="order_id" class="form-select" required>
                    <option value="">-- Pilih pesanan yang siap dikirim --</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}" {{ (string)$selectedOrderId === (string)$order->id ? 'selected' : '' }}>
                            {{ $order->order_number }} - {{ $order->user ? $order->user->company_name : 'N/A' }} ({{ $order->currency }} {{ number_format($order->total_amount, 2) }})
                        </option>
                    @endforeach
                </select>
                @error('order_id') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Pelabuhan Muat / Port of Loading (Asal) *</label>
                    <input type="text" name="origin" class="form-input" value="{{ old('origin', 'Pelabuhan Belawan / Dumai, Indonesia') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Pelabuhan Bongkar / Port of Discharge (Tujuan) *</label>
                    <input type="text" name="destination" class="form-input" value="{{ old('destination') }}" placeholder="Contoh: Port of Rotterdam, Belanda" required>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Perusahaan Pelayaran / Nama Kapal (Ocean Carrier)</label>
                    <input type="text" name="carrier" class="form-input" value="{{ old('carrier') }}" placeholder="Contoh: Maersk Line / MSC / Evergreen">
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Bill of Lading (B/L) / Kode Pelacakan</label>
                    <input type="text" name="tracking_reference" class="form-input" value="{{ old('tracking_reference') }}" placeholder="Contoh: MSKU987654321">
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Perkiraan Waktu Tiba (ETA)</label>
                    <input type="date" name="estimated_arrival" class="form-input" value="{{ old('estimated_arrival', now()->addDays(28)->format('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status Awal Milestone *</label>
                    <select name="initial_status" class="form-select" required>
                        <option value="preparing">Persiapan Terminal (Penyimpanan tangki & inspeksi)</option>
                        <option value="ready_to_ship">Siap Berlayar (Bea Cukai & PEB selesai)</option>
                        <option value="in_transit">Dalam Pelayaran (Kapal bertolak dari pelabuhan)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan Operasional / Staging</label>
                <textarea name="note" class="form-textarea" rows="2" placeholder="Nomor tangki timbun di terminal, hasil sertifikat surveyor Sucofindo/SGS..."></textarea>
            </div>

            <div style="margin-top:24px; display:flex; justify-content:flex-end; gap:12px">
                <a href="{{ route('admin.shipments.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Terbitkan Rekor Pengiriman</button>
            </div>
        </form>
    </div>
</div>
@endsection
