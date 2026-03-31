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
        <img class="h-10 w-10 object-cover rounded-[50px] hover:border hover:border-[#fb663f] cursor-pointer" src="{{ auth()->user()->pfp_url }}">
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
        <img class="h-10 w-10 object-cover rounded-[50px] hover:border hover:border-[#fb663f] cursor-pointer" src="{{ auth()->user()->pfp_url }}">
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