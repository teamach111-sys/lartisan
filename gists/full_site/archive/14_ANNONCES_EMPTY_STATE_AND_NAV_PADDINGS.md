---
description: L'Artisan Marketplace - Annonces Empty State & Mobile Navbar Alignment
---

# Final UI Alignment & Empty States

This document details the final pixel-perfect adjustments made to the platform, strictly ensuring zero responsive discrepancies across dashboard views and consistent Neobrutalist empty states.

## 1. Top Navbar Mobile Margin Alignment

While the inner dashboard content was accurately aligned to `px-3` (matching the home page's `mx-3` 12px margin), the topmost black mobile navigation bar (housing the logo and hamburger menu) was initially drifting inward due to a hardcoded `px-7`. It was subsequently corrected to match the overarching responsive outer shell grid.

### `resources/views/components/layoutdash.blade.php` (Mobile Nav Header)
```html
{{-- Before: class="px-7" || After: class="px-3 md:px-7" --}}
<nav x-data="{ open: false }" class="relative w-full lg:hidden h-16 bg-black flex justify-between items-center px-3 md:px-7">
    <a href="{{ route('home') }}">
        <img class="filter invert h-10" src="{{ asset('imgs/logo.svg') }}" alt="L'Artisan Logo">
    </a>
    
    <p class="text-white text-lg">Dashboard</p>
    
    <!-- Menu button ... -->
</nav>
```

## 2. Annonces Uniform Neobrutalist Empty State

To create visual cohesiveness across the marketplace tabs, the "Annonces" empty state layout block was strictly refactored to clone the Neobrutalist styling of the "Mes Favoris" view. 

It now employs identical `<p>` font hierarchies, fixed `border-black` strokes instead of default dashed grays, and utilizes fully responsive mobile button sizing (`w-full md:w-auto h-14`). Additionally, the string messaging intelligently switches out header content depending on the sorting index chosen.

### `resources/views/annonces.blade.php` (Empty State Array)
```html
@empty
    <div class="col-span-full flex flex-col items-center justify-center gap-4 bg-white w-full rounded-sm border border-black p-4 md:p-8 text-center transition-all duration-300 mt-4 md:mt-0">
        
        @if ($filter === 'valide')
            <p class="text-black font-black text-xl md:text-2xl">Aucune annonce active.</p>
            <p class="text-gray-500 max-w-sm mx-auto font-medium text-sm md:text-base">Vous n'avez pas d'annonces en ligne pour le moment. Créez-en une maintenant.</p>
        @elseif ($filter === 'en_attente')
            <p class="text-black font-black text-xl md:text-2xl">Aucune annonce en attente.</p>
            <p class="text-gray-500 max-w-sm mx-auto font-medium text-sm md:text-base">Vos annonces ont toutes été traitées par notre équipe de modération.</p>
        @elseif ($filter === 'rejete')
            <p class="text-black font-black text-xl md:text-2xl">Aucune annonce rejetée.</p>
            <p class="text-gray-500 max-w-sm mx-auto font-medium text-sm md:text-base">Excellent travail, aucune de vos créations n'a été refusée.</p>
        @elseif ($filter === 'sponsorise')
            <p class="text-black font-black text-xl md:text-2xl">Aucune annonce sponsorisée.</p>
            <p class="text-gray-500 max-w-sm mx-auto font-medium text-sm md:text-base">Mettez en avant vos produits pour atteindre plus de passionnés d'artisanat.</p>
        @else
            <p class="text-black font-black text-xl md:text-2xl">Aucune annonce trouvée.</p>
            <p class="text-gray-500 max-w-sm mx-auto font-medium text-sm md:text-base">Vous n'avez pas encore créé d'annonces. Commencez à vendre dès aujourd'hui.</p>
        @endif

        <button onclick="window.location.href='{{ route('produit.create') }}'"
            class="mt-4 bg-[#FF8E72] w-full md:w-auto flex items-center justify-center rounded-sm h-14 px-4 md:px-8 border border-black cursor-pointer transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] active:translate-x-0 active:translate-y-0 active:shadow-none font-black uppercase text-xs md:text-sm tracking-widest text-center whitespace-normal md:whitespace-nowrap">
            Ajouter une annonce
        </button>
    </div>
@endforelse
```
