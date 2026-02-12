<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use App\Models\Pengaduan;
use App\Models\Kategori;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Query dasar
        $query = Aspirasi::with([
            'pengaduan.siswa',
            'pengaduan.kategori',
            'feedbacks.user' // Load user yang memberi feedback
        ])
        ->orderBy('created_at', 'desc');

        // Jika user adalah siswa
        if (Auth::check() && Auth::user()->role === 'siswa') {
            $nisn = Auth::user()->nisn;
            
            // Tampilkan aspirasi yang:
            // 1. Status BUKAN "baru" (sudah diproses admin)
            // 2. ATAU jika status "ditolak", hanya jika milik siswa tersebut
            $query->where(function($q) use ($nisn) {
                $q->where('status', '!=', 'baru')
                  ->orWhere(function($subQ) use ($nisn) {
                      $subQ->where('status', 'ditolak')
                           ->whereHas('pengaduan', function ($pengaduanQ) use ($nisn) {
                               $pengaduanQ->where('nisn', $nisn);
                           });
                  });
            });
        } else {
            // Untuk non-siswa (admin, kepsek, guest) hanya tampilkan yang statusnya bukan "baru"
            // Juga tampilkan yang bukan "ditolak" kecuali jika login sebagai admin/kepsek
            if (Auth::check() && in_array(Auth::user()->role, ['admin', 'kepsek'])) {
                // Admin dan Kepsek bisa lihat semua yang bukan "baru"
                $query->where('status', '!=', 'baru');
            } else {
                // Guest hanya lihat yang status "selesai" atau "proses"
                $query->whereIn('status', ['selesai', 'proses']);
            }
        }

        // Jika ada parameter pencarian, cari berdasarkan NAMA SISWA saja
        if ($search) {
            $query->whereHas('pengaduan.siswa', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        $aspirasis = $query->paginate(10);
        $kategoris = Kategori::all();

        return view('history', compact('aspirasis', 'kategoris'));
    }

    public function show($id)
    {
        // Query utama
        $query = Aspirasi::with([
            'pengaduan.siswa',
            'pengaduan.kategori',
            'feedbacks.user' // Load user yang memberi feedback
        ]);

        $aspirasi = $query->findOrFail($id);

        // Logika akses
        if (Auth::check()) {
            if (Auth::user()->role === 'siswa') {
                // Siswa hanya boleh lihat:
                // 1. Miliknya sendiri, atau
                // 2. Yang statusnya bukan "ditolak" (untuk melihat aspirasi umum)
                if ($aspirasi->pengaduan->nisn !== Auth::user()->nisn) {
                    // Jika bukan miliknya, hanya boleh lihat yang bukan "ditolak" dan "baru"
                    if (in_array($aspirasi->status, ['ditolak', 'baru'])) {
                        abort(403, 'Anda tidak memiliki akses');
                    }
                }
            }
            // Admin dan Kepsek bisa akses semua
        } else {
            // Guest hanya boleh lihat yang status "selesai" atau "proses"
            if (!in_array($aspirasi->status, ['selesai', 'proses'])) {
                abort(403, 'Anda tidak memiliki akses');
            }
        }

        return view('aspirasi.show', compact('aspirasi'));
    }

    // ===============================
    // BAGIAN DI BAWAH INI BUTUH LOGIN
    // ===============================

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'id_kategori' => 'required|exists:kategoris,id_kategori',
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'required|string|min:10',
        ]);

        DB::beginTransaction();

        try {
            $pengaduan = Pengaduan::create([
                'nisn' => Auth::user()->nisn,
                'id_kategori' => $request->id_kategori,
                'lokasi' => $request->lokasi,
                'keterangan' => $request->keterangan,
            ]);

            Aspirasi::create([
                'id_input_aspirasi' => $pengaduan->id_input_aspirasi,
                'status' => 'menunggu',
            ]);

            DB::commit();

            return redirect()->route('history.index')
                ->with('success', 'Aspirasi Anda berhasil dikirim! Saat ini sedang dalam proses peninjauan oleh admin dan akan muncul di halaman riwayat setelah disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function addFeedback(Request $request, $id)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'kepsek'])) {
            abort(403);
        }

        $request->validate([
            'isi' => 'required|string|min:3|max:1000',
        ]);

        $aspirasi = Aspirasi::findOrFail($id);

        $feedback = Feedback::create([
            'id_aspirasi' => $aspirasi->id_aspirasi,
            'user_id' => Auth::id(), // Simpan ID user yang memberi feedback
            'isi' => $request->isi,
        ]);

        if ($aspirasi->status === 'menunggu') {
            $aspirasi->update(['status' => 'proses']);
        }

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:menunggu,proses,selesai,ditolak',
        ]);

        $aspirasi = Aspirasi::findOrFail($id);
        $aspirasi->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        if (!Auth::check()) abort(403);

        $aspirasi = Aspirasi::findOrFail($id);

        // Admin boleh hapus semua, siswa hanya miliknya
        if (Auth::user()->role === 'siswa' &&
            $aspirasi->pengaduan->nisn !== Auth::user()->nisn) {
            abort(403);
        }

        $aspirasi->feedbacks()->delete();
        $aspirasi->pengaduan()->delete();
        $aspirasi->delete();

        return redirect()->route('history.index')
            ->with('success', 'Aspirasi berhasil dihapus');
    }
}