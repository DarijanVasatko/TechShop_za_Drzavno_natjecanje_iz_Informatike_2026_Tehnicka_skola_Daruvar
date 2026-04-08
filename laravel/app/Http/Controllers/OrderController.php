<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Narudzba;

class OrderController extends Controller
{
    public function index()
    {
 
        $orders = Narudzba::where('Kupac_ID', Auth::id())
            ->with('nacinPlacanja')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Narudzba::with(['detalji.proizvod', 'nacinPlacanja'])
            ->where('Kupac_ID', Auth::id())
            ->where('Narudzba_ID', $id)
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}
