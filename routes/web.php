<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashController;


Route::get('/', function () {
    return view('home');
});

Route::get('/register', [AuthController::class, 'create'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'store'])->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post')->middleware('guest');

route::get('/annonces', [DashController::class, 'annonces'])->name('annonces')->middleware('auth');

route::get('/home', function () {
    return view('home');
})->name('home');
