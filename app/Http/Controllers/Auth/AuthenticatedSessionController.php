<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Logika Pengalihan Berdasarkan Role
        $role = Auth::user()->role;

        switch ($role) {
            case 'bapendik':
                return redirect()->route('bapendik.dashboard');
            case 'dosen':
                return redirect()->route('dosen.dashboard');
            case 'mahasiswa':
                return redirect()->route('mahasiswa.dashboard');
            default:
                Auth::logout();
                return redirect('/login')->with('error', 'Role tidak valid.');
        }
//        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
