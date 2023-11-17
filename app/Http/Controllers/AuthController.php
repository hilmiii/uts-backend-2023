<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request) {
        # Menangkap Inputan
        $input = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ];

        $user = User::create($input);
        $data = [
            'message' => 'User is created successfully'
        ];

        return response()->json($data, 200);
    }

    public function login(Request $request) {
        $input = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($input)) {
            $token = Auth::user()->createToken('auth_token');

            $data = [
                'message' => 'Login Successfully',
                'token' => $token->plainTextToken
            ];

            return response()->json($data, 200);
        } else {
            $data = [
                'message' => 'Username or Password is wrong'
            ];

            return response()->json($data, 401);
        }
    }
}