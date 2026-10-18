@extends('layouts.dashboard', ['breadcrumb' => 'Rincian Penawaran', 'title' => 'RFQ ' . $quotation->quotation_number])

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
        <p class="page-subtitle">Diajukan pada {{ $quotation->created_at->format('d M Y, H:i') }} | Pelabuhan Tujuan: {{ $quotation->destination_country }} {{ $quotation->destination_port ? '('.$quotation->destination_port.')' : '' }}</p>
    </div>
    <div>
        <a href="{{ route('buyer.quotations.index') }}" class="btn btn-secondary">← Kembali ke Daftar Penawaran</a>
    </div>
</div>

<div class="grid-2" style="align-items: flex-start">
    <!-- Left Column: Details & Items -->
    <div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Komoditas yang Diminta</span>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Target Kuantitas</th>
                            <th>Parameter Mutu</th>
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
                                <td>
                                    @if($item->product && $item->product->specifications)
                                        <div style="font-size:11px; line-height: 1.4">
                                            @foreach($item->product->specifications as $k => $v)
                                                <span><strong>{{ ucfirst(str_replace('_',' ',$k)) }}:</strong> {{ $v }}</span><br>
                                            @endforeach
                                        </div>
                                    @else
                                        <span style="color:var(--muted)">Spesifikasi Standar Ekspor</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($quotation->notes)
                <div style="padding:16px 24px; background:#fafafa; border-top:1px solid var(--line); font-size:13px">
                    <strong>Catatan Permintaan Buyer:</strong> {{ $quotation->notes }}
                </div>
            @endif
        </div>

        <!-- Negotiation History -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">Riwayat Versi Negosiasi & Penawaran</span>
            </div>
            <div class="card-body">
                @forelse($quotation->offers as $offer)
                    <div style="padding:16px; border:1px solid var(--line); border-radius:6px; margin-bottom:12px; background: {{ $offer->offered_by === 'admin' ? '#fafcfb' : '#fefdf9' }}">
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px">
                            <strong>Versi {{ $offer->version }} · Diajukan oleh {{ $offer->offered_by === 'admin' ? 'Eksportir (PalmTrade)' : 'Anda (Buyer)' }}</strong>
                            @php
                                $offerStatusIndo = match($offer->status) {
                                    'sent' => 'Aktif / Terkirim',
                                    'countered' => 'Dinegosiasi Ulang',
                                    'accepted' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    'expired' => 'Kadaluarsa',
                                    'superseded' => 'Digantikan',
                                    default => $offer->status,
                                };
                            @endphp
                            <span class="badge badge-{{ $offer->status }}">{{ $offerStatusIndo }}</span>
                        </div>
                        <div class="grid-2" style="font-size:13px; margin-bottom:8px">
                            <div>Total Penawaran: <strong>{{ $offer->currency }} {{ number_format($offer->total_amount, 2) }}</strong></div>
                            <div>Syarat Dagang: <strong>{{ $offer->trade_terms ?? 'FOB Belawan / Dumai' }}</strong></div>
                        </div>
                        <div style="font-size:12px; color:var(--muted)">
                            Metode Pembayaran: {{ $offer->payment_terms ?? 'Letter of Credit (L/C) atau Telegraphic Transfer (T/T)' }}
                            @if($offer->valid_until)
                                | Berlaku sampai: {{ $offer->valid_until->format('d M Y') }}
                            @endif
                        </div>
                        @if($offer->notes)
                            <div style="margin-top:6px; font-size:12px; color:var(--ink); font-style: italic">
                                "{{ $offer->notes }}"
                            </div>
                        @endif
                    </div>
                @empty
                    <p style="color:var(--muted); text-align:center; padding:20px 0">
                        Tim eksportir sedang meninjau permintaan RFQ Anda. Lembar penawaran resmi akan segera diterbitkan.
                    </p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Action Area -->
    <div>
        @php $latestOffer = $quotation->offers->where('status', 'sent')->where('offered_by', 'admin')->last(); @endphp

        @if($latestOffer)
            <div class="card" style="border: 2px solid var(--green)">
                <div class="card-header" style="background: #e8f5e9">
                    <span class="card-title" style="color: var(--forest)">★ Penawaran Resmi Menunggu Keputusan Anda</span>
                </div>
                <div class="card-body">
                    <div style="text-align:center; padding: 12px 0 20px">
                        <span style="font-size:12px; color:var(--muted); text-transform:uppercase">Total Nilai Kontrak Penawaran</span>
                        <div style="font-size:36px; font-family:var(--serif); color:var(--forest); font-weight:700">
                            {{ $latestOffer->currency }} {{ number_format($latestOffer->total_amount, 2) }}
                        </div>
                        <div style="font-size:13px; color:var(--muted)">
                            Incoterms: <strong>{{ $latestOffer->trade_terms }}</strong> | Berlaku hingga: <strong>{{ $latestOffer->valid_until ? $latestOffer->valid_until->format('d M Y') : 'N/A' }}</strong>
                        </div>
                    </div>

                    <!-- Accept Action -->
                    <form method="POST" action="{{ route('buyer.quotations.respond', $quotation) }}" style="margin-bottom:16px">
                        @csrf
                        <input type="hidden" name="offer_id" value="{{ $latestOffer->id }}">
                        <input type="hidden" name="action" value="accept">
                        <button type="submit" class="btn btn-primary" style="width:100%; padding:14px; font-size:15px" onclick="return confirm('Apakah Anda menyetujui penawaran ini? Pesanan ekspor resmi (Order) akan langsung diterbitkan.')">
                            ✓ Setujui Penawaran & Terbitkan Pesanan
                        </button>
                    </form>

                    <!-- Counter Offer Collapsible -->
                    <details style="border:1px solid var(--line); border-radius:6px; padding:12px; margin-bottom:16px">
                        <summary style="font-weight:700; cursor:pointer; color:var(--forest)">Ajukan Negosiasi Ulang (Counter-Offer)</summary>
                        <form method="POST" action="{{ route('buyer.quotations.respond', $quotation) }}" style="margin-top:14px">
                            @csrf
                            <input type="hidden" name="offer_id" value="{{ $latestOffer->id }}">
                            <input type="hidden" name="action" value="counter">

                            @php $firstItem = $latestOffer->items->first(); @endphp
                            <div class="form-group">
                                <label class="form-label">Harga Satuan yang Diusulkan ({{ $latestOffer->currency }}) *</label>
                                <input type="number" step="0.01" name="counter_price" class="form-input" value="{{ $firstItem->unit_price ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Target Volume Pemesanan (MT) *</label>
                                <input type="number" step="0.01" name="counter_quantity" class="form-input" value="{{ $firstItem->quantity ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Alasan / Catatan Penawaran Balik</label>
                                <textarea name="notes" class="form-textarea" rows="2" placeholder="Jelaskan dasar harga target sesuai indeks pasar atau penyesuaian kuantitas..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-brass" style="width:100%">Kirim Counter-Offer ke Eksportir</button>
                        </form>
                    </details>

                    <!-- Reject Action -->
                    <form method="POST" action="{{ route('buyer.quotations.respond', $quotation) }}">
                        @csrf
                        <input type="hidden" name="offer_id" value="{{ $latestOffer->id }}">
                        <input type="hidden" name="action" value="reject">
                        <button type="submit" class="btn btn-secondary btn-sm" style="width:100%; color:#c62828" onclick="return confirm('Apakah Anda yakin ingin menolak penawaran ini?')">
                            Tolak Penawaran Ini
                        </button>
                    </form>
                </div>
            </div>
        @elseif($quotation->status === 'accepted')
            <div class="card" style="border: 1px solid var(--green); background: #fbfdfb">
                <div class="card-body" style="text-align:center; padding: 30px">
                    <div style="font-size:24px; color:var(--green); margin-bottom:8px">✓ Penawaran Telah Disetujui</div>
                    <p style="color:var(--muted); font-size:13px; margin-bottom:18px">
                        Penawaran ini telah resmi dikonfirmasi menjadi kontrak pesanan ekspor.
                    </p>
                    @if($quotation->order)
                        <a href="{{ route('buyer.orders.show', $quotation->order) }}" class="btn btn-primary">
                            Buka Pesanan {{ $quotation->order->order_number }} →
                        </a>
                    @endif
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body" style="text-align:center; padding: 30px; color:var(--muted)">
                    <div style="font-size:20px; color:var(--forest); margin-bottom:6px">Status: {{ ucfirst(str_replace('_',' ',$quotation->status)) }}</div>
                    <p style="font-size:13px">
                        Tidak ada tindakan yang diperlukan saat ini. Tim eksportir kami akan menerbitkan pembaruan penawaran sesegera mungkin.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
