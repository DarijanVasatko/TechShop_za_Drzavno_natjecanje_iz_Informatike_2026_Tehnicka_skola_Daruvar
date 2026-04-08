<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kosarica;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $guestCart = session('cart', []);

            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                $user->update(['google_id' => $googleUser->id]);
                Auth::login($user);
                $this->mergeGuestCart($user, $guestCart);

                return redirect()->route('index.index');
            }

            $newUser = User::create([
                'ime' => $googleUser->user['given_name'] ?? 'Korisnik',
                'prezime' => $googleUser->user['family_name'] ?? '',
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => Hash::make(Str::random(24)),
            ]);

            Auth::login($newUser);
            $this->mergeGuestCart($newUser, $guestCart);

            return redirect()->route('onboarding.show');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Pojavila se greška pri prijavi.');
        }
    }

    private function mergeGuestCart(User $user, array $sessionCart): void
    {
        try {
            if (empty($sessionCart)) {
                return;
            }

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

        } catch (\Exception $e) {
            Log::error('Cart merge failed on Google login: ' . $e->getMessage());
        }
    }
}
