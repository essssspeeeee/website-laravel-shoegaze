<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            } elseif ($user->role === 'petugas') {
                return redirect('/staff/dashboard');
            } else {
                return redirect('/home');
            }
        }

        return back()->withErrors(['loginError' => 'Username atau Password salah']);
    }

    public function logout() {
        Auth::logout();
        return redirect('/login');
    }
}