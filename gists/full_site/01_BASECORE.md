---
contents:
  - id: 1
    label: resources/views/components/layout.blade.php
    language: blade
  - id: 2
    label: resources/views/components/layoutdash.blade.php
    language: blade
  - id: 3
    label: resources/views/components/layoutdash2.blade.php
    language: blade
  - id: 4
    label: resources/css/app.css
    language: css
  - id: 5
    label: resources/js/app.js
    language: javascript
  - id: 6
    label: resources/js/bootstrap.js
    language: javascript
  - id: 7
    label: resources/js/echo.js
    language: javascript
  - id: 8
    label: vite.config.js
    language: javascript
createdAt: 1774827000000
description: Core system layout, styling, and entry points.
folderId: null
id: 1774827000000
isDeleted: 0
isFavorites: 0
name: 01_BASECORE
tags: []
updatedAt: 1774827000000
---

## Fragment: resources/views/components/layout.blade.php
# This file is used as the primary public layout for the marketplace, containing the main navigation and footer.
```blade
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Lartisan | Acceuil</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="favicon_io (3)/android-chrome-512x512.png">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    @font-face {
      font-family: 'mabrypro';
      src: url('{{ asset('fonts/MabryPro-Regular.ttf') }}') format('truetype');
      font-display: swap;
    }

    html,
    body {
      font-family: 'mabrypro', ui-sans-serif, system-ui, sans-serif;
    }
  </style>
</head>

<body class="w-full bg-[#F4F4F0]">
  <div class="bg-[#F4F4F0] max-w-[1800px] mx-3 lg:mx-14">
    <nav class="lg:flex gap-5 lg:h-29 h-auto items-center justify-between py-1  mt-1  ">
      <div class="flex justify-between items-center ">
        <img class="lg:h-full h-auto max-h-20  shrink-0 " src="{{ asset('imgs/logo.svg') }}" alt="">
       @auth
     <div class="lg:hidden relative">
      <a href="{{ route('annonces') }}">
        <img class="h-10 w-10 object-cover rounded-[50px] hover:border hover:border-[#fb663f] cursor-pointer" src="{{ asset('storage/' . (auth()->user()->pfp ?? 'default.svg')) }}">
      </a>
      @if($unreadCount > 0)
        <div class="absolute -top-1 -right-1 bg-[#FF8E72] text-white text-[10px] font-black h-5 w-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm pointer-events-none">
            {{ $unreadCount }}
        </div>
      @endif
     </div>
     @endauth

      </div>
      
      <form action="{{ route('home') }}" method="GET" class="relative lg:flex-grow h-12 my-auto flex justify-between gap-3">
        <input name="q" value="{{ request('q') }}" placeholder="Rechercher"
          class=" pl-9 border border-black  my-auto rounded-sm w-full h-full bg-white outline-[0rem] shadow-none focus:shadow-[0_0_0_1px_#fb663f]"
          type="text">
        <svg class="absolute top-[28%] left-3 size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
          stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <button type="submit" class="hidden"></button>
        <button type="button" onclick="togglesidebar()" class="lg:hidden relative w-auto h-full flex flex-col transition-all duration-200  border rounded-sm bg-white
        hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] hover:cursor-pointer ">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"
            class="size-5 w-auto h-full  p-3  ">
            <path d="M3 5H21V7H3z"></path>
            <path d="M5.5 11H18.5V13H5.5z"></path>
            <path d="M8 17H16V19H8z"></path>
          </svg>
        </button>
      </form>

      @guest
      <button onclick="window.location.href='{{ route('login') }}'" class="rounded-sm bg-[#F4F4F0] p-1  hidden lg:block lg:w-30 border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    ">
        Connexion
</button>
      @else
      <button onclick="window.location.href='{{ route('favoris') }}'" class="rounded-sm bg-[#F4F4F0] p-1  hidden lg:block lg:w-30 border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    ">
        Mes favoris
      </button>
      @endguest

      <button onclick="window.location.href='{{ auth()->check() ? route('annonces') : route('login') }}'" class="rounded-sm bg-black text-white p-1  hidden lg:block lg:w-50 border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:bg-[#FF8E72]
    hover:text-black">
        Déposer une annonce
      </button>

      <div id="sidebar" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="togglesidebar()"></div>
        <div class="absolute top-0 left-0 h-full w-80 bg-white shadow-xl">
          <button onclick="togglesidebar()" class="absolute top-4 -right-7 z-60 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 text-white">
              <path fill-rule="evenodd"
                d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"
                clip-rule="evenodd" />
            </svg>
          </button>
          <div class="flex justify-between p-4 gap-3">
            @auth
            <button onclick="window.location.href='{{ route('favoris') }}'" class="rounded-sm bg-[#F4F4F0] p-1 w-30 text-[15px]  border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    ">
              Mes favoris
            </button>
            @else
            <button onclick="window.location.href='{{ route('login') }}'" class="rounded-sm bg-[#F4F4F0] p-1 w-30 text-[15px]  border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    ">
              Connexion
            </button>
            @endauth

            <button onclick="window.location.href='{{ auth()->check() ? route('annonces') : route('login') }}'" class="rounded-sm bg-black text-white p-1  w-40 text-[15px] border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:bg-[#fb663f]
    hover:text-black">
              Déposer une annonce
            </button>


          </div>
          <div class="h-0.5 border-b border-black"></div>
          <div class="flex flex-col pt-0 mt-0">
            <a href="{{ route('home') }}"
              class="p-4 h-15 flex items-center text-black text-[17px] hover:bg-black hover:text-white {{ !request('cat') ? 'bg-black text-white' : '' }}">Tout</a>
            @foreach($categories as $category)
            <a href="{{ route('home', ['cat' => $category->id]) }}"
              class="p-4 h-15 flex items-center text-black text-[17px] hover:bg-black hover:text-white {{ request('cat') == $category->id ? 'bg-black text-white' : '' }}">{{ $category->nom }}</a>
            @endforeach
          </div>


        </div>

      </div>


  </div>
  <script>
    function togglesidebar() {
      document.getElementById('sidebar').classList.toggle('hidden')
    }
  </script>
  </nav>
  <div class=" hidden lg:block lg:h-auto max-w-[1800px] mx-14 lg:flex justify-between">
    <div>
       <div class="flex text-black gap-3 ">

      <a class="rounded-[50px] border px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:rounded-[50px] hover:border-black hover:bg-white
    hover:text-black {{ !request('cat') ? 'border-black bg-white' : 'border-transparent' }}" href="{{ route('home') }}">Tout</a>

      @foreach($categories->take(8) as $category)
      <a class="border px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:rounded-[50px] hover:border-black hover:bg-white
    hover:text-black {{ request('cat') == $category->id ? 'rounded-[50px] border-black bg-white' : 'border-transparent' }}" 
    href="{{ route('home', ['cat' => $category->id]) }}">{{ $category->nom }}</a>
      @endforeach

      </div>
    
    </div>
    @auth
     <div class="relative">
      <a href="{{ route('annonces') }}">
        <img class="h-10 w-10 object-cover rounded-[50px] hover:border hover:border-[#fb663f] cursor-pointer" src="{{ asset('storage/' . (auth()->user()->pfp ?? 'default.svg')) }}">
      </a>
      @if($unreadCount > 0)
        <div class="absolute -top-1 -right-1 bg-[#FF8E72] text-white text-[10px] font-black h-5 w-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm pointer-events-none">
            {{ $unreadCount }}
        </div>
      @endif
     </div>
     @endauth
   
  </div>
  </div>
  <div class="w-full border-t border-black my-6"></div>





  <main class=" max-w-[1800px] mx-3 lg:mx-14">
     {{ $slot }}
  </main>


  </div>
  </div>

  <footer
    class="hidden w-full bg-black text-white h-130 mt-6 flex flex-col lg:flex-row lg:h-100 justify-around items-start lg:items-center">
    <div class=" flex-col flex items-start max-w-[1800px] mx-3 lg:mx-14">
      <img src="{{ asset('imgs/logo.svg') }}" class="filter invert w-auto  h-auto max-h-30 lg:max-h-40 ">
      <h2 class="px-3">© 2026 Marché Artisanal. All rights reserved. </h2>

    </div>
    <div class="flex justify-between flex-col gap-14 w-auto max-w-144 px-3">
      <div class="flex justify-between  max-w-75">
        <div class=" flex flex-col gap-6 ">
          @foreach($categories->take(4) as $cat)
            <a href="{{ route('home', ['cat' => $cat->id]) }}">{{ $cat->nom }}</a>
          @endforeach
        </div>
        <div class="flex flex-col gap-6">
          @foreach($categories->skip(4)->take(4) as $cat)
            <a href="{{ route('home', ['cat' => $cat->id]) }}">{{ $cat->nom }}</a>
          @endforeach
        </div>


      </div>

      <div class="flex justify-around sm:gap-22 sm:pb-4 w-auto ">
        <img class="w-[7%] md:w-[4%] aspect-square filter invert" src="socialicons/x.svg" alt="">
        <img class="w-[9%] md:w-[6%] aspect-square filter invert" src="socialicons/y.svg" alt="">
        <img class="w-[7%] md:w-[4%] aspect-square filter invert" src="socialicons/i.svg" alt="">
        <img class="w-[7%] md:w-[4%] aspect-square filter invert" src="socialicons/f.svg" alt="">
        <img class="w-[7%] md:w-[4%] aspect-square filter invert" src="socialicons/p.svg" alt="">
      </div>
    </div>        
  </footer>

</body>

</html>
```

## Fragment: resources/views/components/layoutdash.blade.php
# This file is used for the standard User Dashboard, featuring a sidebar with account navigation.
```blade
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon_io (3)/android-chrome-512x512.png">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @font-face {
            font-family: 'mabrypro';
            src: url('{{ asset('fonts/MabryPro-Regular.ttf') }}') format('truetype');
            font-display: swap;
        }

        html,
        body {
            font-family: 'mabrypro', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>

<body class="bg-[#f4f4f0]">
    <nav x-data="{ open: false }" class="relative w-full lg:hidden h-16 bg-black flex justify-between items-center px-7">
        <img class="filter invert h-10" src="{{ asset('imgs/logo.svg') }}" alt="Logo">
        <p class="text-white text-lg">Dashboard</p>
        <button @click="open = !open" class="focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="size-6">
                <path fill-rule="evenodd"
                    d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z"
                    clip-rule="evenodd" />
            </svg>
        </button>
        <div x-show="open" x-cloak @click.away="open = false" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="absolute left-0 top-full z-50 w-full bg-black flex-col lg:static lg:flex lg:flex-row lg:w-auto"
            :class="open ? 'flex' : 'hidden'">

            <a href="{{ route('annonces') }}"
                class="gap-4 flex px-6 py-4 border-b border-white/33 hover:bg-white/5 {{ request()->routeIs('annonces') ? 'text-[#FF8E72]' : 'text-white' }}">
                <svg data-slot="icon" fill="none" class="size-5" stroke-width="1.5" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"></path>
                </svg>
                Mes Annonces
            </a>
            <!-- ... more links ... -->
        </div>
    </nav>

    <div class="flex">
        <aside class="hidden lg:block h-screen w-60 bg-black text-white flex flex-col gap-5 fixed ">
            <img class="filter invert p-7 border-black  mr-3" src="{{ asset('imgs/logo.svg') }}" alt="">
            <div class="gap-5 flex flex-col mr-3">
                <!-- Links here -->
            </div>
        </aside>

        <div class="pt-6 flex flex-col w-full lg:ml-60">
            <div class="pl-7 gap-2 flex flex-col pr-7 h-33">
                <div class="flex justify-between">
                    <h1 class="text-[23px]">{{ $h1 }}</h1>
                </div>
            </div>
            <div class="bg-black h-[0.5px]"></div>
            <main class="p-7">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
```

## Fragment: resources/views/components/layoutdash2.blade.php
# This file is used for the specific Dashboard layout that prevents double scrolling, optimized for the messaging and map interfaces.
```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f4f4f0]">
    <!-- Similar to layoutdash but with overflow-hidden on main container -->
    <div class="flex">
        <aside class="hidden lg:block h-screen w-60 bg-black text-white fixed">
             <!-- Sidebar content -->
        </aside>
        <div class="pt-6 flex flex-col w-full lg:ml-60">
            <main class="p-7 h-[calc(100vh-180px)] overflow-hidden">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
```

## Fragment: resources/css/app.css
# This is used to define the core Neobrutalist design system, grid layouts, and asset configurations.
```css
@import 'tailwindcss';

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';

@theme {
    --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
        'Segoe UI Symbol', 'Noto Color Emoji';
}

[x-cloak] { display: none !important; }
```

## Fragment: resources/js/app.js
# This is the main Javascript entry point that bootstraps Alpine.js and feature scripts.
```javascript
import './bootstrap';
import './echo';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
```

## Fragment: resources/js/bootstrap.js
# This is used to configure global dependencies like Axios for HTTP requests.
```javascript
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
```

## Fragment: resources/js/echo.js
# This is used to initialize the Laravel Echo client for real-time broadcasting.
```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
```

## Fragment: vite.config.js
# This is the configuration file for the Vite build tool, handling asset bundling and CSS processing.
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
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
```
