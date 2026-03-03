@extends('layouts.dashboard')
@section('title','Suivi des gains — Thé Tip Top')

@section('page_styles')
.gains-wrap{position:relative;z-index:1;padding:3rem 2.5rem 5rem;max-width:900px;margin:0 auto}
.gains-table-card{background:var(--white);border-radius:18px;box-shadow:var(--sh-sm);overflow:hidden}
.status-remis{color:var(--txt);font-weight:600}
.status-prep{color:var(--txt);font-weight:600}
.status-dispo{color:var(--txt);font-weight:600}
.status-rejected{color:#c0392b;font-weight:600}
.status-pending{color:var(--txt-l);font-weight:500}
.badge-stat{display:inline-block;padding:.22rem .85rem;border-radius:20px;font-size:.8rem;font-weight:600}
@endsection

@section('content')

{{-- Banner --}}
<section class="page-banner fade-up">
    <svg class="orn" viewBox="0 0 90 70" fill="none">
        <path d="M20 60C24 44,40 36,35 18C46 32,54 50,40 64" fill="#b8962e" opacity=".35"/>
        <path d="M20 60C30 50,38 40,35 18" stroke="#b8962e" stroke-width="1.5" fill="none"/>
        <path d="M45 55C48 42,60 36,56 22C65 34,70 50,58 62" fill="#b8962e" opacity=".28"/>
        <path d="M45 55C52 46,57 38,56 22" stroke="#b8962e" stroke-width="1.5" fill="none"/>
    </svg>
    <div style="text-align:center;flex:1"><span class="banner-tape">Suivi des gains</span></div>
    <svg class="orn" viewBox="0 0 90 70" fill="none" style="transform:scaleX(-1)">
        <path d="M20 60C24 44,40 36,35 18C46 32,54 50,40 64" fill="#b8962e" opacity=".35"/>
        <path d="M20 60C30 50,38 40,35 18" stroke="#b8962e" stroke-width="1.5" fill="none"/>
        <path d="M45 55C48 42,60 36,56 22C65 34,70 50,58 62" fill="#b8962e" opacity=".28"/>
        <path d="M45 55C52 46,57 38,56 22" stroke="#b8962e" stroke-width="1.5" fill="none"/>
    </svg>
</section>

{{-- Main content --}}
<section class="gains-wrap">
    <h2 class="fade-up" style="font-family:'Playfair Display',serif;font-size:1.7rem;font-weight:700;text-align:center;margin-bottom:2rem">
        Suivi de mes gains
    </h2>

    <div class="gains-table-card fade-up s1">
        <table class="data-table">
            <thead>
                <tr>
                    <th>N° Ticket</th>
                    <th>Lot</th>
                    <th>Date limite</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gains as $g)
                <tr>
                    <td><span style="font-family:monospace;font-weight:700;letter-spacing:.06em;color:var(--green)">{{ $g->ticketCode->code ?? '—' }}</span></td>
                    <td style="font-weight:600">{{ $g->prize->name ?? '—' }}</td>
                    <td style="color:var(--txt-m)">
                        @if($g->redemption && $g->redemption->deadline)
                            {{ \Carbon\Carbon::parse($g->redemption->deadline)->format('d/m/Y') }}
                        @else
                            <span style="color:var(--txt-l)">—</span>
                        @endif
                    </td>
                    <td>
                        @php $st = $g->redemption?->status ?? 'no_redemption'; @endphp
                        @if($st === 'completed')
                            <span class="status-remis">Remis</span>
                        @elseif($st === 'approved')
                            <span class="status-prep">En préparation</span>
                        @elseif($st === 'pending')
                            <span class="status-prep">En préparation</span>
                        @elseif($st === 'rejected')
                            <span class="status-rejected">Refusé</span>
                        @else
                            <a href="{{ route('redemption.create', $g->id) }}"
                               class="btn btn-orange btn-sm">Réclamer →</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;padding:4rem 1rem">
                        <div style="font-size:3rem;margin-bottom:.8rem">🏆</div>
                        <div style="font-weight:600;margin-bottom:.4rem">Aucun gain pour l'instant</div>
                        <div style="font-size:.85rem;color:var(--txt-l)">Participez au jeu-concours pour gagner des lots !</div>
                        <a href="{{ route('dashboard') }}" class="btn btn-orange" style="margin-top:1.2rem;display:inline-flex">
                            Participer maintenant
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($gains) && method_exists($gains,'hasPages') && $gains->hasPages())
    <div style="padding:1.2rem;text-align:center">{{ $gains->links() }}</div>
    @endif
</section>

@endsection