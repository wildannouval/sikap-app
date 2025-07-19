<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class DashboardController extends Controller
{
    public function bapendik()
    {
        return view('bapendik.dashboard');
    }

    public function dosen()
    {
        return view('dosen.dashboard');
    }

    public function mahasiswa()
    {
        return view('mahasiswa.dashboard');
    }
}
