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
                    <a class="mt-3" href="{{ route('register') }}">Inscription</a>


                </div>
                <div>
                    <p class="pl-9 border-b pb-2 text-[26px]">Connexion</p>

                </div>

                <form action="{{ route('login') }}" method="POST" class="flex flex-col lg:p-13 xl:p-18 p-7 pt-10 xl:pt-26 gap-6">
                    @csrf
                     @if ($errors->any())
                        <div class="col-span-2 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <div class="flex flex-col">
                        <label class="text-[17px]" for="email">Email</label>
                        <input
                            class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[15px]"
                            type="email" id="email" name="email">

                    </div>
                    <div class="flex flex-col">
                        <label class="text-[17px]" for="password">Mot de passe</label>
                        <input
                            class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[15px]"
                            type="password" id="password" name="password">
                        <div class="flex gap-1 justify-between ">
                            <div>
                                <input class="" type="checkbox" id="remember" name="remember" value="1">

                                <label class="text-[17px]" for="remember">Se souvenir de moi</label>


                            </div>
                            <a href="">Mot de passe oublié?</a>

                        </div>

                    </div>


                    <BUTTON
                        class="transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer mt-3 bg-black text-white h-13 rounded-sm">Connexion</BUTTON>
                </form>



            </div>
            <div class="hidden lg:block">
                <img class="border-l object-cover min-w-192 h-screen" src="{{ asset('storage/login2.svg') }}" alt="">
            </div>

        </div>
    </main>
</body>

</html>