<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AspirasiController extends Controller
{
    /**
     * Menampilkan halaman daftar aspirasi dengan status menunggu
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        // Query dasar dengan relasi lengkap
        $query = Pengaduan::with([
            'siswa', 
            'kategori',
            'aspirasi' => function($q) {
                $q->where('status', 'menunggu');
            }
        ]);
        
        // Filter HANYA yang memiliki aspirasi dengan status menunggu
        $query->whereHas('aspirasi', function($q) {
            $q->where('status', 'menunggu');
        });
        
        // Jika ada pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('siswa', function($siswaQuery) use ($search) {
                    $siswaQuery->where('nama', 'like', "%{$search}%")
                               ->orWhere('nisn', 'like', "%{$search}%");
                })
                ->orWhereHas('kategori', function($kategoriQuery) use ($search) {
                    $kategoriQuery->where('nama_kategori', 'like', "%{$search}%");
                })
                ->orWhere('keterangan', 'like', "%{$search}%")
                ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }
        
        // Urutkan dan paginasi
        $pengaduans = $query->orderBy('created_at', 'desc')->paginate(10);
        $kategoris = Kategori::all();
        
        // Jika request AJAX, kembalikan view tanpa layout
        if ($request->ajax()) {
            return view('admin.aspirasi', compact('pengaduans', 'kategoris'))->render();
        }
        
        return view('admin.aspirasi', compact('pengaduans', 'kategoris'));
    }

    /**
     * Menyimpan feedback untuk aspirasi baru (AJAX)
     */
    public function storeWithFeedback(Request $request, $pengaduan)
    {
        // Validasi input
        $validated = $request->validate([
            'status' => 'required|string|in:proses,selesai',
            'isi' => 'required|string|max:500',
            'id_input_aspirasi' => 'required|exists:input_aspirasis,id_input_aspirasi'
        ], [
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus berupa "proses" atau "selesai"',
            'isi.required' => 'Feedback wajib diisi',
            'isi.max' => 'Feedback maksimal 500 karakter'
        ]);

        DB::beginTransaction();

        try {
            $pengaduanModel = Pengaduan::findOrFail($validated['id_input_aspirasi']);

            // Cek apakah sudah ada aspirasi
            $aspirasi = Aspirasi::where('id_input_aspirasi', $pengaduanModel->id_input_aspirasi)->first();
            
            if (!$aspirasi) {
                // Buat baru jika belum ada
                $aspirasi = Aspirasi::create([
                    'id_input_aspirasi' => $pengaduanModel->id_input_aspirasi,
                    'status' => $validated['status'],
                    'id_kategori' => $pengaduanModel->id_kategori,
                    'id_admin' => auth()->id()
                ]);
            } else {
                // Update status jika sudah ada
                $aspirasi->update([
                    'status' => $validated['status']
                ]);
            }

            // Simpan feedback
            $feedback = Feedback::create([
                'id_aspirasi' => $aspirasi->id_aspirasi,
                'isi' => $validated['isi'],
                'id_user' => auth()->user()->id_user
            ]);

            DB::commit();

            // Tentukan pesan sukses berdasarkan status
            $message = $validated['status'] == 'selesai' 
                ? 'Aspirasi telah diselesaikan!' 
                : 'Feedback berhasil dikirim dan aspirasi dipindahkan ke PROSES!';

            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $validated['status'],
                'updated_at' => $aspirasi->updated_at->toDateTimeString(),
                'id_input_aspirasi' => $validated['id_input_aspirasi'] // Kirim ID untuk hapus row
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengupdate status aspirasi yang sudah ada (AJAX)
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'status' => 'required|string|in:proses,selesai',
            'isi' => 'required|string|max:500',
            'id_aspirasi' => 'required|exists:aspirasis,id_aspirasi',
            'id_input_aspirasi' => 'required|exists:input_aspirasis,id_input_aspirasi'
        ], [
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus berupa "proses" atau "selesai"',
            'isi.required' => 'Feedback wajib diisi',
            'isi.max' => 'Feedback maksimal 500 karakter'
        ]);

        DB::beginTransaction();

        try {
            // Cari aspirasi berdasarkan ID
            $aspirasi = Aspirasi::findOrFail($validated['id_aspirasi']);

            // Update status aspirasi
            $aspirasi->update([
                'status' => $validated['status']
            ]);

            // Buat feedback baru
            $feedback = Feedback::create([
                'id_aspirasi' => $aspirasi->id_aspirasi,
                'isi' => $validated['isi'],
                'id_user' => auth()->user()->id_user
            ]);

            DB::commit();

            // Tentukan pesan sukses berdasarkan status
            $message = $validated['status'] == 'selesai' 
                ? 'Status aspirasi diperbarui ke SELESAI!' 
                : 'Status aspirasi diperbarui ke PROSES!';

            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $validated['status'],
                'updated_at' => $aspirasi->updated_at->toDateTimeString(),
                'id_input_aspirasi' => $validated['id_input_aspirasi'] // Kirim ID untuk hapus row
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus aspirasi (AJAX)
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $aspirasi = Aspirasi::findOrFail($id);
            $aspirasi->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Aspirasi berhasil dihapus!'
            ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}