@extends('layouts.dashboard', ['breadcrumb' => 'Manajemen Penawaran', 'title' => 'Permintaan RFQ Masuk'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Permintaan Penawaran (RFQ) dari Pembeli</h1>
        <p class="page-subtitle">Tinjau inquiry komoditas internasional, rumuskan harga ekspor resmi, dan kelola versi penawaran negosiasi.</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('admin.quotations.index') }}" style="display:flex; gap:12px; align-items:center; width:100%">
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari nomor RFQ, nama buyer, perusahaan..." class="form-input" style="max-width:300px">
            <select name="status" class="form-select" style="max-width:180px" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="submitted" {{ $status === 'submitted' ? 'selected' : '' }}>Diajukan (Baru Masuk)</option>
                <option value="under_review" {{ $status === 'under_review' ? 'selected' : '' }}>Dalam Tinjauan / Ada Counter</option>
                <option value="quoted" {{ $status === 'quoted' ? 'selected' : '' }}>Penawaran Terkirim</option>
                <option value="accepted" {{ $status === 'accepted' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            @if($search || $status)
                <a href="{{ route('admin.quotations.index') }}" class="btn btn-secondary btn-sm" style="color:var(--muted)">Reset</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Referensi RFQ</th>
                    <th>Pembeli & Perusahaan</th>
                    <th>Pelabuhan Tujuan</th>
                    <th>Kargo Diminta</th>
                    <th>Penawaran Terakhir</th>
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
                            <strong>{{ $quotation->user ? $quotation->user->name : 'N/A' }}</strong>
                            <div style="font-size:11px; color:var(--muted)">{{ $quotation->user ? $quotation->user->company_name : '-' }} ({{ $quotation->user ? $quotation->user->country : '-' }})</div>
                        </td>
                        <td>{{ $quotation->destination_country }} {{ $quotation->destination_port ? '('.$quotation->destination_port.')' : '' }}</td>
                        <td>
                            <div>{{ $firstItem && $firstItem->product ? $firstItem->product->name : 'N/A' }}</div>
                            <small style="color:var(--muted)">{{ number_format($firstItem->quantity ?? 0) }} {{ $firstItem->unit ?? 'MT' }}</small>
                        </td>
                        <td>
                            @if($quotation->latestOffer)
                                <strong>{{ $quotation->latestOffer->currency }} {{ number_format($quotation->latestOffer->total_amount, 2) }}</strong>
                                <small style="display:block; color:var(--muted)">Versi {{ $quotation->latestOffer->version }} ({{ $quotation->latestOffer->offered_by }})</small>
                            @else
                                <span class="badge badge-submitted">Menunggu Penawaran</span>
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
                                    default => str_replace('_', ' ', $quotation->status),
                                };
                            @endphp
                            <span class="badge badge-{{ $quotation->status }}">{{ $statusIndo }}</span>
                        </td>
                        <td>{{ $quotation->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.quotations.show', $quotation) }}" class="btn btn-secondary btn-sm">
                                Tinjau & Buat Penawaran →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:40px; color:var(--muted)">
                            Belum ada permintaan penawaran harga masuk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($quotations->hasPages())
        <div style="padding:16px 24px; border-top:1px solid var(--line)">
            {{ $quotations->links() }}
        </div>
    @endif
</div>
@endsection
