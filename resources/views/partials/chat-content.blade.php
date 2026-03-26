{{-- Header --}}
<div class="p-4 h-18 border-b border-gray-100 flex items-center justify-between bg-white z-10 font-bold uppercase tracking-tighter flex-shrink-0">
    <div class="flex items-center gap-4">
        {{-- Back Arrow for Mobile --}}
        <button @click="currentConversation = null" class="md:hidden p-2 hover:bg-gray-100 rounded-full transition-all text-[#FF8E72]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"
                class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </button>

        <div class="relative">
            <img class="h-10 w-10 object-cover rounded-full border border-gray-100"
                :src="currentConversation?.partner_pfp" alt="">
            <span :class="currentConversation?.is_online ? 'bg-green-500' : 'bg-gray-400'"
                class="absolute bottom-0 right-0 h-3 w-3 rounded-full border border-white"></span>
        </div>
        <div>
            <div class="flex flex-col">
                <h2 class="font-bold text-sm md:text-base" x-text="currentConversation?.partner_name"></h2>
                <p class="line-clamp-1 text-[10px] uppercase opacity-60 font-bold"
                    x-text="'Produit: ' + currentConversation?.produit_nom"></p>
            </div>
            <p :class="currentConversation?.is_online ? 'text-green-500' : 'text-gray-400'"
                class="text-[10px] font-bold uppercase tracking-tighter"
                x-text="currentConversation?.is_online ? 'En Ligne' : 'Hors Ligne'"></p>
        </div>
    </div>

    <div class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
            class="size-6 cursor-pointer opacity-60 hover:opacity-100 transition-opacity">
            <path fill-rule="evenodd"
                d="M10.5 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z"
                clip-rule="evenodd" />
        </svg>
    </div>
</div>

{{-- Messages container --}}
<div class="messages-container p-6 flex-1 border-b border-gray-100 w-full overflow-y-auto bg-gray-50 flex flex-col gap-4">
    <template x-for="msg in messages" :key="msg.id">
        <div class="flex items-end gap-3"
            :class="msg.expediteur_id == {{ auth()->id() }} ? 'flex-row-reverse' : 'flex-row'">
            {{-- PFP --}}
            <img class="h-8 w-8 object-cover rounded-full border border-gray-100"
                :src="msg.expediteur_id == {{ auth()->id() }} ? currentConversation?.auth_pfp :
                    currentConversation?.partner_pfp"
                alt="">

            <div class="max-w-[70%] break-words p-4"
                :class="msg.expediteur_id == {{ auth()->id() }} ?
                    'bg-[#FF8E72] text-white rounded-2xl rounded-tr-none' :
                    'bg-gray-100 text-gray-800 rounded-2xl rounded-tl-none'">
                <p class="text-sm font-medium" x-text="msg.contenu"></p>
                <p class="text-[10px] mt-2 opacity-70 text-right font-bold" x-text="msg.time"></p>
            </div>
        </div>
    </template>
</div>

{{-- Input area --}}
<div class="p-4 md:p-6 bg-white border-t border-gray-100 flex-shrink-0">
    <div class="relative flex items-center gap-4">
        <div class="flex-1 relative">
            <input x-model="newMessage" @keyup.enter="sendMessage"
                class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:border-[#FF8E72] focus:ring-1 focus:ring-[#FF8E72] transition-all"
                placeholder="Écrivez un message ici..." type="text">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                class="size-5 absolute top-1/2 -translate-y-1/2 left-4 opacity-40">
                <path fill-rule="evenodd"
                    d="M18.97 3.659a2.25 2.25 0 0 0-3.182 0l-10.94 10.94a3.75 3.75 0 1 0 5.304 5.303l7.693-7.693a.75.75 0 0 1 1.06 1.06l-7.693 7.693a5.25 5.25 0 1 1-7.424-7.424l10.939-10.94a3.75 3.75 0 1 1 5.303 5.304L9.097 18.835l-.008.008-.007.007-.002.002-.003.002A2.25 2.25 0 0 1 5.91 15.66l7.81-7.81a.75.75 0 0 1 1.061 1.06l-7.81 7.81a.75.75 0 0 0 1.054 1.068L18.97 6.84a2.25 2.25 0 0 0 0-3.182Z"
                    clip-rule="evenodd" />
            </svg>
        </div>
        <button @click="sendMessage"
            class="bg-[#FF8E72] text-white p-3 rounded-2xl hover:shadow-lg transition-all hover:-translate-y-0.5 active:translate-y-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path
                    d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z" />
            </svg>
        </button>
    </div>
</div>
