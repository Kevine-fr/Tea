@extends('layouts.app')
@section('title', 'Tickets — Admin Thé Tip Top')

@push('styles')
<style>
.admin-layout { display: grid; grid-template-columns: 250px 1fr; min-height: calc(100vh - 65px); }
.admin-main { padding: 2rem; background: #f3f4f6; }
</style>
@endpush

@section('content')
<div class="admin-layout">
    @include('admin.partials.sidebar')

    <div class="admin-main">
        <h1 style="font-size:1.4rem; margin-bottom:1.5rem;">🎫 Gestion des tickets</h1>

        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif

        {{-- Stats --}}
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1.2rem; margin-bottom:2rem;">
            @foreach([
                ['label' => 'Total générés', 'value' => $stats['total'],     'color' => '#e0f0ff', 'fg' => '#1a4a7a', 'icon' => '🎫'],
                ['label' => 'Utilisés',       'value' => $stats['used'],      'color' => '#fde8e8', 'fg' => '#b91c1c', 'icon' => '✅'],
                ['label' => 'Disponibles',    'value' => $stats['available'], 'color' => '#d4f4e2', 'fg' => '#1a5e2e', 'icon' => '🟢'],
            ] as $s)
            <div style="background:white; border-radius:14px; padding:1.4rem; display:flex; align-items:center; gap:1rem; box-shadow:var(--shadow-sm);">
                <div style="width:48px; height:48px; border-radius:12px; background:{{ $s['color'] }}; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">
                    {{ $s['icon'] }}
                </div>
                <div>
                    <div style="font-size:1.8rem; font-weight:700; color:{{ $s['fg'] }};">{{ number_format($s['value']) }}</div>
                    <div style="font-size:0.82rem; color:var(--text-light);">{{ $s['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Générer des tickets --}}
        <div style="background:white; border-radius:14px; padding:1.5rem; box-shadow:var(--shadow-sm); margin-bottom:2rem;">
            <h2 style="font-size:1rem; font-weight:700; margin-bottom:1rem;">Générer de nouveaux codes</h2>
            <form method="POST" action="{{ route('admin.tickets.generate') }}"
                  style="display:flex; gap:1rem; align-items:flex-end;">
                @csrf
                <div style="flex:1;">
                    <label style="font-size:0.85rem; color:var(--text-mid); display:block; margin-bottom:0.4rem;">
                        Quantité (max 10 000)
                    </label>
                    <input type="number" name="quantity" class="input-field"
                           value="100" min="1" max="10000" style="max-width:200px;">
                </div>
                <button type="submit" class="btn btn-orange">🎫 Générer</button>
            </form>
        </div>

        {{-- Liste derniers tickets --}}
        <div style="background:white; border-radius:14px; overflow:hidden; box-shadow:var(--shadow-sm);">
            <div style="padding:1rem 1.5rem; border-bottom:1px solid var(--border); font-weight:600; font-size:0.9rem;">
                20 derniers codes générés
            </div>
            <table class="table-participations">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Statut</th>
                        <th>Créé le</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTickets as $ticket)
                    <tr>
                        <td><span style="font-family:monospace; color:var(--green-dark); font-weight:600; font-size:1rem;">{{ $ticket->code }}</span></td>
                        <td>
                            @if($ticket->is_used)
                                <span class="badge badge-won">✅ Utilisé</span>
                            @else
                                <span style="background:#d4f4e2; color:#1a5e2e; padding:0.2rem 0.7rem; border-radius:12px; font-size:0.8rem; font-weight:600;">Disponible</span>
                            @endif
                        </td>
                        <td style="color:var(--text-light); font-size:0.85rem;">
                            {{ $ticket->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection