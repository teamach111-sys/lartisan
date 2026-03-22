@props(['conversation'])

<div class="p-4 h-18 border-b flex items-center justify-between">
    <div class="flex justify-between gap-4 items-center">
        <div class="flex justify-center items-center gap-4">
            <img class="h-12 w-12 object-cover rounded-full"
                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQUkfhPYftlUZIJWrKFo6hyc2e5TwZFhphHJQ&s"
                alt="">
            <div>
                <div class="flex gap-2">
                    <h2>John Doe</h2>
                    <p>Pour Produit</p>



                </div>
                <p class="text-green-500">En Ligne</p>
            </div>

        </div>




    </div>

    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 cursor-pointer">
        <path fill-rule="evenodd"
            d="M10.5 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm0 6a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z"
            clip-rule="evenodd" />
    </svg>
</div>

<div class="p-4 h-[calc(100%-9.5rem)] border-b w-full overflow-y-auto bg-gray-50 flex flex-col gap-4">
    @include('components.etranger')

    @include('components.authmessage')

</div>

<div class="p-4 relative flex items-center h-[5rem]">
  @include('components.send')
</div>
