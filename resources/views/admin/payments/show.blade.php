@extends('layouts.dashboard', ['breadcrumb' => 'Verifikasi Bukti Bayar', 'title' => 'Verifikasi ' . $payment->payment_number])

@section('content')
<div class="page-header">
    <div>
        <div style="display:flex; gap:10px; align-items:center; margin-bottom:8px">
            <h1 class="page-title">{{ $payment->payment_number }}</h1>
            @php
                $statusPayIndo = match($payment->status) {
                    'submitted' => 'Menunggu Verifikasi',
                    'verified' => 'Terverifikasi Sah',
                    'rejected' => 'Ditolak',
                    default => $payment->status,
                };
            @endphp
            <span class="badge badge-{{ $payment->status }}">{{ $statusPayIndo }}</span>
        </div>
        <p class="page-subtitle">Diunggah pada {{ $payment->created_at->format('d M Y, H:i') }} · Pesanan Terkait: <strong>{{ $payment->order ? $payment->order->order_number : '-' }}</strong></p>
    </div>
    <div>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">← Kembali ke Pembayaran</a>
    </div>
</div>

<div class="grid-2" style="align-items: flex-start">
    <div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Informasi Remittance / Dana Masuk</span>
            </div>
            <div class="card-body" style="font-size:13px; line-height:1.9">
                <div>Nominal yang Diklaim: <strong style="font-size:16px; color:var(--forest)">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</strong></div>
                <div>Metode Pembayaran: <strong>{{ $payment->method }}</strong></div>
                <div>Nomor Referensi Transaksi / Swift: <strong>{{ $payment->reference }}</strong></div>
                <div>Entitas Pengirim (Buyer): <strong>{{ $payment->order && $payment->order->user ? $payment->order->user->company_name : '-' }}</strong></div>
                <div>Email Kontak Buyer: {{ $payment->order && $payment->order->user ? $payment->order->user->email : '-' }}</div>
                @if($payment->notes)
                    <div style="margin-top:10px; padding:10px; background:#fafafa; border-radius:4px">
                        <strong>Catatan Pengirim:</strong> {{ $payment->notes }}
                    </div>
                @endif
                @if($payment->verified_by)
                    <div style="margin-top:12px; padding-top:12px; border-top:1px solid var(--line); color:var(--muted)">
                        Diverifikasi oleh: {{ $payment->verifier ? $payment->verifier->name : 'Staff Keuangan' }} pada {{ $payment->verified_at ? $payment->verified_at->format('d M Y, H:i') : '' }}
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Lampiran Bukti Slip Bank / Swift L/C</span>
            </div>
            <div class="card-body">
                @if($payment->proof_file)
                    <div style="margin-bottom:14px">
                        <a href="{{ asset('storage/' . $payment->proof_file) }}" target="_blank" class="btn btn-secondary" style="width:100%">
                            Buka Dokumen Bukti Ukuran Penuh ↗
                        </a>
                    </div>
                    @php $ext = pathinfo($payment->proof_file, PATHINFO_EXTENSION); @endphp
                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                        <div style="border:1px solid var(--line); border-radius:6px; overflow:hidden">
                            <img src="{{ asset('storage/' . $payment->proof_file) }}" alt="Bukti pembayaran" style="width:100%; display:block">
                        </div>
                    @else
                        <p style="color:var(--muted); font-size:13px">Dokumen berupa file PDF. Klik tombol di atas untuk membuka dan mengunduh slip.</p>
                    @endif
                @else
                    <p style="color:var(--muted); font-size:13px">Tidak ada berkas bukti yang diunggah.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Action Card -->
    <div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Keputusan Verifikasi Dana</span>
            </div>
            <div class="card-body">
                @if($payment->status === 'submitted' || $payment->status === 'pending')
                    <form method="POST" action="{{ route('admin.payments.verify', $payment) }}" style="margin-bottom:16px">
                        @csrf
                        <input type="hidden" name="action" value="verify">
                        <button type="submit" class="btn btn-primary" style="width:100%; padding:12px; font-size:14px" onclick="return confirm('Sahkan pembayaran ini? Status pesanan akan otomatis berganti menjadi Sedang Diproses.')">
                            ✓ Sahkan & Konfirmasi Dana Masuk
                        </button>
                    </form>

                    <details style="border:1px solid var(--line); border-radius:6px; padding:12px">
                        <summary style="font-weight:700; color:#c62828; cursor:pointer">Tolak Bukti Pembayaran Ini</summary>
                        <form method="POST" action="{{ route('admin.payments.verify', $payment) }}" style="margin-top:12px">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <div class="form-group">
                                <label class="form-label">Alasan Penolakan Bukti *</label>
                                <textarea name="rejection_reason" class="form-textarea" rows="2" placeholder="Contoh: Dana belum efektif kliring di rekening bank, nomor L/C tidak cocok..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger btn-sm" style="width:100%">Konfirmasi Penolakan</button>
                        </form>
                    </details>
                @else
                    <div style="text-align:center; padding:20px; color:var(--muted)">
                        Pembayaran ini telah berstatus: <strong>{{ $payment->status }}</strong>.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
