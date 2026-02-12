<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Pengaduan::whereHas('aspirasi', function ($q) {
                    $q->where('status', 'ditolak');
                })
                ->with([
                    'siswa:nisn,nama,kelas,jurusan',
                    'kategori',
                    'aspirasi:id_aspirasi,id_input_aspirasi,status,created_at',
                    'aspirasi.feedbacks:id_feedback,id_aspirasi,id_user,isi,created_at',
                    'aspirasi.feedbacks.user:id_user,nama,role'
                ])
                ->latest();

            // Search
            if ($request->filled('search')) {
                $search = trim($request->search);

                $query->where(function ($q) use ($search) {
                    $q->whereHas('siswa', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%")
                            ->orWhere('nisn', 'like', "%{$search}%")
                            ->orWhere('kelas', 'like', "%{$search}%")
                            ->orWhere('jurusan', 'like', "%{$search}%");
                    })
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%")
                    ->orWhereHas('aspirasi.feedbacks', function ($fq) use ($search) {
                        $fq->where('isi', 'like', "%{$search}%");
                    })
                    ->orWhereHas('aspirasi.feedbacks.user', function ($uq) use ($search) {
                        $uq->where('nama', 'like', "%{$search}%");
                    });
                });
            }

            $pengaduans = $query->paginate(5)->withQueryString();

            return view('admin.aspirasi-ditolak', compact('pengaduans'));

        } catch (\Exception $e) {
            Log::error('Error ArsipController@index: ' . $e->getMessage());

            return redirect()->route('admin.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat arsip aspirasi ditolak.');
        }
    }

    public function show($id)
    {
        try {
            $pengaduan = Pengaduan::with([
                    'siswa:nisn,nama,kelas,jurusan',
                    'kategori',
                    'aspirasi:id_aspirasi,id_input_aspirasi,status,created_at',
                    'aspirasi.feedbacks:id_feedback,id_aspirasi,id_user,isi,created_at',
                    'aspirasi.feedbacks.user:id_user,nama,role'
                ])
                ->where('id_input_aspirasi', $id)
                ->whereHas('aspirasi', function ($q) {
                    $q->where('status', 'ditolak');
                })
                ->firstOrFail();

            // Ambil feedback terbaru (alasan penolakan terbaru)
            $feedbackTerbaru = $pengaduan->aspirasi->feedbacks
                ->sortByDesc('created_at')
                ->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $pengaduan->id_input_aspirasi,
                    'keterangan' => $pengaduan->keterangan,
                    'lokasi' => $pengaduan->lokasi,
                    'foto' => $pengaduan->foto,
                    'created_at' => $pengaduan->created_at->format('d F Y H:i'),
                    'siswa' => [
                        'nama' => $pengaduan->siswa->nama ?? 'N/A',
                        'nisn' => $pengaduan->siswa->nisn ?? 'N/A',
                        'kelas' => $pengaduan->siswa->kelas ?? '-',
                        'jurusan' => $pengaduan->siswa->jurusan ?? '-',
                    ],
                    'kategori' => $pengaduan->kategori->nama_kategori ?? 'N/A',
                    'aspirasi' => [
                        'status' => $pengaduan->aspirasi->status ?? null,
                        'alasan_penolakan' => $feedbackTerbaru->isi ?? 'Tidak ada alasan',
                        'admin' => $feedbackTerbaru->user->nama ?? 'N/A',
                        'role' => $feedbackTerbaru->user->role ?? 'N/A',
                        'created_at' => $pengaduan->aspirasi->created_at->format('d F Y H:i'),
                        'feedback_created_at' => $feedbackTerbaru
                            ? $feedbackTerbaru->created_at->format('d F Y H:i')
                            : null
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error ArsipController@show: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Data aspirasi ditolak tidak ditemukan'
            ], 404);
        }
    }
}
