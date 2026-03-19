const CACHE_NAME = 'motorista-v1';
const ASSETS_TO_CACHE = [
    '/motorista/',
    '/motorista/index.html',
    '/motorista/app.html',
    '/motorista/css/app.css',
    '/motorista/js/api.js',
    '/motorista/js/push.js',
    '/motorista/manifest.json',
    '/motorista/icons/icon-192.png',
    '/motorista/icons/icon-512.png',
];

// Install: cache shell assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(ASSETS_TO_CACHE))
    );
    self.skipWaiting();
});

// Activate: clean old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
        )
    );
    self.clients.claim();
});

// Fetch: network-first for API, cache-first for assets
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // API requests: always network
    if (url.pathname.startsWith('/api/') || url.pathname.startsWith('/motorista/api/')) {
        return;
    }

    event.respondWith(
        caches.match(event.request).then((cached) => {
            return cached || fetch(event.request).then((response) => {
                // Cache successful GET responses
                if (event.request.method === 'GET' && response.status === 200) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
                }
                return response;
            });
        }).catch(() => {
            // Offline fallback
            if (event.request.destination === 'document') {
                return caches.match('/motorista/index.html');
            }
        })
    );
});

// Push notification received
self.addEventListener('push', (event) => {
    let data = { title: 'Nova Coleta', body: 'Você tem uma nova coleta agendada.' };

    if (event.data) {
        try {
            data = event.data.json();
        } catch (e) {
            data.body = event.data.text();
        }
    }

    const options = {
        body: data.body,
        icon: '/motorista/icons/icon-192.png',
        badge: '/motorista/icons/icon-192.png',
        vibrate: [200, 100, 200],
        tag: data.tag || 'coleta-notification',
        data: {
            url: data.url || '/motorista/app.html',
        },
    };

    event.waitUntil(self.registration.showNotification(data.title, options));
});

// Click on notification → open app
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const urlToOpen = event.notification.data?.url || '/app.html';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
            // Focus existing window if open
            for (const client of windowClients) {
                if (client.url.includes('motorista') && 'focus' in client) {
                    client.navigate(urlToOpen);
                    return client.focus();
                }
            }
            // Open new window
            return clients.openWindow(urlToOpen);
        })
    );
});
