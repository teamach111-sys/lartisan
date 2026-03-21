
@props(['produit'])
@php 
    // Get first image from array
    $firstImage = (is_array($produit->images) && count($produit->images) > 0) 
                  ? $produit->images[0] 
                  : null;
@endphp
            <div
              class="product-card bg-white border rounded-sm  h-120  overflow-hidden cursor-pointer transition-all duration-200 hover:shadow-[4px_4px_0px_0px_#000000]">
              <img class="object-cover h-full w-full max-h-66 border-b"
                src="{{ $firstImage ? asset('storage/' . $firstImage) : 'https://placehold.co/400x300?text=No+Image' }}"
                alt="">

              <div class="h-full max-h-36 border-b">
                <div class="px-5 pt-4 ">
                  <p class="text-[17px] line-clamp-2 font-bold break-words">{{ $produit->titre }}</p>
                </div>
                <div class="px-5 pt-4">
                   <div class="mt-3 flex gap-2 items-center">
              <img class="h-10 w-10 object-cover rounded-[50px] border" src="{{ $produit->vendeur?->pfp ? asset('storage/' . $produit->vendeur->pfp) : asset('imgs/default.svg') }}" alt="">
              <p class="line-clamp-1 underline">{{ $produit->vendeur->name ?? 'Artisan Anonyme' }}</p>


            </div>

                </div>

              </div>


              <div class="p-5 h-14">

                <div class="inline-block bg-black p-[1px] 
               [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
                  <div class="line-clamp-1 bg-[#FF8E72] text-black font-bold text-sm py-1 pl-4 pr-12 
                 [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
                    {{ $produit->prix }} DH
                  </div>
                </div>
              </div>

            </div>
            
            
           















