@extends('layouts.app')
@section('title','Connexion — Thé Tip Top')

@section('page_styles')
.auth-wrap{position:relative;z-index:1;padding:2.5rem 3rem 5rem;max-width:1050px;margin:0 auto}
.auth-subtitle{text-align:center;margin-bottom:2.5rem}
.auth-grid{display:grid;grid-template-columns:1fr 1fr;gap:2rem;align-items:start}
.auth-img{border-radius:18px;overflow:hidden;height:380px;background:linear-gradient(135deg,#2d5a27,#1e3d1a);display:flex;align-items:center;justify-content:center}
.auth-card{background:var(--white);border-radius:18px;box-shadow:var(--sh);padding:2.5rem}
.auth-card-title{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;text-align:center;margin-bottom:1.6rem}
.input-has-eye{position:relative}
.input-has-eye input{padding-right:2.8rem}
.eye-btn{position:absolute;right:.8rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--txt-l);padding:4px;transition:color .2s;line-height:1}
.eye-btn:hover{color:var(--txt)}
.divider{display:flex;align-items:center;gap:1rem;margin:1.2rem 0;color:var(--txt-l);font-size:.83rem}
.divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--cream-d)}
.oauth-btn{display:flex;align-items:center;gap:.8rem;width:100%;padding:.7rem 1.2rem;border-radius:10px;border:1.5px solid var(--cream-d);background:var(--white);font-family:'Jost',sans-serif;font-size:.86rem;font-weight:500;cursor:pointer;transition:var(--t);margin-bottom:.8rem;text-decoration:none;color:var(--txt)}
.oauth-btn:hover{border-color:var(--green-m);background:var(--cream);transform:translateY(-1px)}
@endsection

@section('content')

{{-- Banner --}}
<section class="page-banner fade-up">
    @include('partials.ornament')
    <div style="text-align:center;flex:1"><span class="banner-tape">Connexion</span></div>
    @include('partials.ornament', ['flip' => true])
</section>

<div class="auth-wrap">
    <div class="auth-subtitle fade-up">
        <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;margin-bottom:.5rem">Pause Thé !</h2>
        <p style="color:var(--txt-m);font-size:.9rem">Connecte-toi pour consulter tes lots et suivre tes gains.</p>
    </div>

    <div class="auth-grid fade-up s1">
        {{-- Form --}}
        <div class="auth-card">
            <div class="auth-card-title">Formulaire de connexion</div>

            @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" id="loginForm" novalidate>
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Identifiant</label>
                    <input type="email" name="email" id="email" class="input-field"
                        placeholder="Votre mail.."
                        value="{{ old('email') }}" required autocomplete="email">
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Mot de passe</label>
                    <div class="input-has-eye">
                        <input type="password" name="password" id="password" class="input-field"
                            placeholder="Mot de passe.."
                            required autocomplete="current-password">
                        <button type="button" class="eye-btn" onclick="toggleEye('password',this)" aria-label="Afficher">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>
                <p style="font-size:.83rem;color:var(--txt-m);margin-bottom:1.4rem">
                    Vous n'avez pas de compte ?
                    <a href="{{ route('register.post') }}" style="color:var(--orange);font-weight:600;text-decoration:none"> S'inscrire</a>
                </p>
                <div style="text-align:center">
                    <button type="submit" class="btn btn-orange" id="loginBtn" style="min-width:180px">
                        Se connecter
                    </button>
                </div>
            </form>

            <div class="divider">ou continuer avec</div>
            <a href="{{ route('auth.google') }}" class="oauth-btn">
                <svg viewBox="0 0 24 24" width="20"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                Continuer avec Google
            </a>
            <a href="{{ route('auth.facebook') }}" class="oauth-btn">
                <svg viewBox="0 0 24 24" width="20" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                Continuer avec Facebook
            </a>
        </div>

        {{-- Image --}}
        <div class="auth-img">
            <svg viewBox="0 0 300 380" fill="none" width="280">
                <!-- Person silhouette holding cup -->
                <rect x="80" y="300" width="140" height="80" rx="20" fill="rgba(255,255,255,.08)"/>
                <!-- Cup -->
                <path d="M130 200Q128 230,150 238Q172 230,170 200Z" fill="rgba(255,255,255,.2)"/>
                <ellipse cx="150" cy="200" rx="20" ry="7" fill="rgba(255,255,255,.15)"/>
                <path d="M170 208Q185 208,185 216Q185 224,170 223" stroke="rgba(255,255,255,.3)" stroke-width="4" fill="none" stroke-linecap="round"/>
                <!-- Steam -->
                <path d="M143 190C141 182,145 175,143 167" stroke="rgba(255,255,255,.4)" stroke-width="2" fill="none" stroke-linecap="round"/>
                <path d="M150 188C150 180,154 173,150 165" stroke="rgba(255,255,255,.3)" stroke-width="2" fill="none" stroke-linecap="round"/>
                <path d="M157 190C158 182,155 175,157 167" stroke="rgba(255,255,255,.4)" stroke-width="2" fill="none" stroke-linecap="round"/>
                <!-- Brand box -->
                <rect x="100" y="252" width="100" height="35" rx="8" fill="rgba(212,180,74,.2)" stroke="rgba(212,180,74,.4)" stroke-width="1"/>
                <text x="150" y="266" text-anchor="middle" fill="rgba(255,255,255,.6)" font-size="8" font-family="Jost">THÉ TIP TOP</text>
                <text x="150" y="278" text-anchor="middle" fill="rgba(212,180,74,.7)" font-size="7" font-family="Jost">Thé Tip Top</text>
            </svg>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleEye(id, btn) {
    const inp = document.getElementById(id);
    inp.type = inp.type === 'text' ? 'password' : 'text';
}
document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.textContent = '⏳ Connexion…';
    btn.disabled = true;
});
</script>
@endpush
@endsection