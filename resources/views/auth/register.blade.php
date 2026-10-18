@extends('layouts.public')

@section('content')
<style>
    .auth-page{display:grid;place-items:center;padding:56px 0}
    .auth-card{width:min(100%,650px);padding:36px;border:1px solid var(--line);background:white}
    .auth-card h1{margin:12px 0 8px;color:var(--forest);font:400 42px/1 var(--serif);letter-spacing:-.05em}
    .auth-card>p{margin:0 0 26px;color:var(--muted);font-size:13px;line-height:1.7}
    .auth-form{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    .auth-form label{display:grid;gap:7px;color:var(--forest);font-size:12px;font-weight:750}
    .auth-form label.full{grid-column:1/-1}
    .auth-form input,.auth-form textarea{width:100%;padding:12px;border:1px solid var(--line);border-radius:3px;outline:none}
    .auth-form input{height:46px}
    .auth-form textarea{min-height:88px;resize:vertical}
    .auth-form input:focus,.auth-form textarea:focus{border-color:var(--green);box-shadow:0 0 0 3px rgb(29 91 67 / .09)}
    .field-error{margin:0;color:#9b2635;font-size:11px}
    .auth-form button{grid-column:1/-1}
    .auth-note{margin-top:24px!important;padding-top:20px;border-top:1px solid var(--line);font-size:12px!important}
    @media(max-width:560px){.auth-card{padding:25px}.auth-form{grid-template-columns:1fr}}
</style>
<main class="container auth-page">
    <section class="auth-card">
        <p class="eyebrow">Registrasi Pembeli Internasional</p>
        <h1>Daftarkan Akun Perusahaan Anda.</h1>
        <p>Akun Buyer disediakan khusus bagi importir dan perusahaan industri untuk pengajuan penawaran harga resmi (RFQ) dan pemesanan. Akun administrator dikelola secara internal.</p>
        
        <form class="auth-form" method="POST" action="{{ route('register') }}">
            @csrf
            <label>
                Nama Lengkap PIC *
                <input name="name" value="{{ old('name') }}" autocomplete="name" placeholder="John Doe" required>
                @error('name')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label>
                Email Bisnis Resmi *
                <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="trade@company.com" required>
                @error('email')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label>
                Nama Perusahaan / Importir *
                <input name="company_name" value="{{ old('company_name') }}" autocomplete="organization" placeholder="Global Foods Ltd." required>
                @error('company_name')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label>
                Negara Domisili Perusahaan *
                <input name="country" value="{{ old('country') }}" autocomplete="country-name" placeholder="India, China, Netherlands..." required>
                @error('country')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label>
                Nomor Telepon / WhatsApp Bisnis *
                <input name="phone" value="{{ old('phone') }}" autocomplete="tel" placeholder="+1 234 567 890" required>
                @error('phone')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label>
                Buat Kata Sandi *
                <input type="password" name="password" autocomplete="new-password" placeholder="Minimal 8 karakter" required>
                @error('password')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label class="full">
                Konfirmasi Kata Sandi *
                <input type="password" name="password_confirmation" autocomplete="new-password" placeholder="Ulangi kata sandi" required>
            </label>
            <label class="full">
                Alamat Kantor Bisnis <span style="font-weight:400;color:var(--muted)">(Opsional)</span>
                <textarea name="business_address" autocomplete="street-address" placeholder="Alamat lengkap kantor pusat atau pergudangan...">{{ old('business_address') }}</textarea>
                @error('business_address')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <button class="button button-primary" type="submit">Daftar Akun Buyer Sekarang</button>
        </form>
        <p class="auth-note">Sudah memiliki akun terdaftar? <a class="text-link" href="{{ route('login') }}">Masuk di sini →</a></p>
    </section>
</main>
@endsection
