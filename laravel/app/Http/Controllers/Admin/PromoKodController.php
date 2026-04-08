<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoKod;
use Illuminate\Http\Request;

class PromoKodController extends Controller
{

public function index()
    {
        $promoKodovi = PromoKod::latest()->get();
        return view('admin.promo-kodovi.index', compact('promoKodovi'));
    }
 public function store(Request $request)
    {
        $validated = $request->validate([
            'kod' => 'required|unique:promo_kodovi|max:50',
            'tip' => 'required|in:postotak,fiksno',
            'vrijednost' => 'required|numeric|min:0',
            'vrijedi_do' => 'nullable|date|after_or_equal:today', 
            'max_koristenja' => 'nullable|integer|min:1',
            'minimalan_iznos' => 'nullable|numeric|min:0',
        ]);

        PromoKod::create($validated);

        return redirect()->back()->with('success', 'Promo kod je uspješno kreiran!');
    }
}
