@extends('layouts.app')
@section('title','Inscription — Thé Tip Top')

@section('page_styles')
.reg-wrap{position:relative;z-index:1;padding:2.5rem 3rem 5rem;max-width:1050px;margin:0 auto}
.reg-grid{display:grid;grid-template-columns:1fr 1fr;gap:2rem;align-items:start}
.reg-img{border-radius:18px;overflow:hidden;min-height:500px;background:linear-gradient(145deg,#2d5a27 0%,#1e3d1a 100%);position:relative;display:flex;align-items:flex-end}
.reg-card{background:var(--white);border-radius:18px;box-shadow:var(--sh);padding:2.5rem}
.reg-card-title{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;text-align:center;margin-bottom:1.5rem}
.strength-bars{display:flex;gap:4px;margin-top:.35rem}
.strength-bar{flex:1;height:3px;border-radius:2px;background:var(--cream-d);transition:background .3s}
.checkbox-row{display:flex;align-items:flex-start;gap:.7rem;cursor:pointer;font-size:.86rem;color:var(--txt-m);line-height:1.5;margin-bottom:1.4rem}
.checkbox-row input{width:18px;height:18px;flex-shrink:0;margin-top:1px;accent-color:var(--orange);cursor:pointer}
@endsection

@section('content')

{{-- Banner --}}
<section class="page-banner fade-up">
    @include('partials.ornament')
    <div style="text-align:center;flex:1"><span class="banner-tape">Inscription</span></div>
    @include('partials.ornament', ['flip' => true])
</section>

<div class="reg-wrap">
    <div style="text-align:center;margin-bottom:2rem" class="fade-up">
        <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;margin-bottom:.5rem">Bienvenue dans l'aventure Thé Tip Top</h2>
        <p style="color:var(--txt-m);font-size:.9rem">Crée ton compte, tente ta chance, et découvre tes lots.</p>
    </div>

    <div class="reg-grid fade-up s1">
        {{-- Image side --}}
        <div class="reg-img">
            <svg viewBox="0 0 300 500" fill="none" width="100%" style="position:absolute;inset:0;width:100%;height:100%">
                <!-- Gift boxes -->
                <rect x="40" y="280" width="100" height="90" rx="10" fill="rgba(212,180,74,.2)" stroke="rgba(212,180,74,.35)" stroke-width="1.5"/>
                <rect x="40" y="280" width="100" height="22" rx="4" fill="rgba(212,180,74,.3)"/>
                <line x1="90" y1="280" x2="90" y2="370" stroke="rgba(212,180,74,.4)" stroke-width="2"/>
                <rect x="155" y="300" width="110" height="70" rx="10" fill="rgba(212,180,74,.18)" stroke="rgba(212,180,74,.3)" stroke-width="1.5"/>
                <rect x="155" y="300" width="110" height="18" rx="4" fill="rgba(212,180,74,.25)"/>
                <!-- Tea cans -->
                <rect x="65" y="195" width="55" height="78" rx="10" fill="rgba(255,255,255,.12)"/>
                <ellipse cx="92" cy="195" rx="27" ry="9" fill="rgba(255,255,255,.15)"/>
                <rect x="170" y="210" width="50" height="70" rx="10" fill="rgba(255,255,255,.1)"/>
                <!-- Brand logos on cans -->
                <text x="92" y="240" text-anchor="middle" fill="rgba(212,180,74,.5)" font-size="7" font-family="Jost">TIP TOP</text>
                <!-- Person hands -->
                <path d="M80 390Q75 380,90 375Q105 370,110 380Q120 395,100 400Z" fill="rgba(255,255,255,.15)"/>
                <path d="M200 400Q195 390,210 385Q225 380,230 390Q240 405,220 410Z" fill="rgba(255,255,255,.15)"/>
            </svg>
        </div>

        {{-- Form --}}
        <div class="reg-card">
            <div class="reg-card-title">Inscrivez-vous au Jeu-concours !</div>

            @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="regForm" novalidate>
                @csrf
                <div style="margin-bottom:1.1rem">
                    <input type="text" name="last_name" class="input-field" placeholder="Nom"
                        value="{{ old('last_name') }}" required autocomplete="family-name">
                    @error('last_name')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div style="margin-bottom:1.1rem">
                    <input type="text" name="first_name" class="input-field" placeholder="Prénom"
                        value="{{ old('first_name') }}" required autocomplete="given-name">
                    @error('first_name')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div style="margin-bottom:1.1rem">
                    <input type="email" name="email" class="input-field" placeholder="Email"
                        value="{{ old('email') }}" required autocomplete="email">
                    @error('email')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div style="margin-bottom:.4rem">
                    <input type="password" name="password" id="regPassword" class="input-field"
                        placeholder="Mot de passe" required autocomplete="new-password"
                        oninput="checkStrength(this.value)">
                    @error('password')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="strength-bars" style="margin-bottom:1.1rem">
                    <div class="strength-bar" id="sb1"></div>
                    <div class="strength-bar" id="sb2"></div>
                    <div class="strength-bar" id="sb3"></div>
                    <div class="strength-bar" id="sb4"></div>
                </div>
                <div style="margin-bottom:1.3rem">
                    <input type="password" name="password_confirmation" class="input-field"
                        placeholder="Confirmez mot de passe" required autocomplete="new-password">
                </div>
                <label class="checkbox-row">
                    <input type="checkbox" name="terms" id="termsCheck" required onchange="toggleSubmit()">
                    <span>J'accepte les <a href="{{ route('pages.cgv') }}" style="color:var(--orange)">conditions</a> générales d'utilisation</span>
                </label>
                <div style="text-align:center">
                    <button type="submit" class="btn btn-orange" id="regBtn" style="min-width:200px">
                        Créer mon compte
                    </button>
                </div>
                <p style="text-align:center;margin-top:1.2rem;font-size:.84rem;color:var(--txt-m)">
                    Déjà un compte ?
                    <a href="{{ route('login') }}" style="color:var(--orange);font-weight:600;text-decoration:none"> Se connecter</a>
                </p>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function checkStrength(v) {
    const s = ['sb1','sb2','sb3','sb4'];
    const c = ['#e74c3c','#e67e22','#f1c40f','#27ae60'];
    let score = 0;
    if (v.length >= 8) score++;
    if (/[A-Z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;
    s.forEach((id, i) => {
        document.getElementById(id).style.background = i < score ? c[score-1] : 'var(--cream-d)';
    });
}
function toggleSubmit() {
    const btn = document.getElementById('regBtn');
    btn.disabled = !document.getElementById('termsCheck').checked;
    btn.style.opacity = document.getElementById('termsCheck').checked ? '1' : '.55';
}
document.getElementById('regBtn').disabled = true;
document.getElementById('regBtn').style.opacity = '.55';
document.getElementById('regForm').addEventListener('submit', function() {
    const btn = document.getElementById('regBtn');
    btn.textContent = '⏳ Création en cours…';
    btn.disabled = true;
});
</script>
@endpush
@endsection