<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        foreach ($roles as $role) {
            if (Auth::user()->role == $role) {
                return $next($request);
            }
        }

        // Jika role tidak cocok, kembalikan ke halaman yang sesuai
        $userRole = Auth::user()->role;
        if ($userRole == 'mahasiswa') {
            return redirect('/mahasiswa/dashboard');
        } elseif ($userRole == 'dosen') {
            return redirect('/dosen/dashboard');
        } elseif ($userRole == 'bapendik') {
            return redirect('/bapendik/dashboard');
        }

        return redirect('login');
    }
}
