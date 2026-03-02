@extends('layouts.app')
@section('title','CGU — Thé Tip Top')
@section('page_styles').legal-wrap{position:relative;z-index:1;padding:3rem 3rem 5rem;max-width:900px;margin:0 auto}.legal-card{background:var(--white);border-radius:20px;box-shadow:var(--sh-sm);padding:2.2rem}@endsection
@section('content')
<section class="page-banner fade-up">
    @include('partials.ornament')
    <div style="text-align:center;flex:1"><span class="banner-tape banner-tape-sm" style="font-size:1.15rem">Conditions générales d'utilisation</span></div>
    @include('partials.ornament',['flip'=>true])
</section>
<div class="legal-wrap">
    <div style="margin-bottom:2rem" class="fade-up">
        <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;text-align:center;margin-bottom:.8rem">Règles d'utilisation du site Thé Tip Top</h2>
        <p style="color:var(--txt-m);font-size:.88rem;text-align:center">Ces conditions expliquent les règles d'accès et d'utilisation du site Thé Tip Top (compte, participation, contenus) et les bonnes pratiques à respecter.</p>
    </div>
    <div class="legal-card fade-up s1">
        @foreach([
            'Article 1 — Objet du jeu-concours',
            'Article 2 — Modalités de participation',
            'Article 3 — Déroulement du jeu-concours',
            'Article 4 — Données personnelles : collecte et utilisation',
            'Article 5 — Propriété intellectuelle',
            'Article 6 — Limitation de responsabilité',
            'Article 7 — Mise à jour des conditions',
        ] as $article)
        <div class="acc-item">
            <button class="acc-btn" type="button">
                {{ $article }}
                <span class="acc-icon">+</span>
            </button>
            <div class="acc-body"><div class="acc-body-inner"><p>Contenu de {{ $article }}. Ces conditions régissent l'utilisation du site Thé Tip Top et la participation au jeu-concours, conformément à la réglementation française.</p></div></div>
        </div>
        @endforeach
    </div>
</div>
@endsection