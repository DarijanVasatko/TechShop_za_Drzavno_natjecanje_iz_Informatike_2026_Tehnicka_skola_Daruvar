<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UserAddress;


class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user()->load('addresses');
        return view('profile', compact('user'));
    }



    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'ime' => 'required|string|max:255',
            'prezime' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'telefon' => ['nullable', 'regex:/^[0-9+\s\-]{6,20}$/'],
        ]);

        $user->update([
            'ime' => $request->ime,
            'prezime' => $request->prezime,
            'email' => $request->email,
            'telefon' => $request->telefon,
        ]);

        return redirect()->back()->with('success', 'Podaci su uspješno ažurirani!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|confirmed|min:8',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Trenutna lozinka nije ispravna.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Lozinka je uspješno promijenjena!');
    }


    public function destroy()
    {
        $user = Auth::user();
        $user->delete();

        return redirect('/')->with('success', 'Vaš profil je izbrisan.');
    }

    public function addAddress(Request $request)
{
    $request->validate([
        'adresa' => 'required|string|max:255',
        'grad' => 'required|string|max:100',
        'postanski_broj' => 'required|string|max:20',
        'drzava' => 'required|string|max:100',
    ]);

    $user = Auth::user();

    $address = $user->addresses()->create([
        'adresa' => $request->adresa,
        'grad' => $request->grad,
        'postanski_broj' => $request->postanski_broj,
        'drzava' => $request->drzava,
        'is_default' => $request->has('is_default'),
    ]);

    return back()->with('success', 'Adresa je uspješno dodana.');
}



    public function deleteAddress($id)
    {
        $address = UserAddress::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $address->delete();

        return redirect()->back()->with('success', 'Adresa je uspješno obrisana!');
    }

    public function setDefaultAddress($id)
{
    $user = Auth::user();


    $user->addresses()->update(['is_default' => false]);

    $address = $user->addresses()->findOrFail($id);
    $address->update(['is_default' => true]);

    return back()->with('success', 'Zadana adresa je uspješno postavljena.');
}

}
