<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashController extends Controller
{
     public function annonces() {
        return view('annonces');
    }
}
