<x-layoutdash>
    <x-slot:title>
        Annonces
    </x-slot:title>
        <x-slot:h1>
            Mes Annonces
        </x-slot:h1>
        <x-slot:btnlocation>
            {{ route('annonces') }}
        </x-slot:btnlocation>
        <x-slot:btnname>
            Ajouter une annonce

        </x-slot:btnname>

        <x-slot:firstc>
            Tous
        </x-slot:firstc>
        <x-slot:secondc>
            Actifs
        </x-slot:secondc>
        <x-slot:mobbtnlocation>
            {{ route('produit.create') }}
        </x-slot:mobbtnlocation>
        <x-slot:mobbtnname>
            Ajouter une annonce
        </x-slot:mobbtnname>
    <x-slot:topbar>








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