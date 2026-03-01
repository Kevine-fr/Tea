@extends('layouts.app')

@section('title', 'Tableau de bord Admin — Thé Tip Top')

@push('styles')
<style>
.admin-layout { display: grid; grid-template-columns: 250px 1fr; min-height: calc(100vh - 65px); }
.admin-sidebar {
    background: var(--green-dark);
    padding: 1.5rem 0;
    position: sticky; top: 65px; height: calc(100vh - 65px);
    overflow-y: auto;
}
.sidebar-section { margin-bottom: 2rem; }
.sidebar-label {
    color: rgba(255,255,255,0.4);
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    padding: 0 1.5rem;
    margin-bottom: 0.5rem;
    display: block;
}
.sidebar-link {
    display: flex; align-items: center; gap: 0.7rem;
    padding: 0.7rem 1.5rem;
    color: rgba(255,255,255,0.7);
    font-size: 0.9rem;
    transition: all 0.2s;
    border-left: 3px solid transparent;
}
.sidebar-link:hover, .sidebar-link.active {
    background: rgba(255,255,255,0.1);
    color: var(--white);
    border-left-color: var(--gold);
}
.admin-main { padding: 2rem; background: #f3f4f6; }
.stat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.2rem; margin-bottom: 2rem; }
.stat-card {
    background: var(--white); border-radius: 14px;
    padding: 1.4rem; box-shadow: var(--shadow-sm);
    display: flex; align-items: center; gap: 1rem;
}
.stat-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
}
.stat-value { font-size: 1.7rem; font-weight: 700; color: var(--text-dark); line-height: 1; }
.stat-label { font-size: 0.8rem; color: var(--text-light); margin-top: 0.2rem; }
.admin-card {
    background: var(--white); border-radius: 14px;
    padding: 1.5rem; box-shadow: var(--shadow-sm); margin-bottom: 1.5rem;
}
.admin-card-title {
    font-size: 1rem; font-weight: 700; color: var(--text-dark);
    margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.5rem;
}
</style>
@endpush

@section('content')

<div class="admin-layout">

    {{-- ──────────── SIDEBAR ──────────── --}}
    <aside class="admin-sidebar">
        <div style="padding: 0 1.5rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 1rem;">
            <div style="color:var(--gold); font-family:'Playfair Display',serif; font-style:italic; font-size:1.1rem;">
                🍵 Thé Tip Top
            </div>
            <div style="color:rgba(255,255,255,0.55); font-size:0.8rem; margin-top:0.3rem;">
                Panel {{ auth()->user()->role->name === 'admin' ? 'Administrateur' : 'Employé' }}
            </div>
        </div>

        <div class="sidebar-section">
            <span class="sidebar-label">Tableau de bord</span>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link active">📊 Vue d'ensemble</a>
        </div>

        <div class="sidebar-section">
            <span class="sidebar-label">Jeu-concours</span>
            <a href="{{ route('admin.participations') }}" class="sidebar-link">🎟️ Participations</a>
            <a href="{{ route('admin.redemptions') }}" class="sidebar-link">
                📦 Réclamations
                @if($pendingCount > 0)
                    <span style="background:var(--orange); color:white; border-radius:10px; padding:0.1rem 0.5rem; font-size:0.75rem; margin-left:auto;">{{ $pendingCount }}</span>
                @endif
            </a>
        </div>

        @if(auth()->user()->role->name === 'admin')
        <div class="sidebar-section">
            <span class="sidebar-label">Administration</span>
            <a href="{{ route('admin.prizes') }}" class="sidebar-link">🏆 Lots & Prizes</a>
            <a href="{{ route('admin.tickets') }}" class="sidebar-link">🎫 Tickets</a>
            <a href="{{ route('admin.users') }}" class="sidebar-link">👥 Utilisateurs</a>
        </div>
        @endif

        <div style="position:absolute; bottom:0; left:0; right:0; padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.1);">
            <div style="font-size:0.85rem; color:rgba(255,255,255,0.7); margin-bottom:0.5rem;">
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

    {{-- ──────────── MAIN ──────────── --}}
    <div class="admin-main">

        {{-- Titre --}}
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.5rem;">
            <div>
                <h1 style="font-size:1.5rem; margin-bottom:0.2rem;">Tableau de bord</h1>
                <p style="color:var(--text-light); font-size:0.88rem;">
                    Bienvenue, {{ auth()->user()->email }} — Données en temps réel
                </p>
            </div>
            <div style="font-size:0.85rem; color:var(--text-light);">
                🕐 {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        {{-- ── STATS CARDS ── --}}
        <div class="stat-grid">
            @php
            $stats = [
                ['label' => 'Participations totales', 'value' => $stats['total_participations'], 'icon' => '🎟️', 'bg' => '#e0f0ff', 'color' => '#1a4a7a'],
                ['label' => 'Gagnants',                'value' => $stats['total_winners'],        'icon' => '🏆', 'bg' => '#d4f4e2', 'color' => '#1a5e2e'],
                ['label' => 'Stock lots restant',      'value' => $stats['prizes_remaining_stock'], 'icon' => '📦', 'bg' => '#f4e8d4', 'color' => '#7a4a1a'],
                ['label' => 'Réclamations',            'value' => $stats['total_redemptions'],    'icon' => '✅', 'bg' => '#f0d4f4', 'color' => '#5e1a6e'],
            ];
            @endphp

            @foreach($stats as $s)
            <div class="stat-card">
                <div class="stat-icon" style="background:{{ $s['bg'] }}; color:{{ $s['color'] }};">
                    {{ $s['icon'] }}
                </div>
                <div>
                    <div class="stat-value">{{ number_format($s['value']) }}</div>
                    <div class="stat-label">{{ $s['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">

            {{-- ── Réclamations en attente ── --}}
            <div class="admin-card" style="grid-column: span 2;">
                <div class="admin-card-title">
                    📦 Réclamations en attente
                    @if($pendingCount > 0)
                        <span style="background:var(--orange); color:white; border-radius:10px; padding:0.15rem 0.6rem; font-size:0.75rem;">
                            {{ $pendingCount }}
                        </span>
                    @endif
                    <a href="{{ route('admin.redemptions') }}" style="margin-left:auto; font-size:0.82rem; color:var(--green-light); font-weight:500;">
                        Voir tout →
                    </a>
                </div>

                @if($recentRedemptions->isEmpty())
                    <p style="color:var(--text-light); font-size:0.9rem; padding: 1rem 0; text-align:center;">
                        ✅ Aucune réclamation en attente.
                    </p>
                @else
                    <table class="table-participations" style="font-size:0.88rem;">
                        <thead>
                            <tr>
                                <th>Participant</th>
                                <th>Lot gagné</th>
                                <th>Méthode</th>
                                <th>Demandé le</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentRedemptions as $r)
                            <tr>
                                <td>{{ $r->participation->user->email }}</td>
                                <td>
                                    <span style="font-weight:500; color:var(--green-dark);">
                                        {{ $r->participation->prize->name ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <span style="background:var(--cream); padding:0.2rem 0.7rem; border-radius:12px; font-size:0.82rem;">
                                        @if($r->method === 'store') 🏪 Boutique
                                        @elseif($r->method === 'mail') 📮 Courrier
                                        @else 🌐 En ligne @endif
                                    </span>
                                </td>
                                <td style="color:var(--text-light);">
                                    {{ $r->requested_at->format('d/m/Y') }}
                                </td>
                                <td>
                                    <div style="display:flex; gap:0.5rem;">
                                        <form method="POST" action="{{ route('admin.redemption.status', $r->id) }}" style="display:inline;">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-green" style="padding:0.3rem 0.8rem; font-size:0.8rem;" title="Approuver">
                                                ✓
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.redemption.status', $r->id) }}" style="display:inline;">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" style="background:#fde8e8; border:none; color:#b91c1c; padding:0.3rem 0.8rem; border-radius:6px; cursor:pointer; font-size:0.8rem;" title="Rejeter">
                                                ✗
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- ── Dernières participations ── --}}
            <div class="admin-card">
                <div class="admin-card-title">
                    🎟️ Dernières participations
                    <a href="{{ route('admin.participations') }}" style="margin-left:auto; font-size:0.82rem; color:var(--green-light); font-weight:500;">
                        Voir tout →
                    </a>
                </div>
                @forelse($recentParticipations as $p)
                <div style="padding:0.6rem 0; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <div style="font-size:0.88rem; font-weight:500;">{{ $p->user->email }}</div>
                        <div style="font-family:monospace; font-size:0.8rem; color:var(--text-light);">
                            {{ $p->ticketCode->code }}
                        </div>
                    </div>
                    <div>
                        @if($p->hasWon())
                            <span class="badge badge-won" style="font-size:0.75rem;">🏆 Gagné</span>
                        @else
                            <span class="badge badge-lost" style="font-size:0.75rem;">—</span>
                        @endif
                    </div>
                </div>
                @empty
                <p style="color:var(--text-light); font-size:0.9rem;">Aucune participation.</p>
                @endforelse
            </div>

            {{-- ── Lots stock ── --}}
            <div class="admin-card">
                <div class="admin-card-title">
                    🏆 Stock des lots
                    @if(auth()->user()->role->name === 'admin')
                    <a href="{{ route('admin.prizes') }}" style="margin-left:auto; font-size:0.82rem; color:var(--green-light); font-weight:500;">
                        Gérer →
                    </a>
                    @endif
                </div>
                @foreach($prizes as $prize)
                <div style="margin-bottom:1rem;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.3rem;">
                        <span style="font-size:0.88rem; font-weight:500;">{{ $prize->name }}</span>
                        <span style="font-size:0.85rem; font-weight:700; color:{{ $prize->stock > 10 ? 'var(--green-light)' : ($prize->stock > 0 ? '#e67e22' : '#e74c3c') }};">
                            {{ $prize->stock }}
                        </span>
                    </div>
                    <div style="background: var(--cream); border-radius: 10px; height:8px; overflow:hidden;">
                        @php $maxStock = $prizes->max('stock') ?: 1; $pct = min(100, ($prize->stock / $maxStock) * 100); @endphp
                        <div style="height:100%; border-radius:10px; width:{{ $pct }}%;
                            background:{{ $prize->stock > 10 ? 'var(--green-light)' : ($prize->stock > 0 ? '#e67e22' : '#e74c3c') }};
                            transition: width 0.5s ease;">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>

@endsection