@extends('layouts.kepsek')

@section('title', 'SUARAKITA')
@section('page-title', 'Laporan')
@section('page-description', 'Daftar laporan aspirasi dan pengaduan siswa')
@section('header-title')
<div>
    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Laporan Aspirasi</h2>
    <p class="text-gray-600 text-sm md:text-base">Kelola seluruh aspirasi dari siswa dengan mudah</p>
</div>
@endsection

@php
    function getStatusClass($status) {
        return match(strtolower($status)) {
            'menunggu' => 'bg-yellow-100 text-yellow-800',
            'proses'   => 'bg-blue-100 text-blue-800',
            'selesai'  => 'bg-green-100 text-green-800',
            'ditolak'  => 'bg-red-100 text-red-800',
            default    => 'bg-gray-100 text-gray-800',
        };
    }

    function getStatusText($status) {
        return match(strtolower($status)) {
            'menunggu' => 'Menunggu',
            'proses'   => 'Proses',
            'selesai'  => 'Selesai',
            'ditolak'  => 'Ditolak',
            default    => 'Menunggu',
        };
    }

    function getKategoriClass($kategori) {
        return match(strtolower($kategori)) {
            'pengaduan' => 'bg-red-100 text-red-800 border-red-200',
            'saran'     => 'bg-blue-100 text-blue-800 border-blue-200',
            default => 'bg-yellow-100 text-yellow-800 border-yellow-200'
        };
    }

    function getKategoriIcon($kategori) {
        return match(strtolower($kategori)) {
            'pengaduan' => 'fas fa-exclamation-circle',
            'saran'     => 'fas fa-lightbulb',
            default => 'fas fa-bullhorn'
        };
    }
@endphp

@push('styles')
<style>
    .truncate-2-lines {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .kategori-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 16px;
        font-size: 0.7rem;
        font-weight: 600;
        border: 1px solid;
        margin-top: 4px;
        margin-right: 6px;
    }
    
    .selection-info {
        background-color: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
    }
    
    .table-row.selected {
        background-color: #f0fdf4 !important;
    }
    
    .table-header {
        background-color: #f8fafc;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
    }
    
    .table-row:hover {
        background-color: #f9fafb;
    }
    
    .foto-placeholder {
        width: 100%;
        height: 200px;
        background-color: #f3f4f6;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
    }
    
    .foto-image {
        max-width: 100%;
        max-height: 400px;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    
    .foto-image:hover {
        transform: scale(1.02);
    }
    
    .foto-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 0.5rem;
    }
    
    .spinner {
        display: inline-block;
        width: 2rem;
        height: 2rem;
        border: 3px solid rgba(34, 197, 94, 0.3);
        border-radius: 50%;
        border-top-color: #22c55e;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* ===== MODAL STYLING ===== */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 20px;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-content {
        background-color: white;
        border-radius: 12px;
        width: 100%;
        max-width: 700px; /* SAMA dengan modal sebelumnya */
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        animation: modalFadeIn 0.25s ease-out;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* ===== LIGHTBOX ===== */
    .lightbox {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.9);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    }

    .lightbox.show {
        display: flex;
    }

    .lightbox-img {
        max-width: 90vw;
        max-height: 90vh;
        object-fit: contain;
    }

    .lightbox-close {
        position: absolute;
        top: 20px;
        right: 20px;
        color: white;
        font-size: 2rem;
        background: none;
        border: none;
        cursor: pointer;
        z-index: 10001;
    }

    /* ===== KETERANGAN CARD ===== */
    .keterangan-card {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-left: 4px solid #3b82f6;
        padding: 1rem;
        border-radius: 8px;
        margin-top: 0.5rem;
    }

    .keterangan-text {
        line-height: 1.6;
        color: #4b5563;
        font-size: 0.875rem;
    }

    .keterangan-text strong {
        color: #1f2937;
        font-weight: 600;
    }

    .keterangan-text em {
        color: #6b7280;
        font-style: italic;
    }

    /* ===== STATUS BADGE ===== */
    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-align: center;
        min-width: 100px;
    }

    /* ===== ACTION BUTTONS ===== */
    .btn-action {
        padding: 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
    }
    
    .btn-view {
        background-color: #3b82f6;
        color: white;
    }
    
    .btn-view:hover {
        background-color: #2563eb;
    }
    
    /* ===== CATEGORY INFO IN TABLE ===== */
    .category-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .category-row {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .location-info {
        color: #6b7280;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 2px;
    }
</style>
@endpush

@section('content')

<!-- Selection Info -->
<div id="selection-info" class="selection-info p-3 mb-4 hidden">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-2"></i>
            <span id="selected-count" class="font-medium">0</span>
            <span class="ml-1">data dipilih</span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="selectAllAspirasi()" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                <i class="fas fa-check-double mr-1"></i> Pilih Semua
            </button>
            <button onclick="deselectAllAspirasi()" class="text-sm text-red-600 hover:text-red-800 flex items-center ml-3">
                <i class="fas fa-times mr-1"></i> Batal Pilih
            </button>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form id="filter-form" method="GET" action="{{ route('kepsek.laporan.index') }}" class="mb-0">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

            <!-- Search -->
            <div class="w-full md:w-auto">
                <div class="relative">
                    <input type="text" id="search-input"
                        name="search"
                        placeholder="Cari aspirasi..."
                        value="{{ $filters['search'] ?? '' }}"
                        class="w-full md:w-64 px-4 py-2 pl-10 pr-10 border border-gray-300 rounded-lg 
                            focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-600"
                        onkeyup="debouncedFilter()">

                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>

                    <div id="search-loading" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>

            <!-- Filter tanggal + export -->
            <div class="flex items-center gap-3 w-full md:w-auto">
                <div class="flex items-center gap-2 bg-gray-50 p-2 rounded-lg">
                    <input type="date"
                           id="tanggal-mulai"
                           name="tanggal_mulai"
                           value="{{ $filters['tanggal_mulai'] ?? '' }}"
                           class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-600 bg-white"
                           onchange="applyFilter()">

                    <span class="text-gray-500 text-sm">s/d</span>

                    <input type="date"
                           id="tanggal-selesai"
                           name="tanggal_selesai"
                           value="{{ $filters['tanggal_selesai'] ?? '' }}"
                           class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-600 bg-white"
                           onchange="applyFilter()">
                </div>

                <button type="button"
                    onclick="exportToPDF()"
                    class="px-4 py-2 bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-lg hover:opacity-90 transition duration-200 flex items-center shadow">
                    <i class="fas fa-file-pdf mr-2"></i> Export
                </button>
            </div>

        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
    <div class="table-container overflow-x-auto">
        <table class="w-full min-w-full">
            <thead>
                <tr class="table-header">
                    <th class="py-3 px-4 text-left w-12">
                        <input type="checkbox" id="select-all"
                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                    </th>
                    <th class="py-3 px-4 text-left text-gray-600 w-12">No</th>
                    <th class="py-3 px-4 text-left text-gray-600 min-w-48">Nama Siswa</th>
                    <th class="py-3 px-4 text-left text-gray-600">Keterangan</th>
                    <th class="py-3 px-4 text-left text-gray-600 w-32">Status</th>
                    <th class="py-3 px-4 text-left text-gray-600 w-32">Tanggal</th>
                    <th class="py-3 px-4 text-left text-gray-600 w-20">Aksi</th>
                </tr>
            </thead>

            <tbody id="aspirasi-table-body" class="divide-y divide-gray-200">
                @php
                    $counter = ($pengaduans->currentPage() - 1) * $pengaduans->perPage() + 1;
                @endphp

                @forelse ($pengaduans as $p)
                    @php
                        $aspirasi = $p->aspirasi;
                        $feedbacks = $aspirasi ? $aspirasi->feedbacks : collect();

                        $feedbackArray = [];
                        if ($feedbacks->count() > 0) {
                            foreach ($feedbacks as $feedback) {
                                $feedbackArray[] = [
                                    'isi' => $feedback->isi ?? '',
                                    'created_at' => $feedback->created_at ?? now(),
                                    'is_admin' => $feedback->user && $feedback->user->role === 'admin',
                                    'admin_name' => $feedback->user ? $feedback->user->nama : 'N/A'
                                ];
                            }
                        }

                        $hasFoto = !empty($p->foto) && $p->foto !== 'null' && trim($p->foto) !== '';

                        if ($hasFoto) {
                            $filename = basename($p->foto);

                            if (strpos($filename, '/') !== false) {
                                $filename = substr($filename, strrpos($filename, '/') + 1);
                            }

                            $fotoUrl = asset('storage/pengaduan/' . $filename);
                        } else {
                            $fotoUrl = asset('images/default-image.png');
                        }

                        $status = $aspirasi?->status ?? 'menunggu';
                        $statusClass = getStatusClass($status);
                        $statusText = getStatusText($status);
                        
                        $kategoriNama = $p->kategori->nama_kategori ?? 'Lainnya';
                        $kategoriClass = getKategoriClass($kategoriNama);
                        $kategoriIcon = getKategoriIcon($kategoriNama);
                    @endphp

                    <tr class="table-row hover:bg-gray-50 transition-colors duration-200 aspirasi-row">
                        <td class="py-3 px-4 w-12">
                            <input type="checkbox"
                                   name="selected_ids[]"
                                   value="{{ $p->id_input_aspirasi }}"
                                   class="aspirasi-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500"
                                   data-id="{{ $p->id_input_aspirasi }}">
                        </td>

                        <td class="py-3 px-4 text-gray-800 font-medium w-12">{{ $counter++ }}</td>

                        <td class="py-3 px-4 min-w-48">
                            <div class="font-medium text-gray-800 aspirasi-nama">
                                {{ $p->siswa->nama ?? 'N/A' }}
                            </div>

                            <div class="text-xs text-gray-600">
                                {{ $p->siswa->nisn ?? 'N/A' }}
                            </div>

                            @if($hasFoto)
                                <div>
                                    <span class="foto-badge">
                                        <i class="fas fa-camera mr-1"></i>Foto
                                    </span>
                                </div>
                            @endif
                        </td>

                        <td class="py-3 px-4">
                            <div class="text-gray-800 aspirasi-keterangan truncate-2-lines mb-2">
                                {{ Str::limit($p->keterangan, 120) }}
                            </div>
                            <div class="category-info">
                                <div class="category-row">
                                    <span class="kategori-badge {{ $kategoriClass }}">
                                        <i class="{{ $kategoriIcon }} mr-1"></i> {{ $kategoriNama }}
                                    </span>
                                </div>
                                @if($p->lokasi)
                                <div class="location-info">
                                    <i class="fas fa-map-marker-alt"></i> {{ $p->lokasi }}
                                </div>
                                @endif
                            </div>
                        </td>

                        <td class="py-3 px-4 w-32">
                            <span class="status-badge {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>

                        <td class="py-3 px-4 text-gray-800 w-32">
                            <div class="text-sm">
                                {{ $p->created_at->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-600">
                                {{ $p->created_at->format('H:i') }}
                            </div>
                        </td>

                        <td class="py-3 px-4 w-20">
                            <div class="action-buttons">
                                <button class="btn-action btn-view view-detail-btn"
                                        data-id="{{ $p->id_input_aspirasi }}"
                                        data-nama="{{ $p->siswa->nama ?? 'N/A' }}"
                                        data-nisn="{{ $p->siswa->nisn ?? 'N/A' }}"
                                        data-kelas="{{ $p->siswa->kelas ?? 'N/A' }}"
                                        data-jurusan="{{ $p->siswa->jurusan ?? 'N/A' }}"
                                        data-kategori="{{ $kategoriNama }}"
                                        data-keterangan="{{ $p->keterangan }}"
                                        data-lokasi="{{ $p->lokasi ?? 'Tidak ditentukan' }}"
                                        data-status="{{ $status }}"
                                        data-foto="{{ $fotoUrl }}"
                                        data-has_foto="{{ $hasFoto ? '1' : '0' }}"
                                        data-feedback="{{ json_encode($feedbackArray) }}"
                                        data-created_at="{{ $p->created_at->format('Y-m-d H:i:s') }}"
                                        data-updated_at="{{ ($aspirasi?->updated_at ?? $p->created_at)->format('Y-m-d H:i:s') }}"
                                        title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 px-4 text-center text-gray-600">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                <p class="text-lg font-medium">Tidak ada data laporan</p>
                                <p class="text-sm text-gray-500">Belum ada aspirasi yang tercatat</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($pengaduans->count() > 0)
<div class="flex flex-col md:flex-row justify-between items-center mt-6">
    <div class="text-sm text-gray-600 mb-4 md:mb-0">
        Menampilkan {{ $pengaduans->firstItem() }} - {{ $pengaduans->lastItem() }} dari {{ $pengaduans->total() }} data
    </div>

    <div class="flex items-center space-x-1">
        @if($pengaduans->onFirstPage())
            <span class="px-3 py-1 border border-gray-300 rounded text-gray-400">
                <i class="fas fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $pengaduans->previousPageUrl() }}"
               class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-100 text-gray-600">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
        @endif

        @foreach($pengaduans->getUrlRange(1, $pengaduans->lastPage()) as $page => $url)
            @if($page == $pengaduans->currentPage())
                <span class="px-3 py-1 bg-green-600 text-white rounded">{{ $page }}</span>
            @else
                <a href="{{ $url }}"
                   class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-100 text-gray-600">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        @if($pengaduans->hasMorePages())
            <a href="{{ $pengaduans->nextPageUrl() }}"
               class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-100 text-gray-600">
                <i class="fas fa-chevron-right text-xs"></i>
            </a>
        @else
            <span class="px-3 py-1 border border-gray-300 rounded text-gray-400">
                <i class="fas fa-chevron-right text-xs"></i>
            </span>
        @endif
    </div>
</div>
@endif

@endsection

@section('modals')
<!-- Modal Detail Aspirasi -->
<div id="detail-modal" class="modal-overlay">
    <div class="modal-content" style="max-width: 700px;" >
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Detail Aspirasi</h3>
                <button id="close-detail-modal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <!-- Grid Informasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Info Siswa -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-user-graduate mr-2 text-blue-500"></i>
                            Informasi Siswa
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">Nama Siswa</p>
                                <p class="font-medium text-sm" id="detail-nama">N/A</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-gray-500">NISN</p>
                                    <p class="font-medium text-sm" id="detail-nisn">N/A</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Kelas</p>
                                    <p class="font-medium text-sm" id="detail-kelas">-</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Jurusan</p>
                                <p class="font-medium text-sm" id="detail-jurusan">-</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Info Aspirasi -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-comment-dots mr-2 text-green-500"></i>
                            Informasi Aspirasi
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">Kategori</p>
                                <p class="font-medium text-sm">
                                    <span id="detail-kategori-badge" class="px-2 py-1 rounded text-xs font-medium">N/A</span>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Lokasi</p>
                                <p class="font-medium text-sm" id="detail-lokasi">Tidak ditentukan</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-gray-500">Status</p>
                                    <p class="font-medium text-sm">
                                        <span id="detail-status-badge" class="px-2 py-1 rounded text-xs font-medium">N/A</span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Tanggal Diajukan</p>
                                    <p class="font-medium text-sm" id="detail-tanggal-ajukan">N/A</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Waktu -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>
                            Waktu
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">Tanggal Diajukan</p>
                                <p class="font-medium text-sm" id="detail-tanggal-pengajuan">N/A</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tanggal Update Terakhir</p>
                                <p class="font-medium text-sm" id="detail-tanggal-update">N/A</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Foto Aspirasi (jika ada) -->
                    <div id="detail-foto-section" class="bg-gray-50 p-4 rounded-lg hidden">
                        <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-camera mr-2 text-yellow-500"></i>
                            Foto Pendukung
                        </h4>
                        <div id="detail-foto-container" class="mt-2">
                            <img id="detail-foto" src="" alt="Foto pendukung aspirasi" 
                                class="w-full h-48 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition-opacity"
                                onclick="openLightbox(this.src)">
                            <p class="text-xs text-gray-500 mt-1 text-center">Klik untuk memperbesar</p>
                        </div>
                    </div>
                </div>
                
                <!-- Isi Aspirasi -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-file-alt mr-2 text-red-500"></i>
                        Isi Aspirasi
                    </h4>
                    <div class="bg-white p-3 rounded border border-gray-200">
                        <div class="keterangan-text" id="detail-keterangan">N/A</div>
                    </div>
                </div>
                
                <!-- Riwayat Feedback -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-exchange-alt mr-2 text-indigo-500"></i>
                        Riwayat Feedback
                    </h4>
                    <div id="feedback-history" class="space-y-3 max-h-40 overflow-y-auto p-2">
                        <div class="text-center py-4 text-gray-500 text-sm">Memuat feedback...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox untuk foto -->
<div id="lightbox" class="lightbox">
    <button class="lightbox-close">&times;</button>
    <img id="lightbox-image" class="lightbox-img" src="" alt="">
</div>
@endsection

@push('scripts')
<script>
    let selectedAspirasiIds = [];
    let filterTimeout = null;

    // Fungsi untuk format teks keterangan
    function formatKeterangan(text) {
        if (!text) return 'Tidak ada keterangan';
        
        // Ganti karakter khusus
        text = text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
        
        // Highlight kata-kata penting
        const keywords = [
            { word: 'pengaduan', color: '#ef4444', icon: '🚨' },
            { word: 'saran', color: '#3b82f6', icon: '💡' },
            { word: 'pemberitahuan', color: '#ec4899', icon: '📢' }
        ];
        
        keywords.forEach(keyword => {
            const regex = new RegExp(`\\b${keyword.word}\\b`, 'gi');
            text = text.replace(regex, `<strong style="color: ${keyword.color}">${keyword.icon} ${keyword.word.toUpperCase()}</strong>`);
        });
        
        // Ganti baris baru dengan <br>
        text = text.replace(/\n/g, '<br>');
        
        // Ganti URL menjadi link
        const urlRegex = /(https?:\/\/[^\s]+)/g;
        text = text.replace(urlRegex, '<a href="$1" target="_blank" style="color: #3b82f6; text-decoration: underline;">$1</a>');
        
        // Tambahkan paragraf
        const paragraphs = text.split(/\n{2,}/);
        if (paragraphs.length > 1) {
            text = paragraphs.map(p => `<p style="margin-bottom: 0.5rem;">${p}</p>`).join('');
        }
        
        return text;
    }

    // Fungsi untuk mendapatkan class kategori
    function getKategoriClass(kategori) {
        const map = {
            'pengaduan': 'bg-red-100 text-red-800 border-red-200',
            'saran': 'bg-blue-100 text-blue-800 border-blue-200',
            'pemberitahuan': 'bg-pink-100 text-pink-800 border-pink-200'
        };
        return map[(kategori || '').toLowerCase()] || 'bg-gray-100 text-gray-800 border-gray-200';
    }

    // Fungsi untuk mendapatkan icon kategori
    function getKategoriIcon(kategori) {
        const map = {
            'pengaduan': 'fas fa-exclamation-circle',
            'saran': 'fas fa-lightbulb',
            'pemberitahuan': 'fas fa-bullhorn'
        };
        return map[(kategori || '').toLowerCase()] || 'fas fa-tag';
    }

    // Fungsi untuk filter otomatis
    function applyFilter() {
        const tanggalMulai = document.getElementById('tanggal-mulai');
        const tanggalSelesai = document.getElementById('tanggal-selesai');
        
        if (tanggalMulai && tanggalSelesai && tanggalMulai.value && tanggalSelesai.value) {
            const startDate = new Date(tanggalMulai.value);
            const endDate = new Date(tanggalSelesai.value);
            
            if (startDate > endDate) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
                tanggalMulai.value = '';
                tanggalSelesai.value = '';
                return;
            }
        }
        
        document.getElementById('filter-form').submit();
    }

    // Fungsi debounced filter untuk search input
    function debouncedFilter() {
        clearTimeout(filterTimeout);
        const searchLoading = document.getElementById('search-loading');
        if (searchLoading) searchLoading.classList.remove('hidden');
        
        filterTimeout = setTimeout(() => {
            document.getElementById('filter-form').submit();
        }, 500);
    }

    // FUNGSI UTAMA: Menampilkan detail aspirasi
    function showAspirasiDetail(button) {
        console.log("Tombol detail ditekan!");
        
        // Ambil semua data dari button
        const nama = button.getAttribute('data-nama') || 'N/A';
        const nisn = button.getAttribute('data-nisn') || 'N/A';
        const kelas = button.getAttribute('data-kelas') || 'N/A';
        const jurusan = button.getAttribute('data-jurusan') || 'N/A';
        const kategori = button.getAttribute('data-kategori') || 'N/A';
        const keterangan = button.getAttribute('data-keterangan') || 'Tidak ada keterangan';
        const lokasi = button.getAttribute('data-lokasi') || 'Tidak ditentukan';
        const status = button.getAttribute('data-status') || 'menunggu';
        const foto = button.getAttribute('data-foto') || '';
        const hasFoto = button.getAttribute('data-has_foto') === '1';
        const feedbackJson = button.getAttribute('data-feedback') || '[]';
        const createdAt = button.getAttribute('data-created_at');
        const updatedAt = button.getAttribute('data-updated_at');

        // Fungsi format tanggal
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            try {
                const date = new Date(dateString);
                if (isNaN(date.getTime())) return 'N/A';
                
                return date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (e) {
                console.error("Error formatting date:", e);
                return 'N/A';
            }
        }

        // Isi data ke modal
        document.getElementById('detail-nama').textContent = nama || 'N/A';
        document.getElementById('detail-nisn').textContent = nisn || 'N/A';
        document.getElementById('detail-kelas').textContent = kelas || '-';
        document.getElementById('detail-jurusan').textContent = jurusan || '-';
        document.getElementById('detail-lokasi').textContent = lokasi || 'Tidak ditentukan';
        document.getElementById('detail-tanggal-pengajuan').textContent = formatDate(createdAt);
        document.getElementById('detail-tanggal-ajukan').textContent = formatDate(createdAt);
        document.getElementById('detail-tanggal-update').textContent = formatDate(updatedAt);
        
        // Format keterangan dengan styling
        document.getElementById('detail-keterangan').innerHTML = formatKeterangan(keterangan);

        // Kategori badge
        const kategoriBadge = document.getElementById('detail-kategori-badge');
        const kategoriClass = getKategoriClass(kategori);
        const kategoriIcon = getKategoriIcon(kategori);
        if (kategoriBadge) {
            kategoriBadge.innerHTML = `<i class="${kategoriIcon} mr-1"></i> ${kategori}`;
            kategoriBadge.className = `px-2 py-1 rounded text-xs font-medium ${kategoriClass}`;
        }

        // Status badge
        const statusText = status.charAt(0).toUpperCase() + status.slice(1);
        const statusBadge = document.getElementById('detail-status-badge');
        if (statusBadge) {
            statusBadge.textContent = statusText;
            
            // Reset class dan tambahkan class yang sesuai
            statusBadge.className = 'px-2 py-1 rounded text-xs font-medium';
            if (status === 'menunggu') {
                statusBadge.classList.add('bg-yellow-100', 'text-yellow-800');
            } else if (status === 'proses') {
                statusBadge.classList.add('bg-blue-100', 'text-blue-800');
            } else if (status === 'selesai') {
                statusBadge.classList.add('bg-green-100', 'text-green-800');
            } else if (status === 'ditolak') {
                statusBadge.classList.add('bg-red-100', 'text-red-800');
            }
        }

        // Handle foto
        const fotoSection = document.getElementById('detail-foto-section');
        const fotoImg = document.getElementById('detail-foto');
        
        if (hasFoto && foto && !foto.includes('default-image.png')) {
            fotoSection.classList.remove('hidden');
            
            // Set foto dengan fallback
            fotoImg.onerror = function() {
                fotoSection.classList.add('hidden');
            };
            
            fotoImg.src = foto;
        } else {
            fotoSection.classList.add('hidden');
        }

        // Load feedback history
        renderFeedbackHistory(JSON.parse(feedbackJson));
        
        // Show modal
        const modal = document.getElementById('detail-modal');
        if (modal) {
            modal.classList.add('show');
            console.log("Modal ditampilkan!");
        } else {
            console.error("Modal tidak ditemukan!");
        }
    }

    // Fungsi untuk render feedback history
    function renderFeedbackHistory(feedbacks) {
        const container = document.getElementById('feedback-history');
        
        if (!feedbacks || feedbacks.length === 0) {
            container.innerHTML = '<div class="text-center py-4 text-gray-500 text-sm">Belum ada feedback</div>';
            return;
        }
        
        let html = '';
        feedbacks.forEach(feedback => {
            const userName = feedback.admin_name || 'Unknown';
            const isAdmin = feedback.is_admin || false;
            const createdAt = feedback.created_at || 'N/A';
            const feedbackText = feedback.isi || '-';
            
            // Format tanggal
            let formattedDate = 'N/A';
            try {
                const date = new Date(createdAt);
                formattedDate = date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (e) {
                formattedDate = createdAt;
            }
            
            // Style berbeda untuk admin vs siswa
            const bgColor = isAdmin ? 'bg-blue-50' : 'bg-gray-50';
            const borderColor = isAdmin ? 'border-l-4 border-blue-500' : 'border-l-4 border-green-500';
            const textColor = isAdmin ? 'text-blue-800' : 'text-gray-800';
            const icon = isAdmin ? '👨‍💼' : '👨‍🎓';
            const label = isAdmin ? 'Admin' : 'Siswa';
            
            html += `
                <div class="border rounded-lg p-3 mb-2 ${bgColor} ${borderColor}">
                    <div class="flex justify-between items-start mb-1">
                        <div class="font-medium text-xs ${textColor}">
                            ${icon} ${label}: ${userName}
                        </div>
                        <div class="text-xs text-gray-500">${formattedDate}</div>
                    </div>
                    <p class="text-gray-700 text-sm mt-2">${feedbackText}</p>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }

    // Fungsi untuk lightbox
    function openLightbox(src) {
        document.getElementById('lightbox-image').src = src;
        document.getElementById('lightbox').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    // Fungsi untuk mendapatkan parameter filter
    function getFilterParams() {
        const form = document.getElementById('filter-form');
        if (!form) return '';
        
        const formData = new FormData(form);
        const params = new URLSearchParams();
        
        for (const [key, value] of formData.entries()) {
            if (value && key !== 'selected_ids') {
                params.append(key, value);
            }
        }
        
        return params.toString();
    }

    // Fungsi export ke PDF
    function exportToPDF() {
        const checkboxes = document.querySelectorAll('.aspirasi-checkbox:checked');
        
        if (checkboxes.length > 0) {
            const selectedIds = Array.from(checkboxes).map(checkbox => checkbox.value);
            const params = getFilterParams();
            const selectedParams = selectedIds.map(id => `selected_ids[]=${id}`).join('&');
            
            window.open(`/kepsek/laporan/preview-selected?${params}&${selectedParams}`, '_blank');
        } else {
            const params = getFilterParams();
            window.open(`/kepsek/laporan/preview?${params}`, '_blank');
        }
    }
    
    // Fungsi untuk update selected aspirasi
    function updateSelectedAspirasi(checkbox) {
        const id = checkbox.getAttribute('data-id');
        const row = checkbox.closest('tr');
        
        if (checkbox.checked) {
            if (!selectedAspirasiIds.includes(id)) {
                selectedAspirasiIds.push(id);
            }
            if (row) row.classList.add('selected');
        } else {
            const index = selectedAspirasiIds.indexOf(id);
            if (index > -1) {
                selectedAspirasiIds.splice(index, 1);
            }
            if (row) row.classList.remove('selected');
        }
        
        updateSelectionInfo();
    }

    // Fungsi untuk update info seleksi
    function updateSelectionInfo() {
        const selectedCount = selectedAspirasiIds.length;
        const selectedCountElement = document.getElementById('selected-count');
        const selectionInfo = document.getElementById('selection-info');
        
        if (selectedCountElement) selectedCountElement.textContent = selectedCount;
        
        if (selectionInfo) {
            if (selectedCount > 0) {
                selectionInfo.classList.remove('hidden');
            } else {
                selectionInfo.classList.add('hidden');
            }
        }
        
        const checkboxes = document.querySelectorAll('.aspirasi-checkbox');
        const allChecked = checkboxes.length > 0 && Array.from(checkboxes).every(cb => cb.checked);
        const someChecked = Array.from(checkboxes).some(cb => cb.checked);
        
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
        }
    }

    // Fungsi untuk memilih semua aspirasi
    function selectAllAspirasi() {
        const checkboxes = document.querySelectorAll('.aspirasi-checkbox');
        const rows = document.querySelectorAll('.aspirasi-row');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
            updateSelectedAspirasi(checkbox);
        });
        
        rows.forEach(row => {
            row.classList.add('selected');
        });
        
        updateSelectionInfo();
    }

    // Fungsi untuk membatalkan semua pilihan
    function deselectAllAspirasi() {
        const checkboxes = document.querySelectorAll('.aspirasi-checkbox');
        const rows = document.querySelectorAll('.aspirasi-row');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
            updateSelectedAspirasi(checkbox);
        });
        
        rows.forEach(row => {
            row.classList.remove('selected');
        });
        
        selectedAspirasiIds = [];
        updateSelectionInfo();
    }

    // ========== INITIALIZE ==========
    document.addEventListener('DOMContentLoaded', function() {
        console.log("DOM Content Loaded - Laporan Kepsek");
        
        // Event listener untuk tombol detail
        document.addEventListener('click', function(e) {
            // Cari tombol detail yang diklik
            let target = e.target;
            
            // Jika yang diklik adalah icon dalam button
            if (target.classList.contains('fa-eye')) {
                target = target.closest('.view-detail-btn');
            }
            
            // Jika yang diklik adalah button detail
            if (target && target.classList.contains('view-detail-btn')) {
                e.preventDefault();
                e.stopPropagation();
                console.log("Tombol detail ditemukan, memanggil showAspirasiDetail");
                showAspirasiDetail(target);
            }
            
            // Tombol close modal
            if (target && target.id === 'close-detail-modal') {
                document.getElementById('detail-modal').classList.remove('show');
            }
            
            // Tombol close lightbox
            if (target && target.classList.contains('lightbox-close')) {
                closeLightbox();
            }
        });

        // Event delegation untuk checkbox per baris
        document.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('aspirasi-checkbox')) {
                updateSelectedAspirasi(e.target);
            }
        });

        // Event untuk select all checkbox
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    selectAllAspirasi();
                } else {
                    deselectAllAspirasi();
                }
            });
        }

        // Klik di luar modal untuk close
        document.addEventListener('click', function(e) {
            const detailModal = document.getElementById('detail-modal');
            if (detailModal && e.target === detailModal) {
                detailModal.classList.remove('show');
            }
            
            const lightbox = document.getElementById('lightbox');
            if (lightbox && e.target === lightbox) {
                closeLightbox();
            }
        });

        // ESC key untuk close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('detail-modal')?.classList.remove('show');
                closeLightbox();
            }
        });

        // Auto-hide search loading
        setTimeout(() => {
            const searchLoading = document.getElementById('search-loading');
            if (searchLoading) {
                searchLoading.classList.add('hidden');
            }
        }, 1000);
        
        console.log("Semua event listener terpasang");
    });
</script>
@endpush