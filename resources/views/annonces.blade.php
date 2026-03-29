<x-layoutdash>
    <x-slot:title>
        Annonces
    </x-slot:title>
        <x-slot:h1>
            Mes Annonces
        </x-slot:h1>
        <x-slot:btnlocation>
            {{ route('produit.create') }}
        </x-slot:btnlocation>
        <x-slot:btnname>
            Ajouter une annonce

        </x-slot:btnname>

        <x-slot:customFilters>
            <a href="{{ route('annonces', ['status' => 'tous']) }}" class="flex-shrink-0 snap-center border cursor-pointer text-[15px] rounded-[50px] p-2 transition-all duration-200 {{ $filter === 'tous' ? 'border-black' : 'border-transparent hover:border-black' }}">Tous</a>
            <a href="{{ route('annonces', ['status' => 'valide']) }}" class="flex-shrink-0 snap-center border cursor-pointer text-[15px] rounded-[50px] p-2 transition-all duration-200 {{ $filter === 'valide' ? 'border-black' : 'border-transparent hover:border-black' }}">Actifs</a>
            <a href="{{ route('annonces', ['status' => 'en_attente']) }}" class="flex-shrink-0 snap-center border cursor-pointer text-[15px] rounded-[50px] p-2 transition-all duration-200 {{ $filter === 'en_attente' ? 'border-black' : 'border-transparent hover:border-black' }}">En attente</a>
            <a href="{{ route('annonces', ['status' => 'rejete']) }}" class="flex-shrink-0 snap-center border cursor-pointer text-[15px] rounded-[50px] p-2 transition-all duration-200 {{ $filter === 'rejete' ? 'border-black' : 'border-transparent hover:border-black' }}">Rejeté</a>
            <a href="{{ route('annonces', ['status' => 'sponsorise']) }}" class="flex-shrink-0 snap-center border cursor-pointer text-[15px] rounded-[50px] p-2 transition-all duration-200 {{ $filter === 'sponsorise' ? 'border-black' : 'border-transparent hover:border-black' }}">Sponsorisé</a>
        </x-slot:customFilters>
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
            <div class="col-span-full flex flex-col items-center justify-center gap-4 bg-white w-full rounded-sm border border-black p-4 md:p-8 text-center transition-all duration-300 mt-4 md:mt-0">
                
                @if ($filter === 'valide')
                    <p class="text-black font-black text-xl md:text-2xl">Aucune annonce active.</p>
                    <p class="text-gray-500 max-w-sm mx-auto font-medium text-sm md:text-base">Vous n'avez pas d'annonces en ligne pour le moment. Créez-en une maintenant.</p>
                @elseif ($filter === 'en_attente')
                    <p class="text-black font-black text-xl md:text-2xl">Aucune annonce en attente.</p>
                    <p class="text-gray-500 max-w-sm mx-auto font-medium text-sm md:text-base">Vos annonces ont toutes été traitées par notre équipe de modération.</p>
                @elseif ($filter === 'rejete')
                    <p class="text-black font-black text-xl md:text-2xl">Aucune annonce rejetée.</p>
                    <p class="text-gray-500 max-w-sm mx-auto font-medium text-sm md:text-base">Excellent travail, aucune de vos créations n'a été refusée.</p>
                @elseif ($filter === 'sponsorise')
                    <p class="text-black font-black text-xl md:text-2xl">Aucune annonce sponsorisée.</p>
                    <p class="text-gray-500 max-w-sm mx-auto font-medium text-sm md:text-base">Mettez en avant vos produits pour atteindre plus de passionnés d'artisanat.</p>
                @else
                    <p class="text-black font-black text-xl md:text-2xl">Aucune annonce trouvée.</p>
                    <p class="text-gray-500 max-w-sm mx-auto font-medium text-sm md:text-base">Vous n'avez pas encore créé d'annonces. Commencez à vendre dès aujourd'hui.</p>
                @endif

                <button onclick="window.location.href='{{ route('produit.create') }}'"
                    class="mt-4 bg-[#FF8E72] w-full md:w-auto flex items-center justify-center rounded-sm h-14 px-4 md:px-8 border border-black cursor-pointer transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] active:translate-x-0 active:translate-y-0 active:shadow-none font-black uppercase text-xs md:text-sm tracking-widest text-center whitespace-normal md:whitespace-nowrap">
                    Ajouter une annonce
                </button>
            </div>
        @endforelse
    </div>


</x-layoutdash>
