<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    public function sendPasswordResetLink(Request $request)
    {
        //Validating Email
        $request->validate(['email' => 'required|email']);

        //Sending Password Reset Link
        $status = Password::sendResetLink($request->only('email'));

        //Return Response
        return $status === Password::RESET_LINK_SENT
            ? response()->json(['status' => __($status)])
            : response()->json(['email' => __($status)]);
    }

    public function resetPassword(Request $request)
    {
        //Validating Password Reset Request
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only(
                'token',
                'email',
                'password',
                'password_confirmation'
            ),
            function ($user, $password) use ($request) {
                $user->forceFill(['password' => \Hash::make($password)])->setRememberToken(\Str::random(60));
                $user->save();

                //Firing Password Reset Event
                event(new PasswordReset($user));
            }
        );

        //Return Response
        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['success' => true, 'message' => __($status)], 200);
        } else {
            throw ValidationException::withMessages([
                'email' => __($status),
            ]);
        }
    }
}