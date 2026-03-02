@extends('layouts.app')
@section('title', 'Lots & Prizes — Admin Thé Tip Top')

@push('styles')
<style>
.admin-layout { display: grid; grid-template-columns: 250px 1fr; min-height: calc(100vh - 65px); }
.admin-main { padding: 2rem; background: #f3f4f6; }
</style>
@endpush

@section('content')
<div class="admin-layout">
    @include('admin.partials.sidebar')

    <div class="admin-main">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h1 style="font-size:1.4rem;">🏆 Gestion des lots</h1>
            <button onclick="document.getElementById('modal-add').style.display='flex'"
                    class="btn btn-green" style="font-size:0.88rem;">
                + Ajouter un lot
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif

        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1.2rem;">
            @foreach($prizes as $prize)
            <div style="background:white; border-radius:14px; padding:1.5rem; box-shadow:var(--shadow-sm);">
                <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:0.8rem;">
                    <h3 style="font-size:1rem; color:var(--green-dark);">{{ $prize->name }}</h3>
                    <span style="font-size:1.5rem; font-weight:700; color:{{ $prize->stock > 10 ? 'var(--green-light)' : ($prize->stock > 0 ? '#e67e22' : '#e74c3c') }};">
                        {{ $prize->stock }}
                    </span>
                </div>
                @if($prize->description)
                    <p style="font-size:0.83rem; color:var(--text-mid); margin-bottom:1rem;">{{ $prize->description }}</p>
                @endif
                <div style="font-size:0.8rem; color:var(--text-light); margin-bottom:1rem;">
                    {{ $prize->participations_count }} participation(s)
                </div>
                {{-- Formulaire mise à jour stock --}}
                <form method="POST" action="{{ route('admin.prizes.update', $prize->id) }}"
                      style="display:flex; gap:0.5rem; align-items:center;">
                    @csrf @method('PATCH')
                    <input type="number" name="stock" value="{{ $prize->stock }}" min="0"
                           class="input-field" style="width:80px; padding:0.4rem 0.6rem; text-align:center;">
                    <button type="submit" class="btn btn-green" style="padding:0.4rem 0.8rem; font-size:0.8rem;">
                        Mettre à jour
                    </button>
                </form>
            </div>
            @endforeach
        </div>

        {{-- Modal ajout lot --}}
        <div id="modal-add" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:200; align-items:center; justify-content:center;">
            <div style="background:white; border-radius:16px; padding:2rem; width:100%; max-width:500px;">
                <h2 style="margin-bottom:1.5rem; font-size:1.2rem;">Ajouter un lot</h2>
                <form method="POST" action="{{ route('admin.prizes.store') }}">
                    @csrf
                    <div class="form-group">
                        <label>Nom du lot</label>
                        <input type="text" name="name" class="input-field" required placeholder="Ex: Infuseur à thé">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="input-field" rows="3" placeholder="Description du lot..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Stock initial</label>
                        <input type="number" name="stock" class="input-field" required min="0" value="10">
                    </div>
                    <div style="display:flex; gap:1rem; margin-top:1rem;">
                        <button type="button" onclick="document.getElementById('modal-add').style.display='none'"
                                class="btn btn-outline" style="flex:1;">Annuler</button>
                        <button type="submit" class="btn btn-orange" style="flex:2;">Créer le lot</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection