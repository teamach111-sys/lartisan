<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie; // Import your Categorie model
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProduitController extends Controller
{


    public function show(Produit $produit)
{
    // Eager load the seller data to display their avatar/name on the product page.
    $produit->load('vendeur');

    // Retrieve other products from the same category (excluding the current one) to display as "Featured".
    $relatedProducts = Produit::where('categorie_id', $produit->categorie_id)
        ->where('id', '!=', $produit->id)
        ->limit(4)
        ->get();

    return view('produit.show', compact('produit', 'relatedProducts'));
}
    public function index()
{
    // 1. Fetch the products from your database
    $produits = Produit::latest()->get();

    // 2. Pass them to the view
    return view('home', compact('produits'));
}
    
    public function create()
    {
        // Pass categories to the view so you can use them in your dropdown
        $categories = Categorie::all();
        return view('produit.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // 1. Validate the data
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'categorie' => 'required|exists:categories,id',
            'ville_produit' => 'required|exists:villes,nom',
            'etat_produit' => 'required|string|max:255',
            'images' => 'required|array|size:5',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Handle the 5 images (Save paths to an array, ensuring order by index)
        $paths = [];
        $files = $request->file('images');
        ksort($files); // Ensure order 0, 1, 2, 3, 4
        foreach ($files as $file) {
            $paths[] = $file->store('produits', 'public');
        }

        // 3. Generate a UNIQUE Slug
        $slug = Str::slug($request->titre);
        $originalSlug = $slug;
        $count = 1;
        while (Produit::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // 4. Prepare data for insertion
        $data = [
            'titre'         => $validated['titre'],
            'prix'          => $validated['prix'],
            'description'   => $validated['description'],
            'ville_produit' => $validated['ville_produit'],
            'etat_produit'  => $validated['etat_produit'],
            'images'        => $paths, // Cast to array in Model
            'slug'          => $slug,
            'vendeur_id'    => auth()->id(),
            'categorie_id'  => (int) $request->categorie,
            'telephone_visible' => auth()->user()->display_phone,
        ];

        // 5. Create the product
        Produit::create($data);

        return redirect()->route('annonces')->with('success', 'Produit créé avec succès!');
    }

    public function edit(Produit $produit)
    {
        // Security check
        if ($produit->vendeur_id !== auth()->id()) {
            abort(403);
        }

        $categories = Categorie::all();
        $villes = \App\Models\Ville::all();
        return view('produit.edit', compact('produit', 'categories', 'villes'));
    }

    public function update(Request $request, Produit $produit)
    {
        // 1. Security check
        if ($produit->vendeur_id !== auth()->id()) {
            abort(403);
        }

        // 2. Validate the data
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'categorie' => 'required|exists:categories,id',
            'ville_produit' => 'required|exists:villes,nom',
            'etat_produit' => 'required|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 3. Handle Images if provided (Individual replacement logic)
        if ($request->hasFile('images')) {
            $currentPaths = $produit->images;
            $newFiles = $request->file('images');
            
            foreach ($newFiles as $index => $file) {
                // Replace specific index
                if (isset($currentPaths[$index])) {
                    $currentPaths[$index] = $file->store('produits', 'public');
                } else {
                    $currentPaths[] = $file->store('produits', 'public');
                }
            }
            $produit->images = $currentPaths;
        }

        // 4. Update Slug if title changed
        if ($validated['titre'] !== $produit->titre) {
            $slug = Str::slug($validated['titre']);
            $originalSlug = $slug;
            $count = 1;
            while (Produit::where('slug', $slug)->where('id', '!=', $produit->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            $produit->slug = $slug;
        }

        // 5. Update other fields and reset moderation
        $produit->titre = $validated['titre'];
        $produit->prix = $validated['prix'];
        $produit->description = $validated['description'];
        $produit->ville_produit = $validated['ville_produit'];
        $produit->etat_produit = $validated['etat_produit'];
        $produit->categorie_id = (int) $request->categorie;
        // telephone_visible is now handled via global profile setting, preserving existing record value if any
        $produit->telephone_visible = auth()->user()->display_phone;
        $produit->etat_moderation = 'en_attente'; // Reset moderation on edit

        $produit->save();

        return redirect()->route('annonces')->with('success', 'Annonce mise à jour avec succès !');
    }

    public function destroy(Produit $produit)
    {
        // 1. Security Check: Only the owner can delete their product.
        if ($produit->vendeur_id !== auth()->id()) {
            abort(403);
        }

        // 2. Delete the record
        $produit->delete();

        // 3. Return back with a success message
        return back()->with('success', 'Annonce supprimée avec succès !');
    }

    /**
     * Toggles the favorite status of a product for the authenticated user.
     */
    public function toggleFavorite(Produit $produit)
    {
        $user = auth()->user();

        // Prevents users from favoriting their own products.
        if ($produit->vendeur_id === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas ajouter vos propres produits en favoris.');
        }

        // Toggles the relationship record in the 'favoris' pivot table.
        $user->favoris()->toggle($produit->id);

        return back();
    }

    /**
     * Report a product listing.
     */
    public function signaler(Request $request, Produit $produit)
    {
        $request->validate([
            'type_signalement' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
        ]);

        // Prevent duplicate reports
        $exists = \App\Models\SignalementProduit::where('produit_id', $produit->id)
            ->where('utilisateur_id', auth()->id())
            ->exists();

        if ($exists) {
            return back()->with('info', 'Vous avez déjà signalé cette annonce.');
        }

        \App\Models\SignalementProduit::create([
            'produit_id' => $produit->id,
            'produit_nom' => $produit->titre,
            'utilisateur_id' => auth()->id(),
            'type_signalement' => $request->type_signalement,
            'details' => $request->details,
        ]);

        return back()->with('success', 'Merci, votre signalement a été envoyé.');
    }

    /**
     * Request sponsorship for a product.
     */
    public function demanderSponsor(Produit $produit)
    {
        // Only the owner can request
        if ($produit->vendeur_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Set status to en_attente if not already sponsored
        if ($produit->sponsor_status === 'none' || ($produit->sponsor_status === 'approuve' && $produit->sponsored_until < now())) {
            $produit->update([
                'sponsor_status' => 'en_attente',
            ]);
            return back()->with('success', 'Demande de mise en avant envoyée. En attente d\'approbation par le modérateur.');
        }

        return back()->with('info', 'Ce produit fait déjà l\'objet d\'une mise en avant ou d\'une demande en cours.');
    }
}