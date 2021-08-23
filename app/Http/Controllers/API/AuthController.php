<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function login(Request $request)
    {
        $this->validate($request, [
            'username'  => 'required',
            'password'  => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            // if (!$user || $request->password !== $user->password) {
            // return response()->json([
            //     'message'   => 'Unauthorized'
            // ], 401);

            return view('login');
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
        try {
            $user->update([
                'api_token' => null
            ]);
            $user->tokens()->where('tokenable_id', $user->id)->delete();
            return response()->json([
                'message'   => 'Berhasil Logout',
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message'   => 'Error'
            ], 400);
        }
    }

    function register(Request $request, User $user)
    {
        $this->validate($request, [
            'name'      => 'required|string',
            'username'  => 'required|unique:users|alpha_dash|max:16',
            'email'     => 'required|email',
            'notelp'    => 'required|regex:/(0)[0-9]{9}/',
            'password'  => 'required|alpha_dash',
            'cabang'    => 'required',
            'level'     => 'required',
            'parent_id' => 'required|numeric'
        ]);

        $user = User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'notelp'    => $request->notelp,
            'password'  => Hash::make($request->password),
            'cabang'    => $request->cabang,
            'level'     => $request->level,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json(
            [
                'message'   => 'User ' . $request->name . ' Berhasil Ditambahkan',
                'data'      => $user,
            ],
            200
        );
    }
}
