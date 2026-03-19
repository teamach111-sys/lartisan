<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
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
    <main>
        <div class="flex justify-between">
            <div class="flex flex-col w-full">
                <div class="flex  justify-between w-full px-3">
                    <div class="">
                        <img class="h-25 mt-2" src="{{ asset('storage/logo.svg') }}" alt="">

                    </div>
                    <a class="mt-3" href="{{ route('login') }}">Connexion</a>


                </div>
                <div>
                    <p class="pl-9 border-b pb-2 text-[26px]">Join over 1000 utilisateurs</p>

                </div>

                <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="flex md:grid md:grid-cols-2 flex-col xl:grid  xl:grid-cols-2 lg:p-5 xl:p-18 p-6 gap-2">
                    @csrf
                    @if ($errors->any())
                        <div class="col-span-2 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                     <div  class="flex flex-col">
                        <label class="text-[17px]" for="nom">Nom</label>
                        <input name="name" class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[15px]" type="text" id="nom"
                            >

                    </div>
                    <div class="flex flex-col">
                        <label class="text-[17px]" for="email">Email</label>
                        <input name="email" class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[15px]" type="email" id="email"
                            >

                    </div>
                    <div  class="flex flex-col">
                        <label class="text-[17px]" for="password">Mot de passe</label>
                        <input name="password" class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[15px]" type="password" id="password"
                            >

                    </div>
                    <div  class="flex flex-col">
                        <label class="text-[17px]" for="password_confirmation">Confirmer le mot de passe</label>
                        <input name="password_confirmation" class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[15px]" type="password" id="password_confirmation"
                            >

                    </div>
                    <div  class="flex flex-col">
                        <label class="text-[17px]" for="tel">Numéro de téléphone</label>
                        <input name="telephone" class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[15px]" type="tel" id="tel"
                            >

                    </div>
                     <div  class="flex flex-col">
                        <label class="text-[17px]" for="ville">Ville</label>
                        <select class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[15px]" name="ville_utilisateur" id="ville">
                            <option class="" value="">Sélectionnez une ville</option>
                            <option value="Marrakech">Marrakech</option>
                        </select>

                    </div>
                     <div  class="flex flex-col">
                        <label class="text-[15px] max-w-42 h-13 flex flex-col items-center justify-center hover:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-7" for="profil">Photo de profil</label>
                        <input name="pfp" id="profil" class="" type="file" 
                            >

                    </div>
                   
                    <BUTTON type="submit" class="transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer mt-3 bg-black text-white h-13 rounded-sm">Inscription</BUTTON>
                </form>



            </div>
            <div class="hidden  xl:block">
                <img class="border-l object-cover  min-w-192 h-screen" src="{{ asset('storage/822e112a3b444c69f7ef.svg') }}" alt="">
            </div>

        </div>
    </main>
</body>

</html>