<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function login(Request $request)
    {
        $this->validate($request, [
            'username'  => 'required',
            'email' => 'required|email',
            'password'  => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        // if (!$user || !Hash::check($request->password, $user->password)) {
        if (!$user || $request->password !== $user->password) {
            // return response()->json([
            //     'message'   => 'Unauthorized'
            // ], 401);

            return view('welcome');
        }

        $token = $user->createToken($request->username)->plainTextToken;

        return response()->json([
            'message'   => 'Success',
            'user'      => $user,
            'token'     => $token,
        ], 200);
    }

    function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'message'   => 'Berhasil Logout',
        ], 200);
    }
}
