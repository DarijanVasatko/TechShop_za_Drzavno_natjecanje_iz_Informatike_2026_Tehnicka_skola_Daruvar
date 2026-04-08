<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Narudzba;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Narudzba::with(['user', 'nacinPlacanja'])
            ->orderByDesc('Datum_narudzbe')
            ->paginate(20);

        $statuses = ['Na čekanju', 'U obradi', 'Poslano', 'Dostavljeno', 'Dovršena', 'Otkazana'];

        return view('admin.admin_orders', compact('orders', 'statuses'));
    }

    public function show(Narudzba $order)
    {
        $statuses = ['Na čekanju', 'U obradi', 'Poslano', 'Dostavljeno', 'Dovršena', 'Otkazana'];

        return view('admin.orders.show', [
            'order'    => $order->load(['user', 'detalji.proizvod', 'nacinPlacanja']),
            'statuses' => $statuses,
        ]);
    }

    public function update(Request $request, Narudzba $order)
    {
        $request->validate([
            'Status' => 'required|string|max:50',
        ]);

        $order->Status = $request->Status;
        $order->save();

        return back()->with('success', 'Status narudžbe ažuriran.');
    }

    public function cancel(Narudzba $order)
    {
        $order->Status = 'Otkazana';
        $order->save();

        return back()->with('success', 'Narudžba je otkazana.');
    }

    public function close(Narudzba $order)
    {
        $order->Status = 'Dovršena';
        $order->save();

        return back()->with('success', 'Narudžba je zatvorena.');
    }
}
