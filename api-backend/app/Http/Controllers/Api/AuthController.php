<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Casts\Json;
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
            ], 403);
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
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'User is not created successfully!',
                'error' => $exception->getMessage(),
            ], 403);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials) && $request->user()->hasVerifiedEmail()) {

            $user = $request->user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful!',
                'auth_token' => $token,
                'user' => $user,
            ], 200);
        }

        return response()->json([
            'message' => 'Unsuccessful login!',
        ], 401);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->tokens()->delete();
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        };

        return response()->json([
            'message' => 'User not found!'
        ], 404);
    }
}
