<x-layout>
    <div class="flex flex-col lg:w-2/3 w-full mx-auto h-full overflow-y-auto py-9 px-4 sm:px-0">
        {{-- 1. Image Gallery --}}
        <div x-data="{
            index: 0,
            images: {{ json_encode(collect($produit->images)->map(fn($img) => asset('storage/' . $img))->toArray()) }},
            next() { this.index = (this.index + 1) % (this.images.length || 1) },
            prev() { this.index = (this.index - 1 + (this.images.length || 1)) % (this.images.length || 1) }
        }"
            class="relative bg-white shadow-sm overflow-hidden border border-b-0 h-[400px] md:h-[600px]">

            <template x-if="images && images.length > 1">
                <button @click="next()"
                    class="absolute z-10 right-3 top-1/2 -translate-y-1/2 bg-white border rounded-full hover:bg-gray-100 transition-colors p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </template>

            <template x-if="images && images.length > 1">
                <button @click="prev()"
                    class="absolute z-10 left-3 top-1/2 -translate-y-1/2 bg-white border rounded-full hover:bg-gray-100 transition-colors p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>
            </template>

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

            <template x-if="images && images.length > 0">
                <template x-for="(img, i) in images" :key="i">
                    <img x-show="index === i" :src="img" alt="Product View"
                        class="object-cover h-full w-full" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                </template>
            </template>
        </div>

        {{-- 2. Details & Actions --}}
        <div class="flex flex-col md:flex-row border">

            {{-- Left Side: Details --}}
            <div class="flex flex-col w-full md:w-2/3 border-r-0 md:border-r border-b md:border-b-0">

                {{-- Title --}}
                <div class="bg-white p-5 lg:p-6 border-b">
                    <h1 class="text-2xl font-bold">{{ $produit->titre }}</h1>
                </div>

                {{-- Price & Seller --}}
                <div class="bg-white flex flex-col sm:flex-row border-b">
                    <div class="p-5 lg:p-6 flex items-center border-b sm:border-b-0 sm:border-r">
                        <div
                            class="inline-block bg-black [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
                            <div
                                class="line-clamp-1 bg-[#FF8E72] text-black font-bold text-lg py-1.5 pl-4 pr-12 [clip-path:polygon(0%_0%,_100%_0%,_calc(100%-15px)_50%,_100%_100%,_0%_100%)]">
                                {{ $produit->prix }} DH
                            </div>
                        </div>
                    </div>

                    <div class="p-5 lg:p-6 flex-1 flex items-center">
                        <div class="flex gap-3 items-center">
                            <img class="h-10 w-10 object-cover rounded-full border border-gray-200"
                                src="{{ $produit->vendeur?->pfp ? asset('storage/' . $produit->vendeur->pfp) : asset('imgs/default.svg') }}"
                                alt="">
                            <div class="flex flex-col">
                                <span
                                    class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Vendeur</span>
                                <p class="line-clamp-1 underline font-medium">
                                    {{ $produit->vendeur->name ?? 'Artisan Anonyme' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="bg-white p-5 lg:p-6 grow">
                    <h2 class="text-sm font-bold uppercase text-gray-500 mb-3">Description</h2>
                    <p class="whitespace-pre-line text-gray-800">{{ $produit->description }}</p>
                </div>

            </div>

            {{-- Right Side: Actions --}}
            <div class="bg-white w-full md:w-1/3 p-5 lg:p-6 flex flex-col gap-4">
                <form action="{{ route('produit.contact', $produit->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex justify-center items-center transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer bg-[#ff8e72] text-black border h-14 rounded-sm w-full gap-2 font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" class="size-6">
                            <path
                                d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                            <path
                                d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                        </svg>
                        Contacter le vendeur
                    </button>

                </form>


                <div class="flex gap-3 items-center">
                    <button type="button"
                        class="flex-1 flex justify-center items-center transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer bg-white text-black border h-14 rounded-sm gap-2 font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="size-6 text-red-500">
                            <path fill-rule="evenodd"
                                d="M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z"
                                clip-rule="evenodd" />
                        </svg>
                        Favoris
                    </button>

                    <button type="button"
                        class="w-14 border h-14 rounded-sm flex items-center justify-center transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 cursor-pointer hover:shadow-[4px_4px_0px_0px_#000000] bg-white text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd"
                                d="M15.75 4.5a3 3 0 1 1 .825 2.066l-8.421 4.679a3.002 3.002 0 0 1 0 1.51l8.421 4.679a3 3 0 1 1-.729 1.31l-8.421-4.678a3 3 0 1 1 0-4.132l8.421-4.679a3 3 0 0 1-.096-.755Z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </div>
</x-layout>
