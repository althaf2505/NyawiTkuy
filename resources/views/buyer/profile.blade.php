@extends('layouts.dashboard', ['breadcrumb' => 'Profil Perusahaan', 'title' => 'Profil Pembeli'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Profil Perusahaan & Pengaturan Akun</h1>
        <p class="page-subtitle">Pastikan kredensial legal entitas perusahaan dan kontak Anda selalu terbarui untuk kelancaran penerbitan dokumen ekspor.</p>
    </div>
</div>

<div class="grid-2">
    <!-- Company Information -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">Identitas Legal Entitas Usaha</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('buyer.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Nama Lengkap PIC (Penanggung Jawab) *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Perusahaan Terdaftar *</label>
                    <input type="text" name="company_name" class="form-input" value="{{ old('company_name', $user->company_name) }}" required>
                    @error('company_name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Negara Domisili Perusahaan *</label>
                        <input type="text" name="country" class="form-input" value="{{ old('country', $user->country) }}" required>
                        @error('country') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telepon Bisnis / WhatsApp *</label>
                        <input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" required>
                        @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Lengkap Kantor / Fasilitas Penyimpanan</label>
                    <textarea name="business_address" class="form-textarea" rows="3">{{ old('business_address', $user->business_address) }}</textarea>
                    @error('business_address') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Pembaruan Profil</button>
            </form>
        </div>
    </div>

    <!-- Password Security -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">Keamanan Akun & Kata Sandi</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('buyer.profile.password') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Kata Sandi Saat Ini *</label>
                    <input type="password" name="current_password" class="form-input" required>
                    @error('current_password') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kata Sandi Baru *</label>
                    <input type="password" name="password" class="form-input" required>
                    @error('password') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Kata Sandi Baru *</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>

                <button type="submit" class="btn btn-secondary">Perbarui Kata Sandi</button>
            </form>
        </div>
    </div>
</div>
@endsection
