@extends('layouts.dashboard', ['breadcrumb' => 'Manajemen Produk', 'title' => 'Katalog Produk Sawit'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Katalog Produk Sawit</h1>
        <p class="page-subtitle">Kelola spesifikasi mutu, parameter laboratorium, acuan harga indikatif, MOQ, dan status ketersediaan.</p>
    </div>
    <div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Tambah Produk Baru</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('admin.products.index') }}" style="display:flex; gap:12px; align-items:center; width:100%">
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama atau SKU..." class="form-input" style="max-width:250px">
            <select name="category" class="form-select" style="max-width:180px" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <select name="status" class="form-select" style="max-width:140px" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="1" {{ $status === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $status === '0' ? 'selected' : '' }}>Arsip / Nonaktif</option>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            @if($search || $category || $status !== null)
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm" style="color:var(--muted)">Reset</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Komoditas</th>
                    <th>Kategori & SKU</th>
                    <th>Harga Indikatif</th>
                    <th>Minimum Order (MOQ)</th>
                    <th>Asal Daerah</th>
                    <th>Status</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            <div style="font-size:11px; color:var(--muted); max-width:280px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis">
                                {{ $product->description }}
                            </div>
                        </td>
                        <td>
                            <div>{{ $product->category }}</div>
                            <small style="color:var(--muted)">SKU: {{ $product->sku }}</small>
                        </td>
                        <td>
                            @if($product->indicative_price)
                                <strong>{{ $product->currency }} {{ number_format($product->indicative_price, 2) }}</strong>
                                <small style="display:block; color:var(--muted)">per {{ $product->unit }}</small>
                            @else
                                <span style="color:var(--muted)">Berdasarkan permintaan</span>
                            @endif
                        </td>
                        <td>{{ number_format($product->moq) }} {{ $product->unit }}</td>
                        <td>{{ $product->origin }}</td>
                        <td>
                            <span class="badge {{ $product->is_active ? 'badge-accepted' : 'badge-rejected' }}">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex; gap:6px">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary btn-sm">Ubah</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Arsipkan produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-sm" style="color:#c62828">Arsipkan</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:40px; color:var(--muted)">
                            Belum ada komoditas dalam katalog.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div style="padding:16px 24px; border-top:1px solid var(--line)">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
