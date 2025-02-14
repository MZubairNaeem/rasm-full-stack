<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        //User Registration
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
        ]);

        //Token Generation
        $token = $user->createToken('auth_token')->plainTextToken;

        $user = User::with('roles.permissions')->where('email', $request->email)->first();
        //Returning Response
        return response()->json([
            'success' => true,
            'message' => 'User Registered Successfully!',
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ]);
    }
}