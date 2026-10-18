@extends('layouts.public')

@section('content')
<style>
    .auth-page{display:grid;min-height:calc(100vh - 76px);place-items:center;padding:56px 0}
    .auth-card{width:min(100%,470px);padding:36px;border:1px solid var(--line);background:white}
    .auth-card h1{margin:12px 0 8px;color:var(--forest);font:400 42px/1 var(--serif);letter-spacing:-.05em}
    .auth-card>p{margin:0 0 26px;color:var(--muted);font-size:13px;line-height:1.7}
    .auth-form{display:grid;gap:16px}
    .auth-form label{display:grid;gap:7px;color:var(--forest);font-size:12px;font-weight:750}
    .auth-form input{height:46px;width:100%;padding:0 12px;border:1px solid var(--line);border-radius:3px;outline:none}
    .auth-form input:focus{border-color:var(--green);box-shadow:0 0 0 3px rgb(29 91 67 / .09)}
    .auth-error{margin:0;color:#9b2635;font-size:11px}
    .auth-note{margin-top:24px!important;padding-top:20px;border-top:1px solid var(--line);font-size:12px!important}
    .remember{display:flex!important;grid-template-columns:auto 1fr;align-items:center;gap:8px;color:var(--muted)!important;font-weight:500!important}
    .remember input{width:15px;height:15px}
</style>
<main class="container auth-page">
    <section class="auth-card">
        <p class="eyebrow">Akses Buyer & Eksportir</p>
        <h1>Selamat Datang.</h1>
        <p>Masuk ke akun Anda untuk mengelola pengajuan penawaran (RFQ), kontrak pesanan, dan pemantauan pengiriman.</p>
        
        @if($errors->any())
            <p class="auth-error">{{ $errors->first() }}</p>
        @endif
        
        <form class="auth-form" method="POST" action="{{ route('login') }}">
            @csrf
            <label>
                Alamat Email
                <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="nama@perusahaan.com" required autofocus>
            </label>
            <label>
                Kata Sandi
                <input type="password" name="password" autocomplete="current-password" placeholder="••••••••" required>
            </label>
            <label class="remember">
                <input type="checkbox" name="remember" value="1">
                Ingat saya di perangkat ini
            </label>
            <button class="button button-primary" type="submit">Masuk ke Sistem</button>
        </form>
        
        <p class="auth-note">Pembeli internasional baru? <a class="text-link" href="{{ route('register') }}">Daftar Akun Buyer →</a></p>
        <p class="auth-note" style="background:#fafafa; padding:12px; border-radius:4px">
            <strong>Akun Demo Seeder:</strong><br>
            • Admin: <code>admin@palmtrade.demo</code> / <code>password</code><br>
            • Buyer: <code>buyer@palmtrade.demo</code> / <code>password</code>
        </p>
    </section>
</main>
@endsection
