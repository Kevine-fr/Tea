@extends('layouts.app')

@section('title', 'Réclamer mon lot — Thé Tip Top')

@section('content')

<section class="section">
    <div class="container" style="max-width: 600px;">

        <div style="text-align:center; margin-bottom: 2.5rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🎁</div>
            <h1 style="font-size: 1.8rem; margin-bottom: 0.5rem;">Réclamez votre lot !</h1>
            <p style="color:var(--text-mid);">Félicitations pour votre gain. Choisissez comment le récupérer.</p>
        </div>

        {{-- Lot gagné --}}
        <div style="background: linear-gradient(135deg, var(--green-dark), var(--green-mid)); border-radius: 16px; padding: 1.5rem; margin-bottom: 2rem; color:white; text-align:center;">
            <p style="color:rgba(255,255,255,0.7); font-size:0.85rem; margin-bottom:0.3rem;">Votre lot</p>
            <h2 style="color:var(--gold); font-size: 1.4rem;">{{ $participation->prize->name }}</h2>
            @if($participation->prize->description)
                <p style="color:rgba(255,255,255,0.75); font-size:0.88rem; margin-top:0.5rem;">
                    {{ $participation->prize->description }}
                </p>
            @endif
        </div>

        {{-- Formulaire réclamation --}}
        <div class="auth-card">
            <h2 style="font-size:1.2rem; margin-bottom: 1.5rem;">Comment souhaitez-vous récupérer votre lot ?</h2>

            <form method="POST" action="{{ route('redemption.store') }}">
                @csrf
                <input type="hidden" name="participation_id" value="{{ $participation->id }}">

                @php
                $methods = [
                    'store'  => ['icon' => '🏪', 'label' => 'En boutique', 'desc' => 'Récupérez votre lot dans l\'une de nos boutiques Thé Tip Top. Présentez votre confirmation par email.'],
                    'mail'   => ['icon' => '📮', 'label' => 'Par courrier',  'desc' => 'Votre lot vous sera envoyé à votre adresse postale dans un délai de 7 à 10 jours ouvrés.'],
                    'online' => ['icon' => '🌐', 'label' => 'En ligne',     'desc' => 'Recevez un bon de réduction ou un code cadeau par email dans les 24 heures.'],
                ];
                @endphp

                <div style="display:flex; flex-direction:column; gap: 1rem; margin-bottom:1.5rem;">
                    @foreach($methods as $value => $method)
                    <label style="display:flex; gap: 1rem; padding: 1rem 1.2rem; border: 2px solid var(--border); border-radius: 12px; cursor:pointer; transition: all 0.2s;"
                           onmouseenter="this.style.borderColor='var(--green-light)'"
                           onmouseleave="this.style.borderColor='var(--border)'"
                           onclick="document.querySelectorAll('.method-label').forEach(l => l.style.borderColor='var(--border)'); this.style.borderColor='var(--green-dark)'; this.style.background='var(--cream)';"
                           class="method-label">
                        <input type="radio" name="method" value="{{ $value }}"
                               style="accent-color:var(--green-dark); margin-top: 3px;"
                               {{ old('method') === $value ? 'checked' : '' }}>
                        <div>
                            <div style="font-size: 1.3rem; display:inline; margin-right:0.4rem;">{{ $method['icon'] }}</div>
                            <span style="font-weight:600; color:var(--text-dark);">{{ $method['label'] }}</span>
                            <p style="font-size:0.83rem; color:var(--text-mid); margin-top:0.2rem;">{{ $method['desc'] }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>

                @error('method')
                    <div class="alert alert-error" style="margin-bottom:1rem;">{{ $message }}</div>
                @enderror

                <div style="display:flex; gap:1rem;">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline" style="flex:1; text-align:center;">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-orange" style="flex:2;">
                        Confirmer ma réclamation
                    </button>
                </div>
            </form>
        </div>

    </div>
</section>

@endsection