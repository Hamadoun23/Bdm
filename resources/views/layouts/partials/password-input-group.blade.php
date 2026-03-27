{{-- Champ mot de passe + bouton afficher/masquer (SVG inline, pas d’icônes externes). --}}
@php
    $fieldId = $id ?? $name;
    $inputClass = trim('form-control '.($inputClass ?? ''));
@endphp
<div class="input-group gda-password-field">
    <input
        type="password"
        name="{{ $name }}"
        id="{{ $fieldId }}"
        class="{{ $inputClass }}"
        @if(!empty($required)) required @endif
        autocomplete="{{ $autocomplete ?? 'new-password' }}"
        placeholder="{{ $placeholder ?? '' }}"
        data-gda-pw-input
    >
    <button
        type="button"
        class="btn btn-outline-secondary px-3 gda-pw-toggle"
        data-gda-pw-btn
        aria-label="Afficher le mot de passe"
        title="Afficher le mot de passe"
        aria-pressed="false"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="1.125rem" height="1.125rem" fill="currentColor" class="gda-pw-icon-show align-middle" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.086.13-.292.343-.637.657C12.545 10.21 10.424 12 8 12c-2.424 0-4.545-1.79-5.372-3.343-.345-.314-.551-.527-.637-.657zM8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="1.125rem" height="1.125rem" fill="currentColor" class="gda-pw-icon-hide d-none align-middle" viewBox="0 0 16 16" aria-hidden="true">
            <path d="m13.359 11.238-3.782-3.782a4 4 0 0 0-5.774-5.775l2.842 2.842a1.5 1.5 0 0 1 1.885 1.884l2.829 2.829zm-2.123 2.123-10-10 .708-.708 10 10-.708.708zm-2.476-2.476-1.65-1.65a3 3 0 0 1 3.994 3.994l-1.344-1.344zM2.354 3.646a8.5 8.5 0 1 1 12.293 12.293l-1.5-1.5a6.5 6.5 0 1 0-9.293-9.293l-1.5-1.5z"/>
        </svg>
    </button>
</div>
