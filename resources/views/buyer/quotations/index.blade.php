@extends('layouts.dashboard', ['breadcrumb' => 'Daftar Penawaran', 'title' => 'Penawaran Saya'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Permintaan Penawaran Harga (RFQ)</h1>
        <p class="page-subtitle">Pantau penawaran masuk dari eksportir, lakukan negosiasi harga, dan lanjutkan ke pesanan.</p>
    </div>
    <div>
        <a href="{{ route('buyer.quotations.create') }}" class="btn btn-primary">+ Ajukan RFQ Baru</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('buyer.quotations.index') }}" style="display:flex; gap:12px; align-items:center; width:100%">
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari nomor RFQ atau negara..." class="form-input" style="max-width:300px">
            <select name="status" class="form-select" style="max-width:180px" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="submitted" {{ $status === 'submitted' ? 'selected' : '' }}>Diajukan</option>
                <option value="under_review" {{ $status === 'under_review' ? 'selected' : '' }}>Dalam Peninjauan</option>
                <option value="quoted" {{ $status === 'quoted' ? 'selected' : '' }}>Ada Penawaran Masuk</option>
                <option value="accepted" {{ $status === 'accepted' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            @if($search || $status)
                <a href="{{ route('buyer.quotations.index') }}" class="btn btn-secondary btn-sm" style="color:var(--muted)">Reset</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nomor RFQ</th>
                    <th>Komoditas & Volume</th>
                    <th>Tujuan Ekspor</th>
                    <th>Penawaran Terkini</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotations as $quotation)
                    @php $firstItem = $quotation->items->first(); @endphp
                    <tr>
                        <td><strong>{{ $quotation->quotation_number }}</strong></td>
                        <td>
                            <div>{{ $firstItem && $firstItem->product ? $firstItem->product->name : 'N/A' }}</div>
                            <small style="color:var(--muted)">{{ number_format($firstItem->quantity ?? 0) }} {{ $firstItem->unit ?? 'MT' }}</small>
                        </td>
                        <td>{{ $quotation->destination_country }} {{ $quotation->destination_port ? '('.$quotation->destination_port.')' : '' }}</td>
                        <td>
                            @if($quotation->latestOffer)
                                <strong>{{ $quotation->latestOffer->currency }} {{ number_format($quotation->latestOffer->total_amount, 2) }}</strong>
                                <small style="display:block; color:var(--muted)">Versi {{ $quotation->latestOffer->version }} (Oleh {{ $quotation->latestOffer->offered_by === 'admin' ? 'Eksportir' : 'Anda' }})</small>
                            @else
                                <span style="color:var(--muted)">Menunggu Eksportir</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusIndo = match($quotation->status) {
                                    'submitted' => 'Diajukan',
                                    'under_review' => 'Dalam Peninjauan',
                                    'quoted' => 'Penawaran Siap',
                                    'accepted' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    'cancelled' => 'Dibatalkan',
                                    default => str_replace('_', ' ', $quotation->status),
                                };
                            @endphp
                            <span class="badge badge-{{ $quotation->status }}">{{ $statusIndo }}</span>
                        </td>
                        <td>{{ $quotation->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('buyer.quotations.show', $quotation) }}" class="btn btn-secondary btn-sm">
                                Tinjau & Respon →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding: 40px; color:var(--muted)">
                            Belum ada pengajuan penawaran. Mulai dengan mengajukan RFQ dari katalog produk.
                            <div style="margin-top:12px">
                                <a href="{{ route('buyer.quotations.create') }}" class="btn btn-primary btn-sm">+ Ajukan RFQ</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($quotations->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--line)">
            {{ $quotations->links() }}
        </div>
    @endif
</div>
@endsection
