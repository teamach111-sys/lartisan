<x-layoutdash>
    <x-slot:title>
        Annonces
    </x-slot:title>
        <x-slot:h1>
            Mes Annonces
        </x-slot:h1>
        <x-slot:btnlocation>
            {{ route('annonces') }}
        </x-slot:btnlocation>
        <x-slot:btnname>
            Ajouter une annonce

        </x-slot:btnname>

        <x-slot:firstc>
            Tous
        </x-slot:firstc>
        <x-slot:secondc>
            Actifs
        </x-slot:secondc>
        <x-slot:mobbtnlocation>
            {{ route('produit.create') }}
        </x-slot:mobbtnlocation>
        <x-slot:mobbtnname>
            Ajouter une annonce
        </x-slot:mobbtnname>
    <x-slot:topbar>
 











    </x-slot:topbar>












    <div class="grid grid-cols-1 gap-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-full">
    
        @forelse ($userProduits as $produit)
            {{-- Container for active listings --}}
            <div class="mb-4 last:mb-0 w-full h-auto">
                <x-mylistings :produit="$produit" />
            </div>
        @empty
            {{-- Dedicated div for the empty state --}}
            <div
                class="flex flex-col items-center justify-center gap-4 bg-white w-full h-64 rounded-md border-2 border-dashed border-gray-300 p-8 text-center">
                <p class="text-gray-600 font-medium">Il n'y a pas d'annonces actuellement.</p>

                <button onclick="window.location.href='{{ route('produit.create') }}'"
                    class="bg-[#FF8E72] rounded-sm h-11 px-6 border border-black cursor-pointer transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] active:translate-x-0 active:translate-y-0 active:shadow-none">
                    Ajouter une annonce
                </button>
            </div>
        @endforelse
    </div>


</x-layoutdash>
