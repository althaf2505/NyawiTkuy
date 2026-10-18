@extends('layouts.dashboard', ['breadcrumb' => 'Ubah Produk', 'title' => 'Ubah ' . $product->name])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Ubah Data Produk: {{ $product->name }}</h1>
        <p class="page-subtitle">Perbarui spesifikasi laboratorium, kapasitas stok pasokan, atau acuan harga indikatif.</p>
    </div>
    <div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">← Kembali ke Produk</a>
    </div>
</div>

<div class="card" style="max-width: 860px">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Komoditas *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $product->name) }}" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Kode SKU *</label>
                    <input type="text" name="sku" class="form-input" value="{{ old('sku', $product->sku) }}" required>
                    @error('sku') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Kategori Komoditas *</label>
                    <select name="category" class="form-select" required>
                        <option value="Crude Palm Oil" {{ $product->category === 'Crude Palm Oil' ? 'selected' : '' }}>Crude Palm Oil (CPO)</option>
                        <option value="Refined Palm Oil" {{ $product->category === 'Refined Palm Oil' ? 'selected' : '' }}>Refined Palm Oil (RBD PO / Olein / Stearin)</option>
                        <option value="Palm Kernel" {{ $product->category === 'Palm Kernel' ? 'selected' : '' }}>Palm Kernel / CPKO</option>
                        <option value="Specialty Fats" {{ $product->category === 'Specialty Fats' ? 'selected' : '' }}>Specialty Fats & Byproducts</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Asal Daerah Kebun/Pabrik *</label>
                    <input type="text" name="origin" class="form-input" value="{{ old('origin', $product->origin) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Komoditas *</label>
                <textarea name="description" rows="3" class="form-textarea" required>{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="grid-4">
                <div class="form-group">
                    <label class="form-label">Harga Indikatif</label>
                    <input type="number" step="0.01" name="indicative_price" class="form-input" value="{{ old('indicative_price', $product->indicative_price) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Mata Uang *</label>
                    <select name="currency" class="form-select" required>
                        <option value="USD" {{ $product->currency === 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ $product->currency === 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="IDR" {{ $product->currency === 'IDR' ? 'selected' : '' }}>IDR</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Satuan *</label>
                    <input type="text" name="unit" class="form-input" value="{{ old('unit', $product->unit) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">MOQ *</label>
                    <input type="number" step="0.01" name="moq" class="form-input" value="{{ old('moq', $product->moq) }}" required>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Kapasitas Pasokan Tersedia (MT)</label>
                    <input type="number" step="0.01" name="stock_capacity" class="form-input" value="{{ old('stock_capacity', $product->stock_capacity) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Ganti Foto Produk</label>
                    <input type="file" name="image" class="form-input" accept="image/*">
                </div>
            </div>

            <div class="form-group">
                <label style="display:flex; align-items:center; gap:8px; font-weight:600; cursor:pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                    <span>Aktif di katalog publik</span>
                </label>
            </div>

            <!-- Specifications -->
            <div style="margin-top:24px; padding-top:20px; border-top:1px solid var(--line)">
                <h3 style="font-size:14px; font-weight:700; color:var(--forest); margin-bottom:12px">Spesifikasi Mutu & Laboratorium</h3>
                <div id="specs-container">
                    @if(!empty($product->specifications))
                        @foreach($product->specifications as $key => $val)
                            <div class="grid-2" style="margin-bottom:8px">
                                <input type="text" name="spec_keys[]" class="form-input" value="{{ $key }}">
                                <input type="text" name="spec_values[]" class="form-input" value="{{ $val }}">
                            </div>
                        @endforeach
                    @else
                        <div class="grid-2" style="margin-bottom:8px">
                            <input type="text" name="spec_keys[]" class="form-input" placeholder="Parameter (Contoh: FFA)">
                            <input type="text" name="spec_values[]" class="form-input" placeholder="Nilai (Contoh: Maks 5%)">
                        </div>
                    @endif
                </div>
            </div>

            <div style="margin-top:28px; display:flex; justify-content:flex-end; gap:12px">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui Produk</button>
            </div>
        </form>
    </div>
</div>
@endsection
