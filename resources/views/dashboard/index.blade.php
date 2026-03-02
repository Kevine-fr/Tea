@extends('layouts.dashboard')
@section('title','Tableau de bord — Thé Tip Top')

@section('page_styles')
.ticket-wrap{position:relative;z-index:1;padding:3rem 2rem 1.5rem;max-width:860px;margin:0 auto;}
.ticket-card{display:grid;grid-template-columns:220px 1fr;gap:0;background:var(--white);border-radius:18px;box-shadow:var(--sh-sm);overflow:hidden;margin-top:1.8rem}
.ticket-logo{background:var(--cream-m);display:flex;align-items:center;justify-content:center;padding:2rem 1.5rem}
.ticket-form{padding:2rem 2.2rem}
.code-input{text-transform:uppercase;letter-spacing:.1em;text-align:center;font-size:1rem;font-weight:600}
.progress-bar{height:3px;border-radius:2px;background:var(--cream-d);margin-bottom:1.2rem;overflow:hidden;position:relative}
.progress-fill{height:100%;border-radius:2px;width:0;transition:width .4s ease,background .4s ease}
.part-wrap{position:relative;z-index:1;padding:1.5rem 2rem 4rem;max-width:860px;margin:0 auto}
.part-card{background:var(--cream-m);border-radius:18px;padding:2.2rem}
.part-table-wrap{background:var(--white);border-radius:14px;overflow:hidden;box-shadow:var(--sh-sm)}
.code-badge{font-family:monospace;font-weight:700;font-size:.95rem;letter-spacing:.06em;color:var(--green)}
.status-gagné{color:var(--green-m);font-weight:700}
.status-attente{color:var(--txt-l);font-style:italic}
.empty-state{text-align:center;padding:3.5rem 1rem}
.empty-icon{font-size:3rem;margin-bottom:.8rem}
@endsection

@section('content')

{{-- Banner --}}
<section class="page-banner fade-up">
    <svg class="orn" viewBox="0 0 90 70" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M20 60C24 44,40 36,35 18C46 32,54 50,40 64" fill="#b8962e" opacity=".35"/>
        <path d="M20 60C30 50,38 40,35 18" stroke="#b8962e" stroke-width="1.5" fill="none"/>
        <path d="M45 55C48 42,60 36,56 22C65 34,70 50,58 62" fill="#b8962e" opacity=".28"/>
        <path d="M45 55C52 46,57 38,56 22" stroke="#b8962e" stroke-width="1.5" fill="none"/>
    </svg>
    <div style="text-align:center;flex:1"><span class="banner-tape">Tableau de bord</span></div>
    <svg class="orn" viewBox="0 0 90 70" fill="none" style="transform:scaleX(-1)">
        <path d="M20 60C24 44,40 36,35 18C46 32,54 50,40 64" fill="#b8962e" opacity=".35"/>
        <path d="M20 60C30 50,38 40,35 18" stroke="#b8962e" stroke-width="1.5" fill="none"/>
        <path d="M45 55C48 42,60 36,56 22C65 34,70 50,58 62" fill="#b8962e" opacity=".28"/>
        <path d="M45 55C52 46,57 38,56 22" stroke="#b8962e" stroke-width="1.5" fill="none"/>
    </svg>
</section>

{{-- Code Entry --}}
<section class="ticket-wrap">
    <h2 class="fade-up s1" style="font-family:'Playfair Display',serif;font-size:1.65rem;font-weight:700;text-align:center;">
        Saisir mon code de participation
    </h2>

    @if($errors->has('code'))
    <div class="alert alert-error fade-up s2" style="max-width:600px;margin:1rem auto 0">{{ $errors->first('code') }}</div>
    @endif

    <div class="ticket-card fade-up s2">
        {{-- Logo side --}}
        <div class="ticket-logo">
            <svg viewBox="0 0 100 120" fill="none" width="90">
                <!-- Cup body -->
                <path d="M18 65Q16 100,50 108Q84 100,82 65Z" fill="#1e3d1a"/>
                <ellipse cx="50" cy="65" rx="32" ry="10" fill="#2d5a27"/>
                <!-- Handle -->
                <path d="M82 72Q100 72,100 85Q100 98,82 96" stroke="#1e3d1a" stroke-width="5" fill="none" stroke-linecap="round"/>
                <!-- Saucer -->
                <ellipse cx="50" cy="109" rx="44" ry="8" fill="#d4b44a" opacity=".5"/>
                <!-- Gold ring -->
                <path d="M22 73Q50 80,78 73" stroke="#d4b44a" stroke-width="1.5" fill="none" opacity=".6"/>
                <!-- Leaf 1 -->
                <path d="M38 48C34 33,46 18,42 4C48 18,62 22,58 36C54 48,40 44,38 48Z" fill="#4a7c3f"/>
                <path d="M38 48C42 33,46 18,46 6" stroke="#2d5a27" stroke-width="1.5" fill="none"/>
                <!-- Leaf 2 -->
                <path d="M56 40C52 28,62 16,60 4C65 16,76 20,74 32C72 42,58 38,56 40Z" fill="#6a9c5f"/>
                <path d="M56 40C60 28,63 16,62 5" stroke="#4a7c3f" stroke-width="1.5" fill="none"/>
                <!-- Gold dots -->
                <circle cx="40" cy="88" r="2.5" fill="#d4b44a" opacity=".45"/>
                <circle cx="60" cy="92" r="2" fill="#d4b44a" opacity=".4"/>
            </svg>
        </div>

        {{-- Form side --}}
        <div class="ticket-form">
            <label class="form-label" for="codeInput">Entrez votre N° de ticket</label>
            <form method="POST" action="{{ route('participate') }}" id="ticketForm" novalidate>
                @csrf
                <input type="text" name="code" id="codeInput"
                    class="input-field code-input"
                    placeholder="Code ticket.."
                    maxlength="20"
                    value="{{ old('code') }}"
                    autocomplete="off"
                    oninput="handleCode(this.value)"
                    style="margin-bottom:.7rem">
                <div class="progress-bar"><div class="progress-fill" id="progressFill"></div></div>
                <div style="text-align:center">
                    <button type="submit" class="btn btn-orange" id="validateBtn" disabled
                        style="min-width:180px">Valider</button>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- Participations --}}
<section class="part-wrap">
    <div class="part-card fade-up s3">
        <h2 style="font-family:'Playfair Display',serif;font-size:1.65rem;font-weight:700;text-align:center;margin-bottom:1.8rem">
            Mes participations au jeu-concours
        </h2>
        <div class="part-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>N° Ticket</th>
                        <th>Résultat</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participations as $p)
                    <tr>
                        <td><span class="code-badge">{{ $p->ticketCode->code ?? '—' }}</span></td>
                        <td>
                            @if($p->prize)
                                <span class="win-text">🎉 Gagné</span>
                            @else
                                <span class="status-attente">En attente</span>
                            @endif
                        </td>
                        <td style="color:var(--txt-l);font-size:.83rem">
                            {{ \Carbon\Carbon::parse($p->participation_date)->format('d/m/Y') }}
                        </td>
                        <td>
                            @if($p->prize && !$p->redemption)
                                <a href="{{ route('redemption.create', $p->id) }}"
                                   class="btn btn-orange btn-sm">Réclamer →</a>
                            @elseif($p->redemption)
                                <span style="font-size:.8rem;color:var(--green-m);font-weight:600">
                                    @if($p->redemption->status==='completed') ✅ Remis
                                    @elseif($p->redemption->status==='approved') ✔ Approuvé
                                    @else ⏳ En cours @endif
                                </span>
                            @else
                                <span style="color:var(--cream-d)">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <div class="empty-icon">🎫</div>
                                <div style="font-weight:600;margin-bottom:.4rem">Aucune participation pour l'instant</div>
                                <div style="font-size:.85rem;color:var(--txt-l)">Entrez votre premier code ci-dessus pour participer</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($participations->hasPages())
        <div style="padding:1.2rem;text-align:center">{{ $participations->links() }}</div>
        @endif
    </div>
</section>

@push('scripts')
<script>
function handleCode(v) {
    const btn = document.getElementById('validateBtn');
    const fill = document.getElementById('progressFill');
    const len = v.trim().length;
    document.getElementById('codeInput').value = v.toUpperCase();
    if (len === 0) {
        fill.style.width = '0'; fill.style.background = 'var(--cream-d)';
        btn.disabled = true; btn.style.opacity = '.5';
    } else if (len < 5) {
        fill.style.width = '33%'; fill.style.background = '#e67e22';
        btn.disabled = true; btn.style.opacity = '.6';
    } else if (len < 8) {
        fill.style.width = '66%'; fill.style.background = '#f1c40f';
        btn.disabled = false; btn.style.opacity = '.85';
    } else {
        fill.style.width = '100%'; fill.style.background = 'var(--green-m)';
        btn.disabled = false; btn.style.opacity = '1';
    }
}
document.getElementById('ticketForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('validateBtn');
    btn.textContent = '⏳ Validation…';
    btn.disabled = true;
});
</script>
@endpush
@endsection