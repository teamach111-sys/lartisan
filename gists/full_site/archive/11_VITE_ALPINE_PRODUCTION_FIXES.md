---
description: L'Artisan Marketplace - Vite & Alpine.js Production Deployment Fixes
---

# Vite & Alpine.js Production Deployment Fixes

This document outlines the essential configurations and code changes required to successfully deploy the L'Artisan Marketplace (using Vite, Alpine.js, and Reverb) to Laravel Cloud or other strict CI/CD production environments.

## 1. Ensuring Vite Compiles in Production (`package.json`)

By default, CI environments set `NODE_ENV=production`. This causes `npm ci` or `npm install` to aggressively skip `devDependencies`. Because Laravel usually stores `vite` and `tailwindcss` as dev dependencies, the deployment compile script (`npm run build`) will fail silently with a "command not found" error, serving stale or broken assets.

**The Fix:** Move all build tools into the primary `dependencies` array.

### `package.json`
```json
{
    "private": true,
    "type": "module",
    "scripts": {
        "build": "vite build",
        "dev": "vite",
        "dev:all": "npm run build && concurrently \"php artisan serve\" \"npm run dev\" \"php artisan reverb:start\""
    },
    "devDependencies": {
        "concurrently": "^9.0.1"
    },
    "dependencies": {
        "@tailwindcss/vite": "^4.0.0",
        "axios": "^1.11.0",
        "laravel-echo": "^2.3.1",
        "laravel-vite-plugin": "^2.0.0",
        "pusher-js": "^8.4.3",
        "tailwindcss": "^4.0.0",
        "vite": "^7.0.7",
        "alpinejs": "^3.15.8"
    }
}
```

## 2. Preventing Alpine.js from Crashing on Production

If any Javascript module fails during initialization (e.g., trying to read undefined `.env` variables for WebSockets), the entire `app.js` file will instantly crash in the browser. When this happens *before* Alpine initializes, critical UI elements (like mobile navigation menus) will break.

**The Fix:** Initialize Alpine *first*, and wrap risky network initializations in a `try...catch` block.

### `resources/js/app.js`
Ensure `Alpine.start()` is the very first thing that executes after the bootstrap loads.

```javascript
import './bootstrap';

// Initialize Alpine JS FIRST to guarantee UI works
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Initialize Echo / Websockets which might fail if VITE env vars are missing
import './echo';
import ImageCompressor from './image-compressor';
window.ImageCompressor = ImageCompressor;
```

### `resources/js/echo.js`
Wrap the `new Echo(...)` initialization in a `try/catch` to guarantee it never takes down the entire application script if the `VITE_REVERB_*` variables are missing on the production server.

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

try {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
        wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
    });
} catch (error) {
    console.warn("Echo failed to initialize. Likely missing VITE_ variables:", error);
}
```

## 3. Deployment Script Best Practices (Laravel Cloud)

To prevent invisible Windows line-break errors (Carriage Return `\r\n`) from breaking the deployment script, place all commands on a single continuous line separated by `&&`.

**Vapor / Cloud Deployment Hook:**
```bash
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader && npm ci --audit false && npm run build
```
