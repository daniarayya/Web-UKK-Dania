<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Aspirasi Siswa</title>
    <style>
        /* RESET & BASIC STYLING */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000000;
            line-height: 1.6;
            background: #ffffff;
            padding: 0;
        }
        
        /* PAGE SETUP - MARGIN LEBIH LEGA */
        @page {
            size: A4 portrait;
            margin: 3cm 2.5cm 2.5cm;
            
            @top-left {
                content: "LAPORAN ASPIRASI SISWA";
                font-size: 10pt;
                font-weight: bold;
                color: #000000;
                margin-top: 1cm;
            }
            
            @top-right {
                content: "SMK MASKUMAMBANG 2 DUKUN";
                font-size: 10pt;
                font-weight: bold;
                color: #000000;
                margin-top: 1cm;
            }
            
            @bottom-center {
                content: "Halaman " counter(page) " dari " counter(pages);
                font-size: 10pt;
                color: #000000;
                margin-bottom: 1cm;
            }
        }
        
        .page {
            min-height: 29.7cm;
            padding: 0;
            position: relative;
        }
        
        .page-break {
            page-break-after: always;
            clear: both;
        }
        
        /* HEADER HALAMAN PERTAMA */
        .first-page-header {
            text-align: center;
            margin-bottom: 2.5cm;
            padding-top: 1cm;
        }
        
        .institution-name {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .institution-address {
            font-size: 11pt;
            margin-bottom: 20px;
            line-height: 1.4;
        }
        
        .report-title {
            font-size: 18pt;
            font-weight: bold;
            margin: 30px 0 15px 0;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 1.5px;
            padding: 10px 0;
        }
        
        .document-number {
            font-size: 11pt;
            margin-bottom: 25px;
            text-align: right;
        }
        
        /* KONTEN UTAMA - MARGIN LEBIH LEGA */
        .content-wrapper {
            width: 100%;
            max-width: 17cm;
            margin: 0 auto;
        }
        
        /* INFO CETAK */
        .print-info {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #000000;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 10px;
            font-size: 11pt;
        }
        
        .info-label {
            width: 150px;
            font-weight: bold;
            flex-shrink: 0;
        }
        
        .info-value {
            flex: 1;
            padding-left: 10px;
        }
        
        /* SECTION TITLE */
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            margin: 25px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #000000;
            text-transform: uppercase;
        }
        
        /* TABLE STYLING - MARGIN LEBIH LEGA */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0 20px 0;
            font-size: 11pt;
        }
        
        .data-table th {
            background-color: #f5f5f5;
            border: 1px solid #000000;
            padding: 10px 12px;
            text-align: left;
            font-weight: bold;
        }
        
        .data-table td {
            border: 1px solid #000000;
            padding: 10px 12px;
            vertical-align: top;
        }
        
        .data-label {
            font-weight: bold;
            width: 180px;
            background-color: #f9f9f9;
        }
        
        /* STATUS STYLING */
        .status {
            display: inline-block;
            padding: 4px 12px;
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 3px;
        }
        
        .status-menunggu {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .status-proses {
            background-color: #cce5ff;
            color: #004085;
            border: 1px solid #b8daff;
        }
        
        .status-selesai {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-ditolak {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* KETERANGAN BOX */
        .keterangan-box {
            padding: 15px;
            border: 1px solid #cccccc;
            margin: 15px 0 20px 0;
            background-color: #f9f9f9;
            font-size: 11pt;
            line-height: 1.6;
            white-space: pre-wrap;
            min-height: 80px;
        }
        
        /* FEEDBACK SECTION */
        .feedback-section {
            margin: 20px 0 25px 0;
        }
        
        .feedback-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #cccccc;
        }
        
        .feedback-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 10pt;
        }
        
        .feedback-content {
            padding: 10px 0 10px 15px;
            border-left: 3px solid #666666;
            margin-left: 5px;
            font-size: 11pt;
            line-height: 1.5;
        }
        
        /* FOTO CONTAINER */
        .foto-container {
            text-align: center;
            margin: 20px 0 25px 0;
            padding: 20px;
            border: 1px solid #cccccc;
            background-color: #f9f9f9;
        }
        
        .foto {
            max-width: 100%;
            max-height: 300px;
            display: block;
            margin: 0 auto;
        }
        
        .no-foto {
            color: #666666;
            font-style: italic;
            padding: 30px;
            font-size: 11pt;
        }
        
        /* SIGNATURE AREA */
        .signature-area {
            margin-top: 60px;
            padding-top: 20px;
            text-align: center;
        }
        
        .signature-line {
            width: 300px;
            border-top: 1px solid #000000;
            margin: 50px auto 10px;
        }
        
        .signature-text {
            font-size: 11pt;
            margin-bottom: 5px;
        }
        
        /* NO DATA */
        .no-data {
            text-align: center;
            padding: 100px 50px;
            font-size: 12pt;
            font-style: italic;
            color: #666666;
            border: 1px dashed #cccccc;
            margin: 50px auto;
            max-width: 15cm;
        }
        
        /* UTILITIES */
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .bold {
            font-weight: bold;
        }
        
        .italic {
            font-style: italic;
        }
        
        .mt-30 {
            margin-top: 30px;
        }
        
        .mb-30 {
            margin-bottom: 30px;
        }
        
        .ml-10 {
            margin-left: 10px;
        }
        
        .mr-10 {
            margin-right: 10px;
        }
        
        /* PRINT OPTIMIZATION */
        @media print {
            body {
                font-size: 11pt;
            }
            
            .page {
                min-height: 27.7cm;
            }
            
            .content-wrapper {
                max-width: 100%;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <!-- HALAMAN PERTAMA -->
    <div class="page">
        <!-- HEADER -->
        <div class="first-page-header">
            <div class="institution-name">SMK MASKUMAMBANG 2 DUKUN</div>
            <div class="institution-address">
                Jalan Raya Dukun No. 123, Dukun, Gresik, Jawa Timur<br>
                Telp: (031) 123456 | Email: smk_maskumambang2@sch.id
            </div>
           
            <div class="report-title">Laporan Aspirasi dan Pengaduan Siswa</div>
        </div>
        
        <!-- KONTEN UTAMA -->
        <div class="content-wrapper">
            <!-- INFO CETAK -->
            <div class="print-info">
                <div class="info-row">
                    <div class="info-label">Dicetak Oleh</div>
                    <div class="info-value">{{ $kepsek->nama ?? 'Kepala Sekolah' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Cetak</div>
                    <div class="info-value">{{ $tanggal_cetak }}</div>
                </div>
            </div>
            
            @if(count($pengaduans) > 0)
                <!-- DAFTAR LAPORAN -->
                <div class="section-title">DAFTAR LAPORAN ASPIRASI SISWA</div>
                
                @foreach($pengaduans as $index => $p)
                    @if($index > 0)
                    <!-- PAGE BREAK untuk data selanjutnya -->
                    <div class="page-break"></div>
                    <div class="page">
                        <div class="content-wrapper">
                    @endif
                    
                    <!-- HEADER LAPORAN -->
                    <table class="data-table">
                        <tr>
                            <th colspan="2" style="background-color: #e8e8e8;">
                                LAPORAN #{{ $index + 1 }} - ID: {{ $p->id_input_aspirasi }}
                            </th>
                        </tr>
                        <tr>
                            <td class="data-label">Tanggal Laporan</td>
                            <td>{{ \Carbon\Carbon::parse($p->created_at)->translatedFormat('l, d F Y - H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="data-label">Status</td>
                            <td>
                                @php
                                    $status = $p->aspirasi->status ?? 'menunggu';
                                    $statusClass = 'status-' . strtolower($status);
                                @endphp
                                <span class="status {{ $statusClass }}">
                                    {{ strtoupper($status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                    
                    <!-- DATA SISWA -->
                    <div class="section-title">DATA PELAPOR</div>
                    <table class="data-table">
                        <tr>
                            <td class="data-label">Nama Siswa</td>
                            <td>{{ $p->siswa->nama ?? 'Tidak tersedia' }}</td>
                        </tr>
                        <tr>
                            <td class="data-label">Nomor Induk (NISN)</td>
                            <td>{{ $p->siswa->nisn ?? 'Tidak tersedia' }}</td>
                        </tr>
                        <tr>
                            <td class="data-label">Kelas</td>
                            <td>{{ $p->siswa->kelas ?? 'Tidak tersedia' }}</td>
                        </tr>
                        <tr>
                            <td class="data-label">Kategori Aspirasi</td>
                            <td>{{ $p->kategori->nama_kategori ?? 'Tidak dikategorikan' }}</td>
                        </tr>
                        <tr>
                            <td class="data-label">Lokasi</td>
                            <td>{{ $p->lokasi ?? 'Tidak ditentukan' }}</td>
                        </tr>
                    </table>
                    
                    <!-- KETERANGAN -->
                    <div class="section-title mt-30">KETERANGAN ASPIRASI</div>
                    <div class="keterangan-box">
                        {{ $p->keterangan ?? 'Tidak ada keterangan yang diberikan.' }}
                    </div>
                    
                    <!-- FEEDBACK -->
                    @if($p->aspirasi && $p->aspirasi->feedbacks->count() > 0)
                    <div class="feedback-section">
                        <div class="section-title">TINDAK LANJUT DAN FEEDBACK</div>
                        @foreach($p->aspirasi->feedbacks as $feedback)
                            <div class="feedback-item">
                                <div class="feedback-header">
                                    <span class="bold">{{ $feedback->user->nama ?? 'N/A' }}</span>
                                    <span>{{ \Carbon\Carbon::parse($feedback->created_at)->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="feedback-content">
                                    {{ $feedback->isi ?? '' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif
                    
                    <!-- FOTO -->
                    <div class="section-title mt-30">DOKUMENTASI PENDUKUNG</div>
                    <div class="foto-container">
                        @php
                            $hasFoto = !empty($p->foto);
                            $imageSrc = null;
                            
                            if ($hasFoto) {
                                $filename = basename($p->foto);
                                $possiblePaths = [
                                    storage_path('app/public/pengaduan/' . $filename),
                                    public_path('storage/pengaduan/' . $filename),
                                    public_path('storage/' . $p->foto),
                                    storage_path('app/public/' . $p->foto)
                                ];
                                
                                $found = false;
                                foreach ($possiblePaths as $filePath) {
                                    if (file_exists($filePath)) {
                                        $imageData = base64_encode(file_get_contents($filePath));
                                        $imageSrc = 'data:image/jpeg;base64,' . $imageData;
                                        $found = true;
                                        break;
                                    }
                                }
                                
                                if (!$found) {
                                    $hasFoto = false;
                                }
                            }
                        @endphp
                        
                        @if($hasFoto && $imageSrc)
                            <img class="foto" src="{{ $imageSrc }}" alt="Foto Bukti">
                            <div class="text-center mt-30 italic">
                                Lampiran Foto: {{ $filename }}
                            </div>
                        @else
                            <div class="no-foto">
                                Tidak ada foto dokumentasi yang dilampirkan
                            </div>
                        @endif
                    </div>
            
                    @if($index > 0)
                        </div> <!-- tutup content-wrapper -->
                    </div> <!-- tutup page -->
                    @endif
                    
                @endforeach
                
            @else
            <!-- TIDAK ADA DATA -->
            <div class="no-data">
                <p>TIDAK ADA DATA LAPORAN</p>
                <p>Tidak ditemukan data aspirasi atau pengaduan siswa untuk periode yang dipilih.</p>
            </div>
            @endif
        </div>
    </div>

</body>
</html>