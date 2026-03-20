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
          <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-3 " id="product-grid">
            <div
              class="product-card bg-white border rounded-sm  h-120  overflow-hidden cursor-pointer transition-all duration-200 hover:shadow-[4px_4px_0px_0px_#000000]">
              <img class="object-cover h-full w-full max-h-66 border-b"
                src="https://i.natgeofe.com/n/548467d8-c5f1-4551-9f58-6817a8d2c45e/NationalGeographic_2572187_16x9.jpg?w=1200"
                alt="">

              <div class="h-full max-h-36 border-b">
                <div class="px-5 pt-4 ">
                  <p class="text-[17px] line-clamp-2 font-bold break-words">Titletttttteeeeeeeeeeeeeeeeeeeeeeeeeeeeetttttttttttt</p>
                </div>
                <div class="px-5 pt-4">
                   <div class="mt-3 flex gap-2 items-center">
              <img class="h-10 w-10 object-cover rounded-[50px] border" src="https://images.pexels.com/photos/104827/cat-pet-animal-domestic-104827.jpeg?cs=srgb&dl=pexels-pixabay-104827.jpg&fm=jpg" alt="">
              <p class="line-clamp-1 underline">Seller name and pfp</p>


            </div>

                </div>

              </div>


              <div class="p-5 h-14">

                <div class="inline-block bg-black p-[1px] 
               [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
                  <div class="line-clamp-1 bg-[#FF8E72] text-black font-bold text-sm py-1 pl-4 pr-12 
                 [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
                    11 DH
                  </div>
                </div>
              </div>

            </div>
            
            
           















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

  <footer
    class="w-full bg-black text-white h-130 mt-6 flex flex-col lg:flex-row lg:h-100 justify-around items-start lg:items-center">
    <div class=" flex-col flex items-start max-w-[1800px] mx-3 lg:mx-14">
      <img src="{{ asset('imgs/logo.svg') }}" class="filter invert w-auto  h-auto max-h-30 lg:max-h-40 ">
      <h2 class="px-3">© 2026 Marché Artisanal. All rights reserved. </h2>

    </div>
    <div class="flex justify-between flex-col gap-14 w-auto max-w-144 px-3">
      <div class="flex justify-between  max-w-75">
        <div class=" flex flex-col gap-6 ">

          <a href="">Carrelage</a>
          <a href="">Céramique</a>
          <a href="">Carrelage</a>
          <a href="">Céramique</a>



        </div>
        <div class="flex flex-col gap-4">

          <a href="">Carrelage</a>
          <a href="">Céramique</a>



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




    <script>
      function scrollSlider(direction) {
        const slider = document.querySelector('section');
        const scrollAmount = 640; // width of one card (160 * 4 for gap)

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

        let visibleCount = 4; // How many to show initially

        // 1. Initial Setup: Hide everything past the 9th card
        cards.forEach((card, index) => {
          if (index >= visibleCount) {
            card.style.display = "none";
          }
        });

        // 2. Click Logic
        loadMoreBtn.addEventListener("click", () => {
          let newlyShown = 0;
          const itemsToLoad = 4; // How many to add per click

          for (let i = visibleCount; i < cards.length; i++) {
            if (newlyShown < itemsToLoad) {
              cards[i].style.display = "block"; // or "block" depending on your card style
              newlyShown++;
            }
          }

          visibleCount += itemsToLoad;

          // 3. Hide button if no more cards exist
          if (visibleCount >= cards.length) {
            loadMoreBtn.style.display = "none";
          }
        });
      });
    </script>
  </footer>

</body>

</html>