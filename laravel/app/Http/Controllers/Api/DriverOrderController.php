<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Narudzba;
use App\Models\DetaljiNarudzbe; 
use App\Models\Proizvod;

class DriverOrderController extends Controller
{

    public function index(Request $request)
    {
        $orders = Narudzba::query()
            ->select(['Narudzba_ID', 'Datum_narudzbe', 'Ukupni_iznos', 'Adresa_dostave', 'Status'])
            ->where('Status', '!=', 'Dostavljeno')
            ->orderByDesc('Datum_narudzbe')
            ->limit(50)
            ->get();

        return response()->json(['data' => $orders]);
    }


    public function getOrderDetails($id)
    {
        try {
            $order = Narudzba::with(['detalji.proizvod'])->where('Narudzba_ID', $id)->firstOrFail();

            return response()->json([
                'data' => [
                    'Narudzba_ID'    => (int) $order->Narudzba_ID,
                    'Datum_narudzbe' => $order->Datum_narudzbe,
                    'Ukupni_iznos'   => (float) $order->Ukupni_iznos,
                    'Adresa_dostave' => $order->Adresa_dostave,
                    'Status'         => $order->Status,
                    'detalji'        => $order->detalji->map(function ($item) {
                        return [
                            'name'  => optional($item->proizvod)->Naziv ?? 'Nepoznat proizvod',
                            'qty'   => (int) $item->Kolicina,
                            'price' => (float) $item->cijena_po_komadu 
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine()
            ], 500);
        }
    }

    public function markDelivered(Request $request, $id)
    {
        $order = Narudzba::where('Narudzba_ID', $id)->firstOrFail();

        $order->Status = 'Dostavljeno';

        if ($request->filled('potpis')) {
            $order->potpis = $request->input('potpis');
        }

        $order->save();

        return response()->json([
            'ok' => true,
            'message' => 'Order marked as delivered.',
            'status' => $order->Status,
        ]);
    }

    public function markNotDelivered($id)
    {
        $order = Narudzba::where('Narudzba_ID', $id)->firstOrFail();

        $order->Status = 'Neuspjela dostava';
        $order->save();

        return response()->json([
            'ok' => true,
            'message' => 'Order marked as not delivered.',
            'status' => $order->Status,
        ]);
    }
}
