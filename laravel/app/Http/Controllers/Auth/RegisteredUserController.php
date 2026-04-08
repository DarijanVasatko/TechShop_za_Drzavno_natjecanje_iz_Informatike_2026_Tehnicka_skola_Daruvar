<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kosarica;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'ime' => ['required', 'string', 'max:255'],
            'prezime' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'ime' => $request->ime,
            'prezime' => $request->prezime,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        event(new Registered($user));

        $sessionCart = session('cart', []);

        Auth::login($user);

        foreach ($sessionCart as $productId => $item) {
            Kosarica::create([
                'korisnik_id' => $user->id,
                'proizvod_id' => $productId,
                'kolicina' => $item['quantity'],
            ]);
        }

        session()->forget('cart');

        return redirect()->route('onboarding.show');

    }
}
