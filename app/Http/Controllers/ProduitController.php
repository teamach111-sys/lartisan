<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie; // Import your Categorie model
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProduitController extends Controller
{

    public function index()
{
    // 1. Fetch the products from your database
    $produits = Produit::all(); 

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
            'categorie' => 'required|exists:categories,id', // 👈 Crucial: Validates that the ID exists in the categories table
            'ville_produit' => 'required|string|max:255',
            'etat_produit' => 'required|string|max:255',
            'images' => 'required|array|size:5',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Handle the 5 images (Save paths to an array)
        $paths = [];
        foreach ($request->file('images') as $file) {
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
            'categorie_id'  => (int) $request->categorie, // 👈 Force it to be an integer ID
        ];

        // 5. Create the product
        Produit::create($data);

        return redirect()->route('produit.create')->with('success', 'Produit créé avec succès!');
    }
}