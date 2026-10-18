@extends('layouts.dashboard', ['breadcrumb' => 'Pengajuan RFQ', 'title' => 'Ajukan Penawaran Harga'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Ajukan Permintaan Penawaran (RFQ)</h1>
        <p class="page-subtitle">Tentukan komoditas, kuantitas volume ekspor, dan pelabuhan tujuan untuk menerima kalkulasi penawaran resmi.</p>
    </div>
    <div>
        <a href="{{ route('buyer.quotations.index') }}" class="btn btn-secondary">← Kembali ke Daftar RFQ</a>
    </div>
</div>

<div class="card" style="max-width: 720px">
    <div class="card-body">
        <form method="POST" action="{{ route('buyer.quotations.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Pilih Produk Kelapa Sawit *</label>
                <select name="product_id" class="form-select" required>
                    <option value="">-- Pilih produk dari katalog --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ (string)$selectedProductId === (string)$product->id ? 'selected' : '' }}>
                            {{ $product->name }} (MOQ: {{ number_format($product->moq) }} {{ $product->unit }}) - {{ $product->indicative_price ? $product->currency . ' ' . number_format($product->indicative_price) . ' indikatif' : 'Harga by request' }}
                        </option>
                    @endforeach
                </select>
                @error('product_id') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Target Kuantitas Pemesanan *</label>
                    <input type="number" step="0.01" name="quantity" class="form-input" value="{{ old('quantity') }}" placeholder="Contoh: 500" required>
                    @error('quantity') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Satuan Perdagangan *</label>
                    <select name="unit" class="form-select" required>
                        <option value="MT">Metrik Ton (MT)</option>
                        <option value="L">Liter (L)</option>
                        <option value="KG">Kilogram (KG)</option>
                    </select>
                    @error('unit') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Negara Tujuan Ekspor *</label>
                    <input type="text" name="destination_country" class="form-input" value="{{ old('destination_country', auth()->user()->country) }}" placeholder="Contoh: India, Belanda, Tiongkok" required>
                    @error('destination_country') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Pelabuhan Tujuan / Discharge Port (Opsional)</label>
                    <input type="text" name="destination_port" class="form-input" value="{{ old('destination_port') }}" placeholder="Contoh: Port of Rotterdam, Kandla Port">
                    @error('destination_port') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan Teknis / Persyaratan Khusus</label>
                <textarea name="notes" rows="4" class="form-textarea" placeholder="Jelaskan kebutuhan pengemasan (flexibag, bulk tanker curah, drum), batas mutu FFA tertentu, jadwal kapal yang diharapkan...">{{ old('notes') }}</textarea>
                @error('notes') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="margin-top: 24px; display:flex; justify-content: flex-end; gap: 12px">
                <a href="{{ route('buyer.quotations.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Kirim Permintaan Penawaran (RFQ)</button>
            </div>
        </form>
    </div>
</div>
@endsection
