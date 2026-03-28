<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe - L'Artisan</title>
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

<body class="bg-[#f4f4f0] min-h-screen flex justify-center sm:items-center p-2 sm:p-4">
    <main class="w-full max-w-6xl">
        <div class="flex flex-col lg:flex-row bg-[#f4f4f0] overflow-hidden">
            <div class="flex flex-col w-full lg:w-1/2 p-4 sm:p-8 md:p-12 pt-6">
                <div class="flex justify-between items-start w-full mb-6 sm:mb-10">
                    <div class="flex flex-col gap-1">
                        <a href="{{ url('/') }}">
                            <img class="h-10 sm:h-16" src="{{ asset('imgs/logo.svg') }}" alt="L'Artisan Logo">
                        </a>
                        <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-widest">Nouveau mot de passe</p>
                    </div>
                    <a class="font-bold border-b border-black hover:text-[#fb663f] hover:border-[#fb663f] transition-colors text-sm sm:text-base mt-2" href="{{ route('login') }}">Connexion</a>
                </div>

                <div class="bg-white border border-black p-6 sm:p-8 rounded-sm transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[6px_6px_0px_0px_#000000]">
                    <h1 class="text-2xl sm:text-3xl font-black mb-2 uppercase tracking-tight">Réinitialisation</h1>
                    <p class="text-gray-500 text-xs sm:text-sm mb-6 border-b border-black pb-4">Choisissez un nouveau mot de passe sécurisé pour votre compte.</p>

                    <form action="{{ route('password.update') }}" method="POST" class="space-y-4 sm:space-y-6">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-500 text-red-700 px-4 py-3 rounded-sm mb-6 font-bold text-sm">
                                @foreach ($errors->all() as $error)
                                    <p class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                        </svg>
                                        {{ $error }}
                                    </p>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex flex-col gap-1 sm:gap-2">
                            <label class="font-bold text-xs sm:text-sm uppercase tracking-wider" for="email">Email</label>
                            <input name="email" id="email" type="email" value="{{ $email ?? old('email') }}" required readonly
                                   class="focus:shadow-[0_0_0_3px_#fb663f] outline-none bg-gray-50 border border-black rounded-sm h-12 sm:h-14 px-4 font-bold transition-all">
                        </div>

                        <div class="flex flex-col gap-1 sm:gap-2">
                            <label class="font-bold text-xs sm:text-sm uppercase tracking-wider" for="password">Nouveau mot de passe</label>
                            <input name="password" id="password" type="password" required
                                   class="focus:shadow-[0_0_0_3px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 sm:h-14 px-4 font-bold transition-all hover:bg-gray-50">
                        </div>

                        <div class="flex flex-col gap-1 sm:gap-2">
                            <label class="font-bold text-xs sm:text-sm uppercase tracking-wider" for="password_confirmation">Confirmer</label>
                            <input name="password_confirmation" id="password_confirmation" type="password" required
                                   class="focus:shadow-[0_0_0_3px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 sm:h-14 px-4 font-bold transition-all hover:bg-gray-50">
                        </div>

                        <button type="submit"
                                class="w-full h-12 sm:h-14 bg-black text-white border border-black rounded-sm font-black uppercase tracking-widest transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] active:translate-x-0 active:translate-y-0 active:shadow-none cursor-pointer mt-2 sm:mt-4">
                            Réinitialiser
                        </button>
                    </form>
                </div>
            </div>

            <div class="hidden lg:block lg:w-1/2 relative">
                <div class="absolute inset-0 bg-black/5 z-10"></div>
                <img class="w-full h-full object-cover border-l border-black" src="{{ asset('storage/login2.svg') }}" alt="Craftsman working">
            </div>
        </div>
    </main>
</body>

</html>
