<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', false)  
                     ->withCount('narudzbe')
                     ->orderBy('created_at', 'desc')
                     ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['narudzbe' => function ($q) {
            $q->orderByDesc('Datum_narudzbe');
        }]);

        return view('admin.users.show', compact('user'));
    }
}
