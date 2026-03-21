<x-layoutdash>
    <x-slot:title>
        Annonces
    </x-slot:title>
    <x-slot:topbar>
<div class="pl-7 gap-2 flex flex-col pr-7">
    <div class="flex justify-between">
        <h1 class="text-[23px]">
                    Mes annonces
                 </h1>
        <button
                           onclick="window.location.href='{{ route('produit.create') }}'" class="text-[15px] md:hidden  bg-white rounded-sm h-auto p-2 border cursor-pointer transition-all duration-200 hover:bg-[#FF8E72] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">
                            Ajouter une annonce
                        </button>

    </div>
                
                <div class="flex md:flex-row md:justify-between md:items-center flex-col pb-1">
                    <div class="h-15 flex items-center gap-2 overflow-x-auto snap-x snap-mandatory scroll-smooth ">
                        <a href=""
                            class="    flex-shrink-0 snap-center border  cursor-pointer text-[15px]  rounded-[50px] p-2 transition-all duration-200  ">Nouvelles
                            Annonces</a>
                        <a href=""
                            class="    flex-shrink-0 snap-center border  cursor-pointer text-[15px] hover:border-black   border-transparent rounded-[50px] p-2 transition-all duration-200 ">Prix
                            bas
                        </a>

                    </div>
                    <div>
                        <button
                            class="text-[13px] hidden md:block mx-auto bg-white rounded-sm h-auto p-2 border cursor-pointer transition-all duration-200 hover:bg-[#FF8E72] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">
                            Ajouter une annonce
                        </button>
                    </div>
                </div>




            </div>







    </x-slot:topbar>













 <div
                    class="flex flex-col items-center justify-center gap-3 bg-white w-full h-70 rounded-md border-dashed border p-4">
                    <p>Ilya pas des annonce , ajouter </p>
                    <button
                     onclick="window.location.href='{{ route('produit.create') }}'"   class="mx-auto bg-[#FF8E72] rounded-sm h-11 p-2 border cursor-pointer transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">
                        Ajouter une annonce
                    </button>


                </div>
            </x-layoutdash>