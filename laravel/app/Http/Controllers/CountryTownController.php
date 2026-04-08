<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\CountryTown;

class CountryTownController extends Controller
{
    public function search(Request $request)
    {
        $query = trim((string) $request->get('q', ''));
        $country = trim((string) $request->get('country', ''));

        if ($query === '' || $country === '') {
            return response()->json([]);
        }

        $countryId = Country::where('name', $country)->value('id');

        if (!$countryId) {
            return response()->json([]);
        }

        $towns = CountryTown::where('country_id', $countryId)
            ->where('name', 'like', $query . '%')
            ->orderBy('name')
            ->take(10)
            ->get(['name', 'postal_code']);

        return response()->json(
            $towns->map(fn ($t) => [
                'city' => $t->name,
                'postal_code' => $t->postal_code,
            ])->values()
        );
    }
}
