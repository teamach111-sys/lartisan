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
     <div class="lg:hidden">
      <a href="{{ route('annonces') }}">
        <img class="h-10 w-10 object-cover rounded-[50px] hover:border hover:border-[#fb663f] cursor-pointer" src="{{ asset('storage/' . auth()->user()->pfp ?? 'default.svg') }}">
      </a>
     </div>
     @endauth

      </div>
      
      <div class="relative lg:flex-grow h-12 my-auto flex justify-between gap-3">
        <input placeholder="Rechercher"
          class=" pl-9 border border-black  my-auto rounded-sm w-full h-full bg-white outline-[0rem] shadow-none focus:shadow-[0_0_0_1px_#fb663f]"
          type="text">
        <svg class="absolute top-[28%] left-3 size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
          stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <button onclick="togglesidebar()" class="lg:hidden relative w-auto h-full flex flex-col transition-all duration-200  border rounded-sm bg-white
        hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] hover:cursor-pointer ">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"
            class="size-5 w-auto h-full  p-3  ">
            <path d="M3 5H21V7H3z"></path>
            <path d="M5.5 11H18.5V13H5.5z"></path>
            <path d="M8 17H16V19H8z"></path>
          </svg>



        </button>




      </div>




      @guest
      <button onclick="window.location.href='{{ route('register') }}'" class="rounded-sm bg-[#F4F4F0] p-1  hidden lg:block lg:w-30 border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    ">
        Inscription
</button>
      @else
      <button class="rounded-sm bg-[#F4F4F0] p-1  hidden lg:block lg:w-30 border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    ">
        Mes favoris
      </button>
      @endguest


      <button onclick="window.location.href='{{ route('register') }}'" class="rounded-sm bg-black text-white p-1  hidden lg:block lg:w-50 border border-black h-12 my-auto cursor-pointer 
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
            <button class="rounded-sm bg-[#F4F4F0] p-1 w-30 text-[15px]  border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    ">
              Mes favoris
            </button>
            @else
            <button class="rounded-sm bg-[#F4F4F0] p-1 w-30 text-[15px]  border border-black h-12 my-auto cursor-pointer 
    transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    ">
              Inscription
            </button>
            @endauth

            <button onclick="window.location.href='{{ route('register') }}'" class="rounded-sm bg-black text-white p-1  w-40 text-[15px] border border-black h-12 my-auto cursor-pointer 
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
            <a href=""
              class="p-4 h-15 flex items-center text-black text-[17px] hover:bg-black hover:text-white">Tout</a>
            <a href=""
              class="p-4 h-15 flex items-center text-black text-[17px] hover:bg-black hover:text-white">Mosaique</a>
            <a href=""
              class="p-4 h-15 flex items-center text-black text-[17px] hover:bg-black hover:text-white">Pottery</a>
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

      <a class="rounded-[50px] border border-black bg-white px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
   
    hover:text-black" href="">Tout</a>

      <a class="border border-transparent hover:rounded-[50px] hover:border-black hover:bg-white px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    
    hover:text-black" href="">Carrelage</a>
      <a class="border border-transparent hover:rounded-[50px] hover:border-black hover:bg-white px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:text-black" href="">Marbrerie</a>
      <a class="border border-transparent hover:rounded-[50px] hover:border-black hover:bg-white px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:text-black" href="">Poterie</a>
      <a class="border border-transparent hover:rounded-[50px] hover:border-black hover:bg-white px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:text-black" href="">Céramique</a>
      <a class="border border-transparent hover:rounded-[50px] hover:border-black hover:bg-white px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:text-black" href="">Marqueterie</a>
      <a class="border border-transparent hover:rounded-[50px] hover:border-black hover:bg-white px-3 py-2 w-auto h-auto transition-all duration-200 
    hover:-translate-x-1 hover:-translate-y-1 
    hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]
    hover:text-black" href="">Menuiserie</a>

      </div>
    
    </div>
    @auth
     <div>
      <a href="{{ route('annonces') }}">
        <img class="h-10 w-10 object-cover rounded-[50px] hover:border hover:border-[#fb663f] cursor-pointer" src="{{ asset('storage/' . auth()->user()->pfp ?? 'default.svg') }}">
      </a>
     </div>
     @endauth
   
  </div>
  </div>
  <div class="w-full border-t border-black my-6"></div>





  <main class=" max-w-[1800px] mx-3 lg:mx-14">
    <div class="py-2 flex justify-between">
      <p class="text-[26px] lg:text-[26px] ">Annonces Sponsorisé</p>
      <div class="flex items-center gap-2">
        <button class="cursor-pointer" onclick="scrollSlider('left')"> <svg xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24" width="24" height="24" fill="currentColor" class="size-6">
            <path d="m6 12 6 5v-4h6v-2h-6V7z"></path>
          </svg>
        </button>
        <button class="cursor-pointer" onclick="scrollSlider('right')"><svg xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24" width="24" height="24" fill="currentColor" class="size-6">
            <path d="M6 13h6v4l6-5-6-5v4H6z"></path>
          </svg>
        </button>
      </div>

    </div>

    <section style="scrollbar-width: none; -ms-overflow-style: none;"
      class="py-6 px-1 lg:flex  grid grid-flow-col auto-cols-[calc(60%-2rem)]  gap-3 overflow-x-auto snap-x snap-mandatory scroll-smooth scrollbar-hide">
      <div class="cursor-pointer flex-shrink-0 snap-center  bg-white lg:h-93 rounded-sm border transition-all duration-200 
          hover:shadow-[4px_4px_0px_0px_#000000] flex flex-col lg:flex-row">
        <div>
          <img
            src="https://i.natgeofe.com/n/548467d8-c5f1-4551-9f58-6817a8d2c45e/NationalGeographic_2572187_16x9.jpg?w=1200"
            class="overflow-hidden object-cover w-full lg:h-full lg:max-w-93 h-full border-b lg:border-r" alt="">


        </div>

        <div class=" flex flex-col gap-2 justify-between  ">
          <div class="p-4 border-b lg:border-none flex flex-col gap-3 lg:w-55">
            <p class="font-bold break-words">Titlezzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz</p>
            <p class="text-gray-700 truncate">Description</p>
            <div class="mt-3 flex gap-2 items-center">
              <img class="h-10 w-10 object-cover rounded-[50px] border" src="https://images.pexels.com/photos/104827/cat-pet-animal-domestic-104827.jpeg?cs=srgb&dl=pexels-pixabay-104827.jpg&fm=jpg" alt="">
              <p class="underline">Seller name and pfp</p>


            </div>
          </div>

          <div>
            <div class="mb-2 ml-2 inline-block bg-black p-[1px] 
            [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
              <div class="bg-[#FF8E72] text-black font-bold text-sm py-1 pl-4 pr-12 
              [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
                11 DH
              </div>
            </div>
          </div>


        </div>
      </div>
      
     
      
      
    










    </section>
    <section class="pb-6 pt-11 ">
      <div class="flex lg:justify-between flex-col lg:flex-row  lg:items-center pb-4 gap-4">
        <p class="text-[26px] lg:text-[26px] md:text-[26px] ">Dans le marché</p>
        <div class="">
          <a href=""
            class="border md:text-[20px] bg-white border-black hover:border-black lg:text[20px] cursor-pointer text-[20px]  rounded-[50px] p-2 transition-all duration-200  ">Nouvelles
            Annonces</a>
          <a href=""
            class="border md:text-[20px] lg:text-[20px] cursor-pointer text-[20px] hover:border-black   border-transparent rounded-[50px] p-2 transition-all duration-200 ">Prix
            bas
          </a>

        </div>
      </div>
      <div class="pt-6 flex flex-col items-start lg:flex-row gap-15 ">
        <div class="w-full lg:w-140">
          <div class="w-auto border border-black bg-white font-sans text-sm ">
            <div class="p-4 border-b border-black font-bold">
              Filters
            </div>

            <details class="border-b border-black group" open>
              <summary
                class="flex items-center justify-between p-4 cursor-pointer list-none font-semibold hover:bg-gray-50">
                Tags
                <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </summary>

              <div class="px-4 pb-4 space-y-3">
                <label class="flex items-center justify-between cursor-pointer">
                  <span class="break-words">vrchat (20838)</span>
                  <input type="checkbox" class="w-5 h-5 border border-black rounded-sm appearance-none cursor-pointer
         checked:bg-[#FF99F0] 
         checked:bg-[url('data:image/svg+xml;charset=utf8,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22black%22%20stroke-width%3D%224%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%2220%206%209%2017%204%2012%22%3E%3C/polyline%3E%3C/svg%3E')] 
         bg-center bg-no-repeat bg-[length:80%_80%]" />
                </label>
                <label class="flex items-center justify-between cursor-pointer">
                  <span class="break-words">notion template (11308)</span>
                  <input type="checkbox" class="w-5 h-5 border border-black rounded-sm appearance-none cursor-pointer
         checked:bg-[#FF99F0] 
         checked:bg-[url('data:image/svg+xml;charset=utf8,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22black%22%20stroke-width%3D%224%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%2220%206%209%2017%204%2012%22%3E%3C/polyline%3E%3C/svg%3E')] 
         bg-center bg-no-repeat bg-[length:80%_80%]" />
                </label>
              </div>
            </details>

            <details class="border-b border-black group">
              <summary
                class="flex items-center justify-between p-4 cursor-pointer list-none font-semibold hover:bg-gray-50">
                Contains
                <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </summary>
              <div class="p-4 pt-0 text-gray-500">Filters content here...</div>
            </details>
          </div>
        </div>

        <div class="flex flex-col  gap-9 w-full">
          <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-3 items-stretch " id="product-grid">

             @foreach ($produits as $produit) 
              <x-produit :produit="$produit" />
             
             @endforeach

            </div>


          <button id="load-more"
            class="mx-auto p-4 bg-white border rounded-md transition-all duration-200 cursor-pointer hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">Load
            More</button>
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
            Le Marché Artisanal est votre destination privilégiée pour découvrir le savoir-faire authentique des artisans locaux. Qualité, tradition et modernité réunies.
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-lg font-bold mb-6">Catégories</h3>
          <ul class="space-y-4 text-gray-400 transition-colors">
            <li><a href="#" class="hover:text-[#FF8E72]">Poterie & Céramique</a></li>
            <li><a href="#" class="hover:text-[#FF8E72]">Marbrerie & Taille</a></li>
            <li><a href="#" class="hover:text-[#FF8E72]">Menuiserie d'Art</a></li>
            <li><a href="#" class="hover:text-[#FF8E72]">Tapis & Tissage</a></li>
          </ul>
        </div>

        <!-- Support -->
        <div>
          <h3 class="text-lg font-bold mb-6">L'Artisan</h3>
          <ul class="space-y-4 text-gray-400 transition-colors">
            <li><a href="#" class="hover:text-[#FF8E72]">À propos de nous</a></li>
            <li><a href="#" class="hover:text-[#FF8E72]">Devenir Exposant</a></li>
            <li><a href="#" class="hover:text-[#FF8E72]">Charte Qualité</a></li>
            <li><a href="#" class="hover:text-[#FF8E72]">Contactez-nous</a></li>
          </ul>
        </div>

        <!-- Social Media -->
        <div>
          <h3 class="text-lg font-bold mb-6">Suivez-nous</h3>
          <div class="flex gap-4">
            <a href="#" class="p-2 bg-zinc-900 rounded-sm hover:bg-[#FF8E72] hover:text-black transition-all group">
              <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.248h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <a href="#" class="p-2 bg-zinc-900 rounded-sm hover:bg-[#FF8E72] hover:text-black transition-all">
              <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.981 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.981-6.98.058-1.281.072-1.689.072-4.948 0-3.259-.014-3.668-.072-4.948-.2-4.353-2.62-6.782-6.981-6.981C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
            </a>
            <a href="#" class="p-2 bg-zinc-900 rounded-sm hover:bg-[#FF8E72] hover:text-black transition-all">
              <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
          </div>
        </div>
      </div>

      <!-- Bottom Bar -->
      <div class="border-t border-zinc-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-500">
        <p>© 2026 Marché Artisanal. Fièrement fabriqué à Marrakech. </p>
        <div class="flex gap-6">
          <a href="#" class="hover:text-white transition-colors">Confidentialité</a>
          <a href="#" class="hover:text-white transition-colors">Conditions</a>
          <a href="#" class="hover:text-white transition-colors">Cookies</a>
        </div>
      </div>
    </div>

    <!-- Scripts -->
    <script>
      function scrollSlider(direction) {
        const slider = document.querySelector('section');
        const scrollAmount = 640; 
        if (direction === 'left') {
          slider.scrollLeft -= scrollAmount;
        } else {
          slider.scrollLeft += scrollAmount;
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

</body>

</html>