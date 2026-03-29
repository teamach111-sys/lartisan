<x-layoutdash>
    <x-slot:title>Mon Profil</x-slot:title>
    <x-slot:h1>Mon Profil</x-slot:h1>
    
    <x-slot:btnlocation>{{ route('home') }}</x-slot:btnlocation>
    <x-slot:btnname>Retour au marché</x-slot:btnname>

    <x-slot:mobbtnlocation>{{ route('home') }}</x-slot:mobbtnlocation>
    <x-slot:mobbtnname>Marché</x-slot:mobbtnname>
    <x-slot:topbar></x-slot:topbar>

    <div class="max-w-4xl">
        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-sm mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-sm mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Section Photo de Profil -->
            <div class="bg-white border border-black p-6 rounded-sm transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                <h3 class="text-xl font-bold mb-6 border-b border-black pb-2">Photo de profil</h3>
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="relative group">
                        <img id="pfp-preview" 
                             class="h-32 w-32 rounded-full object-cover border-2 border-black transition-all duration-200 group-hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]" 
                             src="{{ asset('storage/' . (auth()->user()->pfp ?? 'default.svg')) }}" 
                             alt="Photo de profil">
                        <label for="pfp-input" class="absolute bottom-0 right-0 bg-black text-white p-2 rounded-full cursor-pointer hover:bg-[#fb663f] transition-colors shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15a2.25 2.25 0 0 0 2.25-2.25V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                            </svg>
                        </label>
                        <input type="file" name="pfp" id="pfp-input" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-600 text-sm mb-2">Améliorez votre visibilité avec une photo professionnelle. Formats acceptés : JPG, PNG. Max 2MB.</p>
                        <button type="button" onclick="document.getElementById('pfp-input').click()" class="text-sm font-bold underline hover:text-[#fb663f] cursor-pointer">Changer la photo</button>
                    </div>
                </div>
            </div>

            <!-- Informations Générales -->
            <div class="bg-white border border-black p-6 rounded-sm transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                <h3 class="text-xl font-bold mb-6 border-b border-black pb-2">Informations personnelles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm" for="name">Nom complet</label>
                        <input name="name" id="name" value="{{ old('name', auth()->user()->name) }}" 
                               class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 px-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]" 
                               type="text">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm" for="email">E-mail</label>
                        <input name="email" id="email" value="{{ old('email', auth()->user()->email) }}" 
                               class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 px-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]" 
                               type="email">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm" for="telephone">Téléphone</label>
                        <input name="telephone" id="telephone" value="{{ old('telephone', auth()->user()->telephone) }}" 
                               class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 px-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]" 
                               type="text" placeholder="+212 6XX XXX XXX">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm" for="ville_utilisateur">Votre ville</label>
                        <select name="ville_utilisateur" id="ville_utilisateur" 
                                class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 px-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] cursor-pointer">
                            @foreach($villes as $ville)
                                <option value="{{ $ville->nom }}" {{ old('ville_utilisateur', auth()->user()->ville_utilisateur) == $ville->nom ? 'selected' : '' }}>
                                    {{ $ville->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" name="display_phone" id="display_phone" value="1" 
                                   {{ old('display_phone', auth()->user()->display_phone) ? 'checked' : '' }}
                                   class="peer hidden">
                            <div class="w-12 h-6 bg-gray-200 border border-black rounded-full peer-checked:bg-[#fb663f] transition-all duration-200 hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)]"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white border border-black rounded-full transition-transform peer-checked:translate-x-6"></div>
                        </div>
                        <span class="font-bold text-sm">Afficher mon numéro de téléphone sur mes annonces par défaut</span>
                    </label>
                </div>
            </div>

            <!-- Sécurité / Mot de passe -->
            <div class="bg-white border border-black p-6 rounded-sm transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                <h3 class="text-xl font-bold mb-6 border-b border-black pb-2">Sécurité</h3>
                <p class="text-gray-600 text-sm mb-6 italic">Laissez vide si vous ne souhaitez pas modifier votre mot de passe.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm" for="password">Nouveau mot de passe</label>
                        <input name="password" id="password" 
                               class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 px-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]" 
                               type="password">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm" for="password_confirmation">Confirmer le mot de passe</label>
                        <input name="password_confirmation" id="password_confirmation" 
                               class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 px-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]" 
                               type="password">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="window.history.back()" 
                        class="px-8 h-13 bg-white border border-black rounded-sm font-bold transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-gray-50 hover:shadow-[4px_4px_0px_0px_#000000] active:translate-x-0 active:translate-y-0 active:shadow-none cursor-pointer">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-8 h-13 bg-black text-white border border-black rounded-sm font-bold transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] active:translate-x-0 active:translate-y-0 active:shadow-none cursor-pointer">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    <script>
        async function previewImage(input) {
            let file = input.files && input.files[0];
            if (file) {
                try {
                    if (window.ImageCompressor) {
                        file = await window.ImageCompressor.compress(file);
                        window.ImageCompressor.replaceFileInput(input, file);
                    }
                } catch (e) {
                    console.error("Compression failed", e);
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('pfp-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        // Form validation for file size
        document.querySelector('form').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('pfp-input');
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (fileInput.files && fileInput.files[0]) {
                if (fileInput.files[0].size > maxSize) {
                    alert('La photo de profil dépasse la limite de 2 Mo. Veuillez choisir une image plus légère.');
                    e.preventDefault();
                    return;
                }
            }

            // Afficher l'état de chargement
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="flex items-center gap-2"><svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Enregistrement...</span>';
            btn.classList.add('opacity-70', 'cursor-not-allowed');
        });
    </script>
</x-layoutdash>
