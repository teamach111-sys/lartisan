---
contents:
  - id: 1
    label: app/Models/Produit.php
    language: php
  - id: 2
    label: app/Models/Categorie.php
    language: php
  - id: 3
    label: app/Http/Controllers/ProduitController.php
    language: php
  - id: 4
    label: app/Http/Controllers/HomeController.php
    language: php
  - id: 5
    label: app/Http/Controllers/DashController.php
    language: php
  - id: 6
    label: resources/views/home.blade.php
    language: blade
  - id: 7
    label: resources/views/produit/show.blade.php
    language: blade
  - id: 8
    label: resources/views/produit/create.blade.php
    language: blade
  - id: 9
    label: resources/views/produit/edit.blade.php
    language: blade
  - id: 10
    label: resources/views/annonces.blade.php
    language: blade
  - id: 11
    label: resources/views/favoris.blade.php
    language: blade
  - id: 12
    label: resources/views/components/produit.blade.php
    language: blade
  - id: 13
    label: resources/views/components/mylistings.blade.php
    language: blade
  - id: 14
    label: resources/views/partials/produits-grid.blade.php
    language: blade
createdAt: 1774830000000
description: Core marketplace engine including product listings, search/filter system, favorites, and seller dashboard.
folderId: null
id: 1774830000000
isDeleted: 0
isFavorites: 0
name: 04_MARKETPLACE_SYSTEM
tags: []
updatedAt: 1774830000000
---

## Fragment: app/Models/Produit.php
# This file is used to represent artisanal products, managing attributes like price, images, moderation status, and sponsorship.
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produit extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'categorie_id',
        'titre', 
        'slug', 
        'description', 
        'images', 
        'prix', 
        'ville_produit', 
        'telephone_visible',
        'etat_produit',
        'etat_moderation',
        'vendeur_id',
        'sponsor_status',
        'sponsored_until'
    ];

    protected $casts = [
        'images' => 'array',
        'prix' => 'decimal:2',
        'sponsored_until' => 'datetime'
    ];

    public function vendeur() {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function categorie() {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }
}
```

## Fragment: app/Models/Categorie.php
# This file is used to categorize products into logical groups like "Céramique", "Tapis", etc.
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'slug'];

    public function produits() {
        return $this->hasMany(Produit::class, 'categorie_id');
    }
}
```

## Fragment: app/Http/Controllers/ProduitController.php
# This file is used to manage individual product lifecycles: showing details, creating/updating listings, and handling favorites.
```php
<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProduitController extends Controller
{
    public function show(Produit $produit)
    {
        $produit->load('vendeur');
        $relatedProducts = Produit::where('categorie_id', $produit->categorie_id)->where('id', '!=', $produit->id)->limit(4)->get();
        return view('produit.show', compact('produit', 'relatedProducts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'categorie' => 'required|exists:categories,id',
            'ville_produit' => 'required|exists:villes,nom',
            'etat_produit' => 'required|string|max:255',
            'images' => 'required|array|size:5',
        ]);

        $paths = [];
        foreach ($request->file('images') as $file) {
            $paths[] = $file->store('produits', 'public');
        }

        $slug = Str::slug($request->titre);
        // ... slug uniqueness logic ...

        Produit::create([
            'titre' => $validated['titre'],
            'prix' => $validated['prix'],
            'description' => $validated['description'],
            'ville_produit' => $validated['ville_produit'],
            'etat_produit' => $validated['etat_produit'],
            'images' => $paths,
            'slug' => $slug,
            'vendeur_id' => auth()->id(),
            'categorie_id' => (int) $request->categorie,
            'telephone_visible' => auth()->user()->display_phone,
        ]);

        return redirect()->route('annonces')->with('success', 'Produit créé avec succès!');
    }
    
    // ... update, destroy, toggleFavorite, signaler methods ...
}
```

## Fragment: app/Http/Controllers/HomeController.php
# This file is used to power the marketplace landing page, handling complex multi-criteria filtering and AJAX updates.
```php
<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Produit::where('etat_moderation', 'valide');

        if ($request->filled('q')) {
            $query->where('titre', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('cat')) {
            $query->where('categorie_id', $request->cat);
        }
        if ($request->filled('ville')) {
            $query->where('ville_produit', $request->ville);
        }

        $produits = $query->latest()->get();
        
        // ... sponsored products logic ...

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.produits-grid', compact('produits'))->render(),
                // ... meta data ...
            ]);
        }

        return view('home', compact('produits'));
    }
}
```

## Fragment: app/Http/Controllers/DashController.php
# This file is used to manage the seller's specific dashboard views for their own ads and saved favorites.
```php
<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class DashController extends Controller
{
    public function annonces(Request $request) {
        $filter = $request->query('status', 'tous');
        $query = Produit::where('vendeur_id', auth()->id())->latest();
        
        // ... status filtering logic ...
        
        $userProduits = $query->get();
        return view('annonces', compact('userProduits', 'filter'));
    }

    public function favoris(Request $request) {
        $sort = $request->query('sort', 'latest');
        $items = auth()->user()->favoris()->latest()->get();
        return view('favoris', compact('items', 'sort'));
    }
}
```

## Fragment: resources/views/home.blade.php
# This file is used as the primary marketplace entry point, containing the interactive filter sidebar and sponsored slider.
```blade
<!-- Complete home.blade.php content -->
<nav> <!-- Search and Category Bar --> </nav>
<section id="sponsored"> <!-- Horizontal Carousel --> </section>
<main>
    <aside> <!-- Multi-criteria Filter Form --> </aside>
    <div id="product-grid"> <!-- AJAX Loaded Results --> </div>
</main>
<script> <!-- AJAX and Filter Logic --> </script>
```

## Fragment: resources/views/produit/show.blade.php
# This file is used to display the full details of an artisanal product including image gallery and contact options.
```blade
<!-- Complete show.blade.php content -->
<div> <!-- Image Gallery with dots navigation --> </div>
<div> <!-- Title, Price, Seller Profile, and Description --> </div>
<div> <!-- Action Buttons: Chat, Call, Favorite, Share, Report --> </div>
```

## Fragment: resources/views/produit/create.blade.php
# This file is used to provide the multi-step product listing form with individual image previews.
```blade
<!-- Complete create.blade.php content -->
<form action="{{ route('produit.store') }}" method="POST" enctype="multipart/form-data">
    <!-- Basic Info, Technical Details (City, Condition, Category), and 5 Image Slots -->
</form>
```

## Fragment: resources/views/produit/edit.blade.php
# This file is used to allow sellers to modify existing listings, resetting moderation status automatically on update.
```blade
<!-- Complete edit.blade.php content -->
<form action="{{ route('produit.update', $produit) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    <!-- Editable fields and Individual Image Replacement logic -->
</form>
```

## Fragment: resources/views/annonces.blade.php
# This file is used to display the seller's personalized management dashboard for their own listings.
```blade
<!-- Complete annonces.blade.php content -->
<x-slot:customFilters> <!-- All, Active, Pending, Rejected, Sponsored --> </x-slot:customFilters>
<div class="grid"> <!-- List of mylistings components --> </div>
```

## Fragment: resources/views/favoris.blade.php
# This file is used to display the user's saved wishlist products.
```blade
<!-- Complete favoris.blade.php content -->
<x-slot:customFilters> <!-- Sorting: Latest/Oldest --> </x-slot:customFilters>
<div class="grid"> <!-- List of product components --> </div>
```

## Fragment: resources/views/components/produit.blade.php
# This file is used as the standard reusable card for displaying products across the entire marketplace.
```blade
@props(['produit'])
<a href="{{ route('produit.show', $produit->slug) }}">
    <!-- First Image, Title, Seller Avatar, Info, and Price Badge -->
</a>
```

## Fragment: resources/views/components/mylistings.blade.php
# This file is used as an administrative product card with Edit/Delete/Sponsor actions and moderation status badges.
```blade
@props(['produit'])
<div>
    <!-- Action Icons (Trash, Pencil, Star), Moderation Status Badges, and Info -->
</div>
```

## Fragment: resources/views/partials/produits-grid.blade.php
# This file is used as a reusable partial to render a collection of product cards, often fetched via AJAX.
```blade
@forelse ($produits as $produit) 
    <x-produit :produit="$produit" />
@empty
    <!-- Empty state message -->
@endforelse
```
