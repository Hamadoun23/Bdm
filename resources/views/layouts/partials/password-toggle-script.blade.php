<script>
(function () {
    'use strict';
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-gda-pw-btn]');
        if (!btn) {
            return;
        }
        e.preventDefault();
        var grp = btn.closest('.gda-password-field');
        var input = grp && grp.querySelector('[data-gda-pw-input]');
        if (!input) {
            return;
        }
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        var label = show ? 'Masquer le mot de passe' : 'Afficher le mot de passe';
        btn.setAttribute('aria-label', label);
        btn.setAttribute('title', label);
        btn.setAttribute('aria-pressed', show ? 'true' : 'false');
        var iShow = btn.querySelector('.gda-pw-icon-show');
        var iHide = btn.querySelector('.gda-pw-icon-hide');
        if (iShow && iHide) {
            iShow.classList.toggle('d-none', show);
            iHide.classList.toggle('d-none', !show);
        }
    });
})();
</script>
