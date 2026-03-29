<x-layoutdash>
    <x-slot:title>
        Modifier l'annonce
    </x-slot:title>
    <x-slot:h1>
        Modifier l'annonce
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
        <form action="{{ route('produit.update', $produit) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

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
                        <input name="titre" id="titre" value="{{ old('titre', $produit->titre) }}" maxlength="60" 
                               class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 px-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]" 
                               type="text">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm" for="description">Description détaillée</label>
                        <textarea name="description" id="description" rows="4"
                                  class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm p-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">{{ old('description', $produit->description) }}</textarea>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm" for="prix">Prix (DH)</label>
                        <input name="prix" id="prix" type="number" value="{{ old('prix', $produit->prix) }}" 
                               class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 px-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
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
                                <option value="{{ $cat->id }}" {{ old('categorie', $produit->categorie_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm" for="ville_produit">Ville de vente</label>
                        <select name="ville_produit" id="ville_produit" 
                                class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 px-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] cursor-pointer">
                            <option value="">Sélectionnez une ville</option>
                            @foreach($villes as $ville)
                                <option value="{{ $ville->nom }}" {{ old('ville_produit', $produit->ville_produit) == $ville->nom ? 'selected' : '' }}>{{ $ville->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-bold text-sm" for="etat_produit">État du produit</label>
                        <select name="etat_produit" id="etat_produit" 
                                class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 px-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] cursor-pointer">
                            <option value="premiere_main" {{ old('etat_produit', $produit->etat_produit) == 'premiere_main' ? 'selected' : '' }}>Première main / Neuf</option>
                            <option value="occasion" {{ old('etat_produit', $produit->etat_produit) == 'occasion' ? 'selected' : '' }}>Occasion</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Médias -->
            <div class="bg-white border border-black p-6 rounded-sm transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                <h3 class="text-xl font-bold mb-6 border-b border-black pb-2">Photos du produit (5 actuelles)</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6" id="image-grid">
                    @foreach ($produit->images as $index => $image)
                        <div class="aspect-square border-2 border-black rounded-sm relative overflow-hidden group cursor-pointer hover:border-[#fb663f] transition-all" 
                             onclick="document.getElementById('photo-{{ $index }}').click()">
                            <img id="preview-{{ $index }}" src="{{ asset('storage/' . $image) }}" class="absolute inset-0 w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-[10px] font-bold uppercase tracking-widest">Remplacer</span>
                            </div>
                            <input name="images[{{ $index }}]" id="photo-{{ $index }}" class="hidden" type="file" accept="image/*" onchange="previewIndividual(this, {{ $index }})">
                        </div>
                    @endforeach
                    @for ($i = count($produit->images); $i < 5; $i++)
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
                                <span class="text-[10px] font-bold mt-1 uppercase">Ajouter</span>
                            </div>
                            <input name="images[{{ $i }}]" id="photo-{{ $i }}" class="hidden" type="file" accept="image/*" onchange="previewIndividual(this, {{ $i }})">
                        </div>
                    @endfor
                </div>

                <p class="text-xs text-gray-400 mt-2 italic text-center">Cliquez sur une photo pour la remplacer individuellement. Format acceptés : JPG, PNG.</p>
            </div>

            <div class="flex justify-end gap-3 pt-4 pb-12">
                <button onclick="window.location.href='{{ route('annonces') }}'" type="button" 
                        class="px-12 h-13 bg-white border border-black rounded-sm font-bold transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-gray-50 hover:shadow-[4px_4px_0px_0px_#000000] active:translate-x-0 active:translate-y-0 active:shadow-none cursor-pointer">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-12 h-13 bg-black text-white border border-black rounded-sm font-bold transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] active:translate-x-0 active:translate-y-0 active:shadow-none cursor-pointer">
                    Mettre à jour l'annonce
                </button>
            </div>
        </form>
    </div>

    <script>
        async function previewIndividual(input, index) {
            let file = input.files[0];
            if (file) {
                // Show a small loading state on the placeholder if it exists
                const placeholder = document.getElementById(`placeholder-${index}`);
                if (placeholder) {
                    const originalHTML = placeholder.innerHTML;
                    placeholder.innerHTML = '<svg class="animate-spin h-8 w-8 text-[#fb663f]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="text-[10px] font-bold mt-1 uppercase text-[#fb663f]">Compression...</span>';
                    
                    try {
                        if (window.ImageCompressor) {
                            file = await window.ImageCompressor.compress(file);
                            window.ImageCompressor.replaceFileInput(input, file);
                        }
                    } catch (e) {
                        console.error("Compression failed", e);
                    }
                    
                    placeholder.innerHTML = originalHTML; // restore if needed
                } else {
                     // Still process the file even if no placeholder
                     try {
                        if (window.ImageCompressor) {
                            file = await window.ImageCompressor.compress(file);
                            window.ImageCompressor.replaceFileInput(input, file);
                        }
                    } catch (e) {
                        console.error("Compression failed", e);
                    }
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById(`preview-${index}`);
                    if (img) {
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                    }
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(file);
            }
        }

        // Form validation for file size
        document.querySelector('form').addEventListener('submit', function(e) {
            const files = document.querySelectorAll('input[type="file"]');
            let totalSize = 0;
            const maxSizePerFile = 2 * 1024 * 1024; // 2MB
            const maxTotalSize = 10 * 1024 * 1024; // 10MB
            let isValid = true;

            files.forEach((input, index) => {
                if (input.files && input.files[0]) {
                    const fileSize = input.files[0].size;
                    totalSize += fileSize;
                    
                    if (fileSize > maxSizePerFile) {
                        alert(`La photo ${index + 1} dépasse la limite de 2 Mo.`);
                        isValid = false;
                    }
                }
            });

            if (totalSize > maxTotalSize) {
                alert(`Le poids total des images (${(totalSize / (1024 * 1024)).toFixed(2)} Mo) dépasse la limite autorisée de 10 Mo.`);
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                return;
            }

            // Afficher l'état de chargement
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<span class="flex items-center gap-2"><svg class="animate-spin h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mise à jour en cours...</span>';
            btn.classList.add('opacity-70', 'cursor-not-allowed');
        });
    </script>
</x-layoutdash>
