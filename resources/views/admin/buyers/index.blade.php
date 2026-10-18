@extends('layouts.dashboard', ['breadcrumb' => 'Daftar Buyer', 'title' => 'Pembeli Terdaftar'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Direktori Pembeli Internasional</h1>
        <p class="page-subtitle">Daftar entitas importir, kontak penanggung jawab perdagangan, dan riwayat enquiry komersial.</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('admin.buyers.index') }}" style="display:flex; gap:12px; align-items:center; width:100%">
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama buyer, perusahaan, email, negara..." class="form-input" style="max-width:320px">
            <button type="submit" class="btn btn-secondary btn-sm">Cari</button>
            @if($search)
                <a href="{{ route('admin.buyers.index') }}" class="btn btn-secondary btn-sm" style="color:var(--muted)">Reset</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Pembeli</th>
                    <th>Nama Perusahaan</th>
                    <th>Negara</th>
                    <th>Kontak / WhatsApp</th>
                    <th>Total RFQ</th>
                    <th>Total Pesanan</th>
                    <th>Tanggal Bergabung</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($buyers as $buyer)
                    <tr>
                        <td>
                            <strong>{{ $buyer->name }}</strong>
                            <div style="font-size:11px; color:var(--muted)">{{ $buyer->email }}</div>
                        </td>
                        <td>{{ $buyer->company_name ?? '-' }}</td>
                        <td>{{ $buyer->country ?? '-' }}</td>
                        <td>{{ $buyer->phone ?? '-' }}</td>
                        <td><span class="badge badge-submitted">{{ $buyer->quotations_count }}</span></td>
                        <td><span class="badge badge-accepted">{{ $buyer->orders_count }}</span></td>
                        <td>{{ $buyer->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.buyers.show', $buyer) }}" class="btn btn-secondary btn-sm">
                                Lihat Profil →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:40px; color:var(--muted)">
                            Belum ada pembeli internasional yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($buyers->hasPages())
        <div style="padding:16px 24px; border-top:1px solid var(--line)">
            {{ $buyers->links() }}
        </div>
    @endif
</div>
@endsection
