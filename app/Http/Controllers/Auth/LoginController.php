<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Laravel\Sanctum\PersonalAccessToken;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        //If Login Attempt Fails
        if (!\Auth::guard()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'These credentials do not match our records!',
            ]);
        }

        //If Login Attempt Succeeds | Retreiving User
        $user = User::with('roles.permissions')->where('email', $request->email)->first();

        //Token Generation
        $token = $user->createToken('auth_token')->plainTextToken;

        //Logging In User
        \Auth::guard()->login($user);

        //Returning Response
        return response()->json([
            'success' => true,
            'message' => 'User Logged In Successfully!',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        //Logging Out User
        if ($token = $request->bearerToken()) {
            $authToken = PersonalAccessToken::findToken($token);
            $authToken->delete();
        }

        //Return Response
        return response()->json([
            'success' => true,
            'message' => 'User Logged Out Successfully!',
        ]);
    }
}