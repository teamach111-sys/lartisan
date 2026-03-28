@forelse ($produits as $produit) 
    <x-produit :produit="$produit" />
@empty
    <div class="col-span-full py-12 text-center border-2 border-dashed border-gray-300 rounded-sm">
        <p class="text-xl text-gray-500">Aucun produit ne correspond à votre recherche actuelle.</p>
        <a href="{{ route('home') }}" class="mt-4 inline-block underline hover:text-[#fb663f]">Afficher toutes les annonces</a>
    </div>
@endforelse
