<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Validator;
use Illuminate\Auth\Events\Registered;
use App\Notifications\VerifyEmail;

class PassportAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {
        $Validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',            
        ]);
        if ($Validator->fails()) {
            return response()->json($Validator->messages(), 201);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => null,
            'is_verified' => 0,
        ]);

        $user->sendEmailVerificationNotification();
        return response()->json(['user' => $user], 200);
    }

    /**
     * Login
     */
    public function login(Request $request)
    {

        $data = [
            'email' => $request->email,
            'password' => $request->password,            
        ];

        if (auth()->attempt($data)) {            
            if (auth()->user()->is_verified == 1) {
                $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
                return response()->json(['token' => $token], 200);
            }else{
                return response()->json(['error' => 'please verify your account'], 401); 
            }
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
