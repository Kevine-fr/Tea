<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration — Thé Tip Top')</title>
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
        body{font-family:'Jost',sans-serif;background:var(--cream);color:var(--txt);display:flex;flex-direction:column;min-height:100vh}

        /* LAYOUT */
        .admin-layout{display:flex;min-height:100vh}

        /* SIDEBAR */
        .sidebar{width:200px;flex-shrink:0;background:var(--green);display:flex;flex-direction:column;position:sticky;top:0;height:100vh;overflow-y:auto}
        .sidebar-logo{padding:1.8rem 1.5rem 1.5rem;border-bottom:1px solid rgba(255,255,255,.1)}
        .sidebar-logo svg{display:block;margin:0 auto .5rem}
        .sidebar-brand{font-size:.62rem;font-weight:700;letter-spacing:.18em;color:rgba(255,255,255,.55);text-transform:uppercase;text-align:center}
        .sidebar-nav{flex:1;padding:1.2rem .8rem}
        .sidebar-link{display:flex;align-items:center;gap:.7rem;padding:.78rem 1rem;border-radius:10px;text-decoration:none;color:rgba(255,255,255,.72);font-size:.86rem;font-weight:500;transition:background .2s,color .2s;margin-bottom:.2rem;white-space:nowrap}
        .sidebar-link:hover{background:rgba(255,255,255,.1);color:var(--white)}
        .sidebar-link.active{background:rgba(255,255,255,.15);color:var(--white);font-weight:700}
        .sidebar-icon{width:16px;text-align:center;flex-shrink:0;opacity:.8}

        /* TOPBAR */
        .admin-topbar{height:58px;background:var(--green);display:flex;align-items:center;gap:1rem;padding:0 1.5rem;position:sticky;top:0;z-index:100;flex-shrink:0}
        .search-wrap{flex:1;max-width:420px;position:relative}
        .search-wrap svg{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.5);pointer-events:none}
        .search-input{width:100%;background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.2);border-radius:30px;padding:.5rem 1rem .5rem 2.5rem;font-family:'Jost',sans-serif;font-size:.86rem;color:var(--white);outline:none;transition:var(--t)}
        .search-input::placeholder{color:rgba(255,255,255,.45)}
        .search-input:focus{background:rgba(255,255,255,.18);border-color:rgba(255,255,255,.4)}
        .topbar-right{display:flex;align-items:center;gap:.8rem;margin-left:auto}
        .btn-topbar{display:inline-flex;align-items:center;gap:.4rem;padding:.46rem 1.2rem;border-radius:30px;font-family:'Jost',sans-serif;font-weight:600;font-size:.82rem;cursor:pointer;border:none;text-decoration:none;transition:var(--t);white-space:nowrap}
        .btn-topbar-green{background:var(--orange);color:var(--white)}
        .btn-topbar-green:hover{background:var(--orange-h);transform:translateY(-1px);box-shadow:0 4px 14px rgba(217,79,30,.4);color:var(--white)}
        .btn-topbar-outline{background:rgba(255,255,255,.12);color:var(--white);border:1.5px solid rgba(255,255,255,.3)}
        .btn-topbar-outline:hover{background:rgba(255,255,255,.2)}

        /* MAIN CONTENT */
        .admin-main{flex:1;display:flex;flex-direction:column;min-width:0}
        .admin-content{flex:1;padding:2rem;overflow-y:auto}

        /* BUTTONS */
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:.7rem 1.8rem;border-radius:30px;font-family:'Jost',sans-serif;font-weight:600;font-size:.88rem;cursor:pointer;border:none;text-decoration:none;transition:var(--t);white-space:nowrap}
        .btn-orange{background:var(--orange);color:var(--white)}
        .btn-orange:hover{background:var(--orange-h);transform:translateY(-2px);box-shadow:0 6px 20px rgba(217,79,30,.38);color:var(--white)}
        .btn-green{background:var(--green);color:var(--white)}
        .btn-green:hover{background:var(--green-m);transform:translateY(-2px);box-shadow:0 6px 20px rgba(30,61,26,.32);color:var(--white)}
        .btn-sm{padding:.38rem 1rem;font-size:.8rem;border-radius:20px}

        /* CARD */
        .card{background:var(--white);border-radius:var(--r);box-shadow:var(--sh-sm);padding:1.5rem;margin-bottom:1.5rem}
        .card-title{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;margin-bottom:1.2rem;text-align:center}

        /* TABLE */
        .admin-table{width:100%;border-collapse:collapse}
        .admin-table thead th{padding:.85rem 1.1rem;text-align:left;font-size:.78rem;font-weight:700;color:var(--txt-m);border-bottom:1.5px solid var(--cream-d);background:var(--white);white-space:nowrap}
        .admin-table tbody tr{border-bottom:1px solid var(--cream-m);transition:background .15s}
        .admin-table tbody tr:last-child{border-bottom:none}
        .admin-table tbody tr:hover{background:var(--cream)}
        .admin-table tbody td{padding:.85rem 1.1rem;font-size:.84rem}

        /* INPUTS */
        .input-field{width:100%;padding:.75rem 1rem;border:1.5px solid rgba(184,150,46,.26);border-radius:10px;background:var(--cream);font-family:'Jost',sans-serif;font-size:.88rem;color:var(--txt);outline:none;transition:var(--t)}
        .input-field::placeholder{color:#b5aa9a}
        .input-field:focus{border-color:var(--green-m);background:var(--white);box-shadow:0 0 0 3px rgba(45,90,39,.1)}
        .form-label{display:block;font-size:.82rem;font-weight:600;color:var(--txt-m);margin-bottom:.35rem}
        .form-group{margin-bottom:1.1rem}

        /* MODAL */
        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1000;align-items:center;justify-content:center;backdrop-filter:blur(3px)}
        .modal-overlay.active{display:flex;animation:mFade .2s}
        @keyframes mFade{from{opacity:0}to{opacity:1}}
        .modal{background:var(--cream);border-radius:20px;padding:2.2rem;width:100%;max-width:440px;margin:1rem;box-shadow:0 25px 60px rgba(0,0,0,.22);animation:mSlide .3s cubic-bezier(.34,1.56,.64,1)}
        @keyframes mSlide{from{opacity:0;transform:scale(.86) translateY(22px)}to{opacity:1;transform:scale(1) translateY(0)}}
        .modal-title{font-family:'Playfair Display',serif;font-size:1.25rem;font-weight:700;text-align:center;margin-bottom:1.5rem}
        .modal .input-field{background:var(--white)}

        /* ALERTS */
        .alert-box{padding:.8rem 1rem;border-radius:10px;font-size:.84rem;background:#fff3cd;color:#856404;border:1px solid #ffeaa7;display:flex;align-items:center;gap:.6rem;margin-bottom:.7rem}
        .alert-box-icon{font-size:1rem;flex-shrink:0}

        /* TOAST */
        .toast{position:fixed;bottom:2rem;right:2rem;background:var(--green);color:var(--white);padding:1rem 1.5rem;border-radius:14px;box-shadow:var(--sh-lg);z-index:1100;font-weight:600;font-size:.88rem;display:flex;align-items:center;gap:.8rem;transform:translateY(80px);opacity:0;transition:transform .4s cubic-bezier(.34,1.56,.64,1),opacity .3s;max-width:360px}
        .toast.show{transform:translateY(0);opacity:1}
        .toast-close{background:none;border:none;color:inherit;cursor:pointer;font-size:1.1rem;margin-left:auto;line-height:1;opacity:.7}
        .toast-close:hover{opacity:1}

        /* BADGE */
        .badge{display:inline-block;padding:.22rem .8rem;border-radius:20px;font-size:.77rem;font-weight:600}
        .badge-ok{background:#d4edda;color:#155724}
        .badge-warn{background:#fff3cd;color:#856404}
        .badge-info{background:#d1ecf1;color:#0c5460}
        .badge-danger{background:#f8d7da;color:#721c24}

        /* FADE-UP */
        .fade-up{opacity:0;transform:translateY(16px);transition:opacity .55s ease,transform .55s ease}
        .fade-up.visible{opacity:1;transform:translateY(0)}
        .s1{transition-delay:.08s}.s2{transition-delay:.16s}.s3{transition-delay:.24s}

        @yield('page_styles')
    </style>
    @stack('styles')
</head>
<body>

<div class="admin-layout">
    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sidebar-logo">
            <svg viewBox="0 0 48 56" fill="none" width="42">
                <path d="M24 5C44 13,50 38,36 54C27 60,12 60,8 46C1 34,5 16,24 5Z" fill="none" stroke="#d4b44a" stroke-width="1.5" opacity=".7"/>
                <path d="M17 24Q21 16,24 20Q27 24,31 14" stroke="#d4b44a" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                <path d="M15 34Q19 28,24 31Q29 34,33 28" stroke="#d4b44a" stroke-width="2" fill="none" stroke-linecap="round"/>
            </svg>
            <div class="sidebar-brand">Thé Tip Top</div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="sidebar-icon">📊</span> Dashboard
            </a>
            <a href="{{ route('admin.tickets-gains') }}" class="sidebar-link {{ request()->routeIs('admin.tickets-gains') ? 'active' : '' }}">
                <span class="sidebar-icon">🎫</span> Tickets &amp; Gains
            </a>
            <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <span class="sidebar-icon">👥</span> Utilisateurs &amp; droits
            </a>
        </nav>
    </aside>

    {{-- MAIN --}}
    <div class="admin-main">
        {{-- TOPBAR --}}
        <header class="admin-topbar">
            <div class="search-wrap">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" width="16">
                    <circle cx="9" cy="9" r="6"/><path d="M16 16l-3-3" stroke-linecap="round"/>
                </svg>
                <input type="text" class="search-input" id="adminSearch"
                    placeholder="Rechercher : N° Ticket, Nom, Prénom..."
                    oninput="adminSearchTable(this.value)">
            </div>
            <div class="topbar-right">
                <a href="{{ route('admin.export-csv') }}" class="btn-topbar btn-topbar-outline">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="14"><path d="M13 8V2H7v6H3l7 7 7-7h-4z"/></svg>
                    Exporter CSV
                </a>
                <button type="button" class="btn-topbar btn-topbar-green" onclick="@yield('topbar_action', 'void(0)')">
                    Rechercher
                </button>
            </div>
        </header>

        {{-- CONTENT --}}
        <div class="admin-content">
            @if(session('success'))
            <div class="toast show" id="adminToast">✅ {{ session('success') }}
                <button class="toast-close" onclick="this.parentElement.classList.remove('show')">×</button>
            </div>
            @endif
            @if(session('error'))
            <div class="toast show" id="adminToastErr" style="background:#c0392b">⚠️ {{ session('error') }}
                <button class="toast-close" onclick="this.parentElement.classList.remove('show')">×</button>
            </div>
            @endif
            @yield('content')
        </div>
    </div>
</div>

<script>
function openModal(id){const m=document.getElementById(id);if(m){m.classList.add('active');document.body.style.overflow='hidden';}}
function closeModal(id){const m=document.getElementById(id);if(m){m.classList.remove('active');document.body.style.overflow='';}}
document.querySelectorAll('.modal-overlay').forEach(o=>o.addEventListener('click',e=>{if(e.target===o)closeModal(o.id);}));
document.addEventListener('keydown',e=>{if(e.key==='Escape')document.querySelectorAll('.modal-overlay.active').forEach(m=>closeModal(m.id));});
const obs=new IntersectionObserver(e=>e.forEach(i=>{if(i.isIntersecting)i.target.classList.add('visible');}),{threshold:.06});
document.querySelectorAll('.fade-up').forEach(el=>obs.observe(el));
['adminToast','adminToastErr'].forEach(id=>{const el=document.getElementById(id);if(el)setTimeout(()=>el.classList.remove('show'),6000);});
function adminSearchTable(q) {
    const rows = document.querySelectorAll('.searchable-row');
    const lq = q.toLowerCase();
    rows.forEach(r => {
        r.style.display = r.textContent.toLowerCase().includes(lq) ? '' : 'none';
    });
}
</script>
@stack('scripts')
</body>
</html>