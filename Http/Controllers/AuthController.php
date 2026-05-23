<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display the login page
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    /**
     * Process authentication
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            $roleName = $user->role ? $user->role->display_name : 'User';
            
            return $this->redirectBasedOnRole($user)
                ->with('toast_success', "Selamat datang kembali, {$user->name}! Anda login sebagai {$roleName}.");
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email', 'remember'));
    }

    /**
     * Display registration page (only for Pembeli/Customer self-register)
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    /**
     * Process registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $pembeliRole = Role::where('name', 'pembeli')->first();

        $user = User::create([
            'role_id' => $pembeliRole ? $pembeliRole->id : 3, // fallback to id 3 if not found
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('catalog.index')
            ->with('toast_success', "Registrasi berhasil! Selamat datang, {$user->name}.");
    }

    /**
     * Destroy session / logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('toast_success', 'Anda telah berhasil keluar dari sistem.');
    }

    /**
     * Redirect logic based on user's role
     */
    private function redirectBasedOnRole($user)
    {
        if ($user->isAdmin() || $user->isKasir()) {
            return redirect()->intended('/dashboard');
        }
        return redirect()->intended('/');
    }
}
