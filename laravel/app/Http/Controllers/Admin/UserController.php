<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('ime')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('addresses'); 

        return view('admin.users.show', compact('user'));
    }

    public function updateRole(Request $request, User $user)
    {
        if(auth()->id() === $user->id && !$request->has('is_admin')) {
            return back()->with('error', 'Ne možete ukloniti administratorske privilegije sa svoga račina.');
        }

        $user->update([
            'is_admin' => $request->has('is_admin'),
            'is_couirt' => $request->has('is_couirt'),
        ]);

        return back()->with('success', 'Uloga korisnika je ažurirana.');

    }

  
}
