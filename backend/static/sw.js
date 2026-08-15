/**
 * Service Worker — Gda Money PWA
 * Pré-cache les assets statiques (chemins relatifs au répertoire de l’app).
 */
const SW_PATH = self.location.pathname;
const BASE = SW_PATH.replace(/\/?sw\.js$/i, '');

function appPath(path) {
    const p = path.startsWith('/') ? path : '/' + path;
    return (BASE || '') + p;
}

const CACHE_NAME = 'gda-money-static-v1';
const PRECACHE_URLS = [
    appPath('/css/gda-theme.css'),
    appPath('/logo/iconesgda.png'),
    appPath('/logo/gdamoney.png'),
];

function pathRelativeToApp(urlPath) {
    if (!BASE) return urlPath;
    if (urlPath.startsWith(BASE)) {
        const rest = urlPath.slice(BASE.length);
        return rest || '/';
    }
    return urlPath;
}

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE_URLS)).then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) => Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const { request } = event;
    if (request.method !== 'GET') {
        return;
    }
    const url = new URL(request.url);
    if (url.origin !== self.location.origin) {
        return;
    }

    const rel = pathRelativeToApp(url.pathname);
    const isStaticAsset =
        PRECACHE_URLS.includes(url.pathname) ||
        rel.startsWith('/css/') ||
        rel.startsWith('/logo/') ||
        rel.startsWith('/build/assets/');

    if (isStaticAsset) {
        event.respondWith(
            caches.match(request).then((cached) => {
                if (cached) {
                    return cached;
                }
                return fetch(request).then((response) => {
                    const copy = response.clone();
                    if (response.ok && (rel.startsWith('/css/') || rel.startsWith('/logo/'))) {
                        caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                    }
                    return response;
                });
            })
        );
    }
});
