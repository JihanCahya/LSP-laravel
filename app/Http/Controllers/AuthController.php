<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth/register');
    }
    public function login()
    {
        return view('auth/login');
    }

    public function register_action(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            return response()->json(['success' => 'Berhasil melakukan login']);
        }
    }

    public function login_action(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $credentials = $request->only('email', 'password');
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['errors' => ['email' => ['Email tidak terdaftar']]]);
            }

            if (!Auth::attempt($credentials)) {
                return response()->json(['errors' => ['password' => ['Password salah']]]);
            }

            $request->session()->put('user', $user);
            return response()->json(['success' => 'Berhasil melakukan login']);
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        }
        $request->session()->forget('user');

        return redirect('/login');
    }
}
