@extends('layouts.dashboard', ['breadcrumb' => 'Tambah Produk', 'title' => 'Tambah Komoditas Sawit'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Produk Minyak Sawit</h1>
        <p class="page-subtitle">Publikasikan spesifikasi mutu, kapasitas pasokan bulanan, dan acuan harga indikatif.</p>
    </div>
    <div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">← Kembali ke Produk</a>
    </div>
</div>

<div class="card" style="max-width: 860px">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Komoditas Produk *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" placeholder="Contoh: Crude Palm Oil (CPO)" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Stock Keeping Unit (SKU) *</label>
                    <input type="text" name="sku" class="form-input" value="{{ old('sku') }}" placeholder="Contoh: PT-CPO-001" required>
                    @error('sku') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Kategori Komoditas *</label>
                    <select name="category" class="form-select" required>
                        <option value="Crude Palm Oil">Crude Palm Oil (CPO)</option>
                        <option value="Refined Palm Oil">Refined Palm Oil (RBD PO / Olein / Stearin)</option>
                        <option value="Palm Kernel">Palm Kernel / Minyak Inti Sawit (CPKO)</option>
                        <option value="Specialty Fats">Specialty Fats & Produk Turunan</option>
                    </select>
                    @error('category') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Daerah / Provinsi Asal Kebun & Pabrik *</label>
                    <input type="text" name="origin" class="form-input" value="{{ old('origin', 'Indonesia (Sumatera / Kalimantan)') }}" required>
                    @error('origin') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Komoditas *</label>
                <textarea name="description" rows="3" class="form-textarea" placeholder="Rangkuman komersial, mutu grade ekspor, dan aplikasi industri produk..." required>{{ old('description') }}</textarea>
                @error('description') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="grid-4">
                <div class="form-group">
                    <label class="form-label">Harga Indikatif</label>
                    <input type="number" step="0.01" name="indicative_price" class="form-input" value="{{ old('indicative_price') }}" placeholder="Contoh: 890.00">
                </div>
                <div class="form-group">
                    <label class="form-label">Mata Uang *</label>
                    <select name="currency" class="form-select" required>
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="IDR">IDR</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Satuan Dagang *</label>
                    <input type="text" name="unit" class="form-input" value="{{ old('unit', 'MT') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Minimum Order (MOQ) *</label>
                    <input type="number" step="0.01" name="moq" class="form-input" value="{{ old('moq', 100) }}" required>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Kapasitas Pasokan Tersedia (MT)</label>
                    <input type="number" step="0.01" name="stock_capacity" class="form-input" value="{{ old('stock_capacity') }}" placeholder="Contoh: 5000">
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Produk (Opsional)</label>
                    <input type="file" name="image" class="form-input" accept="image/*">
                </div>
            </div>

            <div class="form-group">
                <label style="display:flex; align-items:center; gap:8px; font-weight:600; cursor:pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span>Publikasikan sebagai produk aktif di katalog publik</span>
                </label>
            </div>

            <!-- Technical Specifications JSON builder -->
            <div style="margin-top:24px; padding-top:20px; border-top:1px solid var(--line)">
                <h3 style="font-size:14px; font-weight:700; color:var(--forest); margin-bottom:12px">Spesifikasi Teknis & Parameter Uji Laboratorium</h3>
                <div id="specs-container">
                    <div class="grid-2" style="margin-bottom:8px">
                        <input type="text" name="spec_keys[]" class="form-input" placeholder="Parameter (Contoh: FFA / Asam Lemak Bebas)" value="FFA">
                        <input type="text" name="spec_values[]" class="form-input" placeholder="Nilai (Contoh: Maks 5.0%)" value="Maks 5.0%">
                    </div>
                    <div class="grid-2" style="margin-bottom:8px">
                        <input type="text" name="spec_keys[]" class="form-input" placeholder="Parameter (Contoh: M&I / Kadar Air & Kotoran)" value="M&I">
                        <input type="text" name="spec_values[]" class="form-input" placeholder="Nilai (Contoh: Maks 0.5%)" value="Maks 0.5%">
                    </div>
                    <div class="grid-2" style="margin-bottom:8px">
                        <input type="text" name="spec_keys[]" class="form-input" placeholder="Parameter (Contoh: Nilai DOBI)" value="DOBI">
                        <input type="text" name="spec_values[]" class="form-input" placeholder="Nilai (Contoh: Min 2.3)" value="Min 2.3">
                    </div>
                    <div class="grid-2" style="margin-bottom:8px">
                        <input type="text" name="spec_keys[]" class="form-input" placeholder="Parameter" value="Pengemasan">
                        <input type="text" name="spec_values[]" class="form-input" placeholder="Nilai" value="Kapal Curah (Bulk) atau Flexibag">
                    </div>
                </div>
            </div>

            <div style="margin-top:28px; display:flex; justify-content:flex-end; gap:12px">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Komoditas</button>
            </div>
        </form>
    </div>
</div>
@endsection
