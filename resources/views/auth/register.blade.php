<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - L'Artisan</title>
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

<body class="bg-[#f4f4f0] min-h-screen flex items-center justify-center p-2 sm:p-4">
    <main class="w-full max-w-6xl">
        <div class="flex flex-col lg:flex-row bg-[#f4f4f0] overflow-hidden">
            <div class="flex flex-col w-full lg:w-1/2 p-4 sm:p-8 md:p-12">
                <div class="flex justify-between items-center w-full mb-6 sm:mb-10">
                    <a href="{{ url('/') }}">
                        <img class="h-12 sm:h-16" src="{{ asset('imgs/logo.svg') }}" alt="L'Artisan Logo">
                    </a>
                    <a class="font-bold border-b border-black hover:text-[#fb663f] hover:border-[#fb663f] transition-colors" href="{{ route('login') }}">Connexion</a>
                </div>

                <div class="bg-white border border-black p-6 sm:p-8 rounded-sm transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[6px_6px_0px_0px_#000000]">
                    <h1 class="text-2xl sm:text-3xl font-black mb-6 sm:mb-8 border-b border-black pb-4 uppercase tracking-tight">Inscription</h1>

                    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
                        @csrf
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div class="flex flex-col gap-1 sm:gap-2">
                                <label class="font-bold text-xs sm:text-sm uppercase tracking-wider" for="nom">Nom complet</label>
                                <input name="name" id="nom" type="text" value="{{ old('name') }}" required
                                       class="focus:shadow-[0_0_0_3px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 sm:h-14 px-4 font-bold transition-all hover:bg-gray-50">
                            </div>

                            <div class="flex flex-col gap-1 sm:gap-2">
                                <label class="font-bold text-xs sm:text-sm uppercase tracking-wider" for="email">Email</label>
                                <input name="email" id="email" type="email" value="{{ old('email') }}" required
                                       class="focus:shadow-[0_0_0_3px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 sm:h-14 px-4 font-bold transition-all hover:bg-gray-50">
                            </div>

                            <div class="flex flex-col gap-1 sm:gap-2">
                                <label class="font-bold text-xs sm:text-sm uppercase tracking-wider" for="password">Mot de passe</label>
                                <input name="password" id="password" type="password" required
                                       class="focus:shadow-[0_0_0_3px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 sm:h-14 px-4 font-bold transition-all hover:bg-gray-50">
                            </div>

                            <div class="flex flex-col gap-1 sm:gap-2">
                                <label class="font-bold text-xs sm:text-sm uppercase tracking-wider" for="password_confirmation">Confirmation</label>
                                <input name="password_confirmation" id="password_confirmation" type="password" required
                                       class="focus:shadow-[0_0_0_3px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 sm:h-14 px-4 font-bold transition-all hover:bg-gray-50">
                            </div>

                            <div class="flex flex-col gap-1 sm:gap-2">
                                <label class="font-bold text-xs sm:text-sm uppercase tracking-wider" for="tel">Téléphone</label>
                                <input name="telephone" id="tel" type="tel" value="{{ old('telephone') }}"
                                       class="focus:shadow-[0_0_0_3px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 sm:h-14 px-4 font-bold transition-all hover:bg-gray-50">
                            </div>

                            <div class="flex flex-col gap-1 sm:gap-2">
                                <label class="font-bold text-xs sm:text-sm uppercase tracking-wider" for="ville">Ville</label>
                                <select name="ville_utilisateur" id="ville" required
                                        class="focus:shadow-[0_0_0_3px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 sm:h-14 px-4 font-bold transition-all hover:bg-gray-50">
                                    <option value="">Choisir...</option>
                                    @foreach($villes as $ville)
                                        <option value="{{ $ville->nom }}" {{ old('ville_utilisateur') == $ville->nom ? 'selected' : '' }}>{{ $ville->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col items-center gap-2 sm:gap-3">
                            <label class="font-bold text-xs sm:text-sm uppercase tracking-wider">Photo de profil</label>
                            <label for="pfp" class="relative w-24 h-24 sm:w-32 sm:h-32 rounded-full border-2 border-dashed border-black bg-gray-50 hover:bg-white hover:border-[#fb663f] transition-all cursor-pointer group overflow-hidden flex items-center justify-center">
                                <img id="pfp-preview" class="absolute inset-0 w-full h-full object-cover rounded-full hidden">
                                <div id="pfp-placeholder" class="flex flex-col items-center text-gray-400 group-hover:text-[#fb663f]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 sm:size-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                    </svg>
                                </div>
                                <!-- Hover overlay when image is set -->
                                <div id="pfp-overlay" class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hidden">
                                    <span class="text-white text-[9px] sm:text-[10px] font-bold uppercase tracking-widest">Changer</span>
                                </div>
                                <input name="pfp" id="pfp" type="file" accept="image/*" class="hidden" onchange="previewPfp(this)">
                            </label>
                            <span class="text-[10px] sm:text-xs text-gray-400 font-bold">Cliquez pour ajouter</span>
                        </div>

                        <button type="submit"
                                class="w-full h-12 sm:h-14 bg-black text-white border border-black rounded-sm font-black uppercase tracking-widest transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] active:translate-x-0 active:translate-y-0 active:shadow-none cursor-pointer mt-2 sm:mt-4">
                            Créer mon compte
                        </button>
                    </form>
                </div>
            </div>

            <div class="hidden lg:block lg:w-1/2 relative">
                <div class="absolute inset-0 bg-black/5 z-10"></div>
                <img class="w-full h-full object-cover border-l border-black" src="{{ asset('imgs/register_bg.svg') }}" alt="Artisan workshop">
            </div>
        </div>
    </main>

    <script>
        function previewPfp(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('pfp-preview');
                    const placeholder = document.getElementById('pfp-placeholder');
                    const overlay = document.getElementById('pfp-overlay');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    overlay.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>