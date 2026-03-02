<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Thé Tip Top')</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --cream:#f2ece0; --cream-d:#e8dfd0; --cream-m:#ede5d5;
            --green:#1e3d1a; --green-m:#2d5a27; --green-l:#4a7c3f;
            --gold:#b8962e; --gold-l:#d4b44a;
            --orange:#d94f1e; --orange-h:#c44219;
            --txt:#1a1a1a; --txt-m:#3d3d3d; --txt-l:#8a8a8a;
            --white:#fff;
            --sh:0 4px 24px rgba(30,61,26,.12);
            --sh-sm:0 2px 12px rgba(30,61,26,.08);
            --sh-lg:0 12px 40px rgba(30,61,26,.18);
            --r:16px; --t:all .3s cubic-bezier(.4,0,.2,1);
        }
        *{margin:0;padding:0;box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{font-family:'Jost',sans-serif;background:var(--cream);color:var(--txt);overflow-x:hidden;min-height:100vh}

        /* LEAVES */
        .leaves-bg{position:fixed;inset:0;pointer-events:none;z-index:0;overflow:hidden}
        .leaf{position:absolute;top:-100px;opacity:0;animation:fall linear infinite}
        @keyframes fall{
            0%{opacity:0;transform:translateY(0) rotate(0) translateX(0)}
            8%{opacity:.65}50%{transform:translateY(52vh) rotate(190deg) translateX(30px)}
            88%{opacity:.42}100%{opacity:0;transform:translateY(112vh) rotate(380deg) translateX(-18px)}
        }
        .leaf:nth-child(1){left:3%;width:50px;animation-duration:9s;animation-delay:0s}
        .leaf:nth-child(2){left:10%;width:36px;animation-duration:11s;animation-delay:1.5s}
        .leaf:nth-child(3){left:20%;width:62px;animation-duration:7.5s;animation-delay:3s}
        .leaf:nth-child(4){left:33%;width:44px;animation-duration:13s;animation-delay:.7s}
        .leaf:nth-child(5){left:46%;width:54px;animation-duration:8.5s;animation-delay:2s}
        .leaf:nth-child(6){left:58%;width:40px;animation-duration:10.5s;animation-delay:4.2s}
        .leaf:nth-child(7){left:70%;width:66px;animation-duration:8s;animation-delay:.9s}
        .leaf:nth-child(8){left:80%;width:46px;animation-duration:12s;animation-delay:3.8s}
        .leaf:nth-child(9){left:88%;width:52px;animation-duration:6.5s;animation-delay:.4s}
        .leaf:nth-child(10){left:95%;width:38px;animation-duration:11s;animation-delay:2.6s}
        .leaf:nth-child(11){left:26%;width:34px;animation-duration:9.5s;animation-delay:5.1s}
        .leaf:nth-child(12){left:53%;width:58px;animation-duration:10s;animation-delay:6.3s}

        /* NAVBAR */
        .navbar{position:sticky;top:0;z-index:200;background:rgba(242,236,224,.94);backdrop-filter:blur(14px);border-bottom:1px solid rgba(184,150,46,.18);padding:0 2.5rem;display:flex;align-items:center;height:62px;transition:box-shadow .3s}
        .nav-left,.nav-right{display:flex;align-items:center;gap:2rem;flex:1}
        .nav-right{justify-content:flex-end}
        .nav-logo{position:absolute;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;text-decoration:none;gap:2px}
        .nav-logo-name{font-size:.56rem;font-weight:700;letter-spacing:.18em;color:var(--green);text-transform:uppercase}
        .nav-link{text-decoration:none;color:var(--txt);font-size:.86rem;font-weight:500;position:relative;transition:color .2s;white-space:nowrap}
        .nav-link::after{content:'';position:absolute;bottom:-3px;left:0;width:0;height:1.5px;background:var(--green-m);transition:width .3s}
        .nav-link:hover{color:var(--green-m)}.nav-link:hover::after{width:100%}
        .nav-btn{background:var(--green);color:var(--white)!important;padding:.46rem 1.3rem;border-radius:30px;font-weight:600;font-size:.82rem;text-decoration:none;border:none;cursor:pointer;font-family:'Jost',sans-serif;transition:var(--t)}
        .nav-btn::after{display:none!important}
        .nav-btn:hover{background:var(--green-m);transform:translateY(-1px);box-shadow:0 4px 14px rgba(30,61,26,.28)}

        /* BUTTONS */
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:.78rem 2.2rem;border-radius:30px;font-family:'Jost',sans-serif;font-weight:600;font-size:.9rem;cursor:pointer;border:none;text-decoration:none;transition:var(--t);white-space:nowrap}
        .btn-orange{background:var(--orange);color:var(--white)}
        .btn-orange:hover{background:var(--orange-h);transform:translateY(-2px);box-shadow:0 6px 20px rgba(217,79,30,.38);color:var(--white)}
        .btn-orange:disabled{opacity:.5;transform:none!important;box-shadow:none!important;cursor:not-allowed}
        .btn-green{background:var(--green);color:var(--white)}
        .btn-green:hover{background:var(--green-m);transform:translateY(-2px);box-shadow:0 6px 20px rgba(30,61,26,.32);color:var(--white)}
        .btn-sm{padding:.42rem 1.1rem;font-size:.8rem;border-radius:20px}

        /* BANNER */
        .page-banner{position:relative;z-index:1;background:var(--cream-m);padding:1.8rem 3.5rem;display:flex;align-items:center;justify-content:space-between;min-height:104px;overflow:hidden}
        .banner-tape{display:inline-block;background:var(--green);color:var(--white);font-family:'Playfair Display',serif;font-style:italic;font-weight:600;font-size:1.9rem;padding:.55rem 2.8rem;border-radius:3px;box-shadow:3px 3px 0 rgba(0,0,0,.18),7px 7px 0 rgba(0,0,0,.06);transform:rotate(-.4deg);white-space:nowrap}
        .banner-tape-sm{font-size:1.35rem!important}

        /* GOLD LEAF SVG ORNAMENT */
        .orn{width:82px;opacity:.88;flex-shrink:0}

        /* INPUTS */
        .input-field{width:100%;padding:.82rem 1.15rem;border:1.5px solid rgba(184,150,46,.26);border-radius:10px;background:var(--cream);font-family:'Jost',sans-serif;font-size:.9rem;color:var(--txt);outline:none;transition:var(--t)}
        .input-field::placeholder{color:#b5aa9a}
        .input-field:focus{border-color:var(--green-m);background:var(--white);box-shadow:0 0 0 3px rgba(45,90,39,.1)}
        textarea.input-field{resize:vertical;min-height:140px}
        .form-label{display:block;font-size:.84rem;font-weight:600;color:var(--txt-m);margin-bottom:.4rem}
        .form-error{font-size:.78rem;color:#c0392b;margin-top:.3rem}

        /* ACCORDION */
        .acc-item{background:var(--white);border-radius:12px;margin-bottom:.7rem;overflow:hidden;box-shadow:var(--sh-sm);transition:box-shadow .3s}
        .acc-item:hover{box-shadow:var(--sh)}
        .acc-btn{width:100%;background:none;border:none;padding:1.15rem 1.5rem;text-align:left;font-family:'Jost',sans-serif;font-size:.92rem;font-weight:600;color:var(--txt);cursor:pointer;display:flex;justify-content:space-between;align-items:center;transition:color .2s}
        .acc-btn:hover{color:var(--green-m)}
        .acc-icon{font-size:1rem;color:var(--green-m);transition:transform .3s;flex-shrink:0}
        .acc-body{max-height:0;overflow:hidden;transition:max-height .4s ease}
        .acc-body-inner{padding:0 1.5rem 1.2rem;color:var(--txt-m);line-height:1.78;font-size:.88rem}

        /* MODAL */
        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1000;align-items:center;justify-content:center;backdrop-filter:blur(3px)}
        .modal-overlay.active{display:flex;animation:mFade .2s}
        @keyframes mFade{from{opacity:0}to{opacity:1}}
        .modal{background:var(--white);border-radius:20px;padding:2.2rem;width:100%;max-width:460px;margin:1rem;box-shadow:0 25px 60px rgba(0,0,0,.22);animation:mSlide .3s cubic-bezier(.34,1.56,.64,1)}
        @keyframes mSlide{from{opacity:0;transform:scale(.86) translateY(22px)}to{opacity:1;transform:scale(1) translateY(0)}}

        /* ALERTS */
        .alert{padding:.9rem 1.2rem;border-radius:12px;margin-bottom:1rem;font-size:.87rem;animation:alertIn .4s}
        @keyframes alertIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}
        .alert-success{background:#d4edda;color:#155724;border-left:4px solid #28a745}
        .alert-error{background:#f8d7da;color:#721c24;border-left:4px solid #dc3545}
        .alert-warning{background:#fff3cd;color:#856404;border-left:4px solid #ffc107}

        /* TOAST */
        .toast{position:fixed;bottom:2rem;right:2rem;background:var(--green);color:var(--white);padding:1rem 1.5rem;border-radius:14px;box-shadow:var(--sh-lg);z-index:900;font-weight:600;font-size:.88rem;display:flex;align-items:center;gap:.8rem;transform:translateY(80px);opacity:0;transition:transform .4s cubic-bezier(.34,1.56,.64,1),opacity .3s;max-width:360px}
        .toast.show{transform:translateY(0);opacity:1}
        .toast-error{background:#c0392b}
        .toast-close{background:none;border:none;color:inherit;cursor:pointer;font-size:1.1rem;margin-left:auto;line-height:1}

        /* CARD */
        .card{background:var(--white);border-radius:var(--r);box-shadow:var(--sh-sm);transition:var(--t)}
        .card-hover:hover{transform:translateY(-4px);box-shadow:var(--sh-lg)}

        /* BADGE */
        .badge{display:inline-block;padding:.22rem .8rem;border-radius:20px;font-size:.77rem;font-weight:600}
        .badge-ok{background:#d4edda;color:#155724}
        .badge-warn{background:#fff3cd;color:#856404}
        .badge-info{background:#d1ecf1;color:#0c5460}
        .badge-danger{background:#f8d7da;color:#721c24}

        /* FADE-UP ANIMATION */
        .fade-up{opacity:0;transform:translateY(20px);transition:opacity .65s ease,transform .65s ease}
        .fade-up.visible{opacity:1;transform:translateY(0)}
        .s1{transition-delay:.08s}.s2{transition-delay:.16s}.s3{transition-delay:.24s}.s4{transition-delay:.32s}

        /* FOOTER */
        .footer{background:var(--green);color:rgba(255,255,255,.82);padding:3.5rem 4.5rem 2rem;position:relative;z-index:1}
        .footer-top{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3rem;padding-bottom:2.5rem;border-bottom:1px solid rgba(255,255,255,.13)}
        .footer-col h4{font-weight:700;font-size:.8rem;letter-spacing:.07em;margin-bottom:1rem;color:var(--white);text-transform:uppercase}
        .footer-col a{display:flex;align-items:center;gap:.5rem;color:rgba(255,255,255,.6);text-decoration:none;font-size:.82rem;margin-bottom:.45rem;transition:color .2s}
        .footer-col a:hover{color:var(--white)}
        .footer-bottom{padding-top:1.5rem;text-align:right;font-size:.78rem;color:rgba(255,255,255,.36)}

        @yield('page_styles')
    </style>
    @stack('styles')
</head>
<body>

{{-- Falling Leaves --}}
<div class="leaves-bg" aria-hidden="true">
    @php $lc=['#6a9c5f','#4a7c3f','#8ab578','#3d6b33','#7aac6e','#9ec490','#5d8f52','#b5d4a8','#2d5a27','#6fa864','#4e8843','#82bb72']; @endphp
    @for($i=0;$i<12;$i++)
    <div class="leaf"><svg viewBox="0 0 60 80" fill="none" style="width:100%">
        @if($i%3===0)<path d="M30 4C54 12,62 44,46 68C36 74,16 72,12 58C4 44,8 16,30 4Z" fill="{{ $lc[$i] }}" opacity=".7"/><path d="M30 4C28 30,26 54,22 76" stroke="{{ $lc[($i+4)%12] }}" stroke-width="1.5" fill="none" opacity=".4"/>
        @elseif($i%3===1)<path d="M18 4C44 8,60 38,52 64C44 78,18 78,10 64C0 46,4 14,18 4Z" fill="{{ $lc[$i] }}" opacity=".64"/><path d="M20 4C28 28,32 52,30 78" stroke="{{ $lc[($i+3)%12] }}" stroke-width="1.5" fill="none" opacity=".38"/>
        @else<ellipse cx="30" cy="40" rx="20" ry="34" transform="rotate(-10 30 40)" fill="{{ $lc[$i] }}" opacity=".67"/><path d="M22 8C29 32,31 56,28 76" stroke="{{ $lc[($i+5)%12] }}" stroke-width="1.5" fill="none" opacity=".36"/>@endif
    </svg></div>
    @endfor
</div>

{{-- Navbar --}}
<nav class="navbar" id="mainNav">
    <div class="nav-left">
        <a href="{{ route('home') }}" class="nav-link">Accueil</a>
        <a href="{{ route('pages.jeu') }}" class="nav-link">Jeu</a>
        <a href="{{ route('pages.gain') }}" class="nav-link">Gain</a>
    </div>
    <a href="{{ route('home') }}" class="nav-logo">
        <svg viewBox="0 0 48 56" fill="none" width="38">
            <path d="M24 5C44 13,50 38,36 54C27 60,12 60,8 46C1 34,5 16,24 5Z" fill="none" stroke="#b8962e" stroke-width="1.5" opacity=".7"/>
            <path d="M17 24Q21 16,24 20Q27 24,31 14" stroke="#1e3d1a" stroke-width="2.5" fill="none" stroke-linecap="round"/>
            <path d="M15 34Q19 28,24 31Q29 34,33 28" stroke="#1e3d1a" stroke-width="2" fill="none" stroke-linecap="round"/>
        </svg>
        <span class="nav-logo-name">Thé Tip Top</span>
    </a>
    <div class="nav-right">
        @auth
            @if(auth()->user()->isAdmin()||auth()->user()->isEmployee())
            <a href="{{ route('admin.dashboard') }}" class="nav-link">Admin</a>
            @endif
            <a href="{{ route('dashboard') }}" class="nav-link">Mon espace</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf<button type="submit" class="nav-btn">Déconnexion</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="nav-link">Connexion</a>
            <a href="{{ route('register') }}" class="nav-link">Inscription</a>
        @endauth
        <a href="{{ route('pages.contact') }}" class="nav-link">Contact</a>
    </div>
</nav>

<main>@yield('content')</main>

{{-- Footer --}}
<footer class="footer">
    <div class="footer-top">
        <div>
            <div style="display:flex;align-items:center;gap:.8rem;margin-bottom:.8rem">
                <svg viewBox="0 0 50 65" fill="none" width="50">
                    <path d="M25 6C46 14,52 42,38 62C28 68,12 68,8 54C1 40,5 18,25 6Z" fill="#d4b44a" opacity=".38"/>
                    <path d="M25 6C23 30,21 52,20 66" stroke="#b8962e" stroke-width="2" fill="none"/>
                    <ellipse cx="25" cy="67" rx="12" ry="4" fill="none" stroke="#d4b44a" stroke-width="1.5"/>
                </svg>
                <span style="font-size:.6rem;font-weight:700;letter-spacing:.2em;color:rgba(255,255,255,.45);text-transform:uppercase;line-height:1.3">THÉ<br>TIP TOP</span>
            </div>
            <p style="font-size:1.2rem;font-weight:700;color:var(--white);line-height:1.35;margin-bottom:.4rem">Maison française de thés<br>biologiques et artisanaux.</p>
            <span style="font-size:.8rem;color:rgba(255,255,255,.5)">Créations signatures, infusions bien-être et thés premium.</span>
        </div>
        <div class="footer-col">
            <h4>Jeu-concours</h4>
            <a href="{{ route('pages.jeu') }}">Présentation du jeu</a>
            <a href="{{ route('pages.gain') }}">Lots à gagner</a>
            <a href="{{ route('pages.contact') }}">Nous Contacter</a>
        </div>
        <div class="footer-col">
            <h4>Informations légales</h4>
            <a href="{{ route('pages.politique') }}">Politique de confidentialité</a>
            <a href="{{ route('pages.cgv') }}">CGV</a>
            <a href="{{ route('pages.cgu') }}">CGU</a>
        </div>
        <div class="footer-col">
            <h4>Suivez-nous !</h4>
            <a href="#"><svg viewBox="0 0 20 20" fill="currentColor" width="14"><path d="M18 2h-3.5A4.5 4.5 0 0010 6.5V9H8v3h2v7h3v-7h2.5l.5-3H13V6.5a1.5 1.5 0 011.5-1.5H18V2z"/></svg> Facebook</a>
            <a href="#"><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" width="14"><rect x="2" y="2" width="16" height="16" rx="4"/><circle cx="10" cy="10" r="3.5"/><circle cx="14.5" cy="5.5" r=".8" fill="currentColor" stroke="none"/></svg> Instagram</a>
            <a href="#"><svg viewBox="0 0 24 24" fill="currentColor" width="14"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.79a4.85 4.85 0 01-1.01-.1z"/></svg> Tik-Tok</a>
        </div>
    </div>
    <div class="footer-bottom">© 2026 – Thé Tip Top. Tous droits réservés.</div>
</footer>

<script>
window.addEventListener('scroll',()=>{
    document.getElementById('mainNav').style.boxShadow=window.scrollY>10?'0 2px 20px rgba(30,61,26,.16)':'none';
});
document.querySelectorAll('.acc-btn').forEach(btn=>{
    btn.addEventListener('click',function(){
        const body=this.nextElementSibling, icon=this.querySelector('.acc-icon');
        const open=body.style.maxHeight&&body.style.maxHeight!=='0px';
        document.querySelectorAll('.acc-body').forEach(b=>{b.style.maxHeight='0px';});
        document.querySelectorAll('.acc-icon').forEach(ic=>{ic.style.transform='rotate(0)';});
        if(!open){body.style.maxHeight=body.scrollHeight+'px'; if(icon) icon.style.transform='rotate(45deg)';}
    });
});
function openModal(id){const m=document.getElementById(id);if(m){m.classList.add('active');document.body.style.overflow='hidden';}}
function closeModal(id){const m=document.getElementById(id);if(m){m.classList.remove('active');document.body.style.overflow='';}}
document.querySelectorAll('.modal-overlay').forEach(o=>o.addEventListener('click',e=>{if(e.target===o)closeModal(o.id);}));
document.addEventListener('keydown',e=>{if(e.key==='Escape')document.querySelectorAll('.modal-overlay.active').forEach(m=>closeModal(m.id));});
const obs=new IntersectionObserver(e=>e.forEach(i=>{if(i.isIntersecting)i.target.classList.add('visible');}),{threshold:.08});
document.querySelectorAll('.fade-up').forEach(el=>obs.observe(el));
document.querySelectorAll('.alert').forEach(a=>{setTimeout(()=>{a.style.transition='opacity .5s';a.style.opacity='0';setTimeout(()=>a.remove(),500);},5000);});
</script>
@stack('scripts')
</body>
</html>