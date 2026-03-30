@props(['produit'])
@php 
    $firstImage = (is_array($produit->images) && count($produit->images) > 0) 
                  ? $produit->images[0] 
                  : null;
@endphp

<a href="{{ route('produit.show', $produit->slug) }}" class="product-card rounded-t-sm gap-2 rounded-b-sm bg-white border h-full grid grid-cols-1 w-auto overflow-hidden cursor-pointer transition-all duration-200 hover:shadow-[4px_4px_0px_0px_#000000]">
    <div class="w-full h-66 border-b">
        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out"
            src="{{ $firstImage ? \App\Helpers\ImageHelper::getUrl($firstImage) : 'https://placehold.co/400x300?text=No+Image' }}"
            alt="{{ $produit->titre }} Image" loading="lazy">
    </div>

    <div class="p-4 pb-0">
        <p class="text-[17px] leading-[26px] h-[52px] line-clamp-2 font-bold break-words">
            {{ $produit->titre }}
        </p>
    </div>
    
    

    <div class="p-4">
        <div class="flex flex-col gap-1">
            <span class="text-[10px] font-black uppercase text-black/40">{{ $produit->ville_produit }} • {{ $produit->created_at->diffForHumans() }}</span>
            <div class="flex gap-2 items-center">
                <img class="h-6 w-6 rounded-[50px] object-cover border border-[#000000]/10" 
                     src="{{ $produit->vendeur?->pfp_url ?? asset('imgs/default.svg') }}" alt="">
                <p class="line-clamp-1 underline text-[15px] opacity-80 decoration-[#FF8E72]/30">{{ $produit->vendeur->name ?? 'Artisan' }}</p>
            </div>
        </div>
    </div>

    <div class="p-4 flex   border-t">
        <div class="inline-block bg-black [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
            <div class="line-clamp-1 bg-[#FF8E72] text-black font-bold text-sm py-1 pl-4 pr-12 [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
                {{ $produit->prix }} DH
            </div>
        </div>
    </div>
</a>