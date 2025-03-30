var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/offline',
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
    self.skipWaiting();
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

// 📩 Firebase Messaging
importScripts("https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js");

// Your web app's Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyC8_mVc3OAr7oqeJk00LCh59cQNnnQEUIY",
  authDomain: "entrega-3dfd0.firebaseapp.com",
  projectId: "entrega-3dfd0",
  storageBucket: "entrega-3dfd0.firebasestorage.app",
  messagingSenderId: "34637998721",
  appId: "1:34637998721:web:2059921cb527fff5493b80"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();


// 📩 Maneja las notificaciones en segundo plano
messaging.onBackgroundMessage((payload) => {

  // 📌 Mostrar la notificación

  self.registration.showNotification(payload.notification.title, {
      body: payload.notification.body,
      icon:  "https://entrega.pe/imagenes/Ola-64x64-Orange.png",
      badge:  "https://entrega.pe/imagenes/Ola-64x64-Orange.png",
      requireInteraction: true,
      vibrate: [200, 100, 200],
      data: { url: payload.notification.url },
      tag: "pedido-123",
      renotify: true,
      silent: false,
      actions: [
        {
            action: "ver-detalles",
            title: "📍 Ver Detalles"
        },
        {
            action: "cerrar",
            title: "❌ Cerrar"
        }
    ]
  });
  


});

self.addEventListener("notificationclick", function(event) {
    event.notification.close(); // Cierra la notificación

    event.waitUntil(
        clients.matchAll({ type: "window", includeUncontrolled: true }).then(clientList => {
            for (let client of clientList) {
                if (client.url.includes("/mi-cuenta") && "focus" in client) {
                    return client.focus(); // Si la pestaña ya está abierta, la enfoca
                }
            }

            if (event.action === "ver-detalles") {
                return clients.openWindow(event.notification.data?.url || "https://entrega.pe/mi-cuenta");
            } else if (event.action === "cerrar") {
                console.log("❌ Usuario seleccionó 'Cerrar'. No se abrirá ninguna ventana.");
                return;
            } else {
                return clients.openWindow(event.notification.data?.url || "https://entrega.pe/mi-cuenta");
            }
        })
    );
});


self.addEventListener('push', function(event) {
    const data = event.data.json();

    self.registration.showNotification(data.notification.title, {
        body: data.notification.body,
        icon: "/imagenes/Ola-64x64-Orange.png",
        data: { url: data.data?.url ?? "/mi-cuenta" }
    });
});











