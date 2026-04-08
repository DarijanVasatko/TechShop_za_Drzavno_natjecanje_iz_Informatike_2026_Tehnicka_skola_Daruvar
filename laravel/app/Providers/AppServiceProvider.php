<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Kategorija;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        
    }

    
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('kategorije', Kategorija::all());
            $view->with('categoryId', null);

            if (Auth::check()) {
                $cartCount = DB::table('kosarica')
                    ->where('korisnik_id', Auth::id())
                    ->sum('kolicina');
            } else {
                $cart = session('cart', []);
                $cartCount = collect($cart)->sum('quantity');
            }

            $view->with('cartCount', $cartCount);
        });
    }
}
