<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        $kategori = DB::table('kategori')->count();
        $surat = DB::table('surat')->count();
        $user = session('user');
        return view('home', compact('title', 'surat', 'kategori', 'user'));
    }
}
