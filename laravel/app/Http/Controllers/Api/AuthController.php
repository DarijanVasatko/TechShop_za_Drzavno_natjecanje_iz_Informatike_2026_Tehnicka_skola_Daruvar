<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->is_couirt) {
                Auth::logout(); 
                return response()->json([
                    'message' => 'Pristup odbijen. Nemate ovlasti dostavljača.'
                ], 403); 
            }

            $token = $user->createToken('courier-token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'ime' => $user->ime,
                    'prezime' => $user->prezime,
                    'email' => $user->email,
                    'is_couirt' => $user->is_couirt 
                ]
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid email or password'
        ], 401);
    }
}