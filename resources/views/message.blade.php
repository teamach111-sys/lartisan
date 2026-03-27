<x-layoutdash2>

    <x-slot:title>
        Mes Messages
    </x-slot:title>
    <x-slot:h1>
        Mes Messages
    </x-slot:h1>
    <x-slot:topbar>
        <style>
            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }

            .scrollbar-hide {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
        <div class="flex md:flex-row md:justify-between md:items-center flex-col pb-1">
            <div class="h-15 flex items-center gap-2 overflow-x-auto snap-x snap-mandatory scroll-smooth">
                <a href=""
                    class="flex-shrink-0 snap-center border cursor-pointer text-[15px] rounded-[50px] p-2 transition-all duration-200">Tous</a>
                <a href=""
                    class="flex-shrink-0 snap-center border cursor-pointer text-[15px] hover:border-black border-transparent rounded-[50px] p-2 transition-all duration-200">Non lus</a>
            </div>
            <div>
                <button onclick="window.location.reload()"
                    class="text-[15px] hidden md:block mx-auto bg-white rounded-sm h-auto p-2 border cursor-pointer transition-all duration-200 hover:bg-[#FF8E72] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">
                    Actualiser
                </button>
            </div>
        </div>
    </x-slot:topbar>
    <div x-data="messaging({{ auth()->id() }})" class="flex flex-col md:flex-row h-full overflow-hidden gap-0 md:gap-10">
        {{-- Contacts Sidebar --}}
        <div class="w-full md:w-[320px] lg:w-[380px] flex flex-col flex-shrink-0" :class="currentConversation ? 'hidden md:flex' : 'flex'">
            <div class="flex items-center justify-between mb-6 px-1">
                <h3 class="text-xs font-black uppercase tracking-widest text-black opacity-30">Conversations</h3>
                <div class="relative w-52 group">
                    <input type="text" x-model="searchQuery" placeholder="Rechercher..."
                        class="w-full pl-9 pr-4 py-2 text-sm bg-white border border-black rounded-sm focus:outline-none focus:bg-[#FF8E72]/5 transition-all text-black placeholder:text-black/20 focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="size-4 absolute left-3 top-1/2 -translate-y-1/2 opacity-30">
                        <path fill-rule="evenodd"
                            d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <div class="flex flex-row md:flex-col gap-4 overflow-x-auto md:overflow-y-auto pb-4 md:pb-0 pl-1 md:pl-2 pt-1 md:pt-2 md:pr-2 scrollbar-hide flex-1">
                <template x-for="conv in filteredConversations" :key="conv.id">
                    <div @click="selectConversation(conv)"
                        :class="currentConversation?.id === conv.id ? 'bg-white border-[#FF8E72] shadow-[6px_6px_0px_0px_#000000] -translate-x-1 -translate-y-1' :
                            'bg-white border-black hover:shadow-[4px_4px_0px_0px_#000000] hover:-translate-x-0.5 hover:-translate-y-0.5'"
                        class="flex items-center gap-4 p-4 rounded-sm border border-black cursor-pointer transition-all min-w-[280px] md:min-w-0 relative group">

                        <div class="relative flex-shrink-0">
                            <img class="h-14 w-14 md:h-16 md:w-16 object-cover rounded-full border border-black/5"
                                :src="conv.partner_pfp" alt="">

                            {{-- Status Dot --}}
                            <div :class="conv.is_online ? 'bg-green-500' : 'bg-gray-300'"
                                class="absolute bottom-0 right-0 h-4 w-4 rounded-full border-2 border-white shadow-sm"></div>
                        </div>

                        <div class="flex-1 overflow-hidden">
                            <div class="flex items-center justify-between mb-0.5">
                                <h2 class="font-bold text-base truncate text-black" :class="currentConversation?.id === conv.id ? 'text-[#FF8E72]' : ''" x-text="conv.partner_name"></h2>
                                <span class="text-[10px] font-medium opacity-30 whitespace-nowrap ml-2" x-text="conv.latest_time"></span>
                            </div>
                            <p class="text-[10px] font-black uppercase text-black/40 truncate mb-1" x-text="conv.produit_nom"></p>
                            <p class="text-xs text-gray-400 truncate font-medium leading-none" x-text="conv.latest_message || 'Démarrer la discussion'"></p>
                        </div>
                        
                        <template x-if="conv.unread_count > 0">
                            <div class="absolute -top-2 -right-2 bg-[#FF8E72] text-white text-[10px] font-black h-6 w-6 rounded-full flex items-center justify-center border-2 border-white shadow-sm z-10"
                                x-text="conv.unread_count"></div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        {{-- Desktop Conversation area --}}
        <div class="hidden md:flex flex-1 bg-white flex-col border border-black/10 relative overflow-hidden rounded-sm">
            <template x-if="currentConversation">
                <div class="h-full flex flex-col">
                    @include('partials.chat-content')
                </div>
            </template>
            <template x-if="!currentConversation">
                <div class="h-full flex flex-col items-center justify-center text-center p-12 bg-gray-50/20">
                    <p class="text-sm font-black text-black/20 uppercase tracking-[0.2em]">Cliquez sur un artisan à gauche pour démarrer la discussion !</p>
                </div>
            </template>
        </div>

        {{-- Mobile Full Screen Chat (Teleported to Body) --}}
        <template x-teleport="body">
            <div x-show="currentConversation && isMobile" 
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-y-full"
                 x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="translate-y-0"
                 x-transition:leave-end="translate-y-full"
                 class="fixed inset-0 z-[10000] bg-white flex flex-col md:hidden overflow-hidden h-full w-full">
                <div class="flex flex-col flex-1 h-full w-full bg-white">
                    @include('partials.chat-content')
                </div>
            </div>
        </template>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('messaging', (myId) => ({
                conversations: [], // State array of objects for the left sidebar
                messages: [], // State array of chats for the selected contact
                currentConversation: null, // The currently active chat
                newMessage: '',
                searchQuery: '',
                authUser: @json($auth_user),
                isMobile: window.innerWidth < 768,

                init() {
                    window.addEventListener('resize', () => {
                        this.isMobile = window.innerWidth < 768;
                    });
                    this.fetchConversations();
                },

                get filteredConversations() {
                    if (!this.searchQuery.trim()) return this.conversations;
                    const query = this.searchQuery.toLowerCase();
                    return this.conversations.filter(c =>
                        c.partner_name.toLowerCase().includes(query) ||
                        c.produit_nom.toLowerCase().includes(query)
                    );
                },

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
                    if (this.currentConversation) {
                        window.Echo.leave(`messenger.${this.currentConversation.id}`);
                    }

                    this.currentConversation = conversation;
                    this.messages = []; // Visually wipe the screen clean for a UI transition effect

                    // Real-time listener
                    window.Echo.private(`messenger.${conversation.id}`)
                        .listen('.message.sent', (e) => {
                            if (this.currentConversation && e.id && !this.messages.find(m => m
                                    .id === e.id)) {
                                this.messages.push(e);
                                this.scrollToBottom();
                            }
                        });

                    // Optimistic UI update: pretend the messages are read immediately so the notification badge clears instantly
                    conversation.unread_count = 0;

                    // Make the heavy server lookup across the network
                    this.fetchMessages(conversation.id);
                },

                // Backend pull 
                async fetchMessages(conversationId) {
                    const res = await axios.get(`/api/conversations/${conversationId}/messages`);
                    this.messages = res
                    .data; // Alpine automatically detects array changes and draws the bubbles via HTML `x-for` tracking

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
                        time: new Date().toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        })
                    };
                    this.messages.push(tempMessage);
                    this.scrollToBottom();

                    try {
                        const res = await axios.post(
                            `/api/conversations/${this.currentConversation.id}/messages`, {
                                contenu: content
                            });
                    } catch (err) {
                        console.error(err);
                    }
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const containers = document.querySelectorAll('.messages-container');
                        containers.forEach(container => {
                            if (container) container.scrollTop = container.scrollHeight;
                        });
                    });
                }
            }));
        });
    </script>

</x-layoutdash2>
