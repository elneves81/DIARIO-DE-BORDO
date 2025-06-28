const CACHE_NAME = 'diario-bordo-v1';
const STATIC_CACHE = 'static-v1';
const DYNAMIC_CACHE = 'dynamic-v1';

const STATIC_FILES = [
    '/',
    '/viagens/create',
    '/css/app.css',
    '/js/app.js',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css'
];

// Install Service Worker
self.addEventListener('install', event => {
    console.log('Service Worker: Installing...');
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(cache => {
                console.log('Service Worker: Caching static files');
                return cache.addAll(STATIC_FILES);
            })
            .catch(error => console.log('Service Worker: Error caching static files', error))
    );
});

// Activate Service Worker
self.addEventListener('activate', event => {
    console.log('Service Worker: Activating...');
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => {
                    if (cache !== STATIC_CACHE && cache !== DYNAMIC_CACHE) {
                        console.log('Service Worker: Clearing old cache', cache);
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
});

// Fetch Event - Network First with Cache Fallback
self.addEventListener('fetch', event => {
    // Skip cross-origin requests
    if (!event.request.url.startsWith(self.location.origin)) {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then(response => {
                // Clone response for cache
                const responseClone = response.clone();
                
                // Cache successful responses
                if (response.status === 200) {
                    caches.open(DYNAMIC_CACHE)
                        .then(cache => {
                            cache.put(event.request, responseClone);
                        });
                }
                
                return response;
            })
            .catch(() => {
                // Network failed, try cache
                return caches.match(event.request)
                    .then(cachedResponse => {
                        if (cachedResponse) {
                            return cachedResponse;
                        }
                        
                        // Return offline page for navigation requests
                        if (event.request.mode === 'navigate') {
                            return caches.match('/offline.html');
                        }
                        
                        return new Response('Offline', {
                            status: 503,
                            statusText: 'Service Unavailable'
                        });
                    });
            })
    );
});

// Background Sync for form submissions
self.addEventListener('sync', event => {
    if (event.tag === 'viagem-sync') {
        event.waitUntil(
            syncViagens()
        );
    }
});

// Push Notifications
self.addEventListener('push', event => {
    const options = {
        body: event.data ? event.data.text() : 'Nova notificação do Diário de Bordo',
        icon: '/img/icon-192.png',
        badge: '/img/badge.png',
        vibrate: [200, 100, 200],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
        },
        actions: [
            {
                action: 'explore',
                title: 'Ver detalhes',
                icon: '/img/icon-explore.png'
            },
            {
                action: 'close',
                title: 'Fechar',
                icon: '/img/icon-close.png'
            }
        ]
    };

    event.waitUntil(
        self.registration.showNotification('Diário de Bordo', options)
    );
});

// Notification Click
self.addEventListener('notificationclick', event => {
    event.notification.close();

    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/viagens')
        );
    }
});

// Helper function to sync pending viagens
async function syncViagens() {
    // Implementation for syncing offline form submissions
    console.log('Syncing pending viagens...');
    
    // Get pending viagens from IndexedDB
    // Send to server
    // Clear from local storage on success
}
