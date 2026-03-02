{{-- JEU PAGE --}}
@extends('layouts.app')
@section('title','Jeu-Concours — Thé Tip Top')
@section('page_styles')
.jeu-wrap{position:relative;z-index:1;padding:4rem 4rem 5rem;max-width:1050px;margin:0 auto}
.jeu-grid{display:grid;grid-template-columns:1fr 1fr;gap:3.5rem;align-items:center}
.jeu-img{border-radius:18px;overflow:hidden;height:420px;background:linear-gradient(145deg,#e8dfd0,#d4c9b0);position:relative}
.jeu-text h2{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;margin-bottom:1.2rem}
.jeu-text p{color:var(--txt-m);line-height:1.78;font-size:.9rem;margin-bottom:1rem}
@endsection
@section('content')
<section class="page-banner fade-up">
    @include('partials.ornament')
    <div style="text-align:center;flex:1"><span class="banner-tape">Jeu-Concours</span></div>
    @include('partials.ornament',['flip'=>true])
</section>
<div class="jeu-wrap">
    <div class="jeu-grid">
        <div class="jeu-img fade-up">
            <svg viewBox="0 0 400 420" fill="none" style="width:100%;height:100%">
                <rect width="400" height="420" fill="#d4c9b0"/>
                <!-- Shop facade -->
                <rect x="60" y="120" width="280" height="260" rx="8" fill="#f5f0e8"/>
                <rect x="60" y="120" width="280" height="50" rx="8" fill="#1e3d1a"/>
                <text x="200" y="153" text-anchor="middle" fill="#d4b44a" font-size="16" font-family="Jost" font-weight="700">Thé Tip Top</text>
                <!-- Door -->
                <rect x="165" y="290" width="70" height="90" rx="6" fill="#1e3d1a" opacity=".8"/>
                <circle cx="225" cy="336" r="4" fill="#d4b44a"/>
                <!-- Windows -->
                <rect x="80" y="200" width="90" height="70" rx="6" fill="#d4edda" opacity=".6"/>
                <rect x="230" y="200" width="90" height="70" rx="6" fill="#d4edda" opacity=".6"/>
                <!-- Person in door -->
                <circle cx="200" cy="270" r="18" fill="#b8962e" opacity=".5"/>
                <rect x="188" y="285" width="24" height="40" rx="6" fill="#1e3d1a" opacity=".7"/>
                <text x="200" y="275" text-anchor="middle" fill="white" font-size="8" font-family="Jost">TTT</text>
                <!-- Sign -->
                <rect x="85" y="165" width="230" height="25" rx="4" fill="none" stroke="#d4b44a" stroke-width="1.5" opacity=".5"/>
            </svg>
        </div>
        <div class="jeu-text fade-up s1">
            <h2>Présentation du jeu</h2>
            <p>À l'occasion de l'ouverture de notre 10ème boutique à Nice, nous organisons un grand jeu-concours exclusif pour remercier nos fidèles clients et faire découvrir nos créations de thés bio et artisanaux. Chaque achat supérieur à 49 € donne accès à un code unique permettant de participer au jeu.</p>
            <p>100 % des participations sont gagnantes et offrent la possibilité de remporter des infuseurs, des thés signature, des coffrets découverte ou des lots premium. La participation est simple, rapide et sécurisée, directement depuis ce site dédié, dans le respect des données personnelles et de la réglementation en vigueur.</p>
            <p>Participez dès maintenant pour gagner des cadeaux thé de luxe !</p>
            <a href="{{ route('pages.gain') }}" class="btn btn-orange" style="margin-top:.5rem">Lots à gagner</a>
        </div>
    </div>
</div>
@endsection