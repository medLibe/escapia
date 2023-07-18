<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function auth(Request $request)
    {
        try {
            if(!Auth::attempt($request->only('username', 'password'))){
                return response()->json([
                    'message'   => 'Kredensial tidak cocok dengan record database.'
                ], 401);
            }

            $user = User::where('username', $request->username)->firstOrFail();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message'       => 'Login sukses, akan diarahkan ke beranda dalam 5 detik.',
                'token'         => $token,
                'token_type'    => 'Bearer',
                'redirect'      => '/home',
            ]);
        } catch (Exception $err) {
            return response()->json($err->getMessage(), 500);
        }
    }

    public function logout()
    {
        try{
            Auth::user()->tokens()->delete();

            return response()->json([
                'success'   => true,
                'message'   => 'Logout success',
                'redirect'  => '/',
            ]);
        }catch(Exception $err){
            return response()->json($err->getMessage(), 500);
        }
    }
}
