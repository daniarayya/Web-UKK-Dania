<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Feedback;
use Illuminate\Support\Facades\Storage;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Query dasar - LANGSUNG PAKAI Pengaduan
            $query = Pengaduan::with(['siswa', 'kategori', 'aspirasi'])
                ->whereHas('aspirasi', function($q) {
                    $q->where('status', 'selesai');
                })
                ->orderBy('updated_at', 'desc');
            
            // Pencarian
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('siswa', function($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%")
                           ->orWhere('nisn', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kategori', function($sq) use ($search) {
                        $sq->where('nama_kategori', 'like', "%{$search}%");
                    })
                    ->orWhere('keterangan', 'like', "%{$search}%");
                });
            }
            
            $perPage = $request->get('per_page', 5);
            $pengaduans = $query->paginate($perPage);
            
            // Response untuk AJAX
            if ($request->ajax() || $request->has('ajax')) {
                // Format data agar mirip dengan kode sebelumnya
                return response()->json([
                    'success' => true,
                    'aspirasis' => [
                        'data' => $pengaduans->map(function($pengaduan) {
                            return [
                                'id' => $pengaduan->aspirasi->id_aspirasi ?? $pengaduan->id,
                                'pengaduan' => [
                                    'id' => $pengaduan->id,
                                    'keterangan' => $pengaduan->keterangan,
                                    'foto' => $pengaduan->foto, // FOTO LANGSUNG DI SINI
                                    'lokasi' => $pengaduan->lokasi,
                                    'created_at' => $pengaduan->created_at,
                                    'siswa' => $pengaduan->siswa ? [
                                        'id' => $pengaduan->siswa->id,
                                        'nama' => $pengaduan->siswa->nama,
                                        'nisn' => $pengaduan->siswa->nisn,
                                        'kelas' => $pengaduan->siswa->kelas,
                                        'jurusan' => $pengaduan->siswa->jurusan
                                    ] : null
                                ],
                                'kategori' => $pengaduan->kategori ? [
                                    'nama_kategori' => $pengaduan->kategori->nama_kategori
                                ] : null,
                                'status' => $pengaduan->aspirasi->status ?? 'selesai'
                            ];
                        }),
                        'current_page' => $pengaduans->currentPage(),
                        'per_page' => $pengaduans->perPage(),
                        'total' => $pengaduans->total(),
                        'from' => $pengaduans->firstItem(),
                        'to' => $pengaduans->lastItem(),
                        'last_page' => $pengaduans->lastPage()
                    ]
                ]);
            }
            
            // Untuk view biasa
            return view('admin.history', compact('pengaduans'));
            
        } catch (\Exception $e) {
            \Log::error('HistoryController Error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->has('ajax')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            \Log::info('History show detail for ID: ' . $id);
            
            // Cari berdasarkan ID aspirasi ATAU ID pengaduan
            $aspirasi = null;
            $pengaduan = null;
            
            // Coba cari dulu di Aspirasi
            if ($aspirasiModel = \App\Models\Aspirasi::find($id)) {
                $pengaduan = $aspirasiModel->pengaduan;
                \Log::info('Found via Aspirasi model');
            } else {
                // Cari langsung di Pengaduan
                $pengaduan = Pengaduan::with(['siswa', 'kategori', 'aspirasi.feedbacks.user'])
                    ->whereHas('aspirasi', function($q) use ($id) {
                        $q->where('id_aspirasi', $id);
                    })->first();
                    
                if (!$pengaduan) {
                    // Mungkin ID adalah ID pengaduan langsung
                    $pengaduan = Pengaduan::with(['siswa', 'kategori', 'aspirasi.feedbacks.user'])->find($id);
                }
            }
            
            if (!$pengaduan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
            
            // DEBUG: Lihat struktur data
            \Log::info('Pengaduan data:', [
                'id' => $pengaduan->id,
                'has_foto' => !empty($pengaduan->foto),
                'foto_value' => $pengaduan->foto,
                'foto_type' => gettype($pengaduan->foto)
            ]);
            
            // Generate foto URL - PASTIKAN SAMA DENGAN DI ASPIRASI/CONTROLLER
            $foto_path = $pengaduan->foto;
            $foto_url = null;
            
            if ($foto_path && $foto_path !== 'null' && trim($foto_path) !== '') {
                // SAMA PERSIS dengan cara di AspirasiController
                if (filter_var($foto_path, FILTER_VALIDATE_URL)) {
                    $foto_url = $foto_path;
                } else {
                    // Cek apakah file ada di storage
                    $storagePath = 'public/' . $foto_path;
                    if (Storage::exists($storagePath)) {
                        $foto_url = asset('storage/' . $foto_path);
                    } else {
                        // Coba langsung dari public storage
                        $publicPath = public_path('storage/' . $foto_path);
                        if (file_exists($publicPath)) {
                            $foto_url = asset('storage/' . $foto_path);
                        }
                    }
                }
                
                \Log::info('Generated foto URL:', [
                    'original' => $foto_path,
                    'final_url' => $foto_url,
                    'storage_exists' => Storage::exists('public/' . $foto_path)
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $pengaduan->aspirasi->id_aspirasi ?? $pengaduan->id,
                    'nama_siswa' => $pengaduan->siswa->nama ?? 'N/A',
                    'nisn' => $pengaduan->siswa->nisn ?? 'N/A',
                    'kelas' => $pengaduan->siswa->kelas ?? 'N/A',
                    'jurusan' => $pengaduan->siswa->jurusan ?? 'N/A',
                    'kategori' => $pengaduan->kategori->nama_kategori ?? 'N/A',
                    'keterangan' => $pengaduan->keterangan ?? 'N/A',
                    'lokasi' => $pengaduan->lokasi ?? 'Tidak ditentukan',
                    'foto' => $foto_path, // original path
                    'foto_url' => $foto_url, // full URL
                    'tanggal_ajukan' => $pengaduan->created_at ? $pengaduan->created_at->format('d M Y, H:i') : 'N/A',
                    'tanggal_selesai' => $pengaduan->updated_at->format('d M Y, H:i'),
                    'feedbacks' => $pengaduan->aspirasi ? 
                        $pengaduan->aspirasi->feedbacks->map(function($feedback) {
                            return [
                                'isi' => $feedback->isi,
                                'created_at' => $feedback->created_at->format('d M Y, H:i'),
                                'is_admin' => optional($feedback->user)->role === 'admin',
                                'created_by' => optional($feedback->user)->nama ?? 'Siswa'
                            ];
                        }) : []
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('HistoryController@show Error: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}