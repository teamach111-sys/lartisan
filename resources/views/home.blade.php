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
                <a href="{{ route('home') }}">
                    <img class="lg:h-full h-auto max-h-20  shrink-0 " src="{{ asset('imgs/logo.svg') }}" alt="L'Artisan Logo">
                </a>
                @auth
                    <div class="lg:hidden relative">
                        <a href="{{ route('annonces') }}">
                            <img class="h-10 w-10 object-cover rounded-[50px] hover:border hover:border-[#fb663f] cursor-pointer"
                                src="{{ asset('storage/' . (auth()->user()->pfp ?? 'default.svg')) }}">
                        </a>
                        @if ($unreadCount > 0)
                            <div
                                class="absolute -top-1 -right-1 bg-[#FF8E72] text-white text-[10px] font-black h-5 w-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm pointer-events-none">
                                {{ $unreadCount }}
                            </div>
                        @endif
                    </div>
                @endauth

            </div>

            <form id="search-form" action="{{ route('home') }}" method="GET"
                class="relative lg:flex-grow h-12 my-auto flex justify-between gap-3">
                <input name="q" id="search-input" value="{{ request('q') }}" placeholder="Rechercher"
                    class=" pl-9 border border-black  my-auto rounded-sm w-full h-full bg-white outline-[0rem] shadow-none focus:shadow-[0_0_0_1px_#fb663f]"
                    type="text">
                <svg class="absolute top-[28%] left-3 size-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <button type="submit" class="hidden"></button>
                <button type="button" onclick="togglesidebar()"
                    class="lg:hidden relative w-auto h-full flex flex-col transition-all duration-200  border rounded-sm bg-white
        hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] hover:cursor-pointer ">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                        fill="currentColor" class="size-5 w-auto h-full  p-3  ">
                        <path d="M3 5H21V7H3z"></path>
                        <path d="M5.5 11H18.5V13H5.5z"></path>
                        <path d="M8 17H16V19H8z"></path>
                    </svg>
                </button>
            </form>




            @guest
                <button onclick="window.location.href='{{ route('login') }}'"
                    class="rounded-sm bg-[#F4F4F0] p-1  hidden lg:block lg:w-30 border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    ">
                    Connexion
                </button>
            @else
                <button onclick="window.location.href='{{ route('favoris') }}'"
                    class="rounded-sm bg-[#F4F4F0] p-1  hidden lg:block lg:w-30 border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    ">
                    Mes favoris
                </button>
            @endguest


            <button onclick="window.location.href='{{ auth()->check() ? route('annonces') : route('login') }}'"
                class="rounded-sm bg-black text-white p-1  hidden lg:block lg:w-50 border border-black h-12 my-auto cursor-pointer 
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
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="size-6 text-white">
                            <path fill-rule="evenodd"
                                d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div class="flex justify-between p-4 gap-3">
                        @auth
                            <button onclick="window.location.href='{{ route('favoris') }}'"
                                class="rounded-sm bg-[#F4F4F0] p-1 w-30 text-[15px]  border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    ">
                                Mes favoris
                            </button>
                        @else
                            <button onclick="window.location.href='{{ route('login') }}'"
                                class="rounded-sm bg-[#F4F4F0] p-1 w-30 text-[15px]  border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    ">
                                Connexion
                            </button>
                        @endauth

                        <button onclick="window.location.href='{{ auth()->check() ? route('annonces') : route('login') }}'"
                            class="rounded-sm bg-black text-white p-1  w-40 text-[15px] border border-black h-12 my-auto cursor-pointer 
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
                            class="p-4 h-15 flex items-center text-black text-[17px]  hover:bg-black hover:text-white {{ !request('cat') ? 'bg-black text-white' : '' }}">Tout</a>
                        @foreach ($categories as $category)
                            <a href="{{ request()->fullUrlWithQuery(['cat' => $category->id]) }}"
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

    hover:text-black {{ !request('cat') ? 'border-black bg-white' : 'border-transparent' }}"
                    href="{{ route('home') }}">Tout</a>

                @foreach ($categories->take(8) as $category)
                    <a class="border px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:rounded-[50px] hover:border-black hover:bg-white
    hover:text-black {{ request('cat') == $category->id ? 'rounded-[50px] border-black bg-white' : 'border-transparent' }}"
                        href="{{ request()->fullUrlWithQuery(['cat' => $category->id]) }}">{{ $category->nom }}</a>
                @endforeach

            </div>

        </div>
        @auth
            <div class="relative">
                <a href="{{ route('annonces') }}">
                    <img class="h-10 w-10 object-cover rounded-[50px] hover:border hover:border-[#fb663f] cursor-pointer"
                        src="{{ asset('storage/' . (auth()->user()->pfp ?? 'default.svg')) }}">
                </a>
                @if ($unreadCount > 0)
                    <div
                        class="absolute -top-1 -right-1 bg-[#FF8E72] text-white text-[10px] font-black h-5 w-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm pointer-events-none">
                        {{ $unreadCount }}
                    </div>
                @endif
            </div>
        @endauth

    </div>
    </div>
    <div class="w-full border-t border-black my-6"></div>





    <main class=" max-w-[1800px] mx-3 lg:mx-14">
        <div id="sponsored-section" class="{{ $isFiltering ? 'hidden' : '' }}">
            <div class="py-2 flex justify-between">
                <p class="text-[26px] lg:text-[26px] ">Annonces Sponsorisé</p>
                <div class="hidden lg:flex items-center gap-2">
                    <button class="cursor-pointer" onclick="scrollSlider('left')"> <svg
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                            fill="currentColor" class="size-6">
                            <path d="m6 12 6 5v-4h6v-2h-6V7z"></path>
                        </svg>
                    </button>
                    <button class="cursor-pointer" onclick="scrollSlider('right')"><svg
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                            fill="currentColor" class="size-6">
                            <path d="M6 13h6v4l6-5-6-5v4H6z"></path>
                        </svg>
                    </button>
                </div>

            </div>

            <section id="sponsored-slider" style="scrollbar-width: none; -ms-overflow-style: none;"
                class="relative py-6 px-1 lg:flex grid grid-flow-col auto-cols-[85vw] sm:auto-cols-[60vw] md:auto-cols-[45vw] lg:auto-cols-auto gap-4 overflow-x-auto snap-x snap-proximity lg:snap-none scroll-smooth scrollbar-hide">
                @forelse($sponsoredProducts as $produit)
                <div onclick="window.location.href='{{ route('produit.show', $produit->slug) }}'"
                    class="cursor-pointer flex-shrink-0 snap-center lg:snap-start bg-white lg:min-w-[600px] lg:h-93 rounded-sm border transition-all duration-200 
          hover:shadow-[4px_4px_0px_0px_#000000] flex flex-col lg:flex-row">
                    <div class="relative h-56 sm:h-64 lg:h-full lg:w-93 flex-shrink-0">
                        @php 
                            $firstImage = (is_array($produit->images) && count($produit->images) > 0) ? $produit->images[0] : null;
                        @endphp
                        <img src="{{ $firstImage ? asset('storage/' . $firstImage) : 'https://placehold.co/1200x900?text=No+Image' }}"
                            class="overflow-hidden object-cover w-full h-full lg:h-full lg:w-full border-b lg:border-b-0 lg:border-r"
                            alt="{{ $produit->titre }}">
                            
                        <div class="absolute top-2 left-2 bg-[#FF8E72] border border-black shadow-[2px_2px_0px_0px_#000000] text-black text-xs font-black px-2.5 py-1 uppercase rounded-sm z-10 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-3">
                              <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                            </svg>
                            Sponsorisé
                        </div>
                    </div>

                    <div class=" flex flex-col gap-2 justify-between">
                        <div class="p-4 border-b lg:border-none flex flex-col gap-3 lg:w-55">
                            <p class="font-bold break-words line-clamp-2 h-12">{{ $produit->titre }}</p>
                            <p class="text-gray-700 truncate">{{ $produit->description }}</p>
                            <div class="mt-3 flex gap-2 items-center">
                                <img class="h-10 w-10 object-cover rounded-[50px] border"
                                    src="{{ asset('storage/' . ($produit->vendeur->pfp ?? 'default.svg')) }}"
                                    alt="">
                                <p class="underline">{{ $produit->vendeur->name }}</p>
                            </div>
                        </div>

                        <div>
                            <div
                                class="mb-2 ml-2 inline-block bg-black p-[1px] 
            [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
                                <div
                                    class="bg-[#FF8E72] text-black font-bold text-sm py-1 pl-4 pr-12 
              [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
                                    {{ $produit->prix }} DH
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="w-full text-center text-gray-500 py-10 font-bold border-2 border-dashed border-gray-300 rounded-sm">
                    Aucune annonce sponsorisée pour le moment.
                </div>
                @endforelse















            </section>
        </div>
        <section class="pb-6 pt-11 ">
            <div class="flex lg:justify-between flex-col lg:flex-row lg:items-center pb-4 gap-4">
                <p id="market-title" class="text-[26px] lg:text-[26px] md:text-[26px] font-bold">
                    {{ $filterTitle }}
                </p>
            </div>
            <div class="pt-6 flex flex-col items-start lg:flex-row gap-15 ">
                <div class="w-full lg:w-140">
                    <form id="filter-form" action="{{ route('home') }}" method="GET"
                        class="w-auto border border-black bg-white font-sans text-sm ">
                        <div class="p-4 border-b border-black font-bold flex justify-between items-center">
                            Filtres
                            <a id="clear-filters" href="{{ route('home') }}"
                                class="text-xs font-normal underline {{ $isFiltering ? '' : 'hidden' }}">Effacer</a>
                        </div>

                        <details class="border-b border-black group">
                            <summary
                                class="flex items-center justify-between p-4 cursor-pointer list-none font-semibold hover:bg-gray-50 uppercase tracking-wider">
                                Catégorie
                                <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </summary>

                            <div class="px-4 pb-4 space-y-2">
                                @foreach ($categories as $category)
                                    <label class="flex items-center gap-2 cursor-pointer hover:text-[#fb663f] group">
                                        <input type="radio" name="cat" value="{{ $category->id }}"
                                            {{ request('cat') == $category->id ? 'checked' : '' }}
                                            onclick="toggleFilter(this)"
                                            data-was-checked="{{ request('cat') == $category->id ? 'true' : 'false' }}"
                                            class="filter-input w-4 h-4 border border-black rounded-sm appearance-none cursor-pointer
                    checked:bg-[#fb663f]
                    checked:bg-[url('data:image/svg+xml;charset=utf8,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22black%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%2220%206%209%2017%204%2012%22%3E%3C/polyline%3E%3C/svg%3E')] 
                    bg-center bg-no-repeat bg-[length:1.1em_1.1em]">
                                        <span>{{ $category->nom }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </details>

                        <details class="border-b border-black group">
                            <summary
                                class="flex items-center justify-between p-4 cursor-pointer list-none font-semibold hover:bg-gray-50 uppercase tracking-wider">
                                Ville
                                <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </summary>
                            <div class="px-4 pb-4 space-y-2">
                                @foreach ($villes as $ville)
                                    <label class="flex items-center gap-2 cursor-pointer hover:text-[#fb663f] group">
                                        <input type="radio" name="ville" value="{{ $ville->nom }}"
                                            {{ request('ville') == $ville->nom ? 'checked' : '' }}
                                            onclick="toggleFilter(this)"
                                            data-was-checked="{{ request('ville') == $ville->nom ? 'true' : 'false' }}"
                                            class="filter-input w-4 h-4 border border-black rounded-sm appearance-none cursor-pointer
                    checked:bg-[#fb663f]
                    checked:bg-[url('data:image/svg+xml;charset=utf8,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22black%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%2220%206%209%2017%204%2012%22%3E%3C/polyline%3E%3C/svg%3E')] 
                    bg-center bg-no-repeat bg-[length:1.1em_1.1em]">
                                        <span>{{ $ville->nom }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </details>

                        <details class="group">
                            <summary
                                class="flex items-center justify-between p-4 cursor-pointer list-none font-semibold hover:bg-gray-50 uppercase tracking-wider">
                                Prix (DH)
                                <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </summary>
                            <div class="p-4 space-y-4">
                                <div class="flex flex-col gap-2">
                                    <input type="number" name="min" value="{{ request('min') }}"
                                        placeholder="Min"
                                        class="w-full p-2 border border-black rounded-sm focus:shadow-[2px_2px_0px_0px_#000000] outline-none">
                                    <input type="number" name="max" value="{{ request('max') }}"
                                        placeholder="Max"
                                        class="w-full p-2 border border-black rounded-sm focus:shadow-[2px_2px_0px_0px_#000000] outline-none">
                                </div>
                                <button type="submit"
                                    class="w-full h-12 bg-black text-white rounded-sm font-bold border border-black cursor-pointer transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:bg-[#FF8E72] hover:text-black">Appliquer</button>
                            </div>
                        </details>

                        <input type="hidden" name="sort" id="sort-input"
                            value="{{ request('sort', 'recent') }}">
                    </form>
                </div>

                <div class="flex flex-col gap-5 w-full">
                    {{-- Sorting links moved here to be under filters in mobile --}}
                    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'recent']) }}" data-sort="recent"
                            class="sort-link flex-shrink-0 border text-[15px] cursor-pointer rounded-[50px] p-2 transition-all duration-200 {{ request('sort') == 'recent' || !request('sort') ? 'border-black' : 'hover:border-black border-transparent' }}">Plus
                            récentes</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'prix_bas']) }}" data-sort="prix_bas"
                            class="sort-link flex-shrink-0 border text-[15px] cursor-pointer rounded-[50px] p-2 transition-all duration-200 {{ request('sort') == 'prix_bas' ? 'border-black' : 'hover:border-black border-transparent' }}">Prix
                            bas</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'prix_haut']) }}" data-sort="prix_haut"
                            class="sort-link flex-shrink-0 border text-[15px] cursor-pointer rounded-[50px] p-2 transition-all duration-200 {{ request('sort') == 'prix_haut' ? 'border-black' : 'hover:border-black border-transparent' }}">Prix
                            haut</a>
                    </div>
                    <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-3 items-stretch "
                        id="product-grid">
                        @include('partials.produits-grid')
                    </div>


                    <button id="load-more"
                        class="mx-auto p-4 bg-white border rounded-md transition-all duration-200 cursor-pointer hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">Plus
                        de produits</button>
                </div>

            </div>



        </section>
    </main>


    </div>
    </div>

    <footer class="w-full bg-black text-white pt-16 pb-8 mt-12">
        <div class="max-w-[1800px] mx-auto px-4 lg:px-14">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <!-- Logo & About -->
                <div class="space-y-6">
                    <img src="{{ asset('imgs/logo.svg') }}" class="filter invert h-12 w-auto" alt="Lartisan Logo">
                    <p class="text-gray-400 text-sm leading-relaxed max-w-xs">
                        Le Marché Artisanal est votre destination privilégiée pour découvrir le savoir-faire authentique
                        des artisans locaux. Qualité, tradition et modernité réunies.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-bold mb-6">Catégories</h3>
                    <ul class="space-y-4 text-gray-400 transition-colors">
                        @foreach ($categories->take(4) as $category)
                            <li><a href="{{ request()->fullUrlWithQuery(['cat' => $category->id]) }}"
                                    class="hover:text-[#FF8E72]">{{ $category->nom }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Sécurité & Aide -->
                <div>
                    <h3 class="text-lg font-bold mb-6">Sécurité</h3>
                    <ul class="space-y-4 text-gray-400 transition-colors">
                        <li><a href="{{ route('centre-aide') }}#eviter-arnaques" class="hover:text-[#FF8E72]">Éviter les arnaques</a></li>
                        <li><a href="{{ route('centre-aide') }}#paiement-securise" class="hover:text-[#FF8E72]">Paiement sécurisé</a></li>
                        <li><a href="{{ route('centre-aide') }}#signaler-probleme" class="hover:text-[#FF8E72]">Signaler un problème</a></li>
                        <li><a href="{{ route('centre-aide') }}#acheter-confiance" class="hover:text-[#FF8E72]">Acheter en confiance</a></li>
                        <li><a href="{{ route('centre-aide') }}" class="hover:text-[#FF8E72] font-semibold">Centre d'aide →</a></li>
                    </ul>
                </div>

                <!-- Social Media -->
                <div>
                    <h3 class="text-lg font-bold mb-6">Suivez-nous</h3>
                    <div class="flex gap-4">
                        <a href="#"
                            class="p-2 bg-zinc-900 rounded-sm hover:bg-[#FF8E72] hover:text-black transition-all group">
                            <svg class="size-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.248h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="p-2 bg-zinc-900 rounded-sm hover:bg-[#FF8E72] hover:text-black transition-all">
                            <svg class="size-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.981 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.981-6.98.058-1.281.072-1.689.072-4.948 0-3.259-.014-3.668-.072-4.948-.2-4.353-2.62-6.782-6.981-6.981C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="p-2 bg-zinc-900 rounded-sm hover:bg-[#FF8E72] hover:text-black transition-all">
                            <svg class="size-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div
                class="border-t border-zinc-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-500">
                <p>© 2026 Marché Artisanal. Fièrement fabriqué à Marrakech.</p>
                <a href="{{ route('centre-aide') }}" class="hover:text-white transition-colors">Centre d'aide</a>
            </div>
        </div>

        <!-- Scripts -->
        <script>
            function scrollSlider(direction) {
                const slider = document.getElementById('sponsored-slider');
                if (!slider) return;
                
                const cards = Array.from(slider.querySelectorAll('div.flex-shrink-0'));
                if (cards.length === 0) return;
                
                let targetCard = null;
                const currentScrollLeft = slider.scrollLeft;
                const maxScrollLeft = slider.scrollWidth - slider.clientWidth;
                
                if (direction === 'right') {
                    // Si on est déjà à la fin (ou très proche), on retourne au début
                    if (currentScrollLeft >= maxScrollLeft - 10) {
                        slider.scrollTo({ left: 0, behavior: 'smooth' });
                        return;
                    }

                    targetCard = cards.find(card => {
                        const cardScrollPos = card.offsetLeft - slider.offsetLeft;
                        return cardScrollPos > currentScrollLeft + 10;
                    });
                } else {
                    // Si on est au tout début, on va à la fin
                    if (currentScrollLeft <= 10) {
                        slider.scrollTo({ left: maxScrollLeft, behavior: 'smooth' });
                        return;
                    }

                    targetCard = cards.slice().reverse().find(card => {
                        const cardScrollPos = card.offsetLeft - slider.offsetLeft;
                        return cardScrollPos < currentScrollLeft - 10;
                    });
                }
                
                if (targetCard) {
                    slider.scrollTo({
                        left: targetCard.offsetLeft - slider.offsetLeft,
                        behavior: 'smooth'
                    });
                }
            }
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const cards = document.querySelectorAll(".product-card");
                const loadMoreBtn = document.getElementById("load-more");
                let visibleCount = 12;

                cards.forEach((card, index) => {
                    if (index >= visibleCount) {
                        card.style.display = "none";
                    }
                });

                if (loadMoreBtn) {
                    loadMoreBtn.addEventListener("click", () => {
                        let newlyShown = 0;
                        const itemsToLoad = 12;
                        for (let i = visibleCount; i < cards.length; i++) {
                            if (newlyShown < itemsToLoad) {
                                cards[i].style.display = "block";
                                newlyShown++;
                            }
                        }
                        visibleCount += itemsToLoad;
                        if (visibleCount >= cards.length) {
                            loadMoreBtn.style.display = "none";
                        }
                    });
                }
            });
        </script>
    </footer>

    <script>
        function updateMarket(url) {
            const grid = document.getElementById('product-grid');
            const sponsored = document.getElementById('sponsored-section');
            const title = document.getElementById('market-title');
            const clearBtn = document.getElementById('clear-filters');

            // Indicateur de chargement
            grid.style.opacity = '0.5';
            grid.style.pointerEvents = 'none';

            const ajaxUrl = new URL(url);
            ajaxUrl.searchParams.set('_ajax', '1');

            fetch(ajaxUrl.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Mettre à jour la grille
                    grid.innerHTML = data.html;
                    grid.style.opacity = '1';
                    grid.style.pointerEvents = 'auto';

                    // Visibilité des sponsorisés (caché si recherche OU filtre actif)
                    if (data.isFiltering) {
                        sponsored.classList.add('hidden');
                    } else {
                        sponsored.classList.remove('hidden');
                    }

                    // Titre de la section
                    title.textContent = data.filterTitle;

                    // Bouton effacer
                    if (data.isFiltering) {
                        clearBtn.classList.remove('hidden');
                    } else {
                        clearBtn.classList.add('hidden');
                    }

                    // Mettre à jour l'URL sans recharger
                    window.history.pushState(null, '', url);

                    // Mettre à jour l'état actif des tris (visuel)
                    const params = new URLSearchParams(new URL(url).search);
                    const currentSort = params.get('sort') || 'recent';
                    document.querySelectorAll('.sort-link').forEach(link => {
                        if (link.dataset.sort === currentSort) {
                            link.classList.add('border-black');
                            link.classList.remove('border-transparent');
                        } else {
                            link.classList.remove('border-black');
                            link.classList.add('border-transparent');
                        }
                    });

                    // Mettre à jour search input
                    document.getElementById('search-input').value = params.get('q') || '';
                })
                .catch(error => {
                    console.error('Error:', error);
                    grid.style.opacity = '1';
                    grid.style.pointerEvents = 'auto';
                });
        }

        // Intercepter le formulaire de filtres
        document.getElementById('filter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams(formData);

            // Préserver la recherche q si elle existe déjà dans l'URL
            const currentParams = new URLSearchParams(window.location.search);
            if (currentParams.has('q')) {
                params.set('q', currentParams.get('q'));
            }

            updateMarket(`${window.location.origin}${window.location.pathname}?${params.toString()}`);
        });

        // Intercepter la recherche
        document.getElementById('search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const q = document.getElementById('search-input').value;

            // Créer de nouveaux paramètres pour repartir de zéro (reset filtres et tri)
            const newParams = new URLSearchParams();
            if (q) {
                newParams.set('q', q);
            }

            // Réinitialiser visuellement le formulaire de filtres
            document.getElementById('filter-form').reset();
            document.querySelectorAll('.filter-input').forEach(input => {
                input.dataset.wasChecked = 'false';
                input.checked = false;
            });

            updateMarket(`${window.location.origin}${window.location.pathname}?${newParams.toString()}`);
        });

        // Intercepter les clics sur les tris
        document.querySelectorAll('.sort-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const sort = this.dataset.sort;
                document.getElementById('sort-input').value = sort;
                // Déclencher le submit du formulaire de filtre pour inclure les autres critères
                document.getElementById('filter-form').dispatchEvent(new Event('submit'));
            });
        });

        // Handler pour le "click again to uncheck"
        function toggleFilter(radio) {
            if (radio.dataset.wasChecked == 'true') {
                radio.checked = false;
                radio.dataset.wasChecked = 'false';
            } else {
                // Reset others in same group
                document.querySelectorAll(`input[name="${radio.name}"]`).forEach(r => r.dataset.wasChecked = 'false');
                radio.dataset.wasChecked = 'true';
            }
            document.getElementById('filter-form').dispatchEvent(new Event('submit'));
        }

        // Intercepter "Effacer"
        document.getElementById('clear-filters').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('filter-form').reset();
            document.getElementById('search-input').value = '';
            // Uncheck all radios
            document.querySelectorAll('.filter-input').forEach(r => {
                r.checked = false;
                r.dataset.wasChecked = 'false';
            });
            updateMarket(this.href);
        });
    </script>
</body>

</html>
