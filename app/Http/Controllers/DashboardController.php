<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Aspirasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index()
    {
        // Aspirasi baru (belum diverifikasi)
        $aspirasiBaru = Aspirasi::where('status', 'baru')->count();

        // Aspirasi menunggu
        $aspirasiMenunggu = Aspirasi::where('status', 'menunggu')->count();

        // Aspirasi proses
        $aspirasiProses = Aspirasi::where('status', 'proses')->count();

        // Aspirasi selesai
        $aspirasiSelesai = Aspirasi::where('status', 'selesai')->count();

        // Aspirasi ditolak
        $aspirasiDitolak = Aspirasi::where('status', 'ditolak')->count();


        // Grafik 7 hari terakhir
        $grafikData = Aspirasi::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        $chartLabels = [];
        $chartData   = [];

        $periode = CarbonPeriod::create(now()->subDays(6), now());

        foreach ($periode as $date) {
            $tanggal = $date->format('Y-m-d');
            $label   = $date->format('d M');

            $data = $grafikData->firstWhere('tanggal', $tanggal);

            $chartLabels[] = $label;
            $chartData[]   = $data ? $data->total : 0;
        }


        // Aktivitas terbaru (pengaduan terbaru)
        $aktivitasTerbaru = Pengaduan::with(['siswa', 'kategori', 'aspirasi'])
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($item) {
                $item->type = 'pengaduan';
                return $item;
            });


        // Statistik kategori (1 bulan terakhir)
        $kategoriStatistik = Kategori::withCount(['pengaduan' => function ($query) {
            $query->where('created_at', '>=', now()->subMonth());
        }])
        ->orderByDesc('pengaduan_count')
        ->take(3)
        ->get();


        return view('admin.dashboard', compact(
            'aspirasiBaru',
            'aspirasiMenunggu',
            'aspirasiProses',
            'aspirasiSelesai',
            'aspirasiDitolak',
            'chartLabels',
            'chartData',
            'aktivitasTerbaru',
            'kategoriStatistik'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'nama'     => 'sometimes|required|string|max:255',
            'username' => 'sometimes|nullable|string|max:255',
            'password' => 'sometimes|nullable|min:5|confirmed',
        ];

        if ($request->has('username') && $request->username !== $user->username) {
            $rules['username'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ];
        }

        $validator = Validator::make($request->all(), $rules, [
            'nama.required' => 'Nama wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'password.min' => 'Password minimal 5 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            if ($request->has('nama')) {
                $user->nama = trim($request->nama);
            }

            if ($request->has('username') && $request->username !== $user->username) {
                $user->username = trim($request->username);
            }

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'user' => [
                    'nama'     => $user->nama,
                    'username' => $user->username,
                    'role'     => $user->role ?? 'admin',
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
            ], 500);
        }
    }
}
