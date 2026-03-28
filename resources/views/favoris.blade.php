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
            <div class="col-span-full flex flex-col items-center justify-center gap-4 bg-white w-full h-84 rounded-md border-2 border-dashed border-gray-300 p-8 text-center transition-all duration-300 hover:border-[#FF8E72]/50">
                <div class="bg-gray-50 p-6 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 text-gray-300">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                </div>
                <p class="text-gray-600 font-bold text-lg">Vous n'avez pas encore de favoris.</p>
                <p class="text-gray-400 max-w-sm mx-auto">Parcourez le marché et cliquez sur le cœur pour enregistrer vos créations artisanales préférées.</p>
                <a href="{{ route('home') }}" class="mt-2 bg-[#FF8E72] flex items-center justify-center rounded-sm h-12 px-8 border border-black cursor-pointer transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] active:translate-x-0 active:translate-y-0 active:shadow-none font-black uppercase text-sm tracking-widest">
                    Explorer le marché
                </a>
            </div>
        @endif
    </div>
</x-layoutdash>
