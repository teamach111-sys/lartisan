<x-layoutdash>
    <x-slot:title>
        Ajouter une annonce
    </x-slot:title>
    <x-slot:h1>
        Ajouter une annonce
    </x-slot:h1>
    <x-slot:btnlocation>
        {{ route('annonces') }}
    </x-slot:btnlocation>
    <x-slot:btnname>
        Retour
    </x-slot:btnname>

    <x-slot:mobbtnlocation>
        {{ route('annonces') }}
    </x-slot:mobbtnlocation>
    <x-slot:mobbtnname>
        Retour
    </x-slot:mobbtnname>
    <x-slot:topbar>
    </x-slot:topbar>

    <div class="max-w-4xl">
        <form action="{{ route('produit.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-sm mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Informations Générales -->
            <div class="bg-white border border-black p-6 rounded-sm transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                <h3 class="text-xl font-bold mb-6 border-b border-black pb-2">Informations générales</h3>
                <div class="space-y-6">
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm" for="titre">Titre de l'annonce</label>
                        <input name="titre" id="titre" value="{{ old('titre') }}" 
                               class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 px-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]" 
                               type="text" placeholder="Ex: Plat en céramique peint à la main">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm" for="description">Description détaillée</label>
                        <textarea name="description" id="description" rows="4"
                                  class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm p-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]"
                                  placeholder="Décrivez votre produit, son histoire, ses matériaux...">{{ old('description') }}</textarea>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm" for="prix">Prix (DH)</label>
                        <input name="prix" id="prix" type="number" value="{{ old('prix') }}" 
                               class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 px-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]" 
                               placeholder="0.00">
                    </div>
                </div>
            </div>

            <!-- Détails de l'Annonce -->
            <div class="bg-white border border-black p-6 rounded-sm transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                <h3 class="text-xl font-bold mb-6 border-b border-black pb-2">Détails techniques</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm" for="categorie">Catégorie</label>
                        <select name="categorie" id="categorie" 
                                class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 px-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] cursor-pointer">
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('categorie') == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm" for="ville_produit">Ville de vente</label>
                        <select name="ville_produit" id="ville_produit" 
                                class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 px-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] cursor-pointer">
                            <option value="">Sélectionnez une ville</option>
                            @foreach($villes as $ville)
                                <option value="{{ $ville->nom }}" {{ old('ville_produit') == $ville->nom ? 'selected' : '' }}>{{ $ville->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm" for="etat_produit">État du produit</label>
                        <select name="etat_produit" id="etat_produit" 
                                class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 px-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] cursor-pointer">
                            <option value="premiere_main" {{ old('etat_produit') == 'premiere_main' ? 'selected' : '' }}>Première main / Neuf</option>
                            <option value="occasion" {{ old('etat_produit') == 'occasion' ? 'selected' : '' }}>Occasion</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Médias -->
            <div class="bg-white border border-black p-6 rounded-sm transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                <h3 class="text-xl font-bold mb-6 border-b border-black pb-2">Photos du produit (5 requises)</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                    @for ($i = 0; $i < 5; $i++)
                        <div class="aspect-square border-2 border-dashed border-black/20 rounded-sm bg-gray-50 flex items-center justify-center relative overflow-hidden group cursor-pointer hover:border-[#fb663f] transition-all" 
                             onclick="document.getElementById('photo-{{ $i }}').click()">
                            <img id="preview-{{ $i }}" class="absolute inset-0 w-full h-full object-cover hidden">
                            <div id="overlay-{{ $i }}" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hidden">
                                <span class="text-white text-[10px] font-bold uppercase tracking-widest">Remplacer</span>
                            </div>
                            <div id="placeholder-{{ $i }}" class="flex flex-col items-center text-gray-400 group-hover:text-[#fb663f]">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <span class="text-[10px] font-bold mt-1 uppercase">Photo {{ $i + 1 }}</span>
                            </div>
                            <input name="images[{{ $i }}]" id="photo-{{ $i }}" class="hidden" type="file" accept="image/*" required onchange="previewIndividual(this, {{ $i }})">
                        </div>
                    @endfor
                </div>

                <p class="text-xs text-gray-400 mt-2 italic text-center">Cliquez sur chaque case pour ajouter une photo. Format acceptés : JPG, PNG.</p>
            </div>

            <div class="flex justify-end gap-3 pt-4 pb-12">
                <button onclick="window.location.href='{{ route('annonces') }}'" type="button" 
                        class="px-12 h-13 bg-white border border-black rounded-sm font-bold transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-gray-50 hover:shadow-[4px_4px_0px_0px_#000000] active:translate-x-0 active:translate-y-0 active:shadow-none cursor-pointer">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-12 h-13 bg-black text-white border border-black rounded-sm font-bold transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] active:translate-x-0 active:translate-y-0 active:shadow-none cursor-pointer">
                    Publier l'annonce
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewIndividual(input, index) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById(`preview-${index}`);
                    const placeholder = document.getElementById(`placeholder-${index}`);
                    const overlay = document.getElementById(`overlay-${index}`);
                    
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    if (overlay) {
                        overlay.classList.remove('hidden');
                    }
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-layoutdash>