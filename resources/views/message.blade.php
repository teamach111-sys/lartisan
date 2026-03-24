<x-layoutdash2>

    <x-slot:title>
        Mes Messages
    </x-slot:title>
    <x-slot:h1>
        Mes Messages
    </x-slot:h1>
    <x-slot:topbar>

    </x-slot:topbar>
    <div x-data="messaging({{ auth()->id() }})" x-init="fetchConversations()" class="flex h-full overflow-hidden">
        {{-- Sidebar --}}
        <div :class="currentConversation ? 'hidden md:flex' : 'flex'" 
             class="w-full md:w-1/3 bg-white h-full flex flex-col border rounded-lg overflow-y-auto shadow-md">
            <div class="p-4 h-18 border-b relative">
                <svg data-slot="icon" fill="currentColor" class="absolute top-6 left-6" viewBox="0 0 16 16" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M5.94 8.06a1.5 1.5 0 1 1 2.12-2.12 1.5 1.5 0 0 1-2.12 2.12Z"></path>
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14ZM4.879 4.879a3 3 0 0 0 3.645 4.706L9.72 10.78a.75.75 0 0 0 1.061-1.06L9.585 8.524A3.001 3.001 0 0 0 4.879 4.88Z"></path>
                </svg>
                <input class="pl-9 w-full h-full p-2 border rounded-[50px]" placeholder="Rechercher..." type="text">
            </div>

            <div class="overflow-y-auto flex-1">
                <template x-for="conv in conversations" :key="conv.id">
                    <div @click="selectConversation(conv)" 
                         :class="currentConversation?.id === conv.id ? 'bg-gray-100' : 'hover:bg-gray-50'"
                         class="py-2 pl-4 h-18 border-b flex gap-3 justify-between items-center cursor-pointer transition-colors">
                        <div class="flex gap-4 items-center">
                            <img class="h-12 w-12 object-cover rounded-full"
                                :src="conv.partner_pfp"
                                alt="">
                            <div>
                                <div class="flex gap-1 flex-col sm:flex-row sm:gap-2 items-center">
                                    <h2 class="font-bold text-sm" x-text="conv.partner_name"></h2>
                                    <p class="text-[10px] uppercase opacity-60 font-bold line-clamp-1 " x-text="'Pour: ' + conv.produit_nom"></p>
                                </div>
                                <p class="text-xs opacity-70 truncate w-32 sm:w-40" x-text="conv.latest_message"></p>
                            </div>
                        </div>

                        <div class="flex flex-col items-center p-4 gap-2">
                            <p class="text-[10px] font-bold" x-text="conv.latest_time"></p>
                            <template x-if="conv.unread_count > 0">
                                <p class="bg-[#FF8E72] rounded-full w-5 h-5 text-white text-[10px] flex items-center justify-center font-bold" x-text="conv.unread_count"></p>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Conversation area --}}
        <div :class="currentConversation ? 'flex' : 'hidden md:flex'" 
             class="w-full md:w-2/3 bg-white h-full flex flex-col md:ml-4 rounded-lg border overflow-hidden shadow-md relative">
            <template x-if="currentConversation">
                <div class="h-full flex flex-col">
                    {{-- Header --}}
                    <div class="p-4 h-18 border-b flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <img class="h-10 w-10 md:h-12 md:w-12 object-cover rounded-full"
                                :src="currentConversation.partner_pfp"
                                alt="">
                            <div>
                                <div class="flex flex-col">
                                    <h2 class="font-bold text-sm md:text-base" x-text="currentConversation.partner_name"></h2>
                                    <p class="line-clamp-1 text-[10px] uppercase opacity-60 font-bold" x-text="'Pour: ' + currentConversation.produit_nom"></p>
                                </div>
                                <p class="text-green-500 text-[10px] font-bold uppercase tracking-tighter">En Ligne</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            {{-- Close Button for Mobile (Top Right) --}}
                            <button @click="currentConversation = null" class="md:hidden p-2 hover:bg-gray-100 rounded-full transition-colors text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 cursor-pointer opacity-60 hover:opacity-100 transition-opacity">
                                <path fill-rule="evenodd" d="M10.5 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    {{-- Messages container --}}
                    <div id="messages-container" class="p-4 flex-1 border-b w-full overflow-y-auto bg-gray-50 flex flex-col gap-4">
                        <template x-for="msg in messages" :key="msg.id">
                            <div class="flex items-end gap-2" :class="msg.expediteur_id == {{ auth()->id() }} ? 'flex-row-reverse' : 'flex-row'">
                                {{-- PFP --}}
                                <img class="h-8 w-8 object-cover rounded-full"
                                     :src="msg.expediteur_id == {{ auth()->id() }} ? currentConversation.auth_pfp : currentConversation.partner_pfp" 
                                     alt="">

                                <div class="max-w-[70%] break-words p-3 shadow-sm border"
                                     :class="msg.expediteur_id == {{ auth()->id() }} ? 'bg-[#FF8E72] text-white rounded-t-lg rounded-bl-lg border-black' : 'bg-white text-gray-800 rounded-t-lg rounded-br-lg'">
                                    <p class="text-sm" x-text="msg.contenu"></p>
                                    <p class="text-[10px] mt-1 opacity-60 text-right font-bold" x-text="msg.time"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Input area --}}
                    <div class="p-4 relative flex items-center h-[5rem]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="cursor-pointer size-6 absolute top-7 left-6 opacity-60 hover:opacity-100 transition-opacity">
                            <path fill-rule="evenodd" d="M18.97 3.659a2.25 2.25 0 0 0-3.182 0l-10.94 10.94a3.75 3.75 0 1 0 5.304 5.303l7.693-7.693a.75.75 0 0 1 1.06 1.06l-7.693 7.693a5.25 5.25 0 1 1-7.424-7.424l10.939-10.94a3.75 3.75 0 1 1 5.303 5.304L9.097 18.835l-.008.008-.007.007-.002.002-.003.002A2.25 2.25 0 0 1 5.91 15.66l7.81-7.81a.75.75 0 0 1 1.061 1.06l-7.81 7.81a.75.75 0 0 0 1.054 1.068L18.97 6.84a2.25 2.25 0 0 0 0-3.182Z" clip-rule="evenodd" />
                        </svg>
                        <button @click="sendMessage"
                                class="bg-[#FF8E72] rounded-full w-[3rem] h-[3rem] text-white flex justify-center items-center cursor-pointer absolute top-4 right-5 transition-all duration-200 hover:-translate-y-1 hover:-translate-x-1 hover:shadow-[4px_4px_0px_0px_#000000] active:translate-y-0 active:translate-x-0 active:shadow-none">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 ">
                                <path d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z" />
                            </svg>
                        </button>
                        <input x-model="newMessage" @keyup.enter="sendMessage"
                               class="pl-9 w-[calc(100%-4rem)] h-full p-2 border rounded-[50px] focus:outline-none" placeholder="Message" type="text">
                    </div>
                </div>
            </template>
            <template x-if="!currentConversation">
                <div class="h-full flex flex-col items-center justify-center text-center p-8 bg-gray-50 opacity-60">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-16 mx-auto mb-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
                    </svg>
                    <h3 class="text-xl font-bold uppercase tracking-tighter">Sélectionnez une conversation</h3>
                    <p class="text-sm font-medium">Commencez l'aventure artisanale dès maintenant !</p>
                </div>
            </template>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('messaging', (myId) => ({
                conversations: [], // State array of objects for the left sidebar
                messages: [], // State array of chats for the selected contact
                currentConversation: null, // The currently active chat
                newMessage: '',
                authUser: @json($auth_user),

                // Fires immediately when the page loads
                async fetchConversations() {
                    const res = await axios.get('/api/conversations');
                    this.conversations = res.data;

                    // URL Param Listener: Checks if the user was sent here from a specific product page e.g., `/message?conversation=1`
                    const urlParams = new URLSearchParams(window.location.search);
                    const convId = urlParams.get('conversation');
                    if (convId) {
                        const targetConv = this.conversations.find(c => c.id == convId);
                        if (targetConv) {
                            this.selectConversation(targetConv); // Automatically click it!
                        }
                    }
                },

                // Triggered by Alpine `@click="selectConversation(conversation)"` on the sidebar
                selectConversation(conversation) {
                    this.currentConversation = conversation;
                    this.messages = []; // Visually wipe the screen clean for a UI transition effect

                    // Optimistic UI update: pretend the messages are read immediately so the notification badge clears instantly
                    conversation.unread_count = 0;

                    // Make the heavy server lookup across the network
                    this.fetchMessages(conversation.id);
                },

                // Backend pull 
                async fetchMessages(conversationId) {
                    const res = await axios.get(`/api/conversations/${conversationId}/messages`);
                    this.messages = res.data; // Alpine automatically detects array changes and draws the bubbles via HTML `x-for` tracking

                    // Optional: force scroll to the bottom of the container
                    this.scrollToBottom();
                },

                async sendMessage() {
                    if (!this.newMessage.trim() || !this.currentConversation) return;

                    const content = this.newMessage;
                    this.newMessage = '';

                    // Optimistic update
                    const tempMessage = {
                        id: Date.now(),
                        expediteur_id: myId,
                        contenu: content,
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    };
                    this.messages.push(tempMessage);
                    this.scrollToBottom();

                    try {
                        const res = await axios.post(`/api/conversations/${this.currentConversation.id}/messages`, {
                            contenu: content
                        });
                    } catch (err) {
                        console.error(err);
                    }
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = document.getElementById('messages-container');
                        if (container) container.scrollTop = container.scrollHeight;
                    });
                }
            }));
        });
    </script>

</x-layoutdash2>
