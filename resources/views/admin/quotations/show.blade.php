@extends('layouts.dashboard', ['breadcrumb' => 'Tinjauan RFQ', 'title' => 'Tinjau ' . $quotation->quotation_number])

@section('content')
<div class="page-header">
    <div>
        <div style="display:flex; gap:10px; align-items:center; margin-bottom:8px">
            <h1 class="page-title">{{ $quotation->quotation_number }}</h1>
            @php
                $statusIndo = match($quotation->status) {
                    'submitted' => 'Diajukan',
                    'under_review' => 'Dalam Peninjauan',
                    'quoted' => 'Penawaran Siap',
                    'accepted' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    default => str_replace('_', ' ', $quotation->status),
                };
            @endphp
            <span class="badge badge-{{ $quotation->status }}">{{ $statusIndo }}</span>
        </div>
        <p class="page-subtitle">Dari: <strong>{{ $quotation->user ? $quotation->user->name : 'N/A' }}</strong> ({{ $quotation->user ? $quotation->user->company_name : '-' }}, {{ $quotation->user ? $quotation->user->country : '-' }}) · Email: {{ $quotation->user ? $quotation->user->email : '-' }}</p>
    </div>
    <div>
        <a href="{{ route('admin.quotations.index') }}" class="btn btn-secondary">← Kembali ke Daftar RFQ</a>
    </div>
</div>

<div class="grid-2" style="align-items: flex-start">
    <!-- Left: RFQ Items & Negotiation History -->
    <div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Kargo yang Diminta Pembeli</span>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Komoditas</th>
                            <th>Target Kuantitas</th>
                            <th>Pelabuhan Tujuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotation->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product ? $item->product->name : 'N/A' }}</strong>
                                    <div style="font-size:11px; color:var(--muted)">SKU: {{ $item->product ? $item->product->sku : '-' }}</div>
                                </td>
                                <td>{{ number_format($item->quantity) }} {{ $item->unit }}</td>
                                <td>{{ $quotation->destination_country }} {{ $quotation->destination_port ? '('.$quotation->destination_port.')' : '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($quotation->notes)
                <div style="padding:14px 20px; font-size:13px; background:#fafafa; border-top:1px solid var(--line)">
                    <strong>Catatan Pembeli:</strong> {{ $quotation->notes }}
                </div>
            @endif
        </div>

        <!-- Negotiation History -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">Riwayat Versi Negosiasi Penawaran</span>
            </div>
            <div class="card-body">
                @forelse($quotation->offers as $offer)
                    <div style="padding:14px; border:1px solid var(--line); border-radius:6px; margin-bottom:12px; background:{{ $offer->offered_by === 'admin' ? '#fafcfb' : '#fffdfa' }}">
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px">
                            <strong>Versi {{ $offer->version }} · Diajukan oleh {{ $offer->offered_by === 'admin' ? 'Eksportir (Anda)' : 'Pembeli (Buyer)' }}</strong>
                            <span class="badge badge-{{ $offer->status }}">{{ $offer->status }}</span>
                        </div>
                        <div class="grid-2" style="font-size:13px; margin-bottom:6px">
                            <div>Total Nilai: <strong>{{ $offer->currency }} {{ number_format($offer->total_amount, 2) }}</strong></div>
                            <div>Syarat Dagang: <strong>{{ $offer->trade_terms ?? 'FOB Belawan / Dumai' }}</strong></div>
                        </div>
                        <div style="font-size:12px; color:var(--muted)">
                            Syarat Pembayaran: {{ $offer->payment_terms ?? '100% L/C at sight' }}
                            @if($offer->valid_until) | Berlaku hingga: {{ $offer->valid_until->format('d M Y') }} @endif
                        </div>
                        @if($offer->notes)
                            <div style="margin-top:6px; font-size:12px; font-style:italic">"{{ $offer->notes }}"</div>
                        @endif

                        @if($offer->offered_by === 'buyer' && $offer->status === 'sent')
                            <div style="margin-top:12px; padding-top:10px; border-top:1px solid var(--line); display:flex; gap:8px">
                                <form method="POST" action="{{ route('admin.quotations.respond-counter', $quotation) }}">
                                    @csrf
                                    <input type="hidden" name="offer_id" value="{{ $offer->id }}">
                                    <input type="hidden" name="action" value="accept">
                                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Apakah Anda menyetujui counter-offer pembeli? Pesanan (Order) akan langsung dibuat.')">
                                        ✓ Setujui Counter & Buat Pesanan
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.quotations.respond-counter', $quotation) }}">
                                    @csrf
                                    <input type="hidden" name="offer_id" value="{{ $offer->id }}">
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="btn btn-secondary btn-sm" style="color:#c62828">
                                        Tolak Counter Ini
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <p style="color:var(--muted); text-align:center; padding:16px 0">
                        Belum ada penawaran resmi yang dirumuskan. Isi form di samping untuk menerbitkan penawaran.
                    </p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right: Formulate / Send Offer -->
    <div>
        <div class="card" style="border: 2px solid var(--forest)">
            <div class="card-header" style="background: var(--forest); color:white">
                <span class="card-title" style="color:white">Rumuskan Penawaran Ekspor Resmi</span>
            </div>
            <div class="card-body">
                @php $firstItem = $quotation->items->first(); @endphp
                <form method="POST" action="{{ route('admin.quotations.offer', $quotation) }}">
                    @csrf

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Harga Satuan *</label>
                            <input type="number" step="0.01" name="unit_price" class="form-input" value="{{ old('unit_price', $firstItem && $firstItem->product ? $firstItem->product->indicative_price : '') }}" placeholder="Contoh: 895.00" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kuantitas Ditawarkan ({{ $firstItem->unit ?? 'MT' }}) *</label>
                            <input type="number" step="0.01" name="quantity" class="form-input" value="{{ old('quantity', $firstItem->quantity ?? '') }}" required>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Mata Uang *</label>
                            <select name="currency" class="form-select" required>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Incoterm / Syarat Dagang *</label>
                            <input type="text" name="trade_terms" class="form-input" value="{{ old('trade_terms', 'FOB Belawan / Dumai') }}" placeholder="Contoh: FOB Belawan, CIF Rotterdam" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Syarat Pembayaran *</label>
                        <input type="text" name="payment_terms" class="form-input" value="{{ old('payment_terms', '100% Irrevocable Letter of Credit (L/C) at sight') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Batas Masa Berlaku Penawaran *</label>
                        <input type="date" name="valid_until" class="form-input" value="{{ old('valid_until', now()->addDays(14)->format('Y-m-d')) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Catatan Komersial & Jaminan Kualitas Mutu</label>
                        <textarea name="notes" class="form-textarea" rows="2" placeholder="Ketentuan inspeksi surveyor (SGS/Sucofindo), laytime pelabuhan, toleransi berat ±2%...">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%; padding:12px">
                        Kirim Penawaran Resmi ke Buyer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
