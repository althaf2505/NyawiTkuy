@extends('layouts.dashboard', ['breadcrumb' => 'Rincian Pesanan', 'title' => 'Pesanan ' . $order->order_number])

@section('content')
<div class="page-header">
    <div>
        <div style="display:flex; gap:10px; align-items:center; margin-bottom:8px">
            <h1 class="page-title">{{ $order->order_number }}</h1>
            @php
                $statusIndo = match($order->status) {
                    'awaiting_payment' => 'Menunggu Pembayaran',
                    'processing' => 'Sedang Diproses',
                    'shipped' => 'Dalam Pengiriman',
                    'completed' => 'Selesai / Terkirim',
                    'cancelled' => 'Dibatalkan',
                    default => str_replace('_', ' ', $order->status),
                };
            @endphp
            <span class="badge badge-{{ $order->status }}">{{ $statusIndo }}</span>
        </div>
        <p class="page-subtitle">Diterbitkan pada {{ $order->created_at->format('d M Y, H:i') }} · Referensi RFQ: {{ $order->quotation ? $order->quotation->quotation_number : '-' }}</p>
    </div>
    <div>
        <a href="{{ route('buyer.orders.index') }}" class="btn btn-secondary">← Kembali ke Daftar Pesanan</a>
    </div>
</div>

<div class="grid-2" style="align-items: flex-start">
    <div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Rincian Komoditas yang Dikontrakkan</span>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Snapshot Produk</th>
                            <th>Kuantitas</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product_name }}</strong>
                                    <div style="font-size:11px; color:var(--muted)">SKU: {{ $item->product_sku }}</div>
                                </td>
                                <td>{{ number_format($item->quantity) }} {{ $item->unit }}</td>
                                <td>{{ $order->currency }} {{ number_format($item->unit_price, 2) }}</td>
                                <td><strong>{{ $order->currency }} {{ number_format($item->total_price, 2) }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#fafafa">
                            <td colspan="3" style="text-align:right"><strong>Total Nilai Kontrak:</strong></td>
                            <td><strong style="font-size:16px; color:var(--forest)">{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div style="padding:16px 24px; font-size:12px; color:var(--muted); border-top:1px solid var(--line)">
                Syarat Dagang (Incoterms): <strong>{{ $order->trade_terms ?? 'FOB' }}</strong> | Ketentuan Pembayaran: <strong>{{ $order->payment_terms ?? 'L/C at sight' }}</strong>
            </div>
        </div>

        <!-- Shipments Info if available -->
        @if($order->shipments->isNotEmpty())
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Pengiriman & Logistik Terkait</span>
                </div>
                <div class="card-body">
                    @foreach($order->shipments as $shp)
                        <div style="display:flex; justify-content:space-between; align-items:center; padding:12px; border:1px solid var(--line); border-radius:6px; margin-bottom:8px">
                            <div>
                                <strong>{{ $shp->shipment_number }}</strong>
                                <div style="font-size:12px; color:var(--muted)">{{ $shp->origin }} → {{ $shp->destination }} ({{ $shp->carrier ?? 'Kapal dalam konfirmasi' }})</div>
                            </div>
                            <div>
                                <span class="badge badge-{{ $shp->status }}">{{ $shp->status }}</span>
                                <a href="{{ route('buyer.shipments.show', $shp) }}" class="btn btn-secondary btn-sm" style="margin-left:8px">Lacak Pelayaran →</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Right Column: Payments -->
    <div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Riwayat & Verifikasi Pembayaran</span>
            </div>
            <div class="card-body">
                @forelse($order->payments as $payment)
                    <div style="padding:14px; border:1px solid var(--line); border-radius:6px; margin-bottom:12px; font-size:13px">
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px">
                            <strong>{{ $payment->payment_number }}</strong>
                            @php
                                $payStatusIndo = match($payment->status) {
                                    'submitted' => 'Menunggu Verifikasi',
                                    'verified' => 'Terverifikasi Sah',
                                    'rejected' => 'Ditolak',
                                    'pending' => 'Tertunda',
                                    default => $payment->status,
                                };
                            @endphp
                            <span class="badge badge-{{ $payment->status }}">{{ $payStatusIndo }}</span>
                        </div>
                        <div>Jumlah: <strong>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</strong></div>
                        <div style="color:var(--muted); font-size:12px">Metode: {{ $payment->method }} · No. Ref: {{ $payment->reference }}</div>
                        @if($payment->proof_file)
                            <div style="margin-top:6px">
                                <a href="{{ asset('storage/' . $payment->proof_file) }}" target="_blank" class="btn btn-secondary btn-sm" style="font-size:11px">
                                    Buka Bukti Transfer / L/C ↗
                                </a>
                            </div>
                        @endif
                        @if($payment->rejection_reason)
                            <div style="margin-top:6px; font-size:11px; color:#c62828">
                                Alasan penolakan: {{ $payment->rejection_reason }}
                            </div>
                        @endif
                    </div>
                @empty
                    <p style="color:var(--muted); font-size:13px; text-align:center; padding:12px 0">
                        Belum ada dokumen bukti transfer/pembayaran yang dikirimkan.
                    </p>
                @endforelse

                @if($order->status === 'awaiting_payment')
                    <div style="margin-top:20px; padding-top:20px; border-top:1px solid var(--line)">
                        <h3 style="font-size:14px; color:var(--forest); margin-bottom:12px">Unggah Bukti Remittance (T/T atau L/C)</h3>
                        <form method="POST" action="{{ route('buyer.orders.payment', $order) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="currency" value="{{ $order->currency }}">
                            <div class="form-group">
                                <label class="form-label">Nominal Pembayaran ({{ $order->currency }}) *</label>
                                <input type="number" step="0.01" name="amount" class="form-input" value="{{ $order->total_amount }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Metode Pembayaran *</label>
                                <select name="method" class="form-select" required>
                                    <option value="Telegraphic Transfer (T/T)">Telegraphic Transfer (T/T Swift MT103)</option>
                                    <option value="Letter of Credit (L/C)">Letter of Credit (L/C at sight)</option>
                                    <option value="Bank Wire">Bank Wire Transfer</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nomor Referensi Transaksi / Swift Ref *</label>
                                <input type="text" name="reference" class="form-input" placeholder="Contoh: SWIFT Ref MT103 atau Nomor L/C Bank" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">File Bukti Dokumen (PDF, JPG, PNG maks 5MB) *</label>
                                <input type="file" name="proof_file" class="form-input" required accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Catatan Tambahan untuk Tim Keuangan</label>
                                <textarea name="notes" class="form-textarea" rows="2" placeholder="Nama bank penerbit, tanggal perkiraan dana efektif..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width:100%">Kirim Bukti Pembayaran untuk Diverifikasi</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
