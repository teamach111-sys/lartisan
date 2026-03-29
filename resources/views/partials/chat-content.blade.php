{{-- Header --}}
<div class="p-4 h-20 border-b border-black/5 flex items-center justify-between bg-white z-10 flex-shrink-0">
    <div class="flex items-center gap-4">
        {{-- Back Arrow for Mobile --}}
        <button @click="currentConversation = null" class="md:hidden p-2 hover:bg-gray-50 border border-black rounded-sm transition-all text-black">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </button>

        <div class="relative">
            <img class="h-12 w-12 md:h-14 md:w-14 object-cover rounded-full border border-black/5 shadow-sm flex-shrink-0 aspect-square"
                :src="currentConversation?.partner_pfp" alt="">
            <div :class="currentConversation?.is_online ? 'bg-green-500' : 'bg-gray-300'"
                class="absolute bottom-0 right-0 h-4 w-4 rounded-full border-2 border-white shadow-sm"></div>
        </div>
        <div>
            <div class="flex items-center gap-2 overflow-hidden flex-nowrap">
                <h2 class="font-bold text-base md:text-lg text-black truncate" x-text="currentConversation?.partner_name"></h2>
                <span :class="currentConversation?.is_online ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500'" class="px-1.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest whitespace-nowrap" x-text="currentConversation?.is_online ? 'En Ligne' : 'Hors Ligne'"></span>
            </div>
            <a :href="'/produit/' + currentConversation?.produit_slug" class="line-clamp-1 text-[10px] font-black uppercase text-black/40 hover:text-[#FF8E72] transition-colors tracking-tight block"
                x-text="'Article: ' + currentConversation?.produit_nom"></a>
        </div>
    </div>

    <div class="flex items-center gap-2">
        <div class="relative" x-data="{ dropdown: false }" @click.away="dropdown = false">
            <button @click="dropdown = !dropdown" 
                class="p-2 hover:bg-gray-50 rounded-full transition-colors opacity-30 hover:opacity-100 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd" d="M10.5 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" clip-rule="evenodd" />
                </svg>
            </button>
            <div x-show="dropdown" x-cloak
                class="absolute right-0 mt-2 w-56 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden py-1">
                <button @click="toggleBlock(); dropdown = false"
                    class="w-full text-left px-4 py-2.5 text-sm font-semibold transition-colors hover:bg-gray-50 flex items-center gap-3"
                    :class="is_blocked ? 'text-green-600' : 'text-red-600'">
                    <svg x-show="!is_blocked" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    <svg x-show="is_blocked" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-text="is_blocked ? 'Débloquer cet utilisateur' : 'Bloquer cet utilisateur'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Messages container --}}
<div class="messages-container p-6 md:p-10 flex-1 border-b border-black/5 w-full overflow-y-auto bg-gray-50/30 flex flex-col gap-6">
    <template x-for="msg in messages" :key="msg.id">
        <div class="flex items-end gap-3"
            :class="msg.expediteur_id == {{ auth()->id() }} ? 'flex-row-reverse' : 'flex-row'">
            {{-- PFP --}}
            <img class="h-9 w-9 md:h-10 md:w-10 object-cover rounded-full border border-black/5 bg-white shadow-sm flex-shrink-0 aspect-square"
                :src="msg.expediteur_id == {{ auth()->id() }} ? currentConversation?.auth_pfp :
                    currentConversation?.partner_pfp"
                alt="">

            <div class="max-w-[85%] md:max-w-[75%] break-words p-4 md:px-6 md:py-4 shadow-sm border border-black/5"
                :class="msg.expediteur_id == {{ auth()->id() }} ?
                    'bg-[#F4F4F0] text-gray-800 rounded-2xl rounded-tr-none' :
                    'bg-white text-gray-800 rounded-2xl rounded-tl-none'">
                <p class="text-base md:text-[16px] font-medium leading-relaxed" x-text="msg.contenu"></p>
                <div class="flex items-center justify-end gap-2 mt-2 opacity-50">
                     <p class="text-[10px] font-bold uppercase" x-text="msg.time"></p>
                     <template x-if="msg.expediteur_id == {{ auth()->id() }}">
                         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-3.5">
                             <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                         </svg>
                     </template>
                </div>
            </div>
        </div>
    </template>
</div>

{{-- Input area --}}
<div class="p-6 md:p-8 bg-white border-t border-black/5">
    <template x-if="is_blocked">
        <div class="bg-gray-50 border border-gray-100 p-6 rounded-xl text-center shadow-sm">
            <p class="text-sm font-semibold text-gray-500 mb-2">Vous avez bloqué cet utilisateur. Vous ne recevrez plus de messages de sa part.</p>
            <button @click="toggleBlock()" class="text-xs font-bold text-[#FF8E72] hover:text-[#fb663f] transition-colors uppercase tracking-wider cursor-pointer">Débloquer pour discuter</button>
        </div>
    </template>
    <template x-if="blocked_by">
        <div class="bg-red-50/50 border border-red-100 p-6 rounded-xl text-center">
            <p class="text-sm font-semibold text-red-600">Cet utilisateur vous a bloqué. La communication est désactivée.</p>
        </div>
    </template>
    <template x-if="!is_blocked && !blocked_by">
        <div class="relative flex items-center gap-4">
            <div class="flex-1 relative group flex items-center">
                <textarea x-model="newMessage" @keydown.enter.prevent="sendMessage"
                    class="w-full pl-5 pr-5 py-4 bg-gray-50 border border-black rounded-sm focus:outline-none focus:bg-white focus:shadow-[4px_4px_0px_0px_#000000] transition-all font-bold text-base placeholder:text-black/10 resize-none h-16"
                    placeholder="Votre message ici..."></textarea>
            </div>
            <button @click="sendMessage"
                class="bg-black text-white h-16 w-16 flex items-center justify-center border border-black rounded-sm hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] hover:bg-[#FF8E72] hover:text-black transition-all active:translate-x-0 active:translate-y-0 active:shadow-none flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z" />
                </svg>
            </button>
        </div>
    </template>
</div>
