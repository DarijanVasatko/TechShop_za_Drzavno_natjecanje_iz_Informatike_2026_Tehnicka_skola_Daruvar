<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proizvod;
use App\Models\Narudzba;
use App\Models\User;
use App\Models\PromoKod;

class DashboardController extends Controller
{
    public function index()
    {
        $productsCount = Proizvod::count();
        $ordersCount   = Narudzba::count();
        $usersCount    = User::count();
        $promocodesCount = PromoKod::count();

        $lookerEmbedUrl = config('services.looker.embed_url');
        $lookerUrl      = config('services.looker.open_url');

        return view('admin.dashboard', compact(
            'productsCount',
            'ordersCount',
            'usersCount',
            'promocodesCount',
            'lookerEmbedUrl',
            'lookerUrl'
        ));
    }
}
