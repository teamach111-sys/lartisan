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
                            class="text-[15px]  md:hidden  bg-white rounded-sm h-auto p-2 border cursor-pointer transition-all duration-200 hover:bg-[#FF8E72] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">
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
                            class="text-[15px]  hidden md:block mx-auto bg-white rounded-sm h-auto p-2 border cursor-pointer transition-all duration-200 hover:bg-[#FF8E72] hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000]">
                            Ajouter une annonce
                        </button>
                    </div>
                </div>




            </div>







    </x-slot:topbar>













 
                    



                <form action="{{ route('produit.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-2">
                    @csrf
                    @if ($errors->any())
                        <div class="col-span-2 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <div class="flex-col flex md:flex-row  gap-3">

<div class="flex flex-col gap-2 flex-1">
<div  class="flex flex-col">
                        <label class="text-[17px]" for="nom">Titre</label>
                        <input name="titre" class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[17px] w-full" type="text" id="nom"
                            >

                    </div>
                    <div class="flex flex-col">
                        <label class="text-[17px]" for="description">Description</label>
                        <textarea name="description" class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm text-[17px] w-full" rows="4" id="description"></textarea>

                    </div>
                    <div  class="flex flex-col">
                        <label class="text-[17px]" for="prix">Prix</label>
                        <input name="prix" class="focus:text-[17px] focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[17px] w-full" type="number" id="prix"
                            >

                    </div>
                    <div class=" flex gap-2 hidden lg:flex">
                                        <BUTTON type="submit" class="transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer mt-3 bg-black text-white h-13 rounded-sm w-full">Ajouter le produit</BUTTON>
                    <BUTTON onclick="window.location.href='{{ route('annonces') }}'" type="reset" class="transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer mt-3 bg-black text-white h-13 rounded-sm w-full ">Annuler</BUTTON>


                   </div>
                     </div>
                      
                    <div class="flex flex-col gap-2 flex-1">
                         <div  class="flex flex-col">
                        <label class="text-[17px]" for="categorie">Categorie</label>
                        <select class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[17px] w-full" name="categorie" id="categorie">
                            <option class="" value="">Sélectionnez une categorie</option>
                            
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                            @endforeach
                        </select>

                        </div>
                        <div  class="flex flex-col">
                        <label class="text-[17px]" for="ville_produit">Ville de produit</label>
                        <select class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[17px] w-full" name="ville_produit" id="ville_produit">
                            <option class="" value="">Sélectionnez une ville</option>
                            <option value="Marrakech">Marrakech</option>
                        </select>

                        </div>
                        <div  class="flex flex-col">
                        <label class="text-[17px]" for="etat_produit">état de produit</label>
                        <select class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-13 text-[17px] w-full" name="etat_produit" id="etat_produit">
                            <option class="" value="">Sélectionnez une état</option>
                            <option value="premiere_main">premiere main</option>
                        </select>

                        </div>
                     <div  class="flex flex-col">
                        <label class="text-[17px] max-w-42 h-13 flex flex-col items-center justify-center hover:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border rounded-sm h-7" for="photo">5 Photos du produit</label>
                        <input name="images[]" id="photo" class="w-full" type="file" multiple accept="image/*" 
                            >

                    </div>

                     <div class=" flex gap-2 lg:hidden ">
                                        <BUTTON type="submit" class="transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer mt-3 bg-black text-white h-13 rounded-sm w-full">Ajouter le produit</BUTTON>
                    <BUTTON onclick="window.location.href='{{ route('annonces') }}'" type="reset" class="transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:bg-[#FF8E72] hover:text-black hover:shadow-[4px_4px_0px_0px_#000000] cursor-pointer mt-3 bg-black text-white h-13 rounded-sm w-full ">Annuler</BUTTON>


                   </div>
                    </div>


                    </div>
                     
                    
                    
                   
                </form>



















                </x-layoutdash>