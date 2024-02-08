<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $token = $request->user()->createToken('API Token')->plainTextToken;
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
    // public function store(Request $request)
    // {
    //     if (Auth::attempt($request->only('email', 'password'))) {
    //         $user = Auth::user();
    //         $token = $user->createToken('auth_token')->plainTextToken;
    //         return response()->json(['token' => $token], 201);
    //     }

    //     return response()->json(['error' => 'Unauthorized'], 401);
    // }
}
