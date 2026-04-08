<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Kosarica;

class CartComposer
{
    public function compose(View $view)
    {
        if (Auth::check()) {
            $cartCount = Kosarica::where('korisnik_id', Auth::id())->sum('kolicina');
        } else {
            $cart = session('cart', []);
            $cartCount = collect($cart)->sum('quantity');
        }

        $view->with('cartCount', $cartCount);
    }
}
