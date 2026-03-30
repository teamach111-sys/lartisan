
@props(['produit'])
@php 
    // Get first image from array
    $firstImage = (is_array($produit->images) && count($produit->images) > 0) 
                  ? $produit->images[0] 
                  : null;
@endphp
            <div class="product-card rounded-t-sm gap-2 rounded-b-sm bg-white border h-auto grid grid-cols-1 w-auto overflow-hidden cursor-pointer transition-all duration-200 hover:shadow-[4px_4px_0px_0px_#000000]">
    <div class="w-full h-66 border-b relative">
        {{-- Boutons d'action --}}
        <div class="absolute top-2 left-2 z-10 flex gap-1">
            <form action="{{ route('produit.destroy', $produit) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                @csrf
                @method('DELETE')
                <button type="submit" title="Supprimer" class="p-2 bg-white/90 backdrop-blur-sm border-2 border-black rounded-sm hover:bg-red-500 hover:text-white transition-colors duration-200 cursor-pointer shadow-[2px_2px_0px_0px_#000000] active:translate-x-[1px] active:translate-y-[1px] active:shadow-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                </button>
            </form>

            <a href="{{ route('produit.edit', $produit) }}" title="Modifier" class="p-2 bg-white/90 backdrop-blur-sm border-2 border-black rounded-sm hover:bg-[#FF8E72] hover:text-black transition-colors duration-200 cursor-pointer shadow-[2px_2px_0px_0px_#000000] active:translate-x-[1px] active:translate-y-[1px] active:shadow-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                </svg>
            </a>

            @if($produit->etat_moderation === 'valide' && ($produit->sponsor_status === 'none' || ($produit->sponsor_status === 'approuve' && $produit->sponsored_until && $produit->sponsored_until < now())))
            <form action="{{ route('produit.sponsoriser', $produit) }}" method="POST" onsubmit="return confirm('Voulez-vous demander la mise en avant de cette annonce ?');">
                @csrf
                <button type="submit" title="Sponsoriser" class="p-2 bg-white/90 backdrop-blur-sm border-2 border-black rounded-sm hover:bg-[#FF8E72] hover:text-black transition-colors duration-200 cursor-pointer shadow-[2px_2px_0px_0px_#000000] active:translate-x-[1px] active:translate-y-[1px] active:shadow-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                    </svg>
                </button>
            </form>
            @endif
        </div>

        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out"
            src="{{ $firstImage ? Storage::url($firstImage) : 'https://placehold.co/400x300?text=No+Image' }}"
            alt="">
            
        <div class="absolute top-2 right-2 flex flex-col gap-1 items-end">
            @if ($produit->etat_moderation === 'valide')
                <div class="bg-[#22c55e] border border-black shadow-[2px_2px_0px_0px_#000000] text-white text-xs font-black px-2.5 py-1 uppercase rounded-sm">
                    Dans le marché
                </div>
            @elseif ($produit->etat_moderation === 'rejete')
                <div class="bg-[#ef4444] border border-black shadow-[2px_2px_0px_0px_#000000] text-white text-xs font-black px-2.5 py-1 uppercase rounded-sm">
                    Rejeté
                </div>
            @else
                <div class="bg-[#facc15] border border-black shadow-[2px_2px_0px_0px_#000000] text-black text-xs font-black px-2.5 py-1 uppercase rounded-sm">
                    En attente
                </div>
            @endif

            @if ($produit->sponsor_status === 'en_attente')
                <div class="bg-blue-500 border border-black shadow-[2px_2px_0px_0px_#000000] text-white text-xs font-black px-2.5 py-1 uppercase rounded-sm">
                    Mise en avant (En attente)
                </div>
            @elseif ($produit->sponsor_status === 'approuve' && $produit->sponsored_until && \Carbon\Carbon::parse($produit->sponsored_until)->isFuture())
                @php
                    $diff = \Carbon\Carbon::parse($produit->sponsored_until)->diff(now());
                    $hours = ($diff->days * 24) + $diff->h;
                    $minutes = $diff->i;
                @endphp
                <div class="flex flex-col gap-1 items-end">
                    <div class="bg-purple-600 border border-black shadow-[2px_2px_0px_0px_#000000] text-white text-xs font-black px-2.5 py-1 uppercase rounded-sm">
                        Sponsorisé
                    </div>
                    <div class="bg-white border border-black shadow-[2px_2px_0px_0px_#000000] text-purple-600 text-[10px] font-black px-2 py-0.5 uppercase rounded-sm">
                        {{ $hours }}h {{ $minutes }}m restants
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="p-4 pb-0">
        <p class="text-[17px] leading-[26px] h-[52px] line-clamp-2 font-bold break-words">
            {{ $produit->titre }}
        </p>
    </div>
    <div class="p-4 pb-0">
        <p class="text-[17px] leading-[26px] h-[52px] line-clamp-2 text-gray-700 break-words">
            {{ $produit->description }}
        </p>
    </div>
    
    

   

    <div class="p-4 flex   border-t">
        <div class="inline-block bg-black [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
            <div class="line-clamp-1 bg-[#FF8E72] text-black font-bold text-sm py-1 pl-4 pr-12 [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
                {{ $produit->prix }} DH
            </div>
        </div>
    </div>
</div>
            
            
           















