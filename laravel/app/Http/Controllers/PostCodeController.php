<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostCode;

class PostCodeController extends Controller
{
    public function lookup(Request $request)
    {
        $city = trim((string) $request->query('city', ''));
        $country = $request->query('country', 'HR'); 

        if ($city === '') {
            return response()->json(['postal_code' => null]);
        }

        $row = PostCode::query()
            ->whereRaw('LOWER(city) = ?', [mb_strtolower($city)])
            ->where(function ($q) use ($country) {
                $q->whereNull('country')->orWhere('country', $country);
            })
            ->first();

        return response()->json([
            'postal_code' => $row?->postal_code,
        ]);
    }

    
    public function lookupByPostalCode(Request $request)
    {
        $postalCode = trim((string) $request->query('postal_code', ''));
        $country = $request->query('country', 'HR');

        if ($postalCode === '') {
            return response()->json(['city' => null]);
        }

        $row = PostCode::query()
            ->where('postal_code', $postalCode)
            ->where(function ($q) use ($country) {
                $q->whereNull('country')->orWhere('country', $country);
            })
            ->first();

        return response()->json([
            'city' => $row?->city,
        ]);
    }
}
