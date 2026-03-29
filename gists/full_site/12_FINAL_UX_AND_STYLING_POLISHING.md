---
description: L'Artisan Marketplace - Final UX & Styling Polishing
---

# Final UX & Styling Polishing

This document outlines the final user experience and styling adjustments made to the platform, specifically targeting responsive design, mobile layout edge cases, and active state visual cues.

## 1. Product Title Truncation & Mobile Re-ordering

To prevent extremely long titles from overflowing or breaking the grid, dynamic tracking and `maxlength` were applied at both the creation phase and rendering phase.

### `resources/views/produit/create.blade.php` & `edit.blade.php`
Added `maxlength="60"` to the title input fields to naturally guide artisans toward concise titles.
```html
<label class="font-bold text-sm" for="titre">Titre de l'annonce</label>
<input name="titre" id="titre" value="{{ old('titre') }}" maxlength="60" 
       class="focus:shadow-[0_0_0_2px_#fb663f] outline-none bg-white border border-black rounded-sm h-12 px-4 transition-all duration-200 hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]" 
       type="text" placeholder="Ex: Plat en céramique peint à la main">
```

### `resources/views/produit/show.blade.php`
Re-flowed the header specifically for mobile devices so the city and timestamp wrap beneath the title seamlessly without breaking margins.
```html
{{-- Title --}}
<div class="bg-white p-5 lg:p-6 border-b flex justify-between items-start md:items-center flex-col md:flex-row gap-2 md:gap-0">
    <h1 class="text-2xl font-bold break-words w-full md:max-w-[70%] leading-tight">{{ $produit->titre }}</h1>
    <span class="px-3 py-1 bg-gray-100 border border-black/10 rounded-sm text-sm font-bold shrink-0">{{ $produit->ville_produit }} • {{ $produit->created_at->diffForHumans() }}</span>
</div>
```

## 2. Dynamic Sponsorship Countdown 

In the dashboard, artisans can now view exactly how much time is left on their sponsored products down to the minute.

### `resources/views/components/mylistings.blade.php`
```html
@elseif ($produit->sponsor_status === 'approuve' && $produit->sponsored_until && \Carbon\Carbon::parse($produit->sponsored_until)->isFuture())
    @php
        $diff = \Carbon\Carbon::parse($produit->sponsored_until)->diff(now());
        $hours = ($diff->days * 24) + $diff->h;
        $minutes = $diff->i;
    @endphp
    <div class="flex flex-col gap-1 items-end">
        <div class="bg-purple-600 border border-black shadow-[2px_2px_0px_0px_#000000] text-white text-xs font-black px-2.5 py-1 uppercase rounded-sm">
            Sponsorisé
        </div>
        <div class="bg-white border border-black shadow-[2px_2px_0px_0px_#000000] text-purple-600 text-[10px] font-black px-2 py-0.5 uppercase rounded-sm">
            {{ $hours }}h {{ $minutes }}m restants
        </div>
    </div>
@endif
```

## 3. Mobile Navigation Exact Active Styling

By default, SVG elements using `stroke="currentColor"` will adopt the text color of their parent anchor tag. In the mobile navigation menu, the active page's text lights up orange (`#FF8E72`). To ensure only the *text* highlights while the *icons* remain white, hard-coded `stroke="white"` and `fill="white"` declarations were injected directly into all mobile dashboard SVGs.

### `resources/views/components/layoutdash.blade.php` (Mobile Variant)
```html
<!-- Example of active route detection targeting only the text -->
<a href="{{ route('annonces') }}"
    class="gap-4 flex px-6 py-4 border-b border-white/33 hover:bg-white/5 {{ request()->routeIs('annonces') ? 'text-[#FF8E72]' : 'text-white' }}">

    <!-- Hardcoding stroke to white to prevent currentColor inheritance -->
    <svg data-slot="icon" fill="none" class="size-5" stroke-width="1.5" stroke="white"
        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6... ">
        </path>
    </svg>
    Mes Annonces
</a>
```

## 4. Re-styled Empty Favorites State

The massive grey heart SVG was removed in favor of purely typographic Neobrutalist design. The call-to-action button was converted from `px-8` hardcoded padding to `w-full md:w-auto` to prevent horizontally shrinking out-of-bounds text on narrow mobile screens.

### `resources/views/favoris.blade.php`
```html
@else
    <div class="col-span-full flex flex-col items-center justify-center gap-4 bg-white w-full rounded-sm border border-black p-4 md:p-8 text-center transition-all duration-300 mt-4 md:mt-0">
        <p class="text-black font-black text-xl md:text-2xl">Vous n'avez pas encore de favoris.</p>
        <p class="text-gray-500 max-w-sm mx-auto font-medium text-sm md:text-base">Parcourez le marché et enregistrez vos créations artisanales préférées.</p>
        <a href="{{ route('home') }}" class="mt-4 bg-[#FF8E72] w-full md:w-auto flex items-center justify-center rounded-sm h-14 px-4 md:px-8 border border-black cursor-pointer transition-all duration-200 hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_#000000] active:translate-x-0 active:translate-y-0 active:shadow-none font-black uppercase text-xs md:text-sm tracking-widest text-center whitespace-normal md:whitespace-nowrap">
            Explorer le marché
        </a>
    </div>
@endif
```
