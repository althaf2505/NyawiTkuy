@extends('layouts.dashboard', ['breadcrumb' => 'Verifikasi Pembayaran', 'title' => 'Pembayaran Masuk'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Verifikasi Pembayaran Ekspor</h1>
        <p class="page-subtitle">Periksa bukti transfer telegrafik (T/T Swift MT103) atau Letter of Credit (L/C), lalu sahkan untuk melanjutkan kargo ke pemrosesan.</p>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nomor Pembayaran</th>
                    <th>Nomor Pesanan</th>
                    <th>Pembeli & Perusahaan</th>
                    <th>Nominal</th>
                    <th>Metode & Referensi</th>
                    <th>Status</th>
                    <th>Tanggal Masuk</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td><strong>{{ $payment->payment_number }}</strong></td>
                        <td>{{ $payment->order ? $payment->order->order_number : '-' }}</td>
                        <td>
                            <strong>{{ $payment->order && $payment->order->user ? $payment->order->user->name : 'N/A' }}</strong>
                            <div style="font-size:11px; color:var(--muted)">{{ $payment->order && $payment->order->user ? $payment->order->user->company_name : '-' }}</div>
                        </td>
                        <td><strong>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</strong></td>
                        <td>
                            <div>{{ $payment->method }}</div>
                            <small style="color:var(--muted)">Ref: {{ $payment->reference }}</small>
                        </td>
                        <td>
                            @php
                                $statusPayIndo = match($payment->status) {
                                    'submitted' => 'Menunggu Verifikasi',
                                    'verified' => 'Terverifikasi Sah',
                                    'rejected' => 'Ditolak',
                                    default => $payment->status,
                                };
                            @endphp
                            <span class="badge badge-{{ $payment->status }}">{{ $statusPayIndo }}</span>
                        </td>
                        <td>{{ $payment->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-secondary btn-sm">
                                Periksa Bukti →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:40px; color:var(--muted)">
                            Belum ada dokumen pembayaran yang perlu diverifikasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
        <div style="padding:16px 24px; border-top:1px solid var(--line)">
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection
