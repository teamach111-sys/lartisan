---
description: L'Artisan Marketplace - Core UI, Layouts & Assets
---

# Core UI & Layouts

This module contains the foundational UI architecture, including the main layout, dashboard layout, and asset configurations (Vite/Tailwind).

## 1. Global Layout Component
### `resources/views/components/layout.blade.php`
The main wrapper for the public site, featuring the responsive navigation, search bar, and global category list.

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lartisan | Acceuil</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @font-face {
            font-family: 'mabrypro';
            src: url('{{ asset('fonts/MabryPro-Regular.ttf') }}') format('truetype');
            font-display: swap;
        }
        html, body { font-family: 'mabrypro', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="w-full bg-[#F4F4F0]">
    <div class="bg-[#F4F4F0] max-w-[1800px] mx-3 lg:mx-14">
        <nav class="lg:flex gap-5 lg:h-29 h-auto items-center justify-between py-1 mt-1">
            <!-- Logo, Search Form, Favorites, and Listing Buttons -->
            ...
        </nav>
        <div class="w-full border-t border-black my-6"></div>
        <main class="max-w-[1800px] mx-3 lg:mx-14">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
```

## 2. Dashboard Layout Component
### `resources/views/components/layoutdash.blade.php`
The sidebar-driven layout used for user account management, messaging, and listings.

```html
<body class="bg-[#f4f4f0]">
    <div class="flex">
        <aside class="hidden lg:block h-screen w-60 bg-black text-white flex flex-col gap-5 fixed">
            <!-- Sidebar Navigation: Annonces, Favoris, Messages, Profil -->
        </aside>
        <div class="pt-6 flex flex-col w-full lg:ml-60">
            <main class="px-3 py-7 md:px-7 lg:px-14">
                {{ $slot }}
            </main>
        </div>
    </div>
    <x-image-compressor />
</body>
```

## 3. Global Data Sharing
### `app/Providers/AppServiceProvider.php`
Ensures `categories`, `villes`, and `unreadCount` are available to all views.

```php
public function boot(): void
{
    \Illuminate\Support\Facades\View::composer('*', function ($view) {
        $view->with('categories', \App\Models\Categorie::all());
        $view->with('villes', \App\Models\Ville::orderBy('nom')->get());

        if (auth()->check()) {
            $unreadCount = \App\Models\Message::whereHas('conversation', function($query) {
                $query->where('acheteur_id', auth()->id())
                      ->orWhereHas('produit', function($sq) {
                          $sq->where('vendeur_id', auth()->id());
                      });
            })->where('expediteur_id', '!=', auth()->id())->where('est_lu', false)->count();
            $view->with('unreadCount', $unreadCount);
        } else {
            $view->with('unreadCount', 0);
        }
    });
}
```

## 4. Asset Configuration
### `vite.config.js`
Optimized for Laravel v11 and Tailwind CSS v4.

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
```

### `resources/css/app.css`
```css
@import 'tailwindcss';
[x-cloak] { display: none !important; }
```

### `resources/js/app.js`
```javascript
import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import './echo';
import ImageCompressor from './image-compressor';
window.ImageCompressor = ImageCompressor;
```
