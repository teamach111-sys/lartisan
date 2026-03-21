<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashController;
use App\Http\Controllers\ProduitController;
use App\Models\Produit;


Route::get('/', function () {
    $produits = Produit::all();
    return view('home', ['produits' => $produits]);
})->name('home');

Route::get('/register', [AuthController::class, 'create'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'store'])->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post')->middleware('guest');

route::get('/annonces', [DashController::class, 'annonces'])->name('annonces')->middleware('auth');



route::get('/produit/create', [ProduitController::class, 'create'])->name('produit.create')->middleware('auth');
route::post('/produit/store', [ProduitController::class, 'store'])->name('produit.store')->middleware('auth');

