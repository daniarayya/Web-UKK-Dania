<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $siswaQuery = Siswa::query();

        // Live search functionality
        if ($search) {
            $siswaQuery->where(function ($query) use ($search) {
                $query->where('nisn', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%")
                    ->orWhere('jurusan', 'like', "%{$search}%");
            });
        }

        // Order by created_at DESC agar data terbaru di atas
        $siswaQuery->orderBy('created_at', 'desc');

        // Pagination with 5 items per page
        $siswa = $siswaQuery->paginate(5);

        // Jika request AJAX, kembalikan JSON untuk live search
        if ($request->ajax()) {
            $siswaData = $siswa->map(function ($item) {
                $user = User::where('nisn', $item->nisn)->first();
                $item->user = $user ? [
                    'username' => $user->username,
                    'id_user' => $user->id_user
                ] : null;
                return $item;
            });

            return response()->json([
                'success' => true,
                'siswa' => $siswa
            ]);
        }

        return view('admin.siswa', compact('siswa', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|size:10|unique:siswas,nisn',
            'nama' => 'required|string|max:100',
            'kelas' => 'required|string|max:20',
            'jurusan' => 'required|string|max:50',
            'create_user_account' => 'nullable|boolean'
        ]);

        $siswa = Siswa::create($request->only('nisn', 'nama', 'kelas', 'jurusan'));

        if ($request->has('create_user_account') && $request->create_user_account == '1') {
            $userCreated = $this->createUserAccount($siswa);

            if (!$userCreated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data siswa berhasil ditambahkan, tetapi gagal membuat akun pengguna'
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil ditambahkan' .
                ($request->has('create_user_account') && $request->create_user_account == '1'
                    ? ' dan akun pengguna berhasil dibuat'
                    : '')
        ]);
    }

    private function createUserAccount(Siswa $siswa)
    {
        try {
            $namaDepan = Str::of($siswa->nama)->explode(' ')->first();

            $username = Str::lower(Str::slug($namaDepan, ''));

            $originalUsername = $username;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $originalUsername . $counter;
                $counter++;
            }

            $password = $siswa->nisn;

            User::create([
                'nama' => $siswa->nama,
                'username' => $username,
                'password' => Hash::make($password),
                'role' => 'siswa',
                'nisn' => $siswa->nisn
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('Gagal membuat akun pengguna: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    public function update(Request $request, $nisn)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'kelas' => 'required|string|max:20',
            'jurusan' => 'required|string|max:50',
        ]);

        $siswa = Siswa::where('nisn', $nisn)->firstOrFail();

        $siswa->update($request->only('nama', 'kelas', 'jurusan'));

        $user = User::where('nisn', $nisn)->first();
        if ($user) {
            $user->nama = $request->nama;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil diperbarui'
        ]);
    }

    public function destroy($nisn)
    {
        try {
            User::where('nisn', $nisn)->delete();

            $siswa = Siswa::where('nisn', $nisn)->firstOrFail();
            $siswa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data siswa dan akun pengguna terkait berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error('Gagal menghapus siswa: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data'
            ], 500);
        }
    }

    public function createAccount(Request $request)
    {
        $request->validate([
            'nisn' => 'required|exists:siswas,nisn',
        ]);

        $siswa = Siswa::where('nisn', $request->nisn)->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }

        if (User::where('nisn', $request->nisn)->exists()) {
            $user = User::where('nisn', $request->nisn)->first();
            return response()->json([
                'success' => false,
                'message' => 'Siswa sudah memiliki akun dengan username: ' . $user->username
            ]);
        }

        $created = $this->createUserAccount($siswa);

        if ($created) {
            $user = User::where('nisn', $request->nisn)->first();
            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil dibuat',
                'username' => $user->username
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat akun'
            ]);
        }
    }

    /**
     * Download template Excel untuk import siswa
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'TEMPLATE IMPORT DATA SISWA');
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Isi data siswa sesuai format di bawah. Jangan ubah nama kolom!');
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A2')->getFont()->setItalic(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $headers = ['NISN', 'NAMA LENGKAP', 'KELAS', 'JURUSAN'];
        $sheet->fromArray($headers, null, 'A4');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];
        $sheet->getStyle('A4:D4')->applyFromArray($headerStyle);

        $contohData = [
            ['1234567890', 'Budi Santoso', '10', 'RPL'],
            ['0987654321', 'Siti Aminah', '11', 'FKK'],
            ['1122334455', 'Ahmad Rizki', '12', 'BDP'],
        ];
        $sheet->fromArray($contohData, null, 'A5');

        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD']
                ]
            ]
        ];
        $sheet->getStyle('A5:D7')->applyFromArray($dataStyle);

        $sheet->setCellValue('A9', 'KETERANGAN:');
        $sheet->getStyle('A9')->getFont()->setBold(true);

        $keterangan = [
            ['NISN', 'Harus 10 digit angka, tidak boleh duplikat'],
            ['KELAS', 'Hanya boleh: 10, 11, atau 12'],
            ['JURUSAN', 'Hanya boleh: RPL, FKK, atau BDP'],
        ];

        $row = 10;
        foreach ($keterangan as $item) {
            $sheet->setCellValue('A' . $row, $item[0]);
            $sheet->setCellValue('B' . $row, $item[1]);
            $sheet->mergeCells('B' . $row . ':D' . $row);
            $row++;
        }

        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(15);

        $sheet->setAutoFilter('A4:D7');

        $filename = 'template-import-siswa-' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
            'create_accounts' => 'nullable|boolean'
        ]);

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('file')->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, false);

            if (count($rows) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'File Excel kosong.'
                ], 422);
            }

            // HEADER YANG DIPERBOLEHKAN
            $allowedHeaders = [
                'nisn' => 'nisn',

                'nama' => 'nama',
                'nama lengkap' => 'nama',
                'nama_lengkap' => 'nama',
                'namalengkap' => 'nama',

                'kelas' => 'kelas',

                'jurusan' => 'jurusan',
            ];

            // fungsi normalisasi header excel
            $normalize = function ($value) {
                $value = trim((string) $value);
                $value = strtolower($value);
                $value = preg_replace('/\s+/', ' ', $value); // rapihin spasi
                $value = preg_replace('/[^a-z0-9 _]/', '', $value); // buang simbol
                return trim($value);
            };

            $headerRowIndex = null;
            $headerMapping = [];

            // DETEKSI HEADER (cari baris yang punya minimal 2 kolom cocok)
            foreach ($rows as $index => $row) {
                $tempMapping = [];
                $matchCount = 0;

                foreach ($row as $colIndex => $cell) {
                    $headerName = $normalize($cell);

                    if (isset($allowedHeaders[$headerName])) {
                        $key = $allowedHeaders[$headerName];
                        $tempMapping[$key] = $colIndex;
                        $matchCount++;
                    }
                }

                if ($matchCount >= 2) {
                    $headerRowIndex = $index;
                    $headerMapping = $tempMapping;
                    break;
                }
            }

            if ($headerRowIndex === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Header tidak ditemukan. Pastikan file sesuai template (NISN, NAMA LENGKAP, KELAS, JURUSAN).'
                ], 422);
            }

            // pastikan kolom wajib ada
            $required = ['nisn', 'nama', 'kelas', 'jurusan'];
            foreach ($required as $col) {
                if (!isset($headerMapping[$col])) {
                    return response()->json([
                        'success' => false,
                        'message' => "Kolom '{$col}' tidak ditemukan. Pastikan file sesuai template."
                    ], 422);
                }
            }

            $importedCount = 0;
            $skippedCount = 0;
            $errors = [];

            // mulai baca data setelah header
            for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
                $row = $rows[$i];

                // stop kalau sudah masuk ke bagian KETERANGAN
                $firstCell = strtolower(trim((string)($row[0] ?? '')));
                if ($firstCell === 'keterangan:' || $firstCell === 'keterangan') {
                    break;
                }

                // skip baris kosong
                if (empty(array_filter($row, fn($v) => $v !== null && trim((string)$v) !== ''))) {
                    continue;
                }

                $nisn = trim((string)($row[$headerMapping['nisn']] ?? ''));
                $nama = trim((string)($row[$headerMapping['nama']] ?? ''));
                $kelas = trim((string)($row[$headerMapping['kelas']] ?? ''));
                $jurusan = strtoupper(trim((string)($row[$headerMapping['jurusan']] ?? '')));

                // validasi manual
                $validationErrors = $this->validateImportRow($nisn, $nama, $kelas, $jurusan);

                if (!empty($validationErrors)) {
                    $skippedCount++;
                    $errors[] = "Baris " . ($i + 1) . ": " . implode(", ", $validationErrors);
                    continue;
                }

                try {
                    \App\Models\Siswa::updateOrCreate(
                        ['nisn' => $nisn],
                        [
                            'nama' => $nama,
                            'kelas' => $kelas,
                            'jurusan' => $jurusan
                        ]
                    );

                    $importedCount++;
                } catch (\Exception $e) {
                    $skippedCount++;
                    $errors[] = "Baris " . ($i + 1) . ": " . $e->getMessage();
                }
            }

            $message = "Berhasil mengimport {$importedCount} data siswa.";
            if ($skippedCount > 0) {
                $message .= " {$skippedCount} data dilewati.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'imported' => $importedCount,
                    'skipped' => $skippedCount,
                    'errors' => array_slice($errors, 0, 10)
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error("Import siswa error: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat import: ' . $e->getMessage()
            ], 500);
        }
    }

    private function validateImportRow($nisn, $nama, $kelas, $jurusan)
    {
        $errors = [];

        // Validasi NISN
        if (empty($nisn)) {
            $errors[] = 'NISN kosong';
        } elseif (strlen($nisn) !== 10) {
            $errors[] = 'NISN harus 10 digit';
        } elseif (!is_numeric($nisn)) {
            $errors[] = 'NISN harus angka';
        } elseif (Siswa::where('nisn', $nisn)->exists()) {
            $errors[] = 'NISN sudah terdaftar';
        }

        // Validasi Nama
        if (empty($nama)) {
            $errors[] = 'Nama kosong';
        } elseif (strlen($nama) > 100) {
            $errors[] = 'Nama terlalu panjang (max 100 karakter)';
        }

        // Validasi Kelas
        if (empty($kelas)) {
            $errors[] = 'Kelas kosong';
        } elseif (!in_array($kelas, ['10', '11', '12'])) {
            $errors[] = 'Kelas tidak valid (harus 10, 11, atau 12)';
        }

        // Validasi Jurusan
        if (empty($jurusan)) {
            $errors[] = 'Jurusan kosong';
        } elseif (!in_array($jurusan, ['RPL', 'FKK', 'BDP'])) {
            $errors[] = 'Jurusan tidak valid (harus RPL, FKK, atau BDP)';
        }

        return $errors;
    }
}