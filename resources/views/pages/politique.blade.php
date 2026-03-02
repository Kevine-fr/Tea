@extends('layouts.app')
@section('title','Politique de confidentialité — Thé Tip Top')
@section('page_styles')
.legal-wrap{position:relative;z-index:1;padding:3rem 3rem 5rem;max-width:900px;margin:0 auto}
.legal-card{background:var(--white);border-radius:20px;box-shadow:var(--sh-sm);padding:2.2rem}
@endsection
@section('content')
<section class="page-banner fade-up">
    @include('partials.ornament')
    <div style="text-align:center;flex:1"><span class="banner-tape banner-tape-sm" style="font-size:1.35rem">Politique de confidentialité</span></div>
    @include('partials.ornament',['flip'=>true])
</section>
<div class="legal-wrap">
    <div style="margin-bottom:2rem" class="fade-up">
        <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;text-align:center;margin-bottom:.8rem">Vos données, en toute confiance</h2>
        <p style="color:var(--txt-m);font-size:.88rem;text-align:center;max-width:750px;margin:0 auto">Chez Thé Tip Top, nous protégeons vos données avec sérieux : elles sont utilisées uniquement pour gérer votre compte, votre participation au jeu-concours et le suivi de vos lots.</p>
    </div>
    <div class="legal-card fade-up s1">
        @foreach([
            ['Quelles données nous collectons ?','Nous collectons les informations que vous nous fournissez lors de votre inscription : nom, prénom, adresse e-mail, date de naissance. Ces données sont nécessaires pour la gestion de votre compte et de votre participation au jeu-concours.'],
            ['Comment nous utilisons vos données ?','Vos données sont utilisées exclusivement pour gérer votre compte, votre participation au jeu-concours Thé Tip Top, et le suivi des lots gagnés. Elles ne sont pas utilisées à des fins de prospection commerciale sans votre consentement explicite.'],
            ['Avec qui vos données peuvent être partagées ?','Nous ne vendons pas vos données personnelles. Elles peuvent être partagées avec des prestataires techniques (hébergement, maintenance) dans le strict cadre de l\'exécution de leurs missions, sous obligation de confidentialité.'],
            ['Comment nous protégeons vos données ?','Nous mettons en œuvre des mesures de sécurité techniques et organisationnelles appropriées pour protéger vos données contre tout accès non autorisé, altération, divulgation ou destruction.'],
            ['Vos droits sur vos données','Conformément au RGPD, vous disposez d\'un droit d\'accès, de rectification, d\'effacement, de portabilité et d\'opposition sur vos données. Pour exercer ces droits, contactez-nous à l\'adresse mentionnée ci-dessous.'],
            ['Cookies et traceurs','Notre site utilise des cookies nécessaires au bon fonctionnement et à la sécurité. Vous pouvez configurer votre navigateur pour refuser les cookies non essentiels.'],
            ['Mises à jour de cette politique','Nous nous réservons le droit de modifier cette politique. Toute mise à jour sera notifiée par e-mail ou sur le site. La date de dernière mise à jour est indiquée en bas de cette page.'],
            ['Nous contacter','Pour toute question relative à vos données personnelles, contactez notre DPO à l\'adresse : dpo@thetiptop.fr ou via notre formulaire de contact.'],
        ] as [$title, $body])
        <div class="acc-item">
            <button class="acc-btn" type="button">
                {{ $title }}
                <span class="acc-icon">+</span>
            </button>
            <div class="acc-body"><div class="acc-body-inner"><p>{{ $body }}</p></div></div>
        </div>
        @endforeach
    </div>
</div>
@endsection