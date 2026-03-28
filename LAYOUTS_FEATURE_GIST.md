# L'Artisan — Layouts Feature

> This gist contains the core Blade layout components used throughout the application.
> These layouts include the responsive Neo-Brutalist design, Alpine.js logic for sidebars, and Font setup.

## 1. `resources/views/components/layout.blade.php`
*(Main layout for Public and Auth pages)*
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
    <!-- Navbar Top -->
    <nav class="lg:flex gap-5 lg:h-29 h-auto items-center justify-between py-1 mt-1">
      <div class="flex justify-between items-center ">
        <img class="lg:h-full h-auto max-h-20 shrink-0" src="{{ asset('imgs/logo.svg') }}" alt="">
        @auth
        <div class="lg:hidden">
          <a href="{{ route('annonces') }}">
            <img class="h-10 w-10 object-cover rounded-[50px] hover:border hover:border-[#fb663f] cursor-pointer" src="{{ asset('storage/' . auth()->user()->pfp ?? 'default.svg') }}">
          </a>
        </div>
        @endauth
      </div>
      
      <!-- Search -->
      <div class="relative lg:flex-grow h-12 my-auto flex justify-between gap-3">
        <input placeholder="Rechercher" class="pl-9 border border-black my-auto rounded-sm w-full h-full bg-white outline-[0rem] shadow-none focus:shadow-[0_0_0_1px_#fb663f]" type="text">
        <svg class="absolute top-[28%] left-3 size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <button onclick="togglesidebar()" class="lg:hidden relative w-auto h-full flex flex-col transition-all duration-200 border rounded-sm bg-white hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] hover:cursor-pointer">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor" class="size-5 w-auto h-full p-3">
            <path d="M3 5H21V7H3z"></path>
            <path d="M5.5 11H18.5V13H5.5z"></path>
            <path d="M8 17H16V19H8z"></path>
          </svg>
        </button>
      </div>

      <!-- Auth Buttons -->
      @guest
      <button onclick="window.location.href='{{ route('register') }}'" class="rounded-sm bg-[#F4F4F0] p-1 hidden lg:block lg:w-30 border border-black h-12 my-auto cursor-pointer transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
        Inscription
      </button>
      @else
      <button class="rounded-sm bg-[#F4F4F0] p-1 hidden lg:block lg:w-30 border border-black h-12 my-auto cursor-pointer transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
        Mes favoris
      </button>
      @endguest

      <button onclick="window.location.href='{{ route('register') }}'" class="rounded-sm bg-black text-white p-1 hidden lg:block lg:w-50 border border-black h-12 my-auto cursor-pointer transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:bg-[#FF8E72] hover:text-black">
        Déposer une annonce
      </button>

      <!-- Mobile Sidebar -->
      <div id="sidebar" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="togglesidebar()"></div>
        <div class="absolute top-0 left-0 h-full w-80 bg-white shadow-xl">
          <button onclick="togglesidebar()" class="absolute top-4 -right-7 z-60 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 text-white">
              <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
          </button>
          
          <div class="flex justify-between p-4 gap-3">
            <!-- Sidebar content omitted for brevity -->
          </div>
        </div>
      </div>
    </nav>
    <script>
      function togglesidebar() {
        document.getElementById('sidebar').classList.toggle('hidden');
      }
    </script>
    
    <!-- Navbar Bottom (Categories) -->
    <div class="hidden lg:block lg:h-auto max-w-[1800px] mx-14 lg:flex justify-between">
      <div class="flex text-black gap-3">
        <a class="rounded-[50px] border border-black bg-white px-3 py-2 w-auto h-auto transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:text-black" href="">Tout</a>
        <!-- Categories omitted for brevity... -->
      </div>
      @auth
      <div>
        <a href="{{ route('annonces') }}">
          <img class="h-10 w-10 object-cover rounded-[50px] hover:border hover:border-[#fb663f] cursor-pointer" src="{{ asset('storage/' . auth()->user()->pfp ?? 'default.svg') }}">
        </a>
      </div>
      @endauth
    </div>
    
    <div class="w-full border-t border-black my-6"></div>

    <main class="max-w-[1800px] mx-3 lg:mx-14">
       {{ $slot }}
    </main>

  </div>

  <footer class="hidden w-full bg-black text-white h-130 mt-6 flex flex-col lg:flex-row lg:h-100 justify-around items-start lg:items-center">
    <div class="flex-col flex items-start max-w-[1800px] mx-3 lg:mx-14">
      <img src="{{ asset('imgs/logo.svg') }}" class="filter invert w-auto h-auto max-h-30 lg:max-h-40">
      <h2 class="px-3">© 2026 Marché Artisanal. All rights reserved. </h2>
    </div>
  </footer>

</body>

</html>
```

## 2. `resources/views/components/layoutdash.blade.php`
*(Dashboard layout: Sidebar, Navbar, Titles, Slot injection)*
```blade
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon_io (3)/android-chrome-512x512.png">
    <title>{{ $title ?? 'Dashboard' }}</title>
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
                <!-- Hamburger Icon -->
                <path fill-rule="evenodd" d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
            </svg>
        </button>
        <div x-show="open" x-cloak @click.away="open = false" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            class="absolute left-0 top-full z-50 w-full bg-black flex-col lg:static lg:flex lg:flex-row lg:w-auto"
            :class="open ? 'flex' : 'hidden'">
            
            <a href="{{ route('annonces') }}" class="gap-4 flex px-6 py-4 border-b border-white/33 hover:bg-white/5 {{ request()->routeIs('annonces') ? 'text-[#FF8E72]' : 'text-white' }}">
                Mes Annonces
            </a>
            <a href="{{ route('message') }}" class="gap-4 flex text-white px-6 py-4 border-b border-white/33 hover:bg-white/5 {{ request()->routeIs('message') ? 'text-[#FF8E72]' : 'text-white' }}">
                Mes Messages
            </a>
            <!-- Mobile Links... -->
        </div>
    </nav>

    <div class="flex">
        <aside class="hidden lg:block h-screen w-60 bg-black text-white flex flex-col gap-5 fixed ">
            <img class="filter invert p-7 border-black  mr-3" src="{{ asset('imgs/logo.svg') }}" alt="">
            <div class="gap-5 flex flex-col mr-3">
                <div class="bg-white h-[0.5px]"></div>
                <div class="flex items-center gap-4 pl-5 ">
                    <a class="hover:text-[#FF8E72] {{ request()->routeIs('annonces') ? 'text-[#FF8E72]' : 'text-white' }}" href="{{ route('annonces') }}">Mes Annonces</a>
                </div>
                <div class="bg-white h-[0.5px]"></div>
                <!-- Desktop Sidebar Links... -->
            </div>
        </aside>

        <div class="pt-6 flex flex-col w-full lg:ml-60">
            <div class="pl-7 gap-2 flex flex-col pr-7 h-33">
                <div class="flex justify-between">
                    <!-- Title Injected Here -->
                    <h1 class="text-[23px]">{{ $h1 ?? '' }}</h1>
                    <button onclick="window.location.href='{{ $btnlocation ?? '' }}'"
                        class="lg:text-[23px] text-[15px] md:hidden bg-white rounded-sm h-auto p-2 border cursor-pointer transition-all duration-200 hover:bg-[#FF8E72] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">
                        {{ $btnname ?? '' }}
                    </button>
                </div>
                <div class="flex md:flex-row md:justify-between md:items-center flex-col pb-1">
                    <div class="h-15 flex items-center gap-2 overflow-x-auto snap-x snap-mandatory scroll-smooth ">
                        <a href="" class="flex-shrink-0 snap-center border cursor-pointer text-[15px]  rounded-[50px] p-2 transition-all duration-200">{{ $firstc ?? '' }}</a>
                        <a href="" class="flex-shrink-0 snap-center border cursor-pointer text-[15px] hover:border-black border-transparent rounded-[50px] p-2 transition-all duration-200">{{ $secondc ?? '' }}</a>
                    </div>
                    <div>
                        <button onclick="window.location.href='{{ $mobbtnlocation ?? '' }}'"
                            class="text-[15px] hidden md:block mx-auto bg-white rounded-sm h-auto p-2 border cursor-pointer transition-all duration-200 hover:bg-[#FF8E72] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">
                            {{ $mobbtnname ?? '' }}
                        </button>
                    </div>
                </div>
                {{ $topbar ?? '' }}
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

## 3. `resources/views/components/layoutdash2.blade.php`
*(Same as Dashboard layout, but specifically uses `h-[calc(100vh-180px)] overflow-hidden` for the SPA Messaging structure)*
```blade
<!DOCTYPE html>
<html lang="en">
<!-- Head and Nav identical to layoutdash -->
<body class="bg-[#f4f4f0]">
    <!-- Mobile Nav & Sidebar omitted for brevity -->

        <div class="pt-6 flex flex-col w-full lg:ml-60">
            <div class="pl-7 gap-2 flex flex-col pr-7 h-33">
                <div class="flex justify-between">
                    <h1 class="text-[23px]">{{ $h1 ?? '' }}</h1>
                </div>
                {{ $topbar ?? '' }}
            </div>
            <div class="bg-black h-[0.5px]"></div>
            
            <!-- Specific styling applied for Messaging container lock -->
            <main class="p-7 h-[calc(100vh-180px)] overflow-hidden">
                {{ $slot }}
            </main>
            
        </div>
    </div>
</body>
</html>
```
