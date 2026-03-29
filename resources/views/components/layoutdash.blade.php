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

        <a href="{{ route('home') }}">
            <img class="filter invert h-10" src="{{ asset('imgs/logo.svg') }}" alt="L'Artisan Logo">
        </a>

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
                        d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z">

                    </path>

                </svg>

                Mes Annonces

            </a>

            <a href="{{ route('favoris') }}"
                class="gap-4 flex px-6 py-4 border-b border-white/33 hover:bg-white/5 {{ request()->routeIs('favoris') ? 'text-[#FF8E72]' : 'text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path
                        d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
                </svg>
                Mes Favoris
            </a>

            <a href="{{ route('message') }}"
                class="gap-4 flex px-6 py-4 border-b border-white/33 hover:bg-white/5 {{ request()->routeIs('message') ? 'text-[#FF8E72]' : 'text-white' }}">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">

                    <path
                        d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />

                    <path
                        d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />

                </svg>

                Mes Messages</a>

            <a href="{{ route('profil') }}"
                class="gap-4 flex px-6 py-4 border-b border-white/33 hover:bg-white/5 {{ request()->routeIs('profil') ? 'text-[#FF8E72]' : 'text-white' }}">

                <svg data-slot="icon" fill="white" class="size-5" stroke-width="1.5" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">

                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z">

                    </path>

                </svg>

                Mon Profil</a>

            <a href="{{ route('home') }}"
                class="gap-4 flex px-6 py-4 border-b border-white/33 hover:bg-white/5 text-white">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">

                    <path
                        d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />

                    <path
                        d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />

                </svg>

                Acceuil</a>

            <div class="px-6 py-4 border-b border-white/33 hover:bg-white/5">
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="gap-4 flex text-white w-full text-left items-center cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd"
                                d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 1 1.5 0v3.75a3 3 0 0 1-3 3h-6a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3V9A.75.75 0 0 1 15 9V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm10.72 4.72a.75.75 0 0 1 1.06 0l3 3a.75.75 0 0 1 0 1.06l-3 3a.75.75 0 1 1-1.06-1.06l1.72-1.72H9a.75.75 0 0 1 0-1.5h10.94l-1.72-1.72a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Se déconnecter</span>
                    </button>
                </form>
            </div>





        </div>





    </nav>

    <div class="flex">



        <aside class="hidden lg:block h-screen w-60 bg-black text-white flex flex-col gap-5 fixed ">

            <a href="{{ route('home') }}">
                <img class="filter invert p-7 border-black  mr-3" src="{{ asset('imgs/logo.svg') }}" alt="L'Artisan Logo">
            </a>

            <div class="gap-5 flex flex-col mr-3">

                <div class="bg-white h-[0.5px]"></div>



                <div class="flex items-center gap-4 pl-5 ">

                    <svg data-slot="icon" fill="white" class="size-5" stroke-width="1.5" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">

                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z">

                        </path>

                    </svg>

                    <a class="hover:text-[#FF8E72] {{ request()->routeIs('annonces') ? 'text-[#FF8E72]' : 'text-white' }}"
                        href="{{ route('annonces') }}">Mes Annonces</a>



                </div>



                <div class="bg-white h-[0.5px]"></div>

                <div class="flex items-center gap-4 pl-5">

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">

                        <path
                            d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />

                    </svg>

                    <a class="hover:text-[#FF8E72] {{ request()->routeIs('favoris') ? 'text-[#FF8E72]' : 'text-white' }}" href="{{ route('favoris') }}">Mes Favoris</a>



                </div>



                <div class="bg-white h-[0.5px]"></div>

                <div class="flex items-center gap-4 pl-5 ">

                    <svg data-slot="icon" fill="white" class="size-5" stroke-width="1.5" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">

                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z">

                        </path>

                    </svg>



                    <a class="hover:text-[#FF8E72] {{ request()->routeIs('message') ? 'text-[#FF8E72]' : 'text-white' }}"
                        href="{{ route('message') }}">Mes Messages</a>



                </div>

                <div class="bg-white h-[0.5px]"></div>



                <div class="flex items-center gap-4 pl-5">

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">

                        <path fill-rule="evenodd"
                            d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                            clip-rule="evenodd" />

                    </svg>



                    <a class="hover:text-[#FF8E72] {{ request()->routeIs('profil') ? 'text-[#FF8E72]' : 'text-white' }}" href="{{ route('profil') }}">Mon Profil</a>



                </div>

                <div class="bg-white h-[0.5px]"></div>



                <div class="flex items-center gap-4 pl-5">

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">

                        <path
                            d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />

                        <path
                            d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />

                    </svg>



                    <a class=" hover:text-[#FF8E72]" href="{{ route('home') }}">Accueil</a>



                </div>

                <div class="bg-white h-[0.5px]"></div>



                <div class="flex items-center gap-4 pl-5">

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">

                        <path fill-rule="evenodd"
                            d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 1 1.5 0v3.75a3 3 0 0 1-3 3h-6a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3V9A.75.75 0 0 1 15 9V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm10.72 4.72a.75.75 0 0 1 1.06 0l3 3a.75.75 0 0 1 0 1.06l-3 3a.75.75 0 1 1-1.06-1.06l1.72-1.72H9a.75.75 0 0 1 0-1.5h10.94l-1.72-1.72a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" />

                    </svg>

                    <form action="{{ route('logout') }}" method="POST">

                        @csrf

                        <button type="submit" class="cursor-pointer hover:text-[#FF8E72]">Se deconnecter</button>

                    </form>



                </div>



            </div>













        </aside>





















        <div class="pt-6 flex flex-col w-full lg:ml-60">

            <div class="pl-7 gap-2 flex flex-col pr-7 h-33">

                <div class="flex justify-between">

                    <h1 class="text-[23px]">

                        {{ $h1 }}

                    </h1>

                    <button onclick="window.location.href='{{ $btnlocation }}'"
                        class="lg:text-[23px] text-[15px] md:hidden  bg-white rounded-sm h-auto p-2 border cursor-pointer transition-all duration-200 hover:bg-[#FF8E72] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">

                        {{ $btnname }}

                    </button>



                </div>

                <div class="flex md:flex-row md:justify-between md:items-center flex-col pb-1">

                    <div class="h-15 flex items-center gap-2 overflow-x-auto snap-x snap-mandatory scroll-smooth ">
                        @if(isset($customFilters))
                            {{ $customFilters }}
                        @else
                            @if(isset($firstc) && trim($firstc) !== '')
                                <a href=""
                                    class="flex-shrink-0 snap-center border cursor-pointer text-[15px] rounded-[50px] p-2 transition-all duration-200">{{ $firstc }}</a>
                            @endif
                            @if(isset($secondc) && trim($secondc) !== '')
                                <a href=""
                                    class="flex-shrink-0 snap-center border cursor-pointer text-[15px] hover:border-black border-transparent rounded-[50px] p-2 transition-all duration-200 ">{{ $secondc }}</a>
                            @endif
                        @endif
                    </div>

                    <div>

                        <button onclick="window.location.href='{{ $mobbtnlocation }}'"
                            class="text-[15px]  hidden md:block mx-auto bg-white rounded-sm h-auto p-2 border cursor-pointer transition-all duration-200 hover:bg-[#FF8E72] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">

                            {{ $mobbtnname }}

                        </button>

                    </div>

                </div>

                {{ $topbar }}

            </div>

            <div class="bg-black h-[0.5px]"></div>

            <main class="p-7">

                {{ $slot }}

            </main>





        </div>



    </div>









    <x-image-compressor />
</body>
</html>
