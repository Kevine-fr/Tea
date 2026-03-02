@extends('layouts.app')
@section('title','Lots à gagner — Thé Tip Top')
@section('page_styles')
.gain-wrap{position:relative;z-index:1;padding:3.5rem 3rem 5rem;max-width:1020px;margin:0 auto}
.lots-grid3{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:2rem}
.lot3-card{background:var(--white);border-radius:18px;padding:2rem 1.5rem;box-shadow:var(--sh-sm);transition:var(--t);text-align:justify}
.lot3-card:hover{transform:translateY(-5px);box-shadow:var(--sh-lg)}
.lot3-img{width:88px;height:88px;border-radius:50%;overflow:hidden;background:var(--cream-m);margin:0 auto 1.2rem;display:flex;align-items:center;justify-content:center;font-size:2.5rem}
.lot3-name{font-family:'Playfair Display',serif;font-size:1.08rem;font-weight:700;margin-bottom:.8rem;text-align:center}
.lot3-desc{font-size:.83rem;color:var(--txt-m);line-height:1.68;margin-bottom:.6rem}
@endsection
@section('content')
<section class="page-banner fade-up">
    @include('partials.ornament')
    <div style="text-align:center;flex:1"><span class="banner-tape">Lot à gagner</span></div>
    @include('partials.ornament',['flip'=>true])
</section>
<div class="gain-wrap">
    <div style="text-align:center;margin-bottom:.8rem" class="fade-up">
        <h2 style="font-family:'Playfair Display',serif;font-size:1.65rem;font-weight:700;margin-bottom:1rem">Découvrir les gains</h2>
        <p style="color:var(--txt-m);font-size:.88rem;max-width:800px;margin:0 auto;line-height:1.7">Découvrez les lots mis en jeu : une sélection de cadeaux bio et artisanaux, pensés pour prolonger l'expérience Thé Tip Top. Les modalités d'attribution et de remise des gains (boutique ou en ligne) sont précisées dans le règlement.</p>
    </div>
    <div class="lots-grid3">
        <div class="lot3-card fade-up s1">
            <div class="lot3-img">🍵</div>
            <div class="lot3-name">Lot 1 – Infuseur à thé</div>
            <p class="lot3-desc">Ce lot comprend un infuseur à thé réutilisable, pensé pour accompagner la dégustation des thés et infusions Thé Tip Top au quotidien.</p>
            <p class="lot3-desc">Pratique et simple d'utilisation, il permet de profiter pleinement des arômes des mélanges bio et artisanaux de la marque.</p>
        </div>
        <div class="lot3-card fade-up s2">
            <div class="lot3-img">🍃</div>
            <div class="lot3-name">Lot 2 – Thé ou infusion<br>(100 g)</div>
            <p class="lot3-desc">Ce lot offre une boîte de 100 g de thé ou d'infusion sélectionnée parmi les gammes emblématiques de Thé Tip Top.</p>
            <p class="lot3-desc">Il permet de découvrir des recettes naturelles, issues d'un savoir-faire artisanal, alliant plaisir de dégustation et bien-être.</p>
        </div>
        <div class="lot3-card fade-up s3">
            <div class="lot3-img">🎁</div>
            <div class="lot3-name">Lot 3 – Coffret découverte</div>
            <p class="lot3-desc">Ce lot correspond à un coffret découverte regroupant plusieurs références de thés et d'infusions Thé Tip Top.</p>
            <p class="lot3-desc">Il a été conçu pour proposer une expérience complète de dégustation et mettre en valeur la diversité des créations de la marque.</p>
        </div>
    </div>
    <div style="text-align:center;margin-top:2.5rem" class="fade-up s4">
        <a href="{{ route('dashboard') }}" class="btn btn-orange">Participer au Jeu-Concours</a>
    </div>
</div>
@endsection