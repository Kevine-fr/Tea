@extends('layouts.app')
@section('title','CGV — Thé Tip Top')
@section('page_styles').legal-wrap{position:relative;z-index:1;padding:3rem 3rem 5rem;max-width:900px;margin:0 auto}.legal-card{background:var(--white);border-radius:20px;box-shadow:var(--sh-sm);padding:2.2rem}@endsection
@section('content')
<section class="page-banner fade-up">
    @include('partials.ornament')
    <div style="text-align:center;flex:1"><span class="banner-tape banner-tape-sm" style="font-size:1.3rem">Conditions générales de vente</span></div>
    @include('partials.ornament',['flip'=>true])
</section>
<div class="legal-wrap">
    <div style="margin-bottom:2rem" class="fade-up">
        <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;text-align:center;margin-bottom:.8rem">Vos données, en toute confiance</h2>
        <p style="color:var(--txt-m);font-size:.88rem;text-align:center">Retrouvez ici les règles qui encadrent vos achats chez Thé Tip Top : commande, paiement, livraison, retours et service client.</p>
    </div>
    <div class="legal-card fade-up s1">
        @foreach([
            'Article 1 — Objet des CGV',
            'Article 2 — Description des Produits',
            'Article 3 — Passer une Commande',
            'Article 4 — Prix et Facturation',
            'Article 5 — Paiement et Sécurité',
            'Article 6 — Livraison et Réception',
            'Article 7 — Droit de Rétractation & Retours',
            'Article 8 — Responsabilités et limites',
            'Article 9 — Contenus et propriété intellectuelle',
            'Article 10 — Droit Applicable & Litiges',
        ] as $article)
        <div class="acc-item">
            <button class="acc-btn" type="button">
                {{ $article }}
                <span class="acc-icon">+</span>
            </button>
            <div class="acc-body"><div class="acc-body-inner"><p>Contenu de {{ $article }}. Ces conditions régissent vos achats chez Thé Tip Top et sont conformes à la législation française en vigueur.</p></div></div>
        </div>
        @endforeach
    </div>
</div>
@endsection