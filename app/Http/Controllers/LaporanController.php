<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    private function filteredQuery(Request $request)
    {
        $query = Pengaduan::with([
            'siswa',
            'kategori',
            'aspirasi',
            'aspirasi.feedbacks.user'
        ])
        ->whereHas('aspirasi', function($q){
            $q->whereIn('status', ['menunggu', 'proses', 'selesai', 'ditolak']);
        })
        ->orderBy('created_at', 'desc');

        // Filter tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->whereHas('aspirasi', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('siswa', function ($sub) use ($search) {
                    $sub->where('nama', 'like', "%$search%")
                        ->orWhere('nisn', 'like', "%$search%");
                })
                ->orWhere('keterangan', 'like', "%$search%")
                ->orWhere('lokasi', 'like', "%$search%")
                ->orWhereHas('kategori', function ($sub) use ($search) {
                    $sub->where('nama_kategori', 'like', "%$search%");
                });
            });
        }

        return $query;
    }

    public function index(Request $request)
    {
        $perPage = 5; // Tentukan perPage dengan jelas
        
        $pengaduans = $this->filteredQuery($request)
            ->paginate($perPage)
            ->withQueryString();

        $filters = [
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'search' => $request->search,
            'status' => $request->status,
        ];

        return view('admin.laporan', compact('pengaduans', 'filters'));
    }
    
    public function preview(Request $request)
    {
        $pengaduans = $this->filteredQuery($request)->get();

        $data = [
            'pengaduans'      => $pengaduans,
            'admin'           => Auth::user(),
            'tanggal_cetak'  => now()->format('d/m/Y H:i')
        ];

        $pdf = Pdf::loadView('admin.laporan-pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);

        return $pdf->stream('laporan-aspirasi-' . date('YmdHis') . '.pdf');
    }

    // =========================
    // PREVIEW PDF DATA TERPILIH
    // =========================
    public function previewSelected(Request $request)
    {
        // Validasi apakah ada data terpilih
        if (!$request->has('selected_ids') || empty($request->selected_ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih');
        }

        // Ambil data berdasarkan ID yang dipilih
        $pengaduans = Pengaduan::with([
            'siswa',
            'kategori',
            'aspirasi',
            'aspirasi.feedbacks.user'
        ])
        ->whereIn('id_input_aspirasi', $request->selected_ids)
        ->orderBy('created_at', 'desc')
        ->get();

        $data = [
            'pengaduans'      => $pengaduans,
            'admin'           => Auth::user(),
            'tanggal_cetak'  => now()->format('d/m/Y H:i')
        ];

        $pdf = Pdf::loadView('admin.laporan-pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);

        return $pdf->stream('laporan-aspirasi-terpilih-' . date('YmdHis') . '.pdf');
    }
}