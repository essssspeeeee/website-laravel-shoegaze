<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm() {
        return view('auth.register');
    }

    public function register(Request $request) {
    $request->validate([
        'name' => 'required|string|max:255', // Tambahkan ini
        'email' => 'required|email|unique:users',
        'username' => 'required|unique:users',
        'password' => 'required|min:6',
    ]);

    User::create([
    'name'     => $request->username, // Gunakan username sebagai name sementara jika tidak ingin input dua kali
    'username' => $request->username,
    'email'    => $request->email,
    'password' => Hash::make($request->password),
    'role'     => 'user',
]);

    return redirect('/login')->with('success', 'Registrasi berhasil, silakan login.');
}
}