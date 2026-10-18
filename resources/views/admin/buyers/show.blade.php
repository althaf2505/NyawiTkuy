@extends('layouts.dashboard', ['breadcrumb' => 'Dossier Pembeli', 'title' => 'Pembeli: ' . $buyer->name])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $buyer->name }}</h1>
        <p class="page-subtitle">{{ $buyer->company_name ?? 'Importir Independen' }} · {{ $buyer->country ?? 'Internasional' }}</p>
    </div>
    <div>
        <a href="{{ route('admin.buyers.index') }}" class="btn btn-secondary">← Kembali ke Daftar Buyer</a>
    </div>
</div>

<div class="grid-2" style="align-items: flex-start">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Dossier Identitas & Kontak Perusahaan</span>
        </div>
        <div class="card-body" style="font-size:13px; line-height:2">
            <div>Nama Penanggung Jawab (PIC): <strong>{{ $buyer->name }}</strong></div>
            <div>Email Bisnis: <strong>{{ $buyer->email }}</strong></div>
            <div>Entitas Perusahaan: <strong>{{ $buyer->company_name ?? '-' }}</strong></div>
            <div>Negara Domisili: <strong>{{ $buyer->country ?? '-' }}</strong></div>
            <div>Telepon / WhatsApp: <strong>{{ $buyer->phone ?? '-' }}</strong></div>
            <div>Alamat Kantor / Gudang: <span>{{ $buyer->business_address ?? 'Tidak dicantumkan' }}</span></div>
            <div>Tanggal Terdaftar: <span>{{ $buyer->created_at->format('d M Y, H:i') }}</span></div>
        </div>
    </div>

    <div>
        <!-- Recent Orders -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">Riwayat Pesanan Terkini</span>
            </div>
            <div class="card-body">
                @forelse($buyer->orders as $o)
                    <div style="padding:10px; border:1px solid var(--line); border-radius:4px; margin-bottom:8px; display:flex; justify-content:space-between; align-items:center">
                        <div>
                            <strong>{{ $o->order_number }}</strong>
                            <div style="font-size:11px; color:var(--muted)">{{ $o->currency }} {{ number_format($o->total_amount, 2) }}</div>
                        </div>
                        <span class="badge badge-{{ $o->status }}">{{ $o->status }}</span>
                    </div>
                @empty
                    <p style="color:var(--muted); font-size:13px; text-align:center">Belum ada pesanan.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
