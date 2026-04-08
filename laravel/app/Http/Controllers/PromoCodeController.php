<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromoKod;
use Carbon\Carbon;

class PromoCodeController extends Controller
{
    public function apply(Request $request) {
        $kod = PromoKod::where('kod', $request->promo_code)
            ->where('aktivno', true)
            ->first();
        
        if (!$kod) {
            return response()->json(['success' => false, 'message' => 'Kod nije ispravan!']);
        }

        $now = Carbon::now();


        if ($kod->vrijedi_od && $now->lt(Carbon::parse($kod->vrijedi_od))) {
            return response()->json(['success' => false, 'message' => 'Kod još nije aktivan!']);
        }

      
        if ($kod->vrijedi_do && $now->gt(Carbon::parse($kod->vrijedi_do))) {
            return response()->json(['success' => false, 'message' => 'Kod je istekao!']);
        }

    
        if ($kod->max_koristenja && $kod->koristenja >= $kod->max_koristenja) {
            return response()->json(['success' => false, 'message' => 'Kod je iskorišten maksimalan broj puta!']);
        }

       
        if ($request->total_amount < $kod->minimalan_iznos) {
            return response()->json([
                'success' => false, 
                'message' => 'Minimalan iznos za korištenje ovog koda je ' . number_format($kod->minimalan_iznos, 2) . ' €!'
            ]);
        }

        $discount = 0;
       
        if ($kod->tip === 'postotak') {
            $discount = $request->total_amount * ($kod->vrijednost / 100);
        } else {
            $discount = $kod->vrijednost;
        }

        $newTotal = max(0, $request->total_amount - $discount);

        session([
            'promo_code' => $kod->kod,
            'promo_discount' => round($discount, 2),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kod je uspješno primijenjen!',
            'discount' => round($discount, 2),
            'new_total' => round($newTotal, 2)
        ]);
    }

    public function remove()
    {
        session()->forget(['promo_code', 'promo_discount']);

        return response()->json([
            'success' => true,
            'message' => 'Promo kod je uklonjen.',
        ]);
    }
}