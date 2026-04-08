<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Kosarica;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $sessionCart = session('cart', []);

        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();

        foreach ($sessionCart as $productId => $item) {
            $row = Kosarica::where('korisnik_id', $user->id)
                ->where('proizvod_id', $productId)
                ->first();

            if ($row) {
                $row->kolicina += $item['quantity'];
                $row->save();
            } else {
                Kosarica::create([
                    'korisnik_id' => $user->id,
                    'proizvod_id' => $productId,
                    'kolicina' => $item['quantity'],
                ]);
            }
        }

        session()->forget('cart');

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended('/');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user) {
            $cart = session('cart', []);
            foreach ($cart as $productId => $item) {
                Kosarica::updateOrCreate(
                    ['korisnik_id' => $user->id, 'proizvod_id' => $productId],
                    ['kolicina' => $item['quantity']]
                );
            }
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}