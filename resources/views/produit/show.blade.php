<x-layout>
    <div class="flex flex-col lg:w-2/3 w-full mx-auto h-full overflow-y-auto py-4 lg:py-9">
        {{-- navigation --}}
        <div class="mb-6 ml-1">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-black bg-white rounded-sm font-bold transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Retour au marché
            </a>
        </div>
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
                <div class="bg-white p-5 lg:p-6 border-b flex justify-between items-start md:items-center flex-col md:flex-row gap-2 md:gap-0">
                    <h1 class="text-2xl font-bold break-words w-full md:max-w-[70%] leading-tight">{{ $produit->titre }}</h1>
                    <span class="px-3 py-1 bg-gray-100 border border-black/10 rounded-sm text-sm font-bold shrink-0">{{ $produit->ville_produit }} • {{ $produit->created_at->diffForHumans() }}</span>
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
                                <span class="text-[10px] font-black uppercase text-black/40">{{ $produit->ville }}</span>
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
                {{-- Contact Button --}}
                <form action="{{ route('produit.contact', $produit->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex justify-center items-center transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer bg-[#FF8E72] text-black border border-black h-14 rounded-sm w-full gap-2 font-black uppercase text-sm tracking-wide">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                        </svg>
                        Contacter l'artisan
                    </button>
                </form>

                @if($produit->vendeur->display_phone)
                <div class="p-4 bg-gray-50 border border-black/5 rounded-sm flex items-center gap-3">
                    <div class="bg-black p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="white" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.387a12.035 12.035 0 0 1-7.143-7.143c-.155-.441.011-.928.387-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase font-black text-gray-400 leading-none mb-1">Téléphone de l'artisan</span>
                        <a href="tel:{{ $produit->vendeur->telephone }}" class="font-bold text-lg hover:text-[#fb663f] transition-colors leading-none">
                            {{ $produit->vendeur->telephone ?? 'Non renseigné' }}
                        </a>
                    </div>
                </div>
                @endif

                <div class="flex gap-3 items-center">
                    {{-- Favoris Button --}}
                    @if(auth()->id() !== $produit->vendeur_id)
                    @php $isFavorited = auth()->user()?->favoris->contains($produit->id); @endphp
                    <form action="{{ route('produit.favorite', $produit->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full flex justify-center items-center transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer {{ $isFavorited ? 'bg-[#FF8E72]/10 border-[#FF8E72]' : 'bg-white border-black' }} text-black border h-14 rounded-sm gap-2 font-bold text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 fill="{{ $isFavorited ? '#FF8E72' : 'none' }}" 
                                 viewBox="0 0 24 24" 
                                 stroke-width="2" 
                                 stroke="{{ $isFavorited ? '#FF8E72' : 'currentColor' }}" 
                                 class="size-6 transition-colors duration-200">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                            {{ $isFavorited ? 'Favori' : 'Ajouter aux favoris' }}
                        </button>
                    </form>
                    @endif

                    {{-- Share Button --}}
                    <button type="button"
                        class="w-14 border border-black h-14 rounded-sm flex items-center justify-center transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 cursor-pointer hover:shadow-[4px_4px_0px_0px_#000000] bg-white text-black group"
                        onclick="navigator.clipboard.writeText(window.location.href); alert('Lien copié !')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6 group-hover:text-[#FF8E72] transition-colors">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                        </svg>
                    </button>
                </div>

                {{-- Report Button --}}
                @auth
                @if(auth()->id() !== $produit->vendeur_id)
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button"
                        class="w-full flex justify-center items-center gap-2 text-sm font-bold text-black/40 hover:text-red-500 transition-colors cursor-pointer py-2">
                        Signaler l'annonce
                    </button>

                    {{-- Dropdown Report Form --}}
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         @click.outside="open = false"
                         class="mt-2 border border-black bg-white rounded-sm p-4 shadow-[4px_4px_0px_0px_#000000]">
                        <form action="{{ route('produit.signaler', $produit->id) }}" method="POST">
                            @csrf
                            <label class="block text-xs font-black uppercase text-black/40 mb-2">Raison du signalement</label>
                            <select name="type_signalement" required
                                class="w-full border border-black rounded-sm p-2 text-sm font-medium mb-3 focus:outline-none focus:shadow-[3px_3px_0px_0px_#000000] transition-shadow">
                                <option value="">— Choisir —</option>
                                <option value="Arnaque">Arnaque / Fraude</option>
                                <option value="Contenu inapproprié">Contenu inapproprié</option>
                                <option value="Doublon">Annonce en doublon</option>
                                <option value="Produit interdit">Produit interdit</option>
                                <option value="Autre">Autre</option>
                            </select>

                            <label class="block text-xs font-black uppercase text-black/40 mb-2">Détails (facultatif)</label>
                            <textarea name="details" rows="2" maxlength="1000"
                                class="w-full border border-black rounded-sm p-2 text-sm mb-3 resize-none focus:outline-none focus:shadow-[3px_3px_0px_0px_#000000] transition-shadow"
                                placeholder="Précisez si nécessaire..."></textarea>

                            <button type="submit"
                                class="w-full bg-red-500 text-white border border-black font-bold text-sm py-2.5 rounded-sm transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer">
                                Envoyer le signalement
                            </button>
                        </form>
                    </div>
                </div>
                @endif
                @endauth

                @if(session('success'))
                <div class="p-3 bg-green-50 border border-green-300 rounded-sm text-green-800 text-sm font-medium">
                    {{ session('success') }}
                </div>
                @endif
                @if(session('info'))
                <div class="p-3 bg-yellow-50 border border-yellow-300 rounded-sm text-yellow-800 text-sm font-medium">
                    {{ session('info') }}
                </div>
                @endif

            </div>
        </div>
    </div>
</x-layout>
