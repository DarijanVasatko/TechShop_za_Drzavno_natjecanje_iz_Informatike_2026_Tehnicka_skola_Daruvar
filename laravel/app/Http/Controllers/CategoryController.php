<?php

namespace App\Http\Controllers;

use App\Models\Kategorija;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $kategorije = Kategorija::all();
        return view('category', compact('kategorije'));
    }
}
