@extends('layouts.app')
@section('title','Contact — Thé Tip Top')
@section('page_styles')
.contact-wrap{position:relative;z-index:1;padding:3rem 3rem 5rem;max-width:900px;margin:0 auto}
.contact-card{background:var(--white);border-radius:20px;box-shadow:var(--sh);padding:2.8rem}
.contact-card-title{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;text-align:center;margin-bottom:1.8rem}
@endsection
@section('content')
<section class="page-banner fade-up">
    @include('partials.ornament')
    <div style="text-align:center;flex:1"><span class="banner-tape">Contact</span></div>
    @include('partials.ornament', ['flip'=>true])
</section>
<div class="contact-wrap">
    <div style="text-align:center;margin-bottom:2.2rem" class="fade-up">
        <h2 style="font-family:'Playfair Display',serif;font-size:1.65rem;font-weight:700;margin-bottom:.5rem">Écrivez-nous !</h2>
        <p style="color:var(--txt-m);font-size:.9rem">Une demande, un souci avec un lot, ou juste une question ? On met la bouilloire et on arrive..</p>
    </div>
    <div class="contact-card fade-up s1">
        <div class="contact-card-title">Formulaire de contact</div>
        @if(session('success'))<div class="alert alert-success">✅ {{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-error">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
        <form method="POST" action="{{ route('pages.contact.send') }}" id="contactForm">
            @csrf
            <div style="margin-bottom:1.1rem">
                <input type="text" name="last_name" class="input-field" placeholder="Nom" value="{{ old('last_name') }}" required>
                @error('last_name')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div style="margin-bottom:1.1rem">
                <input type="text" name="first_name" class="input-field" placeholder="Prénom" value="{{ old('first_name') }}" required>
            </div>
            <div style="margin-bottom:1.1rem">
                <input type="email" name="email" class="input-field" placeholder="Email" value="{{ old('email') }}" required>
            </div>
            <div style="margin-bottom:1.1rem">
                <input type="text" name="subject" class="input-field" placeholder="Sujet" value="{{ old('subject') }}" required>
            </div>
            <div style="margin-bottom:1.5rem">
                <textarea name="message" class="input-field" placeholder="Votre message.." required>{{ old('message') }}</textarea>
            </div>
            <div style="text-align:center">
                <button type="submit" class="btn btn-orange" id="contactBtn" style="min-width:160px">Envoyer</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
document.getElementById('contactForm').addEventListener('submit',function(){
    const btn=document.getElementById('contactBtn');btn.textContent='⏳ Envoi…';btn.disabled=true;
});
</script>
@endpush
@endsection