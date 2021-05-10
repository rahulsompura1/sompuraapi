<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Auth;
use Session;

class LoginController extends Controller
{

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        Auth::guard('web')->logout();

        Session::regenerate();

        return response()->json([
            'logout' => 1
        ]);
    }
}
