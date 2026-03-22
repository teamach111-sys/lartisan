
@props(['produit'])
@php 
    // Get first image from array
    $firstImage = (is_array($produit->images) && count($produit->images) > 0) 
                  ? $produit->images[0] 
                  : null;
@endphp
            <div class="product-card rounded-t-sm gap-2 rounded-b-sm bg-white border h-full grid grid-cols-1 w-auto overflow-hidden cursor-pointer transition-all duration-200 hover:shadow-[4px_4px_0px_0px_#000000]">
    <div class="w-full h-66 border-b">
        <img class="object-cover h-full w-full"
            src="{{ $firstImage ? asset('storage/' . $firstImage) : 'https://placehold.co/400x300?text=No+Image' }}"
            alt="">
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
            
            
           















