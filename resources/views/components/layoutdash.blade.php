<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon_io (3)/android-chrome-512x512.png">
    <title>Document</title>
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
    <div class="flex">
        <aside class="h-screen w-60 bg-black text-white flex flex-col gap-5 ">
            <img class="filter invert p-7 border-black border-b mr-3"
                src="{{ asset('imgs/logo.svg') }}" alt="">
            <div class="gap-5 flex flex-col mr-3">
                <div class="flex items-center gap-4 pl-5 ">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
  <path fill-rule="evenodd" d="M1.5 7.125c0-1.036.84-1.875 1.875-1.875h6c1.036 0 1.875.84 1.875 1.875v3.75c0 1.036-.84 1.875-1.875 1.875h-6A1.875 1.875 0 0 1 1.5 10.875v-3.75Zm12 1.5c0-1.036.84-1.875 1.875-1.875h5.25c1.035 0 1.875.84 1.875 1.875v8.25c0 1.035-.84 1.875-1.875 1.875h-5.25a1.875 1.875 0 0 1-1.875-1.875v-8.25ZM3 16.125c0-1.036.84-1.875 1.875-1.875h5.25c1.036 0 1.875.84 1.875 1.875v2.25c0 1.035-.84 1.875-1.875 1.875h-5.25A1.875 1.875 0 0 1 3 18.375v-2.25Z" clip-rule="evenodd" />
</svg>
                    <a class="hover:text-[#FF8E72]" href="">Mes Annonces</a>

                </div>

                <div class="bg-white h-[0.5px]"></div>
                <div class="flex items-center gap-4 pl-5">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
  <path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
</svg>
                    <a class="hover:text-[#FF8E72]" href="">Mes Favoris</a>

                </div>

                <div class="bg-white h-[0.5px]"></div>
                <div class="flex items-center gap-4 pl-5">
                   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
  <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
  <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
</svg>

                    <a class="hover:text-[#FF8E72]" href="">Mes Messages</a>

                </div>
                <div class="bg-white h-[0.5px]"></div>

                <div class="flex items-center gap-4 pl-5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
  <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
</svg>

                    <a class="hover:text-[#FF8E72]" href="">Mon Profil</a>

                </div>
                <div class="bg-white h-[0.5px]"></div>

                <div class="flex items-center gap-4 pl-5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
  <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
  <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
</svg>

                    <a class=" hover:text-[#FF8E72]" href="{{ route('home') }}">Accueil</a>

                </div>
                <div class="bg-white h-[0.5px]"></div>

                <div class="flex items-center gap-4 pl-5">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
  <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 1 1.5 0v3.75a3 3 0 0 1-3 3h-6a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3V9A.75.75 0 0 1 15 9V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm10.72 4.72a.75.75 0 0 1 1.06 0l3 3a.75.75 0 0 1 0 1.06l-3 3a.75.75 0 1 1-1.06-1.06l1.72-1.72H9a.75.75 0 0 1 0-1.5h10.94l-1.72-1.72a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
</svg>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="cursor-pointer hover:text-[#FF8E72]">Se deconnecter</button>
                    </form>

                </div>

            </div>





        </aside>
        <div class="pt-6 flex flex-col w-full">
            <div class="pl-7 gap-2 flex flex-col pr-7">
                <h1 class="text-[26px]">
                    Title
                </h1>
                <div class="flex justify-between items-center ">
                    <div class="h-15 flex items-center gap-2 ">
                        <a href=""
                            class="border  cursor-pointer text-[15px]  rounded-[50px] p-2 transition-all duration-200  ">Nouvelles
                            Annonces</a>
                        <a href=""
                            class="border  cursor-pointer text-[15px] hover:border-black   border-transparent rounded-[50px] p-2 transition-all duration-200 ">Prix
                            bas
                        </a>

                    </div>
                    <div>
                        <button
                            class=" mx-auto bg-white rounded-sm h-11 p-2 border cursor-pointer transition-all duration-200 hover:bg-[#FF8E72] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">
                            Ajouter une annonce
                        </button>
                    </div>
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