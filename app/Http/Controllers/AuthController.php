<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
     public function showLogin()
    {
        // Jika sudah login, redirect sesuai role
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->route('home');
        }
        
        return view('auth');
    }

    public function login(Request $request)
    {
        // dd($request->all()); 

        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            if ($role == 'admin')  return redirect()->route('admin.dashboard');
            if ($role == 'kepsek') return redirect()->route('kepsek.dashboard');
            if ($role == 'siswa')  return redirect()->route('home');

            return redirect()->route('home');
        }
        
        // dd("Login Gagal", $credentials);

        return back()->withErrors([
            'username' => 'Username atau password salah'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
