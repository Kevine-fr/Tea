@extends('layouts.app')
@section('title','404 — Thé Tip Top')
@section('page_styles')
.error-wrap{position:relative;z-index:1;padding:4rem 2rem;display:flex;align-items:center;justify-content:center;min-height:55vh}
.error-card{background:var(--cream-m);border-radius:24px;padding:4rem 3.5rem;max-width:580px;width:100%;text-align:center;box-shadow:var(--sh)}
@endsection
@section('content')
<div class="error-wrap">
    <div class="error-card fade-up">
        <h1 style="font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:700;margin-bottom:.8rem">Oups... 404</h1>
        <p style="font-size:1.1rem;font-weight:600;color:var(--txt-m);margin-bottom:2rem">Cette page s'est évaporée comme une infusion trop chaude</p>
        <div style="margin-bottom:1.5rem">
            <svg viewBox="0 0 160 120" fill="none" width="130" style="display:block;margin:0 auto">
                <path d="M40 70Q38 100,80 108Q122 100,120 70Z" fill="#1e3d1a"/>
                <ellipse cx="80" cy="70" rx="40" ry="13" fill="#2d5a27"/>
                <path d="M120 78Q140 78,140 90Q140 102,120 100" stroke="#1e3d1a" stroke-width="6" fill="none" stroke-linecap="round"/>
                <ellipse cx="80" cy="109" rx="56" ry="10" fill="#d4b44a" opacity=".45"/>
                <path d="M68 50C64 36,72 22,68 10C74 22,86 26,82 40C78 50,70 48,68 50Z" fill="#4a7c3f"/>
                <path d="M68 50C72 36,74 22,74 12" stroke="#2d5a27" stroke-width="1.5" fill="none"/>
            </svg>
        </div>
        <p style="font-size:.9rem;color:var(--txt-l);margin-bottom:2rem">On te ramène à la bonne tasse...</p>
        <a href="{{ route('home') }}" class="btn btn-orange" style="display:inline-flex">Retour à l'accueil</a>
    </div>
</div>
@endsection