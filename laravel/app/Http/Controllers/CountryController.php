<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $countries = Country::where('name', 'like', $query . '%')
            ->orderBy('name')
            ->take(10)
            ->pluck('name');

        return response()->json($countries);
    }
}
