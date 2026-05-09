<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClientAuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.client-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        if (! Auth::user()->hasAnyRole(['user', 'admin', 'staff'])) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Your account is not allowed to access the platform.',
            ])->onlyInput('email');
        }

        if (Auth::user()->hasAnyRole(['admin', 'staff'])) {
            return redirect()->intended(url('/admin'));
        }

        return redirect()->intended(route('client.dashboard'));
    }

    public function showRegister(): View
    {
        return view('auth.client-register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create($data);
        $user->assignRole('user');

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('client.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.login');
    }
}
