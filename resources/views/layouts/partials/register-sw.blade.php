@php
    $bp = rtrim(request()->getBasePath(), '/');
    $pwaScope = ($bp === '' || $bp === '/') ? '/' : $bp.'/';
@endphp
<script>
(function () {
    if (!('serviceWorker' in navigator)) return;
    window.addEventListener('load', function () {
        navigator.serviceWorker.register(@json(asset('sw.js')), { scope: @json($pwaScope) }).catch(function () {});
    });
})();
</script>
