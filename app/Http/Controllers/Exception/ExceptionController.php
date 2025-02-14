<?php

namespace App\Http\Controllers\Exception;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExceptionController extends Controller
{
    public function notLoggedIn()
    {
        return response()->json([
            'success' => false,
            'message' => 'User Not Logged In!',
        ]);
    }

    public function emailNotVerified()
    {
        return response()->json([
            'success' => false,
            'message' => 'User Email Not Verified!',
        ]);
    }
}
