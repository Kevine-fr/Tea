@extends('layouts.app')
@section('title','Accueil — Thé Tip Top')

@section('page_styles')
/* HERO */
.hero{position:relative;z-index:1;padding:2.5rem 3rem;background:var(--cream-m);overflow:hidden}
.hero-inner{max-width:860px;margin:0 auto;background:var(--white);border-radius:20px;box-shadow:var(--sh);padding:2.5rem 3rem;display:grid;grid-template-columns:1fr 300px;gap:2rem;align-items:center;border:2px solid rgba(184,150,46,.2)}
.hero-tag{display:inline-block;background:var(--green);color:var(--white);font-family:'Playfair Display',serif;font-style:italic;font-size:1.55rem;font-weight:600;padding:.45rem 2rem;border-radius:4px;margin-bottom:1.2rem;box-shadow:3px 3px 0 rgba(0,0,0,.15)}
.hero-title{font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;line-height:1.2;margin-bottom:.4rem}
/* SECTION */
.section-wrap{position:relative;z-index:1;padding:3.5rem 3rem}
.section-inner{max-width:840px;margin:0 auto;text-align:center}
.section-title{font-family:'Playfair Display',serif;font-size:1.7rem;font-weight:700;margin-bottom:1.2rem}
.section-text{line-height:1.75;color:var(--txt-m);font-size:.9rem;margin-bottom:1rem}
/* LOTS */
.lots-wrap{position:relative;z-index:1;padding:3rem;background:var(--cream-m)}
.lots-inner{max-width:960px;margin:0 auto;background:var(--cream-d);border-radius:20px;padding:2.5rem}
.lots-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:2rem}
.lot-card{background:var(--white);border-radius:16px;padding:1.8rem 1.4rem;box-shadow:var(--sh-sm);transition:var(--t);position:relative}
.lot-card:hover{transform:translateY(-5px);box-shadow:var(--sh-lg)}
.lot-img-wrap{width:80px;height:80px;border-radius:50%;overflow:hidden;background:var(--cream-m);margin:-1px auto 1rem;display:flex;align-items:center;justify-content:center;font-size:2.2rem}
.lot-name{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;margin-bottom:.8rem;text-align:center}
.lot-desc{font-size:.83rem;color:var(--txt-m);line-height:1.65;text-align:justify}
@endsection

@section('content')

{{-- Hero --}}
<section class="hero">
    <div class="hero-inner fade-up">
        <div>
            <p style="font-size:.88rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--txt-m);margin-bottom:.5rem">PARTICIPEZ AU JEU-CONCOURS</p>
            <div class="hero-tag">Thé Tip TOP</div>
            <p style="color:var(--txt-m);font-size:.9rem;line-height:1.7;margin-bottom:1.6rem;max-width:380px">
                Célébrez l'ouverture de notre 10ème boutique à Nice avec une chance de gagner des cadeaux bio et artisanaux.
            </p>
            <a href="{{ route('dashboard') }}" class="btn btn-orange">Jouer maintenant</a>
        </div>
        <div style="text-align:center">
            <svg viewBox="0 0 220 240" fill="none" width="200">
                <!-- Tea can -->
                <rect x="55" y="50" width="110" height="155" rx="18" fill="#1e3d1a"/>
                <ellipse cx="110" cy="50" rx="55" ry="16" fill="#2d5a27"/>
                <ellipse cx="110" cy="205" rx="55" ry="16" fill="#163012"/>
                <!-- Gold band -->
                <rect x="55" y="80" width="110" height="3" fill="#d4b44a" opacity=".7"/>
                <rect x="55" y="180" width="110" height="3" fill="#d4b44a" opacity=".7"/>
                <!-- Label -->
                <rect x="68" y="95" width="84" height="72" rx="8" fill="rgba(255,255,255,.08)"/>
                <!-- Cup icon -->
                <path d="M90 118Q94 110,110 114Q126 118,130 108" stroke="#d4b44a" stroke-width="2" fill="none" stroke-linecap="round"/>
                <ellipse cx="110" cy="128" rx="18" ry="7" fill="none" stroke="#d4b44a" stroke-width="1.5"/>
                <path d="M128 124Q138 124,138 131Q138 138,128 137" stroke="#d4b44a" stroke-width="2" fill="none" stroke-linecap="round"/>
                <!-- Text area -->
                <rect x="80" y="140" width="60" height="5" rx="2.5" fill="#d4b44a" opacity=".4"/>
                <rect x="85" y="150" width="50" height="3" rx="1.5" fill="#d4b44a" opacity=".3"/>
                <!-- Lid gold -->
                <ellipse cx="110" cy="50" rx="40" ry="10" fill="#d4b44a" opacity=".7"/>
                <!-- Leaf decor -->
                <path d="M48 140C36 126,42 106,54 100" stroke="#4a7c3f" stroke-width="3" fill="none" stroke-linecap="round"/>
                <path d="M54 100C44 112,44 128,50 140" stroke="#4a7c3f" stroke-width="2" fill="none"/>
            </svg>
        </div>
    </div>
</section>

{{-- Grand jeu-concours description --}}
<section class="section-wrap">
    <div class="section-inner">
        <h2 class="section-title fade-up">Grand jeu-concours</h2>
        <p class="section-text fade-up s1">
            À l'occasion de l'ouverture de la 10e boutique Thé Tip Top à Nice, la marque organise un grand jeu-concours exclusif destiné à faire découvrir son univers et ses créations.
        </p>
        <p class="section-text fade-up s1">
            Chaque client ayant effectué un achat supérieur à 49 € reçoit un code unique à 10 caractères lui permettant de participer en ligne. 100 % des participations sont gagnantes et donnent accès à un lot à retirer en boutique ou en ligne, selon les modalités prévues par le règlement.
        </p>
        <a href="{{ route('pages.jeu') }}" class="btn btn-orange fade-up s2" style="margin-top:.5rem">En savoir plus</a>
    </div>
</section>

{{-- Lots à gagner --}}
<section class="lots-wrap">
    <div class="lots-inner">
        <div style="text-align:center">
            <h2 class="section-title fade-up">Lots à gagner</h2>
            <p class="section-text fade-up s1" style="max-width:700px;margin:0 auto 0">
                Découvrez les lots mis en jeu : une sélection de cadeaux bio et artisanaux, pensés pour prolonger l'expérience Thé Tip Top. Les modalités d'attribution et de remise des gains (boutique ou en ligne) sont précisées dans le règlement.
            </p>
        </div>
        <div class="lots-grid">
            <div class="lot-card fade-up s1">
                <div class="lot-img-wrap">🍵</div>
                <div class="lot-name">Lot 1 – Infuseur à thé</div>
                <p class="lot-desc">Ce lot comprend un infuseur à thé réutilisable, pensé pour accompagner la dégustation des thés et infusions Thé Tip Top au quotidien.</p>
                <p class="lot-desc">Pratique et simple d'utilisation, il permet de profiter pleinement des arômes des mélanges bio et artisanaux de la marque.</p>
            </div>
            <div class="lot-card fade-up s2">
                <div class="lot-img-wrap">🍃</div>
                <div class="lot-name">Lot 2 – Thé ou infusion<br>(100 g)</div>
                <p class="lot-desc">Ce lot offre une boîte de 100 g de thé ou d'infusion sélectionnée parmi les gammes emblématiques de Thé Tip Top.</p>
                <p class="lot-desc">Il permet de découvrir des recettes naturelles, issues d'un savoir-faire artisanal, alliant plaisir de dégustation et bien-être.</p>
            </div>
            <div class="lot-card fade-up s3">
                <div class="lot-img-wrap">🎁</div>
                <div class="lot-name">Lot 3 – Coffret<br>découverte</div>
                <p class="lot-desc">Ce lot correspond à un coffret découverte regroupant plusieurs références de thés et d'infusions Thé Tip Top.</p>
                <p class="lot-desc">Il a été conçu pour proposer une expérience complète de dégustation et mettre en valeur la diversité des créations de la marque.</p>
            </div>
        </div>
        <div style="text-align:center;margin-top:2rem" class="fade-up s3">
            <a href="{{ route('dashboard') }}" class="btn btn-orange">Participer au Jeu-Concours</a>
        </div>
    </div>
</section>

@endsection