const CACHE_NAME = 'sipandu-cache-v2'; 
const urlsToCache = [
    '/sipandu_pbl/assets/css/styles.min.css',
    '/sipandu_pbl/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js',
    '/sipandu_pbl/assets/js/sidebarmenu.js',
    '/sipandu_pbl/assets/js/app.min.js', 
    '/sipandu_pbl/assets/images/logos/icon.png',
    '/sipandu_pbl/manifest.json'
];

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function(cache) {
            console.log('[ServiceWorker] Caching static assets');
            return cache.addAll(urlsToCache);
        })
    );
});

self.addEventListener('activate', function(event) {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (!cacheWhitelist.includes(cacheName)) {
                        console.log('[ServiceWorker] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request)
            .then(function(response) {
                return response || fetch(event.request);
            })
    );
});