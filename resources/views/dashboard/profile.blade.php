@extends('layouts.dashboard')
@section('title','Mon profil — Thé Tip Top')

@section('page_styles')
.profile-wrap{position:relative;z-index:1;padding:3rem 2rem 5rem;max-width:760px;margin:0 auto}
.profile-card{background:var(--white);border-radius:20px;box-shadow:var(--sh-sm);padding:2.5rem 2.8rem}
.forgot-link{font-size:.82rem;color:var(--orange);text-decoration:none;display:inline-block;margin-top:.35rem;transition:opacity .2s}
.forgot-link:hover{opacity:.75;text-decoration:underline}
.delete-zone{margin-top:2.5rem;padding-top:2rem;border-top:1px solid var(--cream-d);text-align:center}
.input-has-eye{position:relative}
.input-has-eye input{padding-right:2.8rem}
.eye-btn{position:absolute;right:.8rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--txt-l);padding:4px;transition:color .2s}
.eye-btn:hover{color:var(--txt)}
@endsection

@section('content')

{{-- Banner --}}
<section class="page-banner fade-up">
    <svg class="orn" viewBox="0 0 90 70" fill="none">
        <path d="M20 60C24 44,40 36,35 18C46 32,54 50,40 64" fill="#b8962e" opacity=".35"/>
        <path d="M20 60C30 50,38 40,35 18" stroke="#b8962e" stroke-width="1.5" fill="none"/>
        <path d="M45 55C48 42,60 36,56 22C65 34,70 50,58 62" fill="#b8962e" opacity=".28"/>
        <path d="M45 55C52 46,57 38,56 22" stroke="#b8962e" stroke-width="1.5" fill="none"/>
    </svg>
    <div style="text-align:center;flex:1"><span class="banner-tape">Détails du compte</span></div>
    <svg class="orn" viewBox="0 0 90 70" fill="none" style="transform:scaleX(-1)">
        <path d="M20 60C24 44,40 36,35 18C46 32,54 50,40 64" fill="#b8962e" opacity=".35"/>
        <path d="M20 60C30 50,38 40,35 18" stroke="#b8962e" stroke-width="1.5" fill="none"/>
        <path d="M45 55C48 42,60 36,56 22C65 34,70 50,58 62" fill="#b8962e" opacity=".28"/>
        <path d="M45 55C52 46,57 38,56 22" stroke="#b8962e" stroke-width="1.5" fill="none"/>
    </svg>
</section>

<section class="profile-wrap">
    <div class="profile-card fade-up s1">
        <h2 style="font-family:'Playfair Display',serif;font-size:1.45rem;font-weight:700;text-align:center;margin-bottom:2rem">
            Mes informations
        </h2>

        {{-- Success/Error Alerts --}}
        @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $err)<div>⚠️ {{ $err }}</div>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('dashboard.profile.update') }}" id="profileForm" novalidate>
            @csrf
            @method('PATCH')

            {{-- Nom + Prénom --}}
            <div class="form-row">
                <div>
                    <label class="form-label" for="last_name">Nom *</label>
                    <input type="text" name="last_name" id="last_name"
                        class="input-field {{ $errors->has('last_name') ? 'input-error' : '' }}"
                        placeholder="Nom"
                        value="{{ old('last_name', $user->last_name) }}"
                        required>
                    @error('last_name')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="form-label" for="first_name">Prénom *</label>
                    <input type="text" name="first_name" id="first_name"
                        class="input-field {{ $errors->has('first_name') ? 'input-error' : '' }}"
                        placeholder="Prénom"
                        value="{{ old('first_name', $user->first_name) }}"
                        required>
                    @error('first_name')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label" for="email">Mail *</label>
                <input type="email" name="email" id="email"
                    class="input-field {{ $errors->has('email') ? 'input-error' : '' }}"
                    placeholder="Mail"
                    value="{{ old('email', $user->email) }}"
                    required>
                @error('email')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            {{-- Ancien mot de passe --}}
            <div class="form-group">
                <label class="form-label" for="current_password">Ancien mot de passe *</label>
                <div class="input-has-eye">
                    <input type="password" name="current_password" id="current_password"
                        class="input-field {{ $errors->has('current_password') ? 'input-error' : '' }}"
                        placeholder="Ancien mot de passe"
                        autocomplete="current-password">
                    <button type="button" class="eye-btn" onclick="togglePwd('current_password',this)" aria-label="Afficher/masquer">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('current_password')<span class="form-error">{{ $message }}</span>@enderror
                <a href="{{ route('password.request') }}" class="forgot-link">J'ai oublié mon mot de passe</a>
            </div>

            {{-- Nouveau mot de passe --}}
            <div class="form-group">
                <label class="form-label" for="new_password">Nouveau mot de passe *</label>
                <div class="input-has-eye">
                    <input type="password" name="new_password" id="new_password"
                        class="input-field {{ $errors->has('new_password') ? 'input-error' : '' }}"
                        placeholder="Nouveau mot de passe"
                        autocomplete="new-password"
                        oninput="checkStrength(this.value)">
                    <button type="button" class="eye-btn" onclick="togglePwd('new_password',this)" aria-label="Afficher/masquer">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="18"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('new_password')<span class="form-error">{{ $message }}</span>@enderror
                <div style="display:flex;gap:4px;margin-top:.4rem" id="strengthBars">
                    <div style="flex:1;height:3px;border-radius:2px;background:var(--cream-d);transition:background .3s" id="sb1"></div>
                    <div style="flex:1;height:3px;border-radius:2px;background:var(--cream-d);transition:background .3s" id="sb2"></div>
                    <div style="flex:1;height:3px;border-radius:2px;background:var(--cream-d);transition:background .3s" id="sb3"></div>
                    <div style="flex:1;height:3px;border-radius:2px;background:var(--cream-d);transition:background .3s" id="sb4"></div>
                </div>
            </div>

            {{-- Submit --}}
            <div style="text-align:center;margin-top:1.8rem">
                <button type="submit" class="btn btn-orange" id="saveBtn" style="min-width:180px">
                    Sauvegarder
                </button>
            </div>
        </form>

        {{-- Delete zone --}}
        <div class="delete-zone">
            <p style="font-size:.84rem;color:var(--txt-l);margin-bottom:.8rem">Supprimer définitivement votre compte et toutes vos données.</p>
            <button type="button" class="btn-outline-danger" onclick="openModal('deleteModal')">
                Supprimer mon compte
            </button>
        </div>
    </div>
</section>

{{-- Delete Modal --}}
<div class="modal-overlay" id="deleteModal">
    <div class="modal">
        <h3 style="font-family:'Playfair Display',serif;font-size:1.3rem;margin-bottom:.8rem;color:#c0392b">⚠️ Supprimer le compte</h3>
        <p style="font-size:.88rem;color:var(--txt-m);margin-bottom:1.5rem;line-height:1.65">
            Cette action est <strong>irréversible</strong>. Toutes vos données (participations, gains) seront définitivement supprimées.
        </p>
        <form method="POST" action="{{ route('dashboard.profile.delete') }}">
            @csrf
            @method('DELETE')
            <div class="form-group">
                <label class="form-label">Confirmez votre mot de passe</label>
                <input type="password" name="password" class="input-field" placeholder="Mot de passe" autocomplete="current-password" required>
            </div>
            <div style="display:flex;gap:1rem;justify-content:flex-end;margin-top:1rem">
                <button type="button" class="btn btn-sm" style="background:var(--cream-d);color:var(--txt)" onclick="closeModal('deleteModal')">Annuler</button>
                <button type="submit" class="btn btn-sm" style="background:#c0392b;color:var(--white)">Supprimer définitivement</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function togglePwd(id, btn) {
    const inp = document.getElementById(id);
    const isText = inp.type === 'text';
    inp.type = isText ? 'password' : 'text';
    btn.style.opacity = isText ? '.6' : '1';
}
function checkStrength(v) {
    const bars = ['sb1','sb2','sb3','sb4'];
    const colors = ['#e74c3c','#e67e22','#f1c40f','#27ae60'];
    let score = 0;
    if (v.length >= 8) score++;
    if (/[A-Z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;
    bars.forEach((id, i) => {
        document.getElementById(id).style.background = i < score ? colors[score - 1] : 'var(--cream-d)';
    });
}
document.getElementById('profileForm').addEventListener('submit', function() {
    const btn = document.getElementById('saveBtn');
    btn.textContent = '⏳ Sauvegarde…';
    btn.disabled = true;
});
</script>
@endpush
@endsection