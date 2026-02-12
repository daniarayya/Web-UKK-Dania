<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Kategori;
use App\Models\Aspirasi;
use Illuminate\Support\Facades\DB;

class PengaduanController extends Controller
{
    public function formPengaduan()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        
        // Hitung dari tabel Aspirasi
        $aspirasiMenunggu = Aspirasi::where('status', 'menunggu')->count();
        $aspirasiProses = Aspirasi::where('status', 'proses')->count();
        $aspirasiSelesai = Aspirasi::where('status', 'selesai')->count();
        $aspirasiDitolak = Aspirasi::where('status', 'ditolak')->count();
        
        // 1. DITERIMA = semua kecuali 'baru'
        $pengaduanDiterima = $aspirasiMenunggu + $aspirasiProses + $aspirasiSelesai + $aspirasiDitolak;
        
        // 2. TERBALAS = 'proses' + 'selesai'
        $pengaduanTerbalas = $aspirasiProses + $aspirasiSelesai;
        
        // 3. SELESAI = hanya 'selesai'
        $pengaduanSelesai = $aspirasiSelesai;
        
        $stats = [
            'total_pengaduan' => $pengaduanDiterima,
            'pengaduan_terbalas' => $pengaduanTerbalas,
            'pengaduan_selesai' => $pengaduanSelesai,
        ];
        
        return view('index', compact('kategoris', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lokasi'       => 'required|string|max:255',
            'id_kategori'  => 'required|exists:kategoris,id_kategori',
            'keterangan'  => 'required|max:1000',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Cek apakah user adalah siswa
        if (auth()->user()->role !== 'siswa') {
            return redirect()->back()->with('error', 'Hanya siswa yang dapat mengirim pengaduan!');
        }

        $namaFoto = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFoto = time().'_'.$file->getClientOriginalName();
            $file->storeAs('pengaduan', $namaFoto, 'public');
        }

        // Create pengaduan
        $pengaduan = Pengaduan::create([
            'nisn'         => auth()->user()->nisn,
            'id_kategori'  => $request->id_kategori,
            'lokasi'       => $request->lokasi,
            'keterangan'   => $request->keterangan,
            'foto'         => $namaFoto,
        ]);

        // Create aspirasi entry
        Aspirasi::create([
            'id_input_aspirasi' => $pengaduan->id_input_aspirasi,
            'id_kategori'       => $request->id_kategori,
            'status'            => 'baru',
        ]);

        return redirect()->route('history.index')
            ->with('success', 'Aspirasi Anda berhasil dikirim! Saat ini sedang dalam proses peninjauan oleh admin dan akan muncul di halaman riwayat setelah disetujui.');
    }

    public function index()
    {
        $data = Pengaduan::with(['kategori','admin'])
                    ->where('nisn', auth()->user()->nisn)
                    ->latest()
                    ->get();

        return view('history', compact('data'));
    }

    public function all()
    {
        $data = Pengaduan::with(['siswa','kategori','admin'])
                    ->latest()
                    ->get();

        return view('admin.pengaduan.index', compact('data'));
    }

    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'status' => 'required|string|max:50'
        ]);

        // Update status pengaduan
        $pengaduan->update([
            'status'  => $request->status,
            'id_user' => auth()->user()->id_user // simpan admin pemroses
        ]);

        // Update status aspirasi juga
        if ($pengaduan->aspirasi) {
            $pengaduan->aspirasi->update([
                'status' => $request->status
            ]);
        }

        return back()->with('success', 'Status pengaduan berhasil diperbarui!');
    }
}