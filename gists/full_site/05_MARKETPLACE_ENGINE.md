---
description: L'Artisan Marketplace - Marketplace Engine: Products & Listings
---

# Marketplace Engine

This module is the heart of the platform, managing product discovery, creation, and interaction (Favorites, Reporting, and Sponsoring).

## 1. Product Discovery
### `app/Http/Controllers/HomeController.php`
Handles categorical filtering and keyword search with indexed queries.

```php
public function index(Request $request)
{
    $query = Produit::where('etat_moderation', 'valide');

    if ($request->filled('q')) { $query->where('titre', 'like', '%' . $request->q . '%'); }
    if ($request->filled('cat')) { $query->where('categorie_id', $request->cat); }

    $produits = $query->latest()->paginate(12);

    if ($request->ajax()) {
        return response()->json([
            'html' => view('partials.produits-grid', compact('produits'))->render(),
            'hasNextPage' => $produits->hasMorePages(),
            'nextPageUrl' => $produits->nextPageUrl(),
        ]);
    }

    return view('home', compact('produits'));
}
```

## 2. Listing Creation & Logic
### `app/Http/Controllers/ProduitController.php@store`
Manages the upload of exactly 5 images, client-side compression, unique slug generation, and seller association.

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'prix' => 'required|numeric|min:0',
        'categorie' => 'required|exists:categories,id',
        'images' => 'required|array|size:5',
    ]);

    // Handle 5 images with compression
    $paths = [];
    foreach ($request->file('images') as $file) {
        $paths[] = ImageHelper::compressAndStore($file, 'produits');
    }

    // Unique Slug
    $slug = Str::slug($request->titre);
    $data = array_merge($validated, [
        'images' => $paths,
        'slug' => $slug,
        'vendeur_id' => auth()->id(),
    ]);

    Produit::create($data);
    return redirect()->route('annonces')->with('success', 'Annonce publiée !');
}
```

## 3. Interaction Logic: Favorites & Sponsoring
### `app/Http/Controllers/ProduitController.php`
- **Favorites**: Uses a simple pivot table `toggle()` to manage user wishlists.
- **Reporting**: Allows users to flag inappropriate content for human moderation.
- **Sponsoring**: Lets sellers request "Featured" placement for their artisanal products.

```php
public function toggleFavorite(Produit $produit)
{
    if ($produit->vendeur_id === auth()->id()) { return back(); }
    auth()->user()->favoris()->toggle($produit->id);
    return back();
}

public function demanderSponsor(Produit $produit)
{
    $produit->update(['sponsor_status' => 'en_attente']);
    return back()->with('success', 'Demande de mise en avant envoyée.');
}
```

## 4. Market Views
- **Show Product**: `resources/views/produit/show.blade.php`
- **Create Listing**: `resources/views/produit/create.blade.php`
- **Products Grid**: `resources/views/partials/produits-grid.blade.php`
