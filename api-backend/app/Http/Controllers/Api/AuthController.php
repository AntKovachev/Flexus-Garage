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
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255|',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->errors(),
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            event(new Registered($user));

            Auth::login($user);

            return response()->json([
                'message' => 'User created successfully!',
                'auth_token' => $token,
                'user' => $user
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'messages' => 'User is not created successfully!',
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {

            $user = $request->user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful!',
                'auth_token' => $token,
                'user' => $user,
            ], 200);
        }

        return response()->json([
            'messages' => 'Unsuccessful login!',
        ], 422);
    }

    public function logout(Request $request) //Have to bind the ID of the user
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
