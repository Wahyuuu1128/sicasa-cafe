const CACHE_NAME = 'sicasa-pos-v3';
const OFFLINE_URL = '/offline.html';

const STATIC_ASSETS = [
    '/',
    '/sistem',
    '/offline.html',
    '/manifest.json',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
    '/images/logo.png',
    '/images/sicasa.png'
];

// Install: Pre-cache static assets & offline fallback
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(STATIC_ASSETS);
        }).then(() => self.skipWaiting())
    );
});

// Activate: Clean up old cache versions & take control immediately
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys => {
            return Promise.all(
                keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key))
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch: Network-first for pages, cache-first for assets, offline fallback
self.addEventListener('fetch', event => {
    // Only handle GET requests
    if (event.request.method !== 'GET') {
        return;
    }

    const url = new URL(event.request.url);

    // Skip non-http(s)
    if (!url.protocol.startsWith('http')) {
        return;
    }

    // HTML Navigation requests
    if (event.request.mode === 'navigate' || event.request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(
            fetch(event.request)
                .then(networkResponse => {
                    if (networkResponse && networkResponse.status === 200) {
                        const responseClone = networkResponse.clone();
                        caches.open(CACHE_NAME).then(cache => {
                            cache.put(event.request, responseClone);
                        });
                    }
                    return networkResponse;
                })
                .catch(async () => {
                    // Try to return matching cached page
                    const cachedResponse = await caches.match(event.request);
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // Otherwise return beautiful offline page
                    return caches.match(OFFLINE_URL);
                })
        );
        return;
    }

    // Assets: Cache-first with network fallback & auto-cache
    event.respondWith(
        caches.match(event.request).then(cachedResponse => {
            if (cachedResponse) {
                return cachedResponse;
            }
            return fetch(event.request).then(networkResponse => {
                if (networkResponse && networkResponse.status === 200) {
                    const responseClone = networkResponse.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseClone);
                    });
                }
                return networkResponse;
            }).catch(() => {
                // If offline and requesting an image, we can return null or cached logo
                if (event.request.destination === 'image') {
                    return caches.match('/images/logo.png');
                }
            });
        })
    );
});
