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
            <div x-data="{ filter: 'all' }" class="h-15 flex items-center gap-2 overflow-x-auto snap-x snap-mandatory scroll-smooth">
                <button @click="$dispatch('set-filter', 'all'); filter = 'all'"
                    class="flex-shrink-0 snap-center border cursor-pointer text-[15px] rounded-[50px] p-2 transition-all duration-200"
                    :class="filter === 'all' ? 'border-black' : 'border-transparent hover:border-black'">Tous</button>
                <button @click="$dispatch('set-filter', 'unread'); filter = 'unread'"
                    class="flex-shrink-0 snap-center border cursor-pointer text-[15px] rounded-[50px] p-2 transition-all duration-200"
                    :class="filter === 'unread' ? 'border-black' : 'border-transparent hover:border-black'">Non lus</button>
            </div>
            <div>
                <button onclick="window.location.reload()"
                    class="text-[15px] hidden md:block mx-auto bg-white rounded-sm h-auto p-2 border cursor-pointer transition-all duration-200 hover:bg-[#FF8E72] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">
                    Actualiser
                </button>
            </div>
        </div>
    </x-slot:topbar>
    <div x-data="messaging({{ auth()->id() }})" @set-filter.window="filter = $event.detail" class="flex flex-col md:flex-row h-full overflow-hidden gap-0 md:gap-10">
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
                            <div class="flex items-start justify-between mb-0.5">
                                <div class="flex-1 overflow-hidden">
                                    <h2 class="font-bold text-base truncate text-black" :class="currentConversation?.id === conv.id ? 'text-[#FF8E72]' : ''" x-text="conv.partner_name"></h2>
                                    <a :href="'/produit/' + conv.produit_slug" class="text-[10px] font-black uppercase text-black/40 hover:text-[#FF8E72] transition-colors truncate mb-1 block" x-text="conv.produit_nom"></a>
                                </div>
                                <div class="flex flex-col items-end ml-2 mt-0.5">
                                    <span class="text-[10px] font-medium opacity-30 whitespace-nowrap mb-1.5" x-text="conv.latest_time"></span>
                                    <button @click.stop="deleteConversation(conv.id)" title="Supprimer la conversation"
                                        class="cursor-pointer text-black/20 hover:text-red-500 z-20">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 truncate font-medium leading-none" x-text="conv.latest_message || 'Démarrer la discussion'"></p>
                        </div>
                        
                        <template x-if="conv.unread_count > 0">
                            <div class="absolute -top-2 -right-2 bg-[#FF8E72] text-white text-[10px] font-black h-6 w-6 rounded-full flex items-center justify-center border-2 border-white shadow-sm z-10"
                                x-text="conv.unread_count"></div>
                        </template>
                    </div>
                </template>
                <template x-if="filteredConversations.length === 0">
                    <div class="flex items-center justify-center h-full py-16 px-6">
                        <p class="text-sm text-black/30 font-bold text-center leading-relaxed" x-text="filter === 'unread' ? 'Aucun message non lu.' : 'Contactez des artisans pour commencer à converser.'"></p>
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
                filter: 'all',
                authUser: @json($auth_user),
                isMobile: window.innerWidth < 768,
                onlineUsers: new Set(),
                presenceLoaded: false,
                is_blocked: false,
                blocked_by: false,

                init() {
                    window.addEventListener('resize', () => {
                        this.isMobile = window.innerWidth < 768;
                    });
                    
                    window.Echo.join('chat.presence')
                        .here((users) => {
                            this.presenceLoaded = true;
                            this.onlineUsers = new Set(users.map(u => u.id));
                            this.updateOnlineStatuses();
                        })
                        .joining((user) => {
                            this.onlineUsers.add(user.id);
                            this.updateOnlineStatuses();
                        })
                        .leaving((user) => {
                            this.onlineUsers.delete(user.id);
                            this.updateOnlineStatuses();
                        });

                    this.fetchConversations();
                },

                updateOnlineStatuses() {
                    if (!this.presenceLoaded) return;
                    this.conversations.forEach(conv => {
                        conv.is_online = this.onlineUsers.has(conv.partner_id);
                    });
                },

                get filteredConversations() {
                    let filtered = this.conversations;
                    
                    if (this.filter === 'unread') {
                        filtered = filtered.filter(c => c.unread_count > 0);
                    }
                    
                    if (this.searchQuery.trim()) {
                        const query = this.searchQuery.toLowerCase();
                        filtered = filtered.filter(c =>
                            c.partner_name.toLowerCase().includes(query) ||
                            c.produit_nom.toLowerCase().includes(query)
                        );
                    }
                    
                    return filtered;
                },

                // Fires immediately when the page loads
                async fetchConversations() {
                    try {
                        const res = await axios.get('/api/conversations');
                        this.conversations = Array.isArray(res.data) ? res.data : [];
                    } catch (err) {
                        console.error('Failed to fetch conversations:', err);
                        this.conversations = [];
                    }
                    
                    this.updateOnlineStatuses();

                    // Intercept messages for ALL existing conversations for real-time sidebar notifications
                    this.conversations.forEach(conv => {
                        try {
                            window.Echo.private(`messenger.${conv.id}`)
                                .listen('.message.sent', (e) => {
                                    // If this is the active conversation
                                    if (this.currentConversation && this.currentConversation.id === conv.id) {
                                        if (!this.messages.find(m => m.id === e.id)) {
                                            this.messages.push(e);
                                            this.scrollToBottom();
                                        }
                                        conv.unread_count = 0;
                                    } else {
                                        // Inactive conversation
                                        if (e.expediteur_id !== myId) {
                                            conv.unread_count++;
                                        }
                                    }
                                    
                                    // Update sidebar text
                                    conv.latest_message = e.contenu;
                                    conv.latest_time = e.time;
                                    
                                    // Float to top
                                    this.conversations = [
                                        conv,
                                        ...this.conversations.filter(c => c.id !== conv.id)
                                    ];
                                });
                        } catch (echoErr) {
                            // Echo/websockets not available — ignore silently
                        }
                    });

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
                    this.is_blocked = conversation.is_blocked;
                    this.blocked_by = conversation.blocked_by;

                    // Make the heavy server lookup across the network
                    this.fetchMessages(conversation.id);
                },

                // Backend pull 
                async fetchMessages(conversationId) {
                    const res = await axios.get(`/api/conversations/${conversationId}/messages`);
                    this.messages = res.data.messages;
                    this.is_blocked = res.data.is_blocked;
                    this.blocked_by = res.data.blocked_by;

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
                },

                async deleteConversation(id) {
                    if (!confirm('Voulez-vous vraiment supprimer cette conversation ?')) return;

                    // Optimistic UI updates
                    this.conversations = this.conversations.filter(c => c.id !== id);
                    if (this.currentConversation && this.currentConversation.id === id) {
                        this.currentConversation = null;
                        this.messages = [];
                    }

                    try {
                        await axios.delete(`/api/conversations/${id}`);
                    } catch (err) {
                        console.error('Erreur lors de la suppression', err);
                    }
                },

                async toggleBlock() {
                    if (!this.currentConversation) return;
                    const partnerId = this.currentConversation.partner_id;
                    const endpoint = this.is_blocked ? `/api/unblock/${partnerId}` : `/api/block/${partnerId}`;
                    
                    try {
                        const res = await axios.post(endpoint);
                        this.is_blocked = res.data.is_blocked;
                        // Update the conversation list matching the current one
                        const conv = this.conversations.find(c => c.id === this.currentConversation.id);
                        if (conv) {
                            conv.is_blocked = this.is_blocked;
                        }
                    } catch (err) {
                        console.error('Erreur lors du blocage/déblocage', err);
                        alert(err.response?.data?.message || 'Une erreur est survenue.');
                    }
                }
            }));
        });
    </script>

</x-layoutdash2>
