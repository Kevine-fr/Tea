@extends('layouts.app')
@section('title', 'Participations — Admin Thé Tip Top')

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

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h1 style="font-size:1.4rem;">🎟️ Participations</h1>
            <span style="background:var(--cream); padding:0.4rem 1rem; border-radius:20px; font-size:0.85rem; color:var(--text-mid);">
                Total : {{ $participations->total() }}
            </span>
        </div>

        {{-- Recherche --}}
        <form method="GET" style="margin-bottom:1.2rem; display:flex; gap:0.7rem;">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="input-field" placeholder="Rechercher par email ou code..."
                   style="max-width:350px;">
            <button type="submit" class="btn btn-green" style="padding:0.6rem 1.2rem;">Rechercher</button>
            @if(request('search'))
                <a href="{{ route('admin.participations') }}" class="btn btn-outline" style="padding:0.6rem 1.2rem;">Réinitialiser</a>
            @endif
        </form>

        <div style="background:white; border-radius:14px; overflow:hidden; box-shadow:var(--shadow-sm);">
            <table class="table-participations">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Code ticket</th>
                        <th>Date</th>
                        <th>Lot gagné</th>
                        <th>Réclamation</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participations as $p)
                    <tr>
                        <td style="font-size:0.88rem;">{{ $p->user->email }}</td>
                        <td><span style="font-family:monospace; color:var(--green-dark); font-weight:600;">{{ $p->ticketCode->code }}</span></td>
                        <td style="color:var(--text-light); font-size:0.85rem;">{{ $p->participation_date->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($p->prize)
                                <span class="badge badge-won">{{ $p->prize->name }}</span>
                            @else
                                <span style="color:var(--text-light); font-size:0.85rem;">—</span>
                            @endif
                        </td>
                        <td>
                            @if($p->redemption)
                                @php $colors = ['pending'=>'#fff3cd,#856404','approved'=>'#d4f4e2,#1a5e2e','completed'=>'#d1ecf1,#0c5460','rejected'=>'#fde8e8,#b91c1c']; [$bg,$fg] = explode(',', $colors[$p->redemption->status] ?? '#f0f0f0,#666'); @endphp
                                <span style="background:{{ $bg }}; color:{{ $fg }}; padding:0.2rem 0.7rem; border-radius:12px; font-size:0.78rem; font-weight:600;">
                                    {{ ucfirst($p->redemption->status) }}
                                </span>
                            @else
                                <span style="color:var(--text-light); font-size:0.82rem;">Non réclamé</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center; padding:2rem; color:var(--text-light);">Aucune participation trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:1.2rem;">{{ $participations->links() }}</div>
    </div>
</div>
@endsection