'use strict';

// ⚠️ Scope: static assets only. NO HTML, LOGIN PAGE, nor user data is cached offline.
// Audited cache list (fetch handler matches these exact paths):
const STATIC_URLS = [
  '/manifest.webmanifest',
  '/css/app.css',
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open('joinara-static-v1').then((cache) => cache.addAll(STATIC_URLS))
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((names) => Promise.all(
      names.filter((n) => n !== 'joinara-static-v1').map((n) => caches.delete(n))
    ))
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);
  if (url.pathname.endsWith('.webmanifest') || url.pathname.endsWith('.js')) {
    event.respondWith(fetchStatic(url.pathname));
  }
});

async function fetchStatic(path) {
  const cache = await caches.open('joinara-static-v1');
  const cached = await cache.match(path);
  const networkFirst = fetch(path, { credentials: 'same-origin' }).then((response) => {
    if (response.ok) cache.put(path, response.clone());
    return response;
  });
  return cached || networkFirst;
}
