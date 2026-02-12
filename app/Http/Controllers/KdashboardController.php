<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Aspirasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class KdashboardController extends Controller
{
    // alias agar route /dashboard tidak error
    public function dashboard()
    {
        return $this->index();
    }

    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalPengaduan = Aspirasi::count();

        // Menambahkan status ditolak
        $aspirasiMenunggu = Aspirasi::where('status', 'menunggu')->count();
        $aspirasiProses   = Aspirasi::where('status', 'proses')->count();
        $aspirasiSelesai  = Aspirasi::where('status', 'selesai')->count();
        $aspirasiDitolak  = Aspirasi::where('status', 'ditolak')->count();

        return view('kepsek.dashboard', compact(
            'totalSiswa',
            'totalPengaduan',
            'aspirasiMenunggu',
            'aspirasiProses',
            'aspirasiSelesai',
            'aspirasiDitolak'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }
}