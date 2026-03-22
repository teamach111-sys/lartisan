<x-layoutdash2>
    
    <x-slot:title>
        Mes Messages
    </x-slot:title>
    <x-slot:h1>
        Mes Messages
    </x-slot:h1>
    <x-slot:topbar>

    </x-slot:topbar>
    <div class="flex gap-2 h-full">
        <div class="w-2/4 bg-white h-full flex flex-col border rounded-lg overflow-y-auto shadow-md">
            <div class="p-4 h-18 border-b  relative">
                <svg data-slot="icon" fill="currentColor" class="absolute top-6 left-6" viewBox="0 0 16 16" width="20"
                    height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M5.94 8.06a1.5 1.5 0 1 1 2.12-2.12 1.5 1.5 0 0 1-2.12 2.12Z"></path>
                    <path clip-rule="evenodd" fill-rule="evenodd"
                        d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14ZM4.879 4.879a3 3 0 0 0 3.645 4.706L9.72 10.78a.75.75 0 0 0 1.061-1.06L9.585 8.524A3.001 3.001 0 0 0 4.879 4.88Z">
                    </path>
                </svg>
                <input class="pl-9 w-full h-full p-2 border rounded-[50px] " type="text">
            </div>
                @include('components.messageblock')
           

            
           
        </div>
        <div class="w-3/4 bg-white h-full flex flex-col rounded-lg border overflow-hidden shadow-md">
            @include('components.conversation')
        </div>
    </div>


</x-layoutdash2>
