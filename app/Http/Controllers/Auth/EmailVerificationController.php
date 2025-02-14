<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function sendVerificationEmail(Request $request)
    {
        //Checking if User has already Email Verified
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email Already Verified.',
            ]);
        }

        //Sending Verification Email
        $request->user()->sendEmailVerificationNotification();

        //Returning Success Response
        return response()->json([
            'message' => 'Email Verification Link Sent.',
        ]);
    }

    public function verify(EmailVerificationRequest $request)
    {
        //Checking if User has already Email Verified
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email Already Verified.',
            ]);
        }

        //Verifying User Email
        if ($request->user()->markEmailAsVerified()) {
            //Firing Verified Event
            event(new Verified($request->user()));
        }
        $user = User::with('roles.permissions')->where('id', $request->user()->id)->first();
        //Returning Success Response
        return response()->json([
            'success' => true,
            'message' => 'Email Verification Successfull.',
            'user' => $user,
        ]);
    }
}