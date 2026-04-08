<?php

namespace App\Http\Controllers;

use App\Models\Recenzija;
use App\Models\DetaljiNarudzbe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecenzijaController extends Controller
{
    public function store(Request $request, $proizvodId)
    {
        $request->validate([
            'ocjena' => 'required|integer|between:1,5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        $kupljeno = DetaljiNarudzbe::where('Proizvod_ID', $proizvodId)
            ->whereHas('narudzba', function ($query) {
                $query->where('Kupac_ID', Auth::id())
                      ->whereIn('Status', ['Dostavljeno', 'Dovršena']);
            })
            ->exists();

        if (!$kupljeno) {
            return back()->with('error', 'Možete recenzirati samo proizvode koje ste kupili.');
        }

        $vec_postoji = Recenzija::where('user_id', Auth::id())
            ->where('proizvod_id', $proizvodId)
            ->exists();

        if ($vec_postoji) {
            return back()->with('error', 'Već ste ostavili recenziju za ovaj proizvod.');
        }

        Recenzija::create([
            'user_id' => Auth::id(),
            'proizvod_id' => $proizvodId,
            'ocjena' => $request->ocjena,
            'komentar' => $request->komentar,
            'odobrena' => false,
        ]);

        return back()->with('success', 'Vaša recenzija je poslana i čeka odobrenje administratora.');
    }
}
