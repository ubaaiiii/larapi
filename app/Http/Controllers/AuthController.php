<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showFormLogin()
    {
        if (Auth::check()) { // true sekalian session field di users nanti bisa dipanggil via Auth
            //Login Success
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username'  => 'required|',
            'password'  => 'required|string'
        ]);

        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput($request->all);
        // }

        $data = [
            'username'  => $request->input('username'),
            'password'  => $request->input('password'),
        ];

        Auth::attempt($data, $request->remember_me);

        if (Auth::check()) { // true sekalian session field di users nanti bisa dipanggil via Auth
            //Login Success
            session()->regenerate();
            session(['mode', 'light']);
            return redirect()->route('home');
        } else {
            //Login Fail
            return redirect()->back()->withErrors(['Username Atau Password Salah']);
        }
    }

    public function register(Request $request)
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

        if ($user->id) {
            $request->session()->flash('success', 'Register berhasil! Silahkan login untuk mengakses data');
            return redirect()->route('login');
        } else {
            $request->session()->flash('errors', ['' => 'Register gagal! Silahkan ulangi beberapa saat lagi']);
            return redirect()->route('register');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout(); // menghapus session yang aktif
        return redirect()->route('login');
        // pindahin ke api/authcontroller/logout
        // $user->currentAccessToken()->delete();
    }

    public function DarkMode($value)
    {
        if ($value == 1) {
            $mode = "dark";
        } else {
            $mode = "light";
        }

        $user = User::find(Auth::user()->id);
        try {
            $user = $user->update([
                'mode' => $mode
            ]);
            return response()->json([
                'message'   => 'Success'
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message'   => 'Error'
            ], 400);
        }
    }
}
