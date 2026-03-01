@extends('layouts.app')

@section('title', 'Thé Tip Top — Thés biologiques & Jeu-Concours')

@section('content')

{{-- ══════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════ --}}
<section class="hero" style="background-image: url('{{ asset('images/hero-tea-field.jpg') }}'); height: 420px;"
         onerror="this.style.backgroundImage='linear-gradient(135deg, #1e3d2f 0%, #2a5240 100%)'">
    <div class="hero-content">
        <h1 style="font-size: clamp(2.5rem, 5vw, 3.8rem); font-style: italic;">Thé Tip Top</h1>
        <a href="{{ route('login') }}" class="btn btn-orange" style="font-size: 1rem; padding: 0.8rem 2.2rem;">
            Participer au jeu-concours
        </a>
    </div>
</section>

{{-- ══════════════════════════════════════════
     VALEURS — Thé Bio / Artisanal / Premium
══════════════════════════════════════════ --}}
<section class="section section-beige">
    <div class="container">
        <div class="grid-3" style="margin-top: 3.5rem;">

            @php
            $values = [
                [
                    'icon'  => 'icon-bio.svg',
                    'emoji' => '🌿',
                    'title' => 'Thé Bio',
                    'desc'  => 'Des thés issus de l\'agriculture biologique, cultivés dans le respect de la nature et des producteurs. Sans pesticides ni additifs, nos créations préservent les saveurs authentiques et les bienfaits naturels du thé, pour une dégustation saine et responsable.',
                ],
                [
                    'icon'  => 'icon-artisanal.svg',
                    'emoji' => '✋',
                    'title' => 'Thé Artisanal',
                    'desc'  => 'Chaque thé est imaginé et assemblé à la main selon un savoir-faire artisanal exigeant. Nos mélanges signatures sont le fruit d\'une sélection minutieuse des feuilles et d\'un travail précis, garantissant une qualité constante et une identité unique.',
                ],
                [
                    'icon'  => 'icon-premium.svg',
                    'emoji' => '⭐',
                    'title' => 'Thé Premium',
                    'desc'  => 'Une expérience haut de gamme pensée pour les amateurs de thés d\'exception. Feuilles rares, recettes exclusives et coffrets raffinés font de chaque dégustation un moment unique, entre plaisir, élégance et découverte.',
                ],
            ];
            @endphp

            @foreach($values as $val)
            <div style="padding-top: 3.5rem;">
                <div class="feature-card">
                    <div class="feature-icon">
                        <span style="font-size: 2rem;">{{ $val['emoji'] }}</span>
                    </div>
                    <h3>{{ $val['title'] }}</h3>
                    <p>{{ $val['desc'] }}</p>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     NOS THÉS — Carousel
══════════════════════════════════════════ --}}
<section class="section" id="nos-thes">
    <div class="container">
        <h2 class="section-title">Nos Thés</h2>
        <p class="section-subtitle">Des thés aux saveurs authentiques, pensés pour chaque moment de dégustation.</p>

        <div style="position:relative; display:flex; align-items:center; gap: 1rem;">
            <button class="carousel-arrow" onclick="slideCarousel('teas', -1)">«</button>

            <div id="teas-carousel" style="display:grid; grid-template-columns: repeat(3,1fr); gap: 1.5rem; flex:1; overflow:hidden;">
                @php
                $teas = [
                    [
                        'name'  => 'Thé vert',
                        'img'   => 'tea-green.jpg',
                        'emoji' => '🍃',
                        'desc'  => 'Frais et délicat, le thé vert séduit par ses notes végétales et légèrement herbacées. Riche en antioxydants, il accompagne parfaitement les moments de détente et de bien-être au quotidien.',
                        'color' => '#d4f4d8',
                    ],
                    [
                        'name'  => 'Thé noir',
                        'img'   => 'tea-black.jpg',
                        'emoji' => '🖤',
                        'desc'  => 'Intense et chaleureux, le thé noir offre des arômes profonds et structurés. Idéal pour une pause énergisante, il se déguste nature ou accompagné de notes épicées ou gourmandes.',
                        'color' => '#f4e8d4',
                    ],
                    [
                        'name'  => 'Thé blanc',
                        'img'   => 'tea-white.jpg',
                        'emoji' => '☁️',
                        'desc'  => 'Subtil et raffiné, le thé blanc est reconnu pour sa douceur et sa finesse. Peu transformé, il révèle des saveurs délicates et florales, pour une expérience de dégustation tout en élégance.',
                        'color' => '#f0eaff',
                    ],
                    [
                        'name'  => 'Thé oolong',
                        'img'   => 'tea-oolong.jpg',
                        'emoji' => '🌸',
                        'desc'  => 'Mi-chemin entre le vert et le noir, l\'oolong offre une complexité aromatique unique. Floral, fruité ou boisé selon le terroir, il révèle toute sa richesse au fil des infusions.',
                        'color' => '#fce8f4',
                    ],
                    [
                        'name'  => 'Thé rouge',
                        'img'   => 'tea-red.jpg',
                        'emoji' => '❤️',
                        'desc'  => 'Naturellement sans théine, le thé rouge (rooibos) est parfait pour toute la famille. Doux, légèrement sucré, il réchauffe les soirées et accompagne aussi bien le matin que le soir.',
                        'color' => '#fce8e8',
                    ],
                ];
                @endphp

                @foreach($teas as $tea)
                <div class="product-card">
                    <div style="height: 200px; background-color: {{ $tea['color'] }}; display:flex; align-items:center; justify-content:center; font-size: 5rem;">
                        {{ $tea['emoji'] }}
                    </div>
                    <div class="product-card-body">
                        <h3>{{ $tea['name'] }}</h3>
                        <p>{{ $tea['desc'] }}</p>
                        <a href="#" class="btn btn-orange" style="font-size:0.85rem; padding: 0.5rem 1.4rem;">Lire la suite</a>
                    </div>
                </div>
                @endforeach
            </div>

            <button class="carousel-arrow" onclick="slideCarousel('teas', 1)">»</button>
        </div>

        <div style="text-align:center; margin-top: 2.5rem;">
            <a href="#" class="btn btn-orange">En savoir plus</a>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     NOS COFFRETS
══════════════════════════════════════════ --}}
<section class="section section-beige" id="coffrets">
    <div class="container">
        <h2 class="section-title">Nos coffrets</h2>
        <p class="section-subtitle">Des coffrets pensés pour offrir ou se faire plaisir, autour de nos créations emblématiques.</p>

        <div style="position:relative; display:flex; align-items:center; gap: 1rem;">
            <button class="carousel-arrow" onclick="slideCarousel('coffrets', -1)">«</button>

            <div id="coffrets-carousel" style="display:grid; grid-template-columns: repeat(3,1fr); gap: 1.5rem; flex:1; overflow:hidden;">
                @php
                $coffrets = [
                    [
                        'name'  => 'Coffret bien-être',
                        'img'   => 'coffret-bienetre.jpg',
                        'emoji' => '🌿',
                        'color' => '#d4f4d8',
                        'desc'  => 'Ce coffret réunit une sélection de thés et d\'infusions bio spécialement conçus pour accompagner les moments de détente et de recentrage. Il met à l\'honneur des mélanges naturels aux propriétés apaisantes, idéaux pour une pause bien-être au quotidien.',
                    ],
                    [
                        'name'  => 'Coffret découverte',
                        'img'   => 'coffret-decouverte.jpg',
                        'emoji' => '🎁',
                        'color' => '#f4e8d4',
                        'desc'  => 'Le coffret découverte permet d\'explorer l\'univers Thé Tip Top à travers plusieurs références emblématiques de la marque. Il offre une variété de saveurs et d\'arômes, afin de découvrir différents types de thés et d\'infusions issus d\'un savoir-faire artisanal.',
                    ],
                    [
                        'name'  => 'Coffret premium',
                        'img'   => 'coffret-premium.jpg',
                        'emoji' => '✨',
                        'color' => '#f0eaff',
                        'desc'  => 'Ce coffret premium rassemble des créations sélectionnées pour leur qualité exceptionnelle et leur raffinement. Il s\'adresse aux amateurs de thé à la recherche d\'une expérience de dégustation plus exclusive, mettant en valeur des mélanges signatures.',
                    ],
                ];
                @endphp

                @foreach($coffrets as $coffret)
                <div class="product-card">
                    <div style="height: 200px; background-color: {{ $coffret['color'] }}; display:flex; align-items:center; justify-content:center; font-size: 5rem;">
                        {{ $coffret['emoji'] }}
                    </div>
                    <div class="product-card-body">
                        <h3>{{ $coffret['name'] }}</h3>
                        <p>{{ $coffret['desc'] }}</p>
                        <a href="#" class="btn btn-orange" style="font-size:0.85rem; padding: 0.5rem 1.4rem;">Lire la suite</a>
                    </div>
                </div>
                @endforeach
            </div>

            <button class="carousel-arrow" onclick="slideCarousel('coffrets', 1)">»</button>
        </div>

        <div style="text-align:center; margin-top: 2.5rem;">
            <a href="#" class="btn btn-orange">En savoir plus</a>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     GRAND JEU-CONCOURS
══════════════════════════════════════════ --}}
<section class="section" id="jeu-concours">
    <div class="container">
        <h2 class="section-title">Grand jeu-concours</h2>

        <div style="background: var(--cream); border-radius: 14px; padding: 1.5rem 2rem; margin-bottom: 2.5rem; font-size: 0.93rem; color: var(--text-mid); line-height: 1.8;">
            <p>À l'occasion de l'ouverture de sa 10ᵉ boutique Thé Tip Top à Nice, la marque organise un grand jeu-concours exclusif destiné à faire découvrir son univers et ses créations.</p>
            <p style="margin-top: 0.7rem;">Chaque client ayant effectué un achat supérieur à 49 € reçoit un code unique à 10 caractères lui permettant de participer en ligne. <strong>100 % des participations sont gagnantes</strong> et donnent accès à un lot à retirer en boutique ou en ligne, selon les modalités prévues par le règlement.</p>
        </div>

        <div class="grid-3">
            @php
            $lots = [
                [
                    'num'   => 1,
                    'name'  => 'Infuseur à thé',
                    'emoji' => '🫖',
                    'color' => '#d4f4d8',
                    'desc'  => 'Ce lot comprend un infuseur à thé réutilisable, pensé pour accompagner la dégustation des thés et infusions Thé Tip Top au quotidien. Pratique et simple d\'utilisation, il permet de profiter pleinement des arômes des mélanges bio et artisanaux de la marque.',
                ],
                [
                    'num'   => 2,
                    'name'  => 'Thé ou infusion (100 g)',
                    'emoji' => '🍵',
                    'color' => '#f4e8d4',
                    'desc'  => 'Ce lot offre une boîte de 100 g de thé ou d\'infusion sélectionnée parmi les gammes emblématiques de Thé Tip Top. Il permet de découvrir des recettes naturelles issues d\'un savoir-faire artisanal, alliant plaisir de dégustation et bien-être.',
                ],
                [
                    'num'   => 3,
                    'name'  => 'Coffret découverte',
                    'emoji' => '🎁',
                    'color' => '#f0eaff',
                    'desc'  => 'Ce lot correspond à un coffret découverte regroupant plusieurs références de thés et d\'infusions Thé Tip Top. Il a été conçu pour proposer une expérience complète de dégustation et mettre en valeur la diversité des créations de la marque.',
                ],
            ];
            @endphp

            @foreach($lots as $lot)
            <div class="lot-card">
                <div style="width:90px; height:90px; border-radius:50%; background:{{ $lot['color'] }}; display:flex; align-items:center; justify-content:center; font-size:2.8rem; margin: 0 auto 1rem; border: 4px solid white; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    {{ $lot['emoji'] }}
                </div>
                <h3>Lot {{ $lot['num'] }} — {{ $lot['name'] }}</h3>
                <p>{{ $lot['desc'] }}</p>
            </div>
            @endforeach
        </div>

        <div style="text-align:center; margin-top: 3rem;">
            <a href="{{ route('login') }}" class="btn btn-orange" style="font-size: 1rem; padding: 0.85rem 2.5rem;">
                Participer au jeu-concours
            </a>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     FAQ
══════════════════════════════════════════ --}}
<section class="section section-beige" id="faq">
    <div class="container" style="max-width: 850px;">
        <h2 class="section-title">FAQ</h2>

        @php
        $faqs = [
            [
                'q' => 'Qu\'est-ce que Thé Tip Top ?',
                'a' => 'Thé Tip Top est une maison française spécialisée dans les thés biologiques et artisanaux. Fondée par des passionnés d\'infusions, elle propose une gamme de créations signatures, de thés premium et de coffrets découverte, tous issus d\'une sélection rigoureuse de feuilles cultivées dans le respect de la nature.',
            ],
            [
                'q' => 'Pourquoi Thé Tip Top organise ce jeu-concours ?',
                'a' => 'Ce grand jeu-concours est organisé à l\'occasion de l\'ouverture de notre 10ᵉ boutique à Nice. C\'est une façon de remercier notre communauté de clients fidèles et de faire découvrir nos nouvelles créations de thés bio et artisanaux. 100 % des participations sont gagnantes !',
            ],
            [
                'q' => 'Quels types de thés propose Thé Tip Top ?',
                'a' => 'Thé Tip Top propose une large gamme incluant des thés verts, noirs, blancs, oolong, rooibos et infusions bio. Chaque référence est soigneusement sélectionnée et assemblée selon notre savoir-faire artisanal, pour offrir des expériences gustatives uniques adaptées à chaque moment de la journée.',
            ],
            [
                'q' => 'Le jeu-concours est-il accessible à tous ?',
                'a' => 'Le jeu-concours est accessible à toute personne majeure résidant en France métropolitaine ayant effectué un achat supérieur à 49 € dans l\'une de nos boutiques ou sur notre site. Chaque achat ouvre droit à un code unique à 10 caractères permettant de participer en ligne.',
            ],
            [
                'q' => 'Mes données personnelles sont-elles protégées ?',
                'a' => 'Absolument. Thé Tip Top s\'engage à protéger vos données personnelles conformément au RGPD. Vos informations sont utilisées uniquement dans le cadre du jeu-concours et ne sont jamais revendues à des tiers. Vous pouvez exercer vos droits d\'accès, de rectification et de suppression à tout moment via notre formulaire de contact.',
            ],
        ];
        @endphp

        <div style="margin-top: 1rem;">
            @foreach($faqs as $faq)
            <div class="faq-item">
                <button class="faq-question">
                    {{ $faq['q'] }}
                    <span class="faq-icon">+</span>
                </button>
                <div class="faq-answer">{{ $faq['a'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// Simple carousel state
const carouselState = {};

function slideCarousel(name, dir) {
    const el = document.getElementById(name + '-carousel');
    const cards = el.querySelectorAll('.product-card');
    const total = cards.length;
    if (!carouselState[name]) carouselState[name] = 0;
    carouselState[name] = (carouselState[name] + dir + total) % total;
    const visible = window.innerWidth < 700 ? 1 : 3;
    cards.forEach((c, i) => {
        const pos = (i - carouselState[name] + total) % total;
        c.style.display = pos < visible ? 'block' : 'none';
    });
}
</script>
@endpush