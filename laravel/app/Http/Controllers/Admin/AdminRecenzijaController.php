<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recenzija;

class AdminRecenzijaController extends Controller
{
    public function index()
    {
        $recenzije = Recenzija::with(['user', 'proizvod'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.recenzije.index', compact('recenzije'));
    }

    public function approve(Recenzija $recenzija)
    {
        $recenzija->update(['odobrena' => true]);

        return back()->with('success', 'Recenzija je odobrena.');
    }

    public function reject(Recenzija $recenzija)
    {
        $recenzija->delete();

        return back()->with('success', 'Recenzija je odbačena.');
    }
}
