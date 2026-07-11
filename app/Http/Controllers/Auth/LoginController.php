<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
        }

        if (!Auth::user()->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        $role = Auth::user()->role;

        return match ($role) {
            'super_admin' => redirect()->intended('/admin/dashboard'),
            'admin'       => redirect()->intended('/admin/dashboard'),
            'staff'       => redirect()->intended('/staff/order'),
            'customer'    => redirect()->intended('/pelanggan/dashboard'),
            default       => redirect('/'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
