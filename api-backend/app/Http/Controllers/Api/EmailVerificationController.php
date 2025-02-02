<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{

    public function show(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified!'
            ], 200);
        }

        return response()->json([
            'message' => 'Please verify your email.'
        ], 200);
    }

    public function verify()
    {

    }

    public function resend()
    {

    }
}
