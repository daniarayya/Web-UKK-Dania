<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Data diurutkan dari yang terbaru dibuat
        $users  = User::with('siswa')->orderBy('created_at', 'desc')->get();
        
        // Ambil semua siswa
        $siswas = Siswa::orderBy('nama')->get();
        
        // Ambil NISN yang sudah punya akun
        $usedNisn = User::whereNotNull('nisn')->pluck('nisn')->toArray();

        $siswaData = $siswas->map(function ($siswa) use ($usedNisn) {
            return [
                'nisn'    => $siswa->nisn,
                'nama'    => $siswa->nama,
                'kelas'   => $siswa->kelas,
                'jurusan' => $siswa->jurusan,
                'used'    => in_array($siswa->nisn, $usedNisn)
            ];
        });

        return view('admin.pengguna', compact('users','siswas','siswaData', 'usedNisn'));
    }

    public function store(Request $request)
    {
        // Validasi awal
        $request->validate([
            'username' => 'required|unique:users,username',
            'nama'     => 'nullable|string|max:100',
            'password' => 'required|min:5',
            'role'     => 'required|in:kepsek,admin,siswa',
            'nisn'     => 'nullable|exists:siswas,nisn'
        ]);

        // Validasi khusus untuk role siswa: cek apakah NISN sudah digunakan
        if ($request->role == 'siswa' && $request->nisn) {
            $existingUserWithNisn = User::where('nisn', $request->nisn)->first();
            if ($existingUserWithNisn) {
                return response()->json([
                    'success' => false,
                    'message' => 'NISN sudah digunakan oleh pengguna lain',
                    'errors' => [
                        'nisn' => ['NISN sudah digunakan oleh pengguna lain']
                    ]
                ], 422);
            }
        }

        // Validasi khusus untuk role siswa: pastikan NISN diisi
        if ($request->role == 'siswa' && empty($request->nisn)) {
            return response()->json([
                'success' => false,
                'message' => 'NISN wajib diisi untuk role siswa',
                'errors' => [
                    'nisn' => ['NISN wajib diisi untuk role siswa']
                ]
            ], 422);
        }

        // Jika role bukan siswa, hapus NISN dari data
        $nisn = ($request->role == 'siswa') ? $request->nisn : null;

        User::create([
            'username' => $request->username,
            'nama'     => $request->nama,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'nisn'     => $nisn
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan'
        ]);
    }

    public function update(Request $request, $id_user)
    {
        $user = User::findOrFail($id_user);

        // Validasi awal
        $request->validate([
            'username' => 'required|unique:users,username,' . $id_user . ',id_user',
            'nama'     => 'nullable|string|max:100',
            'role'     => 'required|in:kepsek,admin,siswa',
            'nisn'     => 'nullable|exists:siswas,nisn'
        ]);

        // Validasi khusus untuk role siswa: cek apakah NISN sudah digunakan oleh user lain
        if ($request->role == 'siswa' && $request->nisn) {
            $existingUserWithNisn = User::where('nisn', $request->nisn)
                                        ->where('id_user', '!=', $id_user)
                                        ->first();
            if ($existingUserWithNisn) {
                return response()->json([
                    'success' => false,
                    'message' => 'NISN sudah digunakan oleh pengguna lain',
                    'errors' => [
                        'nisn' => ['NISN sudah digunakan oleh pengguna lain']
                    ]
                ], 422);
            }
        }

        // Validasi khusus untuk role siswa: pastikan NISN diisi
        if ($request->role == 'siswa' && empty($request->nisn)) {
            return response()->json([
                'success' => false,
                'message' => 'NISN wajib diisi untuk role siswa',
                'errors' => [
                    'nisn' => ['NISN wajib diisi untuk role siswa']
                ]
            ], 422);
        }

        // Persiapkan data untuk update
        $data = [
            'username' => $request->username,
            'nama'     => $request->nama,
            'role'     => $request->role,
            'nisn'     => ($request->role == 'siswa') ? $request->nisn : null
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui'
        ]);
    }

    public function destroy($id_user)
    {
        User::findOrFail($id_user)->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }
}