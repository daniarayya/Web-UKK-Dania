<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AspirasiBaruController extends Controller
{
    /**
     * Menampilkan daftar aspirasi baru (status = baru)
     */
    public function index(Request $request)
    {
        try {
            $query = Pengaduan::query()
                ->whereHas('aspirasi', function ($q) {
                    $q->where('status', 'baru');
                })
                ->with([
                    'siswa:nisn,nama,kelas,jurusan',
                    'kategori:id_kategori,nama_kategori',
                    'aspirasi:id_aspirasi,status,id_input_aspirasi'
                ])
                ->latest('created_at');

            // Search
            if ($request->filled('search')) {
                $search = trim($request->search);

                $query->where(function ($q) use ($search) {
                    $q->where('keterangan', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%")
                        ->orWhereHas('siswa', function ($q2) use ($search) {
                            $q2->where('nama', 'like', "%{$search}%")
                                ->orWhere('nisn', 'like', "%{$search}%");
                        })
                        ->orWhereHas('kategori', function ($q3) use ($search) {
                            $q3->where('nama_kategori', 'like', "%{$search}%");
                        });
                });
            }

            $pengaduans = $query->paginate(5)->withQueryString();

            return view('admin.aspirasi-masuk', compact('pengaduans'));

        } catch (\Exception $e) {
            Log::error("Error AspirasiBaruController@index: " . $e->getMessage());

            return redirect()->route('admin.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat data aspirasi.');
        }
    }

    public function terima($id)
    {
        DB::beginTransaction();
        try {
            Log::info("Mencoba terima aspirasi ID: " . $id);

            $pengaduan = Pengaduan::where('id_input_aspirasi', $id)
                ->with('aspirasi')
                ->first();

            if (!$pengaduan) {
                Log::warning("Pengaduan tidak ditemukan untuk id_input_aspirasi: " . $id);
                return response()->json(['success' => false, 'message' => 'Data pengaduan tidak ditemukan'], 404);
            }

            if (!$pengaduan->aspirasi) {
                Log::warning("Relasi aspirasi tidak ada untuk pengaduan ID: " . $id);
                return response()->json(['success' => false, 'message' => 'Relasi aspirasi tidak ditemukan'], 404);
            }

            Log::info("Status sebelum: " . $pengaduan->aspirasi->status);

            $pengaduan->aspirasi->update(['status' => 'menunggu']);

            Log::info("Status berhasil diubah menjadi menunggu untuk ID: " . $id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Aspirasi berhasil diterima.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error terima aspirasi ID {$id}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
   
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'isi' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();

        try {
            $pengaduan = Pengaduan::where('id_input_aspirasi', $id)
                ->with('aspirasi:id_aspirasi,id_input_aspirasi,status')
                ->firstOrFail();

            if (!$pengaduan->aspirasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aspirasi tidak ditemukan.'
                ], 404);
            }

            $aspirasi = $pengaduan->aspirasi;

            $aspirasi->update([
                'status' => 'ditolak'
            ]);

            if ($request->filled('isi')) {
                Feedback::create([
                    'id_aspirasi' => $aspirasi->id_aspirasi,
                    'id_user' => auth()->user()->id_user, 
                    'isi' => trim($request->isi)
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Aspirasi berhasil ditolak.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Error AspirasiBaruController@tolak: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getDetail($id)
    {
        try {
            $pengaduan = Pengaduan::with([
                    'siswa:id,nisn,nama,kelas,jurusan',
                    'kategori:id,nama_kategori',
                    'aspirasi:id,id_aspirasi,status,id_input_aspirasi'
                ])
                ->where('id_input_aspirasi', $id)
                ->firstOrFail();

            if (!$pengaduan->aspirasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aspirasi tidak ditemukan.'
                ], 404);
            }

            $fotoUrl = null;
            $hasFoto = false;

            if (!empty($pengaduan->foto) && $pengaduan->foto !== 'null') {
                $storagePath = 'pengaduan/' . trim($pengaduan->foto);

                if (Storage::disk('public')->exists($storagePath)) {
                    $fotoUrl = asset('storage/' . $storagePath);
                    $hasFoto = true;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $pengaduan->id_input_aspirasi,
                    'keterangan' => $pengaduan->keterangan,
                    'lokasi' => $pengaduan->lokasi,
                    'foto' => $pengaduan->foto,
                    'foto_url' => $fotoUrl,
                    'has_foto' => $hasFoto,
                    'created_at' => $pengaduan->created_at->format('Y-m-d H:i:s'),
                    'created_at_formatted' => $pengaduan->created_at->translatedFormat('d F Y H:i'),
                    'siswa' => $pengaduan->siswa,
                    'kategori' => $pengaduan->kategori,
                    'aspirasi' => $pengaduan->aspirasi
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Error AspirasiBaruController@getDetail: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail aspirasi.'
            ], 500);
        }
    }

    /**
     * Get total aspirasi baru untuk badge/notifikasi
     */
    public function getCount()
    {
        try {
            $count = Pengaduan::whereHas('aspirasi', function ($q) {
                $q->where('status', 'baru');
            })->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);

        } catch (\Exception $e) {
            Log::error("Error AspirasiBaruController@getCount: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'count' => 0
            ], 500);
        }
    }
}
