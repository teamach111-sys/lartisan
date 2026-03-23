<x-layout>

    <div class="flex flex-col lg:w-2/3 w-full  mx-auto h-full overflow-y-auto py-9">
        {{-- 1. Only render the gallery if images exist and the count is > 0 --}}

        <div x-data="{
            index: 0,
            {{-- In your x-data --}}
            images: {{ json_encode(collect($produit->images)->map(fn($img) => asset('storage/' . $img))->toArray()) }},
            {{-- Helper functions for the arrows --}}
            next() { this.index = (this.index + 1) % this.images.length },
            prev() { this.index = (this.index - 1 + this.images.length) % this.images.length }
        }" class="relative bg-white shadow-md overflow-hidden border h-[600px]">

            <button @click="next()"
                class="absolute z-10 right-3 top-1/2 -translate-y-1/2 bg-white border rounded-full hover:bg-gray-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            <button @click="prev()"
                class="absolute z-10 left-3 top-1/2 -translate-y-1/2 bg-white border rounded-full hover:bg-gray-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>

            <div class="absolute z-10 bottom-4 left-1/2 -translate-x-1/2 bg-transparent">
                <div class="flex gap-2">
                    <template x-for="(img, i) in images" :key="i">
                        <button type="button" class="dot-button transition-transform hover:scale-110"
                            :data-image="i + 1" @click="index = i">
                            <svg class="h-5 w-5" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="50" cy="50" r="45" fill="black" />
                                <circle cx="50" cy="50" r="42" :fill="index === i ? 'black' : 'white'"
                                    class="transition-colors duration-200" />
                            </svg>
                        </button>
                    </template>
                </div>
            </div>

            <template x-for="(img, i) in images" :key="i">
                <img x-show="index === i" :src="img" alt="Product View" class="object-cover h-full w-full"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100">
            </template>
        </div>


        <div class="flex border-l  lg:border-r sm:border-r-transparent flex-col md:flex-row">
            <div class="flex flex-col lg:w-3/4 w-full border-r border-b">
                <div class="bg-white bg-white p-4 border-b">
                    <p>{{ $produit->titre }}</p>
                </div>
                <div class="bg-white h-auto flex border-b ">
                    <div class="p-4 flex border-r">
                        <div
                            class="inline-block bg-black [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
                            <div
                                class="line-clamp-1 bg-[#FF8E72] text-black font-bold text-sm py-1 pl-4 pr-12 [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
                                {{ $produit->prix }} DH
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex gap-2 items-center">
                            <img class="h-7 w-7 object-cover rounded-[50px] border"
                                src="{{ $produit->vendeur?->pfp ? asset('storage/' . $produit->vendeur->pfp) : asset('imgs/default.svg') }}"
                                alt="">
                            <p class="line-clamp-1 underline">{{ $produit->vendeur->name ?? 'Artisan Anonyme' }}</p>
                        </div>
                    </div>


                </div>

                <div class="bg-white bg-white p-4">
                    <p>{{ $produit->description }}</p>
                </div>






            </div>

            <div class="bg-white lg:w-2/4 w-full  h-auto p-4 flex flex-col border-r border-b ">
                <div class=" flex flex-col">
                    <BUTTON type="button"
                        class="flex justify-center items-center transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer mt-3 bg-[#ff8e72] text-black border h-13 rounded-sm w-full flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" class="size-6">
                            <path
                                d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                            <path
                                d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                        </svg>
                        Contacter le vendeur pour ce produit
                    </BUTTON>
                    <div class="flex gap-2 items-center justify-center">
                        <BUTTON type="button"
                            class="flex justify-center items-center transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer mt-3 bg-[#ff8e72] text-black border h-13 rounded-sm w-full flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" class="size-6">
                                <path fill-rule="evenodd"
                                    d="M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z"
                                    clip-rule="evenodd" />
                            </svg>

                            Favoris
                        </BUTTON>
                        <button
                            class="mt-3 w-13 border h-13 rounded-sm flex items-center justify-center transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 cursor-pointer hover:shadow-[4px_4px_0px_0px_#000000]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-6">
                                <path fill-rule="evenodd"
                                    d="M15.75 4.5a3 3 0 1 1 .825 2.066l-8.421 4.679a3.002 3.002 0 0 1 0 1.51l8.421 4.679a3 3 0 1 1-.729 1.31l-8.421-4.678a3 3 0 1 1 0-4.132l8.421-4.679a3 3 0 0 1-.096-.755Z"
                                    clip-rule="evenodd" />
                            </svg>

                        </button>


                    </div>




                </div>
            </div>
        </div>
    </div>
</x-layout>
