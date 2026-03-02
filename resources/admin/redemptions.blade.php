@extends('layouts.app')
@section('title', 'Réclamations — Admin Thé Tip Top')

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
            <h1 style="font-size:1.4rem;">📦 Réclamations en attente</h1>
            @if($pendingCount > 0)
                <span style="background:var(--orange); color:white; border-radius:20px; padding:0.3rem 1rem; font-size:0.85rem;">
                    {{ $pendingCount }} en attente
                </span>
            @endif
        </div>

        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif

        <div style="background:white; border-radius:14px; overflow:hidden; box-shadow:var(--shadow-sm);">
            @if($pending->isEmpty())
                <div style="padding:3rem; text-align:center; color:var(--text-light);">
                    <div style="font-size:3rem; margin-bottom:1rem;">✅</div>
                    <p>Aucune réclamation en attente.</p>
                </div>
            @else
                <table class="table-participations">
                    <thead>
                        <tr>
                            <th>Participant</th>
                            <th>Lot gagné</th>
                            <th>Méthode</th>
                            <th>Demandé le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending as $r)
                        <tr>
                            <td style="font-size:0.88rem;">{{ $r->participation->user->email ?? '—' }}</td>
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
                            <td style="color:var(--text-light); font-size:0.85rem;">
                                {{ \Carbon\Carbon::parse($r->requested_at)->format('d/m/Y') }}
                            </td>
                            <td>
                                <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                                    <form method="POST" action="{{ route('admin.redemption.status', $r->id) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-green" style="padding:0.3rem 0.9rem; font-size:0.8rem;">✓ Approuver</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.redemption.status', $r->id) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" style="background:#d1ecf1; border:none; color:#0c5460; padding:0.3rem 0.9rem; border-radius:6px; cursor:pointer; font-size:0.8rem;">📦 Remis</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.redemption.status', $r->id) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" style="background:#fde8e8; border:none; color:#b91c1c; padding:0.3rem 0.9rem; border-radius:6px; cursor:pointer; font-size:0.8rem;">✗ Refuser</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="padding:1rem 1.5rem;">{{ $pending->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection