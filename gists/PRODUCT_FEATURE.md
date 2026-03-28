---
contents:
  - id: 1
    label: create_categories_table.php
    language: php
  - id: 2
    label: create_produits_table.php
    language: php
  - id: 3
    label: app/Models/Categorie.php
    language: php
  - id: 4
    label: app/Models/Produit.php
    language: php
  - id: 5
    label: app/Http/Controllers/ProduitController.php
    language: php
  - id: 6
    label: app/Http/Controllers/DashController.php
    language: php
  - id: 7
    label: routes/web.php
    language: php
createdAt: 1774641546686
description: null
folderId: null
id: 1774641546686
isDeleted: 0
isFavorites: 0
name: PRODUCT_FEATURE
tags: []
updatedAt: 1774641546686
---

## Fragment: create_categories_table.php
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
```

## Fragment: create_produits_table.php
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendeur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('titre');
            $table->string('slug')->unique()->index();
            $table->text('description')->nullable();
            $table->boolean('telephone_visible')->default(false);
            $table->decimal('prix', 12, 2)->index();
            $table->string('ville_produit', 100)->index();
            $table->json('images')->nullable();
            $table->string('etat_produit')->default('neuf');
            $table->string('etat_moderation')->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
```

## Fragment: app/Models/Categorie.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = ['nom'];

    public function produits()
    {
        return $this->hasMany(Produit::class, 'categorie_id');
    }
}
```

## Fragment: app/Models/Produit.php
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
        'etat_produit',
        'vendeur_id',
    ];

    protected $casts = [
        'images' => 'array',
        'prix'   => 'decimal:2',
    ];

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }
}
```

## Fragment: app/Http/Controllers/ProduitController.php
```php
<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::latest()->get();
        return view('home', compact('produits'));
    }

    public function show(Produit $produit)
    {
        $produit->load('vendeur');
        $relatedProducts = Produit::where('categorie_id', $produit->categorie_id)
            ->where('id', '!=', $produit->id)
            ->limit(4)->get();

        return view('produit.show', compact('produit', 'relatedProducts'));
    }

    public function create()
    {
        $categories = Categorie::all();
        return view('produit.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'        => 'required|string|max:255',
            'prix'         => 'required|numeric|min:0',
            'description'  => 'nullable|string|max:1000',
            'categorie'    => 'required|exists:categories,id',
            'ville_produit'=> 'required|string|max:255',
            'etat_produit' => 'required|string|max:255',
            'images'       => 'required|array|size:5',
            'images.*'     => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $paths = [];
        foreach ($request->file('images') as $file) {
            $paths[] = $file->store('produits', 'public');
        }

        $slug = Str::slug($request->titre);
        $originalSlug = $slug;
        $count = 1;
        while (Produit::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        Produit::create([
            'titre'        => $validated['titre'],
            'prix'         => $validated['prix'],
            'description'  => $validated['description'],
            'ville_produit'=> $validated['ville_produit'],
            'etat_produit' => $validated['etat_produit'],
            'images'       => $paths,
            'slug'         => $slug,
            'vendeur_id'   => auth()->id(),
            'categorie_id' => (int) $request->categorie,
        ]);

        return redirect()->route('produit.create')->with('success', 'Produit créé avec succès!');
    }
}
```

## Fragment: app/Http/Controllers/DashController.php
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashController extends Controller
{
    public function annonces()
    {
        $userProduits = auth()->user()->produits()->latest()->get();
        return view('annonces', compact('userProduits'));
    }
}
```

## Fragment: routes/web.php
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\DashController;

// Public Home
Route::get('/', [ProduitController::class, 'index'])->name('home');

// Dashboard Route
Route::get('/annonces', [DashController::class, 'annonces'])->name('annonces')->middleware('auth');

// Note: /produit/create MUST be defined BEFORE /produit/{produit:slug}
Route::get('/produit/create', [ProduitController::class, 'create'])->name('produit.create')->middleware('auth');
Route::post('/produit/store', [ProduitController::class, 'store'])->name('produit.store')->middleware('auth');

Route::get('/produit/{produit:slug}', [ProduitController::class, 'show'])->name('produit.show');
```

