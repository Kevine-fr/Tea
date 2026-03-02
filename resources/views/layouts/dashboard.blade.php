<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mon espace — Thé Tip Top')</title>
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
        @keyframes fall{0%{opacity:0;transform:translateY(0) rotate(0) translateX(0)}8%{opacity:.65}50%{transform:translateY(52vh) rotate(190deg) translateX(30px)}88%{opacity:.42}100%{opacity:0;transform:translateY(112vh) rotate(380deg) translateX(-18px)}}
        .leaf:nth-child(1){left:3%;width:50px;animation-duration:9s;animation-delay:0s}.leaf:nth-child(2){left:10%;width:36px;animation-duration:11s;animation-delay:1.5s}.leaf:nth-child(3){left:20%;width:62px;animation-duration:7.5s;animation-delay:3s}.leaf:nth-child(4){left:33%;width:44px;animation-duration:13s;animation-delay:.7s}.leaf:nth-child(5){left:46%;width:54px;animation-duration:8.5s;animation-delay:2s}.leaf:nth-child(6){left:58%;width:40px;animation-duration:10.5s;animation-delay:4.2s}.leaf:nth-child(7){left:70%;width:66px;animation-duration:8s;animation-delay:.9s}.leaf:nth-child(8){left:80%;width:46px;animation-duration:12s;animation-delay:3.8s}.leaf:nth-child(9){left:88%;width:52px;animation-duration:6.5s;animation-delay:.4s}.leaf:nth-child(10){left:95%;width:38px;animation-duration:11s;animation-delay:2.6s}.leaf:nth-child(11){left:26%;width:34px;animation-duration:9.5s;animation-delay:5.1s}.leaf:nth-child(12){left:53%;width:58px;animation-duration:10s;animation-delay:6.3s}

        /* TOP BAR */
        .user-topbar{position:sticky;top:0;z-index:200;background:rgba(242,236,224,.96);backdrop-filter:blur(14px);border-bottom:1px solid rgba(184,150,46,.15);padding:0 1.5rem;height:58px;display:flex;align-items:center;justify-content:space-between;transition:box-shadow .3s}
        .profile-link{display:flex;align-items:center;gap:.55rem;text-decoration:none;color:var(--txt);font-size:.88rem;font-weight:600;transition:color .2s}
        .profile-link:hover{color:var(--green-m)}
        .profile-icon{width:34px;height:34px;border-radius:50%;border:1.5px solid var(--txt-l);display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .hamburger{background:none;border:none;cursor:pointer;padding:6px;display:flex;flex-direction:column;gap:5px}
        .hamburger span{display:block;width:24px;height:2px;background:var(--txt);border-radius:2px;transition:var(--t)}
        .hamburger.open span:nth-child(1){transform:translateY(7px) rotate(45deg)}
        .hamburger.open span:nth-child(2){opacity:0}
        .hamburger.open span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}

        /* SLIDE MENU */
        .slide-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.35);z-index:399}
        .slide-overlay.open{display:block;animation:fadeInOv .25s}
        @keyframes fadeInOv{from{opacity:0}to{opacity:1}}
        .slide-menu{position:fixed;top:0;right:-300px;width:280px;height:100vh;background:var(--white);z-index:400;box-shadow:-6px 0 30px rgba(0,0,0,.14);transition:right .35s cubic-bezier(.4,0,.2,1);padding:1.5rem;overflow-y:auto}
        .slide-menu.open{right:0}
        .slide-menu-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--cream-d)}
        .slide-menu-close{background:none;border:none;font-size:1.4rem;cursor:pointer;color:var(--txt-l);line-height:1;transition:color .2s}
        .slide-menu-close:hover{color:var(--txt)}
        .menu-item{display:flex;align-items:center;gap:.8rem;padding:.85rem 1rem;border-radius:10px;text-decoration:none;color:var(--txt);font-size:.88rem;font-weight:500;transition:background .2s,color .2s;margin-bottom:.25rem;border:none;cursor:pointer;width:100%;background:none;font-family:'Jost',sans-serif;text-align:left}
        .menu-item:hover{background:var(--cream);color:var(--green-m)}
        .menu-item.active{background:var(--cream-m);color:var(--green);font-weight:700}
        .menu-item-icon{width:20px;text-align:center;font-size:1rem;flex-shrink:0}
        .menu-divider{border:none;border-top:1px solid var(--cream-d);margin:.8rem 0}
        .menu-item-danger:hover{background:#fdf2f2;color:#c0392b}

        /* BANNER */
        .page-banner{position:relative;z-index:1;background:var(--cream-m);padding:1.8rem 2.5rem;display:flex;align-items:center;justify-content:space-between;min-height:104px;overflow:hidden}
        .banner-tape{display:inline-block;background:var(--green);color:var(--white);font-family:'Playfair Display',serif;font-style:italic;font-weight:600;font-size:1.85rem;padding:.55rem 2.5rem;border-radius:3px;box-shadow:3px 3px 0 rgba(0,0,0,.18),7px 7px 0 rgba(0,0,0,.06);transform:rotate(-.4deg);white-space:nowrap}

        /* BUTTONS */
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:.78rem 2.2rem;border-radius:30px;font-family:'Jost',sans-serif;font-weight:600;font-size:.9rem;cursor:pointer;border:none;text-decoration:none;transition:var(--t);white-space:nowrap}
        .btn-orange{background:var(--orange);color:var(--white)}
        .btn-orange:hover{background:var(--orange-h);transform:translateY(-2px);box-shadow:0 6px 20px rgba(217,79,30,.38);color:var(--white)}
        .btn-orange:disabled{opacity:.5;transform:none!important;box-shadow:none!important;cursor:not-allowed}
        .btn-green{background:var(--green);color:var(--white)}
        .btn-green:hover{background:var(--green-m);transform:translateY(-2px);box-shadow:0 6px 20px rgba(30,61,26,.32);color:var(--white)}
        .btn-sm{padding:.4rem 1.1rem;font-size:.8rem;border-radius:20px}
        .btn-outline-danger{background:none;border:2px solid #c0392b;color:#c0392b;border-radius:30px;padding:.6rem 1.5rem;font-family:'Jost',sans-serif;font-weight:600;font-size:.88rem;cursor:pointer;transition:var(--t)}
        .btn-outline-danger:hover{background:#c0392b;color:var(--white)}

        /* INPUTS */
        .input-field{width:100%;padding:.82rem 1.15rem;border:1.5px solid rgba(184,150,46,.26);border-radius:10px;background:var(--cream);font-family:'Jost',sans-serif;font-size:.9rem;color:var(--txt);outline:none;transition:var(--t)}
        .input-field::placeholder{color:#b5aa9a}
        .input-field:focus{border-color:var(--green-m);background:var(--white);box-shadow:0 0 0 3px rgba(45,90,39,.1)}
        .form-label{display:block;font-size:.84rem;font-weight:600;color:var(--txt-m);margin-bottom:.4rem}
        .form-error{font-size:.78rem;color:#c0392b;margin-top:.3rem}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:1.2rem;margin-bottom:1.2rem}
        .form-group{margin-bottom:1.2rem}

        /* MODAL */
        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1000;align-items:center;justify-content:center;backdrop-filter:blur(3px)}
        .modal-overlay.active{display:flex;animation:mFade .2s}
        @keyframes mFade{from{opacity:0}to{opacity:1}}
        .modal{background:var(--white);border-radius:20px;padding:2.2rem;width:100%;max-width:460px;margin:1rem;box-shadow:0 25px 60px rgba(0,0,0,.22);animation:mSlide .3s cubic-bezier(.34,1.56,.64,1)}
        @keyframes mSlide{from{opacity:0;transform:scale(.86) translateY(22px)}to{opacity:1;transform:scale(1) translateY(0)}}

        /* TOAST */
        .toast{position:fixed;bottom:2rem;right:2rem;background:var(--green);color:var(--white);padding:1rem 1.5rem;border-radius:14px;box-shadow:var(--sh-lg);z-index:900;font-weight:600;font-size:.88rem;display:flex;align-items:center;gap:.8rem;transform:translateY(80px);opacity:0;transition:transform .4s cubic-bezier(.34,1.56,.64,1),opacity .3s;max-width:360px}
        .toast.show{transform:translateY(0);opacity:1}
        .toast-err{background:#c0392b}
        .toast-close{background:none;border:none;color:inherit;cursor:pointer;font-size:1.1rem;margin-left:auto;line-height:1;opacity:.7}
        .toast-close:hover{opacity:1}

        /* ALERTS */
        .alert{padding:.9rem 1.2rem;border-radius:12px;margin-bottom:1rem;font-size:.87rem;animation:alertIn .4s}
        @keyframes alertIn{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}
        .alert-success{background:#d4edda;color:#155724;border-left:4px solid #28a745}
        .alert-error{background:#f8d7da;color:#721c24;border-left:4px solid #dc3545}

        /* TABLE */
        .data-table{width:100%;border-collapse:collapse}
        .data-table thead th{padding:1.05rem 1.5rem;text-align:left;font-size:.83rem;font-weight:700;color:var(--txt);border-bottom:1px solid var(--cream-d);background:var(--white)}
        .data-table tbody tr{border-bottom:1px solid var(--cream-m);transition:background .18s}
        .data-table tbody tr:last-child{border-bottom:none}
        .data-table tbody tr:hover{background:var(--cream)}
        .data-table tbody td{padding:1.05rem 1.5rem;font-size:.88rem}

        /* ANIMATIONS */
        .fade-up{opacity:0;transform:translateY(20px);transition:opacity .65s ease,transform .65s ease}
        .fade-up.visible{opacity:1;transform:translateY(0)}
        .s1{transition-delay:.08s}.s2{transition-delay:.16s}.s3{transition-delay:.24s}

        /* WIN PULSE */
        @keyframes winPulse{0%,100%{transform:scale(1)}50%{transform:scale(1.06)}}
        .win-text{animation:winPulse 2s ease-in-out infinite;color:var(--green);font-weight:700}

        @yield('page_styles')
    </style>
    @stack('styles')
</head>
<body>

{{-- Leaves --}}
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

{{-- Slide Overlay --}}
<div class="slide-overlay" id="slideOverlay" onclick="closeMenu()"></div>

{{-- Slide Menu --}}
<nav class="slide-menu" id="slideMenu" aria-label="Navigation utilisateur">
    <div class="slide-menu-header">
        <div>
            <div style="font-weight:700;font-size:.92rem;color:var(--txt)">{{ auth()->user()->first_name ?? 'Mon espace' }}</div>
            <div style="font-size:.78rem;color:var(--txt-l)">{{ auth()->user()->email ?? '' }}</div>
        </div>
        <button class="slide-menu-close" onclick="closeMenu()" aria-label="Fermer">✕</button>
    </div>
    <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="menu-item-icon">🏠</span> Tableau de bord
    </a>
    <a href="{{ route('dashboard.gains') }}" class="menu-item {{ request()->routeIs('dashboard.gains') ? 'active' : '' }}">
        <span class="menu-item-icon">🏆</span> Suivi des gains
    </a>
    <a href="{{ route('dashboard.profile') }}" class="menu-item {{ request()->routeIs('dashboard.profile') ? 'active' : '' }}">
        <span class="menu-item-icon">👤</span> Mon profil
    </a>
    @if(auth()->user()->isAdmin()||auth()->user()->isEmployee())
    <hr class="menu-divider">
    <a href="{{ route('admin.dashboard') }}" class="menu-item">
        <span class="menu-item-icon">⚙️</span> Administration
    </a>
    @endif
    <hr class="menu-divider">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="menu-item menu-item-danger">
            <span class="menu-item-icon">🚪</span> Déconnexion
        </button>
    </form>
</nav>

{{-- Top Bar --}}
<header class="user-topbar" id="userTopbar">
    <a href="{{ route('dashboard.profile') }}" class="profile-link">
        <div class="profile-icon">
            <svg viewBox="0 0 24 24" fill="none" width="17" height="17">
                <circle cx="12" cy="8" r="4" stroke="#3d3d3d" stroke-width="1.6"/>
                <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" stroke="#3d3d3d" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
        </div>
        Mon profil
    </a>
    <button class="hamburger" id="hamburgerBtn" onclick="openMenu()" aria-label="Menu" aria-expanded="false">
        <span></span><span></span><span></span>
    </button>
</header>

{{-- TOASTS --}}
@if(session('success'))
<div class="toast show" id="toastSuccess">
    ✅ {{ session('success') }}
    <button class="toast-close" onclick="this.parentElement.classList.remove('show')">×</button>
</div>
@endif
@if(session('participation_success'))
<div class="toast show" id="toastWin" style="background:var(--green-m)">
    🎉 {{ session('participation_success') }}
    <button class="toast-close" onclick="this.parentElement.classList.remove('show')">×</button>
</div>
@endif
@if(session('error'))
<div class="toast show toast-err" id="toastErr">
    ⚠️ {{ session('error') }}
    <button class="toast-close" onclick="this.parentElement.classList.remove('show')">×</button>
</div>
@endif

<main>@yield('content')</main>

<script>
function openMenu(){
    document.getElementById('slideMenu').classList.add('open');
    document.getElementById('slideOverlay').classList.add('open');
    document.getElementById('hamburgerBtn').classList.add('open');
    document.getElementById('hamburgerBtn').setAttribute('aria-expanded','true');
    document.body.style.overflow='hidden';
}
function closeMenu(){
    document.getElementById('slideMenu').classList.remove('open');
    document.getElementById('slideOverlay').classList.remove('open');
    document.getElementById('hamburgerBtn').classList.remove('open');
    document.getElementById('hamburgerBtn').setAttribute('aria-expanded','false');
    document.body.style.overflow='';
}
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeMenu();});
window.addEventListener('scroll',()=>{
    document.getElementById('userTopbar').style.boxShadow=window.scrollY>5?'0 2px 16px rgba(30,61,26,.12)':'none';
});
function openModal(id){const m=document.getElementById(id);if(m){m.classList.add('active');document.body.style.overflow='hidden';}}
function closeModal(id){const m=document.getElementById(id);if(m){m.classList.remove('active');document.body.style.overflow='';}}
document.querySelectorAll('.modal-overlay').forEach(o=>o.addEventListener('click',e=>{if(e.target===o)closeModal(o.id);}));
document.addEventListener('keydown',e=>{if(e.key==='Escape')document.querySelectorAll('.modal-overlay.active').forEach(m=>closeModal(m.id));});
const obs=new IntersectionObserver(e=>e.forEach(i=>{if(i.isIntersecting)i.target.classList.add('visible');}),{threshold:.08});
document.querySelectorAll('.fade-up').forEach(el=>obs.observe(el));
['toastSuccess','toastWin','toastErr'].forEach(id=>{
    const el=document.getElementById(id);
    if(el) setTimeout(()=>el.classList.remove('show'),6000);
});
</script>
@stack('scripts')
</body>
</html>