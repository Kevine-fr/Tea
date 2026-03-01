<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Thé Tip Top — Maison française de thés biologiques')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400;1,600&family=Lato:wght@300;400;500;700&display=swap" rel="stylesheet">

    {{-- App CSS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body>

{{-- ═══════════════════════ NAVBAR ═══════════════════════ --}}
<nav class="navbar">
    <div class="nav-links">
        <a href="{{ route('home') }}">Accueil</a>
        <a href="#">Nos Thés</a>
        <a href="#">Collections</a>
    </div>

    <a href="{{ route('home') }}" class="nav-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Thé Tip Top"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
        {{-- Fallback logo SVG --}}
        <svg style="display:none" width="45" height="45" viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
            <circle cx="40" cy="40" r="38" fill="#1e3d2f"/>
            <text x="40" y="52" text-anchor="middle" fill="#b8975a"
                  font-family="Georgia,serif" font-size="22" font-style="italic">ثT</text>
        </svg>
        <span>THÉ TIP TOP</span>
    </a>

    <div class="nav-links">
        <a href="#">Bien-Être</a>
        <a href="#">Maison du Thé</a>
        <a href="#">Contact</a>
        @auth
            <a href="{{ route('dashboard') }}" style="color: var(--green-light); font-weight: 600;">Mon espace</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-green" style="padding: 0.4rem 1.2rem;">
                Participer
            </a>
        @endauth
    </div>

    {{-- Menu mobile --}}
    <button class="mobile-menu-btn" onclick="toggleMobileMenu()" aria-label="Menu"
            style="display:none; background:none; border:none; font-size:1.5rem; cursor:pointer; color:var(--text-dark)">
        ☰
    </button>
</nav>

{{-- ═══════════════════════ CONTENU ═══════════════════════ --}}
<main>
    @if(session('success'))
        <div class="container" style="padding-top: 1rem;">
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="container" style="padding-top: 1rem;">
            <div class="alert alert-error">⚠️ {{ session('error') }}</div>
        </div>
    @endif

    @yield('content')
</main>

{{-- ═══════════════════════ FOOTER ═══════════════════════ --}}
<footer class="footer">
    <div class="footer-grid">
        {{-- Brand --}}
        <div class="footer-brand">
            <div style="display:flex; align-items:center; gap:0.8rem; margin-bottom: 0.8rem;">
                <svg width="50" height="50" viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="40" cy="40" r="38" fill="rgba(255,255,255,0.15)"/>
                    <text x="40" y="52" text-anchor="middle" fill="#b8975a"
                          font-family="Georgia,serif" font-size="22" font-style="italic">T</text>
                </svg>
                <div>
                    <div style="font-family:'Playfair Display',serif; color:var(--gold); font-size:1rem; font-style:italic">Thé Tip Top</div>
                </div>
            </div>
            <p><strong style="color:white">Maison française de thés<br>biologiques et artisanaux.</strong></p>
            <p class="tagline">Créations signatures, infusions bien-être et thés premium.</p>
            <div class="social-links" style="margin-top: 1rem;">
                <a href="#" title="Instagram">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                    </svg>
                </a>
                <a href="#" title="Facebook">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- À propos --}}
        <div>
            <h4>À propos</h4>
            <ul>
                <li><a href="#">Qui sommes-nous ?</a></li>
                <li><a href="#">Notre savoir-faire</a></li>
                <li><a href="#">Nos thés bio</a></li>
                <li><a href="#">Nos boutiques</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>

        {{-- Informations légales --}}
        <div>
            <h4>Informations légales</h4>
            <ul>
                <li><a href="#">Mentions légales</a></li>
                <li><a href="#">Politique de confidentialité</a></li>
                <li><a href="#">Protection des données (RGPD)</a></li>
                <li><a href="#">Règlement du jeu-concours</a></li>
            </ul>
        </div>

        {{-- Jeu-concours --}}
        <div>
            <h4>Jeu-concours</h4>
            <ul>
                <li><a href="#">Présentation du jeu</a></li>
                <li><a href="#">Lots à gagner</a></li>
                <li><a href="{{ route('login') }}">Participer</a></li>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Assistance</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <span>Suivez-nous !
            <a href="#" style="color:rgba(255,255,255,0.65); margin-left:0.5rem">📸</a>
            <a href="#" style="color:rgba(255,255,255,0.65); margin-left:0.3rem">👍</a>
        </span>
        <span>© 2026 — Thé Tip Top. Tous droits réservés.</span>
    </div>
</footer>

<script>
function toggleMobileMenu() {
    const links = document.querySelectorAll('.nav-links');
    links.forEach(l => l.classList.toggle('mobile-open'));
}
// FAQ Accordion
document.querySelectorAll('.faq-question').forEach(btn => {
    btn.addEventListener('click', () => {
        const item = btn.closest('.faq-item');
        item.classList.toggle('open');
    });
});
</script>

@stack('scripts')
</body>
</html>