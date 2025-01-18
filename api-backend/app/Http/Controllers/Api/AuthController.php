<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255|',
            'password' => 'required|string|min:8|confirmed'
        ]);

        // if ($validated->fails()) {
        //     return response()->json([
        //         'message' => 'all fields must be filled.',
        //         'error' => $validated->messages()
        //     ], 422);
        // }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        event(new Registered($user));

        Auth::login($user);

        return response()->json([
            'auth_token' => $token,
            'user' => $user
        ], 200);
    }

    public function login()
    {

    }

    public function logout()
    {

    }


}
