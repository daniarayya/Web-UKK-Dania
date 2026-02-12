<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Aspirasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function index(Request $request)
    { 
        $query = Aspirasi::with([
            'pengaduan.siswa',
            'pengaduan.kategori',
            'feedbacks' => function($query) {
                $query->latest()->limit(5);
            }
        ])
        ->where('status', 'proses');
        
        // Tambahkan live search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('pengaduan.siswa', function($subq) use ($search) {
                    $subq->where('nama', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%");
                })
                ->orWhereHas('pengaduan.kategori', function($subq) use ($search) {
                    $subq->where('nama_kategori', 'like', "%{$search}%");
                })
                ->orWhereHas('pengaduan', function($subq) use ($search) {
                    $subq->where('keterangan', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%");
                });
            });
        }
        
        // Pagination dengan 10 data per halaman
        $aspirasis = $query->orderBy('created_at', 'desc')->paginate(5);
        
        // Jika request AJAX untuk pagination/search
        if ($request->ajax()) {
            return view('admin.feedback', compact('aspirasis'))->render();
        }
        
        return view('admin.feedback', compact('aspirasis'));
    }

    public function getFeedbacks($idAspirasi)
    {
        try {
            $aspirasi = Aspirasi::find($idAspirasi);
            
            if (!$aspirasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aspirasi tidak ditemukan'
                ], 404);
            }
            
            $feedbacks = Feedback::where('id_aspirasi', $idAspirasi)
                ->with(['user' => function($query) {
                    $query->select('id_user', 'nama', 'role');
                }])
                ->orderBy('created_at', 'desc')
                ->get();
            
            $mappedFeedbacks = $feedbacks->map(function($feedback) {
                $isAdmin = $feedback->user && $feedback->user->role === 'admin';
                
                return [
                    'isi' => $feedback->isi,
                    'created_at' => $feedback->created_at->format('Y-m-d H:i:s'),
                    'created_at_formatted' => $feedback->created_at->format('d/m/Y H:i'),
                    'is_admin' => $isAdmin,
                    'user_name' => $feedback->user ? $feedback->user->nama : 'Unknown',
                    'role' => $feedback->user ? $feedback->user->role : 'unknown'
                ];
            });
            
            return response()->json([
                'success' => true,
                'feedbacks' => $mappedFeedbacks,
                'current_status' => $aspirasi->status,
                'total_feedbacks' => $feedbacks->count()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getFeedbacks:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat riwayat feedback: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update status dan kirim feedback dalam satu fungsi
     * HANYA BISA MENGUBAH KE SELESAI
     */
    public function update(Request $request, $id)
    {
        // Validasi input - HANYA SELESAI
        $validated = $request->validate([
            'status' => 'required|string|in:selesai', // HANYA selesai
            'isi' => 'required|string|max:500'
        ], [
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus "selesai"',
            'isi.required' => 'Feedback wajib diisi',
            'isi.max' => 'Feedback maksimal 500 karakter'
        ]);

        DB::beginTransaction();

        try {
            // Cari aspirasi berdasarkan ID
            $aspirasi = Aspirasi::findOrFail($id);

            // Update status aspirasi menjadi SELESAI
            $aspirasi->update([
                'status' => 'selesai' // SELALU selesai
            ]);

            // Buat feedback baru
            $feedback = Feedback::create([
                'id_aspirasi' => $aspirasi->id_aspirasi,
                'isi' => $validated['isi'],
                'id_user' => auth()->user()->id_user
            ]);

            DB::commit();

            // Pesan selalu sama karena selalu selesai
            $message = 'Aspirasi telah diselesaikan dan feedback berhasil dikirim!';

            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => 'selesai', // SELALU selesai
                'updated_at' => $aspirasi->updated_at->toDateTimeString(),
                'id_aspirasi' => $aspirasi->id_aspirasi,
                'feedback_count' => Feedback::where('id_aspirasi', $aspirasi->id_aspirasi)->count()
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in FeedbackController update:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tambahkan fungsi untuk membalas feedback tanpa mengubah status
     */
    public function reply(Request $request, $id)
    {
        $validated = $request->validate([
            'isi' => 'required|string|max:500'
        ], [
            'isi.required' => 'Feedback wajib diisi',
            'isi.max' => 'Feedback maksimal 500 karakter'
        ]);

        try {
            $aspirasi = Aspirasi::findOrFail($id);

            // Buat feedback baru tanpa mengubah status
            $feedback = Feedback::create([
                'id_aspirasi' => $aspirasi->id_aspirasi,
                'isi' => $validated['isi'],
                'id_user' => auth()->user()->id_user
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Feedback berhasil dikirim!',
                'feedback' => [
                    'isi' => $feedback->isi,
                    'created_at' => $feedback->created_at->format('d/m/Y H:i'),
                    'user_name' => auth()->user()->nama
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in FeedbackController reply:', [
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}