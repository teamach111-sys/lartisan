<x-layoutdash>
    <x-slot:title>
        Favoris
    </x-slot:title>
    <x-slot:h1>
        Mes Favoris
    </x-slot:h1>
    <x-slot:btnlocation>
        {{ route('home') }}
    </x-slot:btnlocation>
    <x-slot:btnname>
        Retour au marché
    </x-slot:btnname>
    <x-slot:mobbtnlocation>
        {{ route('home') }}
    </x-slot:mobbtnlocation>
    <x-slot:mobbtnname>
        Retour au marché
    </x-slot:mobbtnname>
    <x-slot:topbar>
        {{-- Empty or additional top content --}}
    </x-slot:topbar>
    
    <x-slot:customFilters>
        <a href="{{ route('favoris', ['sort' => 'latest']) }}" 
           class="flex-shrink-0 snap-center border cursor-pointer text-[15px] rounded-[50px] p-2 transition-all duration-200 {{ $sort === 'latest' ? 'border-black' : 'border-transparent hover:border-black' }}">
           Plus récents
        </a>
        <a href="{{ route('favoris', ['sort' => 'oldest']) }}" 
           class="flex-shrink-0 snap-center border cursor-pointer text-[15px] rounded-[50px] p-2 transition-all duration-200 {{ $sort === 'oldest' ? 'border-black' : 'border-transparent hover:border-black' }}">
           Plus anciens
        </a>
    </x-slot:customFilters>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 w-full">
        @if(isset($items) && $items->count() > 0)
            @foreach ($items as $produit)
                <x-produit :produit="$produit" />
            @endforeach
        @else
            <div class="col-span-full flex flex-col items-center justify-center gap-4 bg-white w-full rounded-sm border border-black p-4 md:p-8 text-center transition-all duration-300 mt-4 md:mt-0">
                <p class="text-black font-black text-xl md:text-2xl">Vous n'avez pas encore de favoris.</p>
                <p class="text-gray-500 max-w-sm mx-auto font-medium text-sm md:text-base">Parcourez le marché et enregistrez vos créations artisanales préférées.</p>
                <a href="{{ route('home') }}" class="mt-4 bg-[#FF8E72] w-full md:w-auto flex items-center justify-center rounded-sm h-14 px-4 md:px-8 border border-black cursor-pointer transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] active:translate-x-0 active:translate-y-0 active:shadow-none font-black uppercase text-xs md:text-sm tracking-widest text-center whitespace-normal md:whitespace-nowrap">
                    Explorer le marché
                </a>
            </div>
        @endif
    </div>
</x-layoutdash>
