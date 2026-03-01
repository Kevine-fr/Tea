@extends('layouts.app')

@section('title', 'Mon Espace — Thé Tip Top')

@push('styles')
<style>
body { background: var(--cream-light); }

/* Override navbar for dashboard */
.dash-header {
    background: var(--white);
    padding: 0.9rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    position: sticky;
    top: 0;
    z-index: 99;
}

/* Code input special */
.code-input {
    font-family: 'Courier New', monospace;
    font-size: 1.1rem;
    letter-spacing: 4px;
    text-transform: uppercase;
    text-align: center;
    padding: 0.9rem 1.5rem;
    border: 2px solid var(--border);
    border-radius: 10px;
    background: var(--white);
    width: 100%;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.code-input:focus {
    border-color: var(--green-light);
    box-shadow: 0 0 0 3px rgba(61,122,90,0.12);
}

/* Status message after code check */
.code-status-success {
    background: #d4f4e2;
    border: 1.5px solid #6fcf97;
    color: #1a5e2e;
    border-radius: 8px;
    padding: 0.8rem 1.2rem;
    font-size: 0.9rem;
    font-weight: 500;
}
.code-status-error {
    background: #fde8e8;
    border: 1.5px solid #f5b6b6;
    color: #b91c1c;
    border-radius: 8px;
    padding: 0.8rem 1.2rem;
    font-size: 0.9rem;
}
.code-status-empty {
    background: var(--white);
    border: 1.5px solid var(--border);
    color: var(--text-light);
    border-radius: 8px;
    padding: 0.8rem 1.2rem;
    font-size: 0.9rem;
    min-height: 2.8rem;
}

/* Sections */
.dash-section {
    background: var(--cream-light);
    padding: 3rem 1.5rem;
}
.dash-section-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem;
    text-align: center;
    margin-bottom: 1.8rem;
    color: var(--text-dark);
}

/* Dashboard card */
.dash-card {
    background: var(--white);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: var(--shadow-sm);
    max-width: 850px;
    margin: 0 auto;
}
</style>
@endpush

@section('content')

{{-- ── Header Dashboard ── --}}
<div class="dash-header">
    <div class="dash-user">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
        </svg>
        <span>Mon profil</span>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" style="background:none; border:none; cursor:pointer; font-size:0.9rem; color:var(--text-mid); font-family:inherit;">
            Deconnexion
        </button>
    </form>
</div>

{{-- ══════════════════════════════════════════
     1. SAISIR MON CODE
══════════════════════════════════════════ --}}
<section class="dash-section">
    <div class="container" style="max-width: 850px;">
        <h2 class="dash-section-title">Saisir mon code de participation</h2>

        @if(session('participation_success'))
            <div class="alert alert-success" style="max-width:850px; margin: 0 auto 1.5rem;">
                🎉 {{ session('participation_success') }}
            </div>
        @endif
        @if(session('participation_error'))
            <div class="alert alert-error" style="max-width:850px; margin: 0 auto 1.5rem;">
                {{ session('participation_error') }}
            </div>
        @endif

        <div class="dash-card">
            <div class="grid-2" style="align-items: center; gap: 2rem;">

                {{-- Illustration --}}
                <div style="display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, #f0efe8, #e8e0d0); border-radius: 14px; padding: 2.5rem; min-height: 160px;">
                    <div style="text-align: center;">
                        <div style="font-size: 5rem; line-height: 1;">🍵</div>
                        <p style="font-family:'Playfair Display',serif; font-style:italic; font-size:0.85rem; color:var(--gold); margin-top:0.5rem;">Thé Tip Top</p>
                    </div>
                </div>

                {{-- Formulaire code --}}
                <div>
                    <form method="POST" action="{{ route('participate') }}">
                        @csrf
                        <label for="ticket_code" style="display:block; font-size:0.9rem; color:var(--text-mid); margin-bottom:0.6rem; font-weight:500;">
                            Entrez votre N° de ticket
                        </label>
                        <input type="text"
                               id="ticket_code"
                               name="code"
                               class="code-input @error('code') border-red-500 @enderror"
                               placeholder="Code ticket.."
                               maxlength="10"
                               value="{{ old('code') }}"
                               autocomplete="off">
                        @error('code')
                            <span style="color:var(--orange); font-size:0.82rem; display:block; margin-top:0.3rem;">{{ $message }}</span>
                        @enderror

                        <div style="display:flex; gap: 1rem; margin-top: 1.2rem;">
                            <button type="button" onclick="addTicket()"
                                    class="btn btn-green" style="flex:1; font-size:0.9rem;">
                                Ajouter un ticket
                            </button>
                            <button type="submit"
                                    class="btn btn-orange" style="flex:1; font-size:0.9rem;">
                                Valider
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     2. VÉRIFIER MON CODE
══════════════════════════════════════════ --}}
<section class="dash-section section-beige">
    <div class="container" style="max-width: 850px;">
        <h2 class="dash-section-title">Vérifier mon code</h2>

        <div class="dash-card">
            <p style="font-size:0.85rem; color:var(--text-mid); margin-bottom:1rem; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">
                Etat du code
            </p>

            @if(session('code_status'))
                <div class="code-status-{{ session('code_status_type', 'success') }}">
                    {{ session('code_status') }}
                </div>
            @elseif($lastParticipation ?? false)
                <div class="code-status-success">
                    🎉 Félicitations ! Vous avez remporté un lot.
                </div>
            @else
                <div class="code-status-empty">
                    Aucun code vérifié récemment.
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     3. MES PARTICIPATIONS
══════════════════════════════════════════ --}}
<section class="dash-section">
    <div class="container" style="max-width: 850px;">
        <h2 class="dash-section-title">Mes participations au jeu-concours</h2>

        <div style="background: var(--white); border-radius: 16px; overflow:hidden; box-shadow: var(--shadow-sm);">
            @if($participations->isEmpty())
                <div style="padding: 3rem; text-align:center; color:var(--text-light);">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🫖</div>
                    <p>Vous n'avez pas encore participé.</p>
                    <p style="font-size: 0.85rem; margin-top: 0.4rem;">Saisissez votre code ticket pour commencer !</p>
                </div>
            @else
                <table class="table-participations">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Date</th>
                            <th>Résultat</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participations as $p)
                        <tr>
                            <td>
                                <span style="font-family:monospace; font-size:0.95rem; color:var(--green-dark); font-weight:600;">
                                    {{ $p->ticketCode->code }}
                                </span>
                            </td>
                            <td style="color:var(--text-mid); font-size:0.88rem;">
                                {{ $p->participation_date->format('d/m/Y') }}
                            </td>
                            <td>
                                @if($p->hasWon())
                                    <span class="badge badge-won">🏆 Gagné</span>
                                @elseif($p->redemption)
                                    <span class="badge badge-pending">⏳ En attente</span>
                                @else
                                    <span class="badge badge-lost">Pas de lot</span>
                                @endif
                            </td>
                            <td>
                                @if($p->hasWon() && !$p->redemption)
                                    <a href="{{ route('redemption.create', $p->id) }}"
                                       class="btn btn-orange" style="font-size:0.8rem; padding: 0.3rem 1rem;">
                                        Réclamer
                                    </a>
                                @elseif($p->redemption)
                                    <span style="font-size:0.82rem; color:var(--text-light);">
                                        {{ ucfirst($p->redemption->status) }}
                                    </span>
                                @else
                                    <span style="font-size:0.82rem; color:var(--text-light);">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                @if($participations->hasPages())
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border);">
                    {{ $participations->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     4. SUIVI DE MES GAINS
══════════════════════════════════════════ --}}
<section class="dash-section section-beige">
    <div class="container" style="max-width: 850px;">
        <h2 class="dash-section-title">Suivi de mes gains</h2>

        <div style="background:var(--white); border-radius:16px; overflow:hidden; box-shadow:var(--shadow-sm);">

            @php
                $pending   = $participations->filter(fn($p) => $p->redemption && $p->redemption->status === 'pending');
                $approved  = $participations->filter(fn($p) => $p->redemption && $p->redemption->status === 'approved');
                $completed = $participations->filter(fn($p) => $p->redemption && $p->redemption->status === 'completed');
            @endphp

            <div class="gains-grid">
                {{-- Colonne En cours de traitement --}}
                <div class="gains-col">
                    <div class="gains-header">Gains en cours de traitement</div>
                    @forelse($pending as $p)
                        <div class="gains-item">{{ $p->prize->name ?? 'Lot' }}</div>
                    @empty
                        <div class="gains-item" style="color:var(--text-light); font-style:italic;">Aucun</div>
                    @endforelse
                </div>

                {{-- Colonne Gains réclamés --}}
                <div class="gains-col">
                    <div class="gains-header">Gains réclamés</div>
                    @forelse($approved as $p)
                        <div class="gains-item">{{ $p->prize->name ?? 'Lot' }}</div>
                    @empty
                        <div class="gains-item" style="color:var(--text-light); font-style:italic;">Aucun</div>
                    @endforelse
                </div>

                {{-- Colonne Gains remis / livrés --}}
                <div class="gains-col">
                    <div class="gains-header">Gains remis / livrés</div>
                    @forelse($completed as $p)
                        <div class="gains-item">
                            <div style="font-weight:500;">{{ $p->prize->name ?? 'Lot' }}</div>
                            <div style="font-size:0.8rem; color:var(--text-light); margin-top:2px;">
                                @if($p->redemption->method === 'store') En boutique
                                @elseif($p->redemption->method === 'mail') Par courrier
                                @else En ligne @endif
                            </div>
                        </div>
                    @empty
                        <div class="gains-item" style="color:var(--text-light); font-style:italic;">Aucun</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function addTicket() {
    const input = document.getElementById('ticket_code');
    input.value = '';
    input.focus();
}

// Auto-uppercase code input
document.getElementById('ticket_code').addEventListener('input', function() {
    this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
});
</script>
@endpush