---
description: L'Artisan Marketplace - Dashboard Responsive Paddings & Admin Table Enhancements
---

# Dashboard Layout & Admin Table Enhancements

This document logs recent refinements involving mobile-responsive layout consistency applied across the global dashboard skeleton and an administrative data improvement for product tracking.

## 1. Matching Global Paddings for Desktop & Mobile

To ensure a seamless transition visually when a user navigates between the public home page and their private dashboard, identical grid padding structures were synchronized.

The home page layout uses an external wrapper spacing approach: `mx-3` (12px margin) for mobile, switching to `lg:mx-14` for desktop browsers.

These exact spacing values were mathematically duplicated as inside paddings (`px-*`) for the dashboard `<main>` container and layout wrapper `layoutdash.blade.php`:

### `resources/views/components/layoutdash.blade.php` 
Padding replaced the hardcoded `p-7`.
```html
<div class="px-3 md:px-7 lg:px-14 gap-2 flex flex-col h-33">
    <!-- Top Dashboard Header items -->
</div>

<!-- Main Content Blade Slot -->
<main class="px-3 py-7 md:px-7 lg:px-14">
    {{ $slot }}
</main>
```

### `resources/views/produit/show.blade.php` 
Alignment padding on mobile fixed to reflect the core `div` container.
```html
<x-layout>
    <div class="flex flex-col lg:w-2/3 w-full mx-auto h-full overflow-y-auto py-9 px-3 md:px-7 lg:px-0">
        <!-- Product View Contents -->
    </div>
</x-layout>
```

## 2. Vendor Telephone Added to Filament Admin Table

In order to facilitate quicker offline resolution or contacting of artisans concerning their products/inventory, the seller's telephone number is now queryable directly from the Filament `Produits` Resource table.

By default, to avoid column-crowding on small laptop screens, the column state is tucked away via `isToggledHiddenByDefault: true`. Admins can dynamically manifest the column using Filament's internal view toggle menu.

### `app/Filament/Resources/Produits/Tables/ProduitsTable.php`
```php
TextColumn::make('vendeur.name')
    ->label('Vendeur')
    ->searchable()
    ->sortable()
    ->toggleable(isToggledHiddenByDefault: true),

TextColumn::make('vendeur.telephone')
    ->label('Téléphone')
    ->searchable()
    ->sortable()
    ->toggleable(isToggledHiddenByDefault: true),
```
