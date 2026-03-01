{{-- resources/views/admin/partials/sidebar.blade.php --}}

<aside style="background: var(--green-dark); padding: 1.5rem 0; position:sticky; top:65px; height:calc(100vh - 65px); overflow-y:auto;">
    <div style="padding: 0 1.5rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 1rem;">
        <div style="color:var(--gold); font-family:'Playfair Display',serif; font-style:italic; font-size:1.1rem;">
            🍵 Thé Tip Top
        </div>
        <div style="color:rgba(255,255,255,0.55); font-size:0.8rem; margin-top:0.3rem;">
            {{ auth()->user()->role->name === 'admin' ? 'Administrateur' : 'Employé' }}
        </div>
    </div>

    @php
    $links = [
        ['route' => 'admin.dashboard',       'icon' => '📊', 'label' => 'Vue d\'ensemble'],
        ['route' => 'admin.participations',  'icon' => '🎟️', 'label' => 'Participations'],
        ['route' => 'admin.redemptions',     'icon' => '📦', 'label' => 'Réclamations'],
    ];
    if (auth()->user()->role->name === 'admin') {
        $links = array_merge($links, [
            ['route' => 'admin.prizes',  'icon' => '🏆', 'label' => 'Lots & Prizes'],
            ['route' => 'admin.tickets', 'icon' => '🎫', 'label' => 'Tickets'],
            ['route' => 'admin.users',   'icon' => '👥', 'label' => 'Utilisateurs'],
        ]);
    }
    @endphp

    @foreach($links as $link)
    <a href="{{ route($link['route']) }}"
       style="display:flex; align-items:center; gap:0.7rem; padding:0.7rem 1.5rem; color:rgba(255,255,255,{{ request()->routeIs($link['route']) ? '1' : '0.7' }}); font-size:0.9rem; border-left:3px solid {{ request()->routeIs($link['route']) ? 'var(--gold)' : 'transparent' }}; background:{{ request()->routeIs($link['route']) ? 'rgba(255,255,255,0.1)' : 'transparent' }}; transition:all 0.2s;">
        {{ $link['icon'] }} {{ $link['label'] }}
    </a>
    @endforeach

    <div style="position:absolute; bottom:0; left:0; right:0; padding:1rem 1.5rem; border-top:1px solid rgba(255,255,255,0.1);">
        <div style="font-size:0.8rem; color:rgba(255,255,255,0.6); margin-bottom:0.4rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
            {{ auth()->user()->email }}
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none; border:none; color:rgba(255,255,255,0.5); font-size:0.82rem; cursor:pointer; font-family:inherit; padding:0;">
                ↩ Déconnexion
            </button>
        </form>
    </div>
</aside>