@extends('layouts.app')

@section('title', 'Détail campagne')

@section('content')
@php
    $isDirectionDetail = $isDirectionDetail ?? false;
    $activeTab = $activeTab ?? request('tab', 'pilotage');
    $retourRoute = $isDirectionDetail ? route('direction.campagnes.index') : route('admin.campagnes.index');
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h4 class="mb-0">{{ $campagne->nom }}</h4>
        <p class="text-muted small mb-0">Pilotage et suivi de campagne</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ $retourRoute }}" class="btn btn-outline-secondary btn-sm">← Liste</a>
        @unless($isDirectionDetail)
        <a href="{{ route('admin.campagnes.edit', $campagne) }}" class="btn btn-primary btn-sm">Paramètres complets</a>
        @endunless
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success py-2">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger py-2">
        <ul class="mb-0 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<ul class="nav nav-tabs mb-3" id="campagneDetailTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link @if($activeTab === 'pilotage') active @endif" data-bs-toggle="tab" data-bs-target="#tab-pilotage" type="button" role="tab">Pilotage</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link @if($activeTab === 'commerciaux') active @endif" data-bs-toggle="tab" data-bs-target="#tab-commerciaux" type="button" role="tab">
            Commerciaux
            <span class="badge bg-secondary ms-1">{{ $commerciauxPerimetre->count() ?? 0 }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link @if($activeTab === 'contrat') active @endif" data-bs-toggle="tab" data-bs-target="#tab-contrat" type="button" role="tab">Contrat</button>
    </li>
    @if($campagne->aide_hebdo_active)
    <li class="nav-item" role="presentation">
        <button class="nav-link @if($activeTab === 'aide') active @endif" data-bs-toggle="tab" data-bs-target="#tab-aide" type="button" role="tab">Aide hebdo</button>
    </li>
    @endif
    <li class="nav-item" role="presentation">
        <button class="nav-link @if($activeTab === 'performances') active @endif" data-bs-toggle="tab" data-bs-target="#tab-performances" type="button" role="tab">Performances</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link @if($activeTab === 'historique') active @endif" data-bs-toggle="tab" data-bs-target="#tab-historique" type="button" role="tab">Historique</button>
    </li>
</ul>

<div class="tab-content" id="campagneDetailTabContent">
    @include('admin.campagnes.partials.show-pilotage')
    @include('admin.campagnes.partials.show-commerciaux')
    @include('admin.campagnes.partials.show-contrat')
    @if($campagne->aide_hebdo_active)
        @include('admin.campagnes.partials.show-aide')
    @endif
    @include('admin.campagnes.partials.show-performances')
    @include('admin.campagnes.partials.show-historique')
</div>

@unless($isDirectionDetail)
    @include('admin.campagnes.partials.show-modals')
@endunless

@push('scripts')
<script>
function toggleDetailBeneficiaires(el) {
    var wrap = document.getElementById('detail-wrap-beneficiaires');
    if (!wrap) return;
    wrap.style.display = el.checked ? 'none' : 'block';
    if (el.checked) {
        wrap.querySelectorAll('input[type=checkbox]').forEach(function (c) { c.checked = false; });
    }
}
(function () {
    var sel = document.getElementById('select-periode-preset');
    var form = document.getElementById('form-periode-campagne');
    if (sel && form) {
        sel.addEventListener('change', function () {
            var perso = sel.value === 'perso';
            form.querySelectorAll('[name="date_debut"],[name="date_fin"]').forEach(function (el) {
                el.disabled = !perso;
            });
            if (!perso) form.submit();
        });
    }
    var tab = new URLSearchParams(window.location.search).get('tab');
    if (tab) {
        var btn = document.querySelector('[data-bs-target="#tab-' + tab + '"]');
        if (btn && typeof bootstrap !== 'undefined') {
            bootstrap.Tab.getOrCreateInstance(btn).show();
        }
    }
    document.querySelectorAll('#campagneDetailTabs .nav-link').forEach(function (link) {
        link.addEventListener('shown.bs.tab', function (e) {
            var id = e.target.getAttribute('data-bs-target');
            if (!id) return;
            var name = id.replace('#tab-', '');
            var url = new URL(window.location.href);
            url.searchParams.set('tab', name);
            window.history.replaceState({}, '', url);
        });
    });
})();
</script>
@endpush
@endsection
