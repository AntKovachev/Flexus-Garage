<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

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

    public function verify(Request $request, $id, $hash)
    {
        $user = User::find($id);

        // Check if user exists
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Validate the signed URL
        if (!URL::hasValidSignature($request)) {
            return response()->json(['error' => 'Invalid verification link.'], 403);
        }

        // Validate the hash
        if (!hash_equals((string) $hash, sha1($user->email))) {
            return response()->json(['error' => 'Invalid verification link.'], 403);
        }

        // Check if the user has already verified their email
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 200);
        }

        // Mark the email as verified
        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json(['message' => 'Email successfully verified.'], 200);
    }


    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified!'
            ], 200);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification email resend!'
        ], 200);
    }
}
