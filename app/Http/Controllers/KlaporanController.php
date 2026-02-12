<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class KlaporanController extends Controller
{
    public function index(Request $request)
    {
        $pengaduans = $this->filteredQuery($request)
            ->paginate(5)
            ->withQueryString();

        $filters = [
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'search' => $request->search,
            'status' => $request->status,
        ];

        // Hitung offset dan perPage untuk pagination
        $perPage = $pengaduans->perPage();
        $currentPage = $pengaduans->currentPage();
        $offset = ($currentPage - 1) * $perPage;

        return view('kepsek.laporan', compact('pengaduans', 'filters', 'perPage', 'currentPage', 'offset'));
    }

    private function filteredQuery(Request $request)
    {
        $query = Pengaduan::query()
            ->with([
                'siswa:nisn,nama,kelas,jurusan',
                'kategori:id_kategori,nama_kategori',
                'aspirasi:id_aspirasi,id_input_aspirasi,status,created_at,updated_at',
                'aspirasi.feedbacks:id_feedback,id_aspirasi,id_user,isi,created_at',
                'aspirasi.feedbacks.user:id_user,nama,role'
            ])
            ->whereHas('aspirasi', function ($q) {
                $q->whereIn('status', ['menunggu', 'proses', 'selesai', 'ditolak']);
            })
            ->latest('created_at');

        // Filter tanggal mulai
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        // Filter tanggal selesai
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->whereHas('aspirasi', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('keterangan', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%")
                    ->orWhereHas('siswa', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%")
                            ->orWhere('nisn', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kategori', function ($kq) use ($search) {
                        $kq->where('nama_kategori', 'like', "%{$search}%");
                    })
                    ->orWhereHas('aspirasi.feedbacks', function ($fq) use ($search) {
                        $fq->where('isi', 'like', "%{$search}%");
                    });
            });
        }

        return $query;
    }

    public function preview(Request $request)
    {
        $pengaduans = $this->filteredQuery($request)->get();

        if ($pengaduans->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data untuk dicetak');
        }

        $data = [
            'pengaduans'     => $pengaduans,
            'user'           => Auth::user(),
            'tanggal_cetak'  => now()->format('d/m/Y H:i'),
            'filter_tanggal' => [
                'mulai' => $request->tanggal_mulai,
                'selesai' => $request->tanggal_selesai
            ]
        ];

        $pdf = Pdf::loadView('kepsek.laporan-pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif'
            ]);

        return $pdf->stream('laporan-aspirasi-' . date('YmdHis') . '.pdf');
    }

    public function previewSelected(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:input_aspirasis,id_input_aspirasi'
        ]);

        $pengaduans = Pengaduan::with([
            'siswa:nisn,nama,kelas,jurusan',
            'kategori:id_kategori,nama_kategori',
            'aspirasi:id_aspirasi,id_input_aspirasi,status,created_at,updated_at',
            'aspirasi.feedbacks:id_feedback,id_aspirasi,id_user,isi,created_at',
            'aspirasi.feedbacks.user:id_user,nama,role'
        ])
        ->whereIn('id_input_aspirasi', $request->selected_ids)
        ->whereHas('aspirasi', function ($q) {
            $q->whereIn('status', ['menunggu', 'proses', 'selesai', 'ditolak']);
        })
        ->latest('created_at')
        ->get();

        if ($pengaduans->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih');
        }

        $data = [
            'pengaduans'     => $pengaduans,
            'user'           => Auth::user(),
            'tanggal_cetak'  => now()->format('d/m/Y H:i'),
            'is_selected'    => true
        ];

        $pdf = Pdf::loadView('kepsek.laporan-pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif'
            ]);

        return $pdf->stream('laporan-aspirasi-terpilih-' . date('YmdHis') . '.pdf');
    }

    public function exportPdf(Request $request)
    {
        $pengaduans = $this->filteredQuery($request)->get();

        if ($pengaduans->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data untuk diexport');
        }

        $data = [
            'pengaduans'     => $pengaduans,
            'user'           => Auth::user(),
            'tanggal_cetak'  => now()->format('d/m/Y H:i'),
            'filter_tanggal' => [
                'mulai' => $request->tanggal_mulai,
                'selesai' => $request->tanggal_selesai
            ]
        ];

        $pdf = Pdf::loadView('kepsek.laporan.pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif'
            ]);

        return $pdf->download('laporan-aspirasi-' . date('YmdHis') . '.pdf');
    }

    public function previewSingle(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:input_aspirasis,id_input_aspirasi'
        ]);

        $pengaduan = Pengaduan::with([
            'siswa:nisn,nama,kelas,jurusan',
            'kategori:id_kategori,nama_kategori',
            'aspirasi:id_aspirasi,id_input_aspirasi,status,created_at,updated_at',
            'aspirasi.feedbacks:id_feedback,id_aspirasi,id_user,isi,created_at',
            'aspirasi.feedbacks.user:id_user,nama,role'
        ])
        ->whereHas('aspirasi', function ($q) {
            $q->whereIn('status', ['menunggu', 'proses', 'selesai', 'ditolak']);
        })
        ->where('id_input_aspirasi', $request->id)
        ->firstOrFail();

        $data = [
            'pengaduan'      => $pengaduan,
            'user'           => Auth::user(),
            'tanggal_cetak'  => now()->format('d/m/Y H:i'),
            'is_single'      => true
        ];

        $pdf = Pdf::loadView('kepsek.laporan-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif'
            ]);

        return $pdf->stream('laporan-aspirasi-' . $pengaduan->id_input_aspirasi . '-' . date('YmdHis') . '.pdf');
    }
}