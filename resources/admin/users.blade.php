@extends('layouts.app')
@section('title', 'Utilisateurs — Admin Thé Tip Top')

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
            <h1 style="font-size:1.4rem;">👥 Utilisateurs</h1>
            <span style="background:var(--cream); padding:0.4rem 1rem; border-radius:20px; font-size:0.85rem; color:var(--text-mid);">
                Total : {{ $users->total() }}
            </span>
        </div>

        <form method="GET" style="margin-bottom:1.2rem; display:flex; gap:0.7rem;">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="input-field" placeholder="Rechercher par email..."
                   style="max-width:350px;">
            <button type="submit" class="btn btn-green" style="padding:0.6rem 1.2rem;">Rechercher</button>
            @if(request('search'))
                <a href="{{ route('admin.users') }}" class="btn btn-outline" style="padding:0.6rem 1.2rem;">Réinitialiser</a>
            @endif
        </form>

        <div style="background:white; border-radius:14px; overflow:hidden; box-shadow:var(--shadow-sm);">
            <table class="table-participations">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Date de naissance</th>
                        <th>Inscrit le</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td style="font-size:0.88rem; font-weight:500;">{{ $user->email }}</td>
                        <td>
                            @php $roleColors = ['admin' => '#f0d4f4,#5e1a6e', 'employee' => '#d4e8f4,#1a4a7a', 'user' => '#d4f4e2,#1a5e2e']; [$bg,$fg] = explode(',', $roleColors[$user->role->name] ?? '#f0f0f0,#666'); @endphp
                            <span style="background:{{ $bg }}; color:{{ $fg }}; padding:0.2rem 0.8rem; border-radius:12px; font-size:0.8rem; font-weight:600;">
                                {{ ucfirst($user->role->name) }}
                            </span>
                        </td>
                        <td style="color:var(--text-light); font-size:0.85rem;">
                            {{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d/m/Y') : '—' }}
                        </td>
                        <td style="color:var(--text-light); font-size:0.85rem;">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center; padding:2rem; color:var(--text-light);">Aucun utilisateur trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:1.2rem;">{{ $users->links() }}</div>
    </div>
</div>
@endsection