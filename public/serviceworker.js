var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/offline',
   '/public/css/app.css',
    '/public/js/app.js',
    '/images/icons/favicon1-72x72.png',
    '/images/icons/favicon1-96x96.png',
    '/images/icons/favicon1-128x128.png',
    '/images/icons/favicon1-144x144.png',
    '/images/icons/favicon1-152x152.png',
    '/images/icons/favicon1-192x192.png',
    '/images/icons/favicon1-384x384.png',
    '/images/icons/favicon1-512x512.png',
];

// Cache on install (con manejo individual de archivos)
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return Promise.all(
                    filesToCache.map(file =>
                        cache.add(file).catch(err => console.error("Error al guardar en caché:", file, err))
                    )
                );
            })
    );
});


// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});


self.addEventListener('push', function(event) {
    if (!event.data) {
        console.log('No hay datos en la notificación push');
        return;
    }

    const options = {
        body: event.data.text(),
        icon: '/imagenes/noti.png',
        badge: '/imagenes/noti-badge.png',
        tag: 'notificacion-pwa'
    };

    event.waitUntil(
        self.registration.showNotification('Nueva Notificación', options)
            .catch(err => console.error('Error al mostrar la notificación:', err))
    );
});


self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow('/')
    );
});