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
            src: url('./MabryPro-Regular.ttf') format('truetype');
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
        <aside class="h-screen w-55 bg-black text-white flex flex-col gap-5">
            <img class="filter invert p-7 border-black border-b mr-3"
                src="{{ asset('storage/logo.svg') }}" alt="">
            <div class="gap-5 flex flex-col mr-3">
                <div class="flex items-center gap-2 pl-4 ">
                    <img class="h-7 h-7 FILTER invert" src="dashboard/product-item-svgrepo-com.svg" alt="">
                    <a class="hover:text-[#FF8E72]" href="">Mes Annonces</a>

                </div>

                <div class="bg-white h-[0.5px]"></div>
                <div class="flex items-center gap-2 pl-4 ">
                    <img class="h-6 h-6 FILTER invert" src="dashboard/favorite-filled-svgrepo-com.svg" alt="">
                    <a class="pl-1 hover:text-[#FF8E72]" href="">Mes Favoris</a>

                </div>

                <div class="bg-white h-[0.5px]"></div>
                <div class="flex items-center gap-2 pl-4">
                    <img class="h-5 h-5 FILTER invert" src="dashboard/message-bubble-2-svgrepo-com.svg" alt="">
                    <a class="pl-2 hover:text-[#FF8E72]" href="">Mes Messages</a>

                </div>
                <div class="bg-white h-[0.5px]"></div>

                <div class="flex items-center gap-2 pl-4">
                    <img class="h-6 h-6 FILTER invert" src="dashboard/profile-svgrepo-com (1).svg" alt="">
                    <a class="pl-1 hover:text-[#FF8E72]" href="">Mon Profil</a>

                </div>
                <div class="bg-white h-[0.5px]"></div>

                <div class="flex items-center gap-2 pl-4">
                    <img class="h-7 h-7 FILTER invert" src="dashboard/home-svgrepo-com.svg" alt="">
                    <a class=" hover:text-[#FF8E72]" href="">Accueil</a>

                </div>
                <div class="bg-white h-[0.5px]"></div>

                <div class="flex items-center gap-2 pl-4">
                    <img class="h-7 h-7 FILTER invert" src="dashboard/logout-svgrepo-com (1).svg" alt="">
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