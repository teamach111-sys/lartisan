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
    <div x-data="messaging({{ auth()->id() }})" class="flex flex-col h-full overflow-hidden gap-6">
        {{-- Horizontal Contacts List --}}
        <div class="w-full" :class="currentConversation ? 'hidden md:block' : 'block'">
            <div class="flex items-center gap-6 mb-4 px-2">
                <h3 class="text-xs font-bold uppercase opacity-50 tracking-widest">Contacts</h3>
                <div class="relative w-48 group">
                    <input type="text" x-model="searchQuery" placeholder="Rechercher..."
                        class="w-full pl-8 pr-3 py-1.5 text-xs bg-gray-50 border border-gray-100 rounded-full focus:outline-none focus:border-[#FF8E72] focus:bg-white transition-all text-gray-800 placeholder:opacity-50">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="size-4 absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF8E72] transition-colors">
                        <path fill-rule="evenodd"
                            d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                <template x-for="conv in filteredConversations" :key="conv.id">
                    <div @click="selectConversation(conv)"
                        :class="currentConversation?.id === conv.id ? 'border-[#FF8E72] bg-white ring-1 ring-[#FF8E72]' :
                            'bg-white'"
                        class="flex items-center gap-4 p-3 rounded-sm border cursor-pointer transition-all min-w-[220px] relative group shadow-sm hover:shadow-md">

                        <div class="relative">
                            <img class="h-12 w-12 object-cover rounded-full border border-gray-100 shadow-sm"
                                :src="conv.partner_pfp" alt="">

                            {{-- Status Dot --}}
                            <span :class="conv.is_online ? 'bg-green-500' : 'bg-gray-400'"
                                class="absolute bottom-0 right-0 h-3.5 w-3.5 rounded-full border border-white"></span>

                            <template x-if="conv.unread_count > 0">
                                <span
                                    class="absolute -top-1 -right-1 bg-[#FF8E72] text-white text-[10px] font-bold h-5 w-5 rounded-full flex items-center justify-center border border-white"
                                    x-text="conv.unread_count"></span>
                            </template>
                        </div>

                        <div class="flex-1 overflow-hidden">
                            <div class="flex items-center justify-between">
                                <h2 class="font-bold text-sm truncate"
                                    :class="currentConversation?.id === conv.id ? 'text-[#FF8E72]' : 'text-gray-800'"
                                    x-text="conv.partner_name"></h2>
                            </div>
                            <p class="text-[10px] uppercase opacity-60 font-bold truncate" x-text="conv.produit_nom">
                            </p>
                            <p class="text-[10px] opacity-40 truncate" x-text="conv.latest_time"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Desktop Conversation area (Visible only on MD+) --}}
        <div class="hidden md:flex flex-1 bg-white flex-col border border-gray-200 shadow-sm relative overflow-hidden rounded-sm">
            <template x-if="currentConversation">
                <div class="h-full flex flex-col">
                    @include('partials.chat-content')
                </div>
            </template>
            <template x-if="!currentConversation">
                <div class="h-full flex flex-col items-center justify-center text-center p-8 bg-gray-50/50">
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center gap-4">
                        <div class="bg-[#FF8E72]/10 p-5 rounded-full text-[#FF8E72]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold uppercase tracking-tighter text-gray-800">Sélectionnez une conversation</h3>
                        <p class="text-sm font-medium text-gray-500 max-w-[250px]">Cliquez sur un artisan en haut pour commencer l'aventure !</p>
                    </div>
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
