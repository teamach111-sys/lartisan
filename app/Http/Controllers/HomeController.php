<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Produit::where('etat_moderation', 'valide');

        // Recherche par texte
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%');
            });
        }

        // Filtre par catégorie
        if ($request->filled('cat')) {
            $query->where('categorie_id', $request->cat);
        }

        // Filtre par ville
        if ($request->filled('ville')) {
            $query->where('ville_produit', $request->ville);
        }

        // Filtre par prix min
        if ($request->filled('min')) {
            $query->where('prix', '>=', $request->min);
        }

        // Filtre par prix max
        if ($request->filled('max')) {
            $query->where('prix', '<=', $request->max);
        }

        // Tri
        if ($request->sort === 'prix_bas') {
            $query->orderBy('prix', 'asc');
        } elseif ($request->sort === 'prix_haut') {
            $query->orderBy('prix', 'desc');
        } else {
            $query->latest();
        }

        $produits = $query->get();
        
        $isSearching = $request->filled('q');
        $isFiltering = $request->anyFilled(['q', 'cat', 'ville', 'min', 'max', 'sort']);

        // Dynamically calculate the title based on search/filters
        $filterTitle = 'Dans le marché';
        if ($isSearching) {
            $filterTitle = 'Résultats de recherche pour "' . $request->q . '"';
        } elseif ($request->filled('cat')) {
            $catName = \App\Models\Categorie::find($request->cat)?->nom;
            $filterTitle = $catName ? 'Articles en ' . $catName : 'Résultats par catégorie';
        } elseif ($isFiltering) {
            $filterTitle = 'Résultats de recherche';
        }

        // Fetch Sponsored Products
        $sponsoredProducts = Produit::where('sponsor_status', 'approuve')
            ->where('sponsored_until', '>', now())
            ->where('etat_moderation', 'valide')
            ->latest()
            ->take(8)
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.produits-grid', compact('produits'))->render(),
                'isSearching' => $isSearching,
                'isFiltering' => $isFiltering,
                'filterTitle' => $filterTitle,
            ])->header('Vary', 'X-Requested-With');
        }

        return response()->view('home', compact('produits', 'sponsoredProducts', 'isFiltering', 'isSearching', 'filterTitle'))
            ->header('Vary', 'X-Requested-With');
    }
}
