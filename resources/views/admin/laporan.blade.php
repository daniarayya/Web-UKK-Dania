@extends('layouts.admin')
@section('title', 'SUARAKITA')
@section('page-title', 'Laporan Aspirasi')
@section('page-description', 'Kelola seluruh aspirasi dari siswa dengan mudah')

@php
function getStatusClass($status) {
    return match($status) {
        'menunggu' => 'status-menunggu',
        'proses'   => 'status-proses',
        'selesai'  => 'status-selesai',
        'ditolak'  => 'status-ditolak',
        default    => 'status-menunggu',
    };
}
@endphp

@push('styles')
<style>
    /* ===== STATUS BADGE ===== */
    .status-badge {
        padding: 6px 14px !important;
        border-radius: 20px !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        display: inline-block !important;
        text-align: center !important;
        min-width: 100px !important;
        border: none !important;
    }
    
    .status-menunggu {
        background-color: #fef3c7 !important;
        color: #92400e !important;
    }
    
    .status-proses {
        background-color: #dbeafe !important;
        color: #1e40af !important;
    }
    
    .status-selesai {
        background-color: #d1fae5 !important;
        color: #065f46 !important;
    }
    
    .status-ditolak {
        background-color: #fee2e2 !important;
        color: #991b1b !important;
    }
    
    /* ===== FILTER STATUS BUTTONS ===== */
    .status-filter-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .status-filter-btn.active {
        border-color: currentColor;
        font-weight: 600;
    }
    
    /* ===== ACTION BUTTONS ===== */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
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
    
    /* ===== TABLE STYLING ===== */
    .table-container {
        overflow-x: auto;
        border-radius: 10px;
    }
    
    .table-container::-webkit-scrollbar {
        height: 8px;
        width: 8px;
    }
    
    .table-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .table-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    
    .table-header {
        background-color: #f8fafc;
        color: #6b7280 !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .table-row:hover {
        background-color: #f9fafb;
    }
    
    /* ===== PAGINATION ===== */
    .pagination-link {
        padding: 0.5rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        color: #6b7280;
        transition: all 0.2s;
    }
    
    .pagination-link:hover {
        background-color: #f3f4f6;
    }
    
    .pagination-link.active {
        background-color: #22c55e;
        color: white;
        border-color: #22c55e;
    }
    
    /* ===== SELECTION STYLING ===== */
    .selection-info {
        background-color: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        animation: fadeIn 0.3s ease;
    }
    
    .table-row.selected {
        background-color: #f0fdf4 !important;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* ===== FOTO BADGE ===== */
    .foto-badge {
        display: inline-block !important;
        padding: 4px 10px !important;
        background-color: #3b82f6 !important;
        color: white !important;
        border-radius: 12px !important;
        font-size: 0.65rem !important;
        font-weight: 500 !important;
        margin-top: 4px !important;
        border: 1px solid #1e40af !important;
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
        max-width: 700px;
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

    /* ===== KATEGORI BADGE ===== */
    .kategori-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        background-color: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
        min-width: 100px;
        text-align: center;
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
<form id="filter-form" method="GET" action="{{ route('admin.laporan') }}" class="mb-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <!-- Left Side: Search -->
        <div class="w-full md:w-auto">
            <div class="relative">
                <input type="text" id="search-input" 
                    name="search"
                    placeholder="Cari aspirasi..." 
                    value="{{ $filters['search'] ?? '' }}"
                    class="filter-auto w-full md:w-64 px-4 py-2 pl-10 pr-10 border border-gray-300 rounded-lg 
                        focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-700"
                    onkeyup="debouncedFilter()">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>
        
        <!-- Right Side: Date Filter & Export -->
        <div class="flex items-center gap-3 w-full md:w-auto">
            <div class="flex items-center gap-2">
                <input type="date" 
                       id="tanggal-mulai" 
                       name="tanggal_mulai"
                       value="{{ $filters['tanggal_mulai'] ?? '' }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700"
                       title="Tanggal mulai"
                       onchange="applyFilter()">
                <span class="text-gray-500">s/d</span>
                <input type="date" 
                       id="tanggal-selesai" 
                       name="tanggal_selesai"
                       value="{{ $filters['tanggal_selesai'] ?? '' }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700"
                       title="Tanggal selesai"
                       onchange="applyFilter()">
            </div>

            <!-- Export Button -->
            <button type="button" onclick="exportToPDF()" class="px-4 py-2 bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-lg hover:opacity-90 transition duration-200 flex items-center">
                <i class="fas fa-file-pdf mr-2"></i> Export
            </button>
        </div>
    </div>
    
    <!-- Hidden input untuk status -->
    <input type="hidden" name="status" id="status-input" value="{{ $filters['status'] ?? '' }}">
</form>

<!-- Table Container -->
<div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
    <div class="table-container">
        <table class="w-full min-w-full">
            <thead>
                <tr class="table-header">
                    <th class="py-3 px-4 text-left" style="width: 50px;">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                    </th>
                    <th class="py-3 px-4 text-left text-gray-600" style="width: 60px;">No</th>
                    <th class="py-3 px-4 text-left text-gray-600" style="min-width: 150px;">Nama Siswa</th>
                    <th class="py-3 px-4 text-left text-gray-600" style="min-width: 250px;">Keterangan</th>
                    <th class="py-3 px-4 text-left text-gray-600" style="width: 120px;">Status</th>
                    <th class="py-3 px-4 text-left text-gray-600" style="width: 120px;">Tanggal</th>
                    <th class="py-3 px-4 text-left text-gray-600" style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="aspirasi-table-body">
                @forelse ($pengaduans as $index => $p)
                    @php
                        $aspirasi = $p->aspirasi;
                        $status = $aspirasi->status ?? 'menunggu';
                        $statusClass = getStatusClass($status);
                        $statusText = ucfirst($status);
                        
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
                    @endphp
                    
                    <tr class="table-row hover:bg-gray-50 transition-colors duration-200 aspirasi-row">
                        <!-- Kolom Checkbox -->
                        <td class="py-3 px-4">
                            <input type="checkbox" 
                                   name="selected_ids[]" 
                                   value="{{ $p->id_input_aspirasi }}"
                                   class="aspirasi-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500"
                                   data-id="{{ $p->id_input_aspirasi }}">
                        </td>
                        
                        <!-- No -->
                        <td class="py-3 px-4 text-gray-700">
                            {{ ($pengaduans->currentPage() - 1) * $pengaduans->perPage() + $index + 1 }}
                        </td>
                        
                        <!-- Nama Siswa -->
                        <td class="py-3 px-4">
                            <div class="font-medium text-gray-800 aspirasi-nama">{{ $p->siswa->nama ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $p->siswa->nisn ?? 'N/A' }}</div>
                            @if($hasFoto)
                            <div>
                                <span class="foto-badge">
                                    <i class="fas fa-camera mr-1"></i>Foto
                                </span>
                            </div>
                            @endif
                        </td>
                        
                        <!-- Keterangan -->
                        <td class="py-3 px-4">
                            <div class="truncate-text aspirasi-keterangan text-gray-700" title="{{ $p->keterangan }}">
                                {{ Str::limit($p->keterangan, 150) }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-tag mr-1"></i> {{ $p->kategori->nama_kategori ?? 'Tidak ada' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $p->lokasi ?? 'Tidak ditentukan' }}
                            </div>
                        </td>
                        
                        <!-- Status -->
                        <td class="py-3 px-4">
                            <span class="status-badge {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        
                        <!-- Tanggal -->
                        <td class="py-3 px-4 text-gray-700">
                            <div class="text-sm">
                                {{ $p->created_at->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $p->created_at->format('H:i') }}
                            </div>
                        </td>
                        
                        <!-- Aksi -->
                        <td class="py-3 px-4">
                            <div class="action-buttons">
                                <button class="btn-action btn-view view-detail-btn"
                                        data-id="{{ $p->id_input_aspirasi }}" 
                                        data-nama="{{ $p->siswa->nama ?? 'N/A' }}"
                                        data-nisn="{{ $p->siswa->nisn ?? 'N/A' }}"
                                        data-kelas="{{ $p->siswa->kelas ?? 'N/A' }}"
                                        data-jurusan="{{ $p->siswa->jurusan ?? 'N/A' }}"
                                        data-kategori="{{ $p->kategori->nama_kategori ?? 'N/A' }}"
                                        data-keterangan="{{ $p->keterangan }}"
                                        data-lokasi="{{ $p->lokasi ?? 'Tidak ditentukan' }}"
                                        data-status="{{ $status }}"
                                        data-foto="{{ $fotoUrl }}"
                                        data-has_foto="{{ $hasFoto ? '1' : '0' }}"
                                        data-feedback="{{ $aspirasi ? json_encode($aspirasi->feedbacks) : '[]' }}"
                                        data-created_at="{{ $p->created_at }}"
                                        data-updated_at="{{ $aspirasi->updated_at ?? $p->created_at }}"
                                        title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 px-4 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-inbox text-4xl mb-3 text-gray-400"></i>
                                <p class="text-lg">Tidak ada data laporan</p>
                                <p class="text-sm">Belum ada aspirasi yang tercatat</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($pengaduans->hasPages())
<div class="flex flex-col md:flex-row justify-between items-center mt-6">
    <div class="text-sm text-gray-600 mb-4 md:mb-0">
        Menampilkan {{ $pengaduans->firstItem() ?? 0 }} - {{ $pengaduans->lastItem() ?? 0 }} dari {{ $pengaduans->total() }} data
    </div>
    
    <div class="flex items-center space-x-2">
        {{-- Previous Page Link --}}
        @if ($pengaduans->onFirstPage())
            <span class="px-3 py-1 border border-gray-300 rounded text-gray-400 cursor-not-allowed"><</span>
        @else
            <a href="{{ $pengaduans->previousPageUrl() }}" 
               class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 text-gray-700 transition-colors duration-200 pagination-link"><</a>
        @endif
        
        {{-- Pagination Elements --}}
        @php
            $current = $pengaduans->currentPage();
            $last = $pengaduans->lastPage();
            $start = max(1, $current - 2);
            $end = min($last, $current + 2);
        @endphp
        
        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $current)
                <span class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition-colors duration-200">{{ $i }}</span>
            @else
                <a href="{{ $pengaduans->url($i) }}" 
                   class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 text-gray-700 transition-colors duration-200 pagination-link">{{ $i }}</a>
            @endif
        @endfor
        
        {{-- Next Page Link --}}
        @if ($pengaduans->hasMorePages())
            <a href="{{ $pengaduans->nextPageUrl() }}" 
               class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 text-gray-700 transition-colors duration-200 pagination-link">></a>
        @else
            <span class="px-3 py-1 border border-gray-300 rounded text-gray-400 cursor-not-allowed">></span>
        @endif
    </div>
</div>
@endif

<!-- Detail Modal -->
<div id="detail-modal" class="modal-overlay">
    <div class="modal-content">
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
                                    <span id="detail-kategori-badge" class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">N/A</span>
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
                    
                    <!-- Tanggal Update -->
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
                        <p class="text-gray-700 text-sm whitespace-pre-line" id="detail-keterangan">N/A</p>
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
<div id="lightbox" class="fixed inset-0 bg-black bg-opacity-90 z-[10000] hidden items-center justify-center">
    <button type="button" class="absolute top-4 right-4 text-white text-4xl" onclick="closeLightbox()">&times;</button>
    <img id="lightbox-image" class="max-w-full max-h-[90vh] object-contain" src="" alt="">
</div>
@endsection

@push('scripts')
<script>
    let selectedAspirasiIds = [];
    let filterTimeout = null;

    // Fungsi untuk mendapatkan class status
    function getStatusClass(status) {
        const map = {
            'menunggu': 'status-menunggu',
            'proses': 'status-proses',
            'selesai': 'status-selesai',
            'ditolak': 'status-ditolak'
        };
        return map[(status || 'menunggu').toLowerCase()] || 'status-menunggu';
    }

    // Fungsi filter berdasarkan status
    function filterByStatus(status) {
        document.getElementById('status-input').value = status;
        document.getElementById('filter-form').submit();
    }

    // Fungsi untuk filter otomatis
    function applyFilter() {
        const tanggalMulai = document.getElementById('tanggal-mulai').value;
        const tanggalSelesai = document.getElementById('tanggal-selesai').value;
        
        if (tanggalMulai && tanggalSelesai) {
            const startDate = new Date(tanggalMulai);
            const endDate = new Date(tanggalSelesai);
            
            if (startDate > endDate) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
                document.getElementById('tanggal-mulai').value = '';
                document.getElementById('tanggal-selesai').value = '';
                return;
            }
        }
        
        document.getElementById('filter-form').submit();
    }

    // Fungsi debounced filter untuk search input
    function debouncedFilter() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
            document.getElementById('filter-form').submit();
        }, 500);
    }

    // Fungsi untuk mendapatkan parameter filter
    function getFilterParams() {
        const form = document.getElementById('filter-form');
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
            
            window.open(`/admin/laporan/preview-selected?${params}&${selectedParams}`, '_blank');
        } else {
            const params = getFilterParams();
            window.open(`/admin/laporan/preview?${params}`, '_blank');
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
            row.classList.add('selected');
        } else {
            const index = selectedAspirasiIds.indexOf(id);
            if (index > -1) {
                selectedAspirasiIds.splice(index, 1);
            }
            row.classList.remove('selected');
        }
        
        updateSelectionInfo();
    }

    // Fungsi untuk update info seleksi
    function updateSelectionInfo() {
        const selectedCount = selectedAspirasiIds.length;
        const selectedCountElement = document.getElementById('selected-count');
        const selectionInfo = document.getElementById('selection-info');
        
        selectedCountElement.textContent = selectedCount;
        
        if (selectedCount > 0) {
            selectionInfo.classList.remove('hidden');
        } else {
            selectionInfo.classList.add('hidden');
        }
        
        const checkboxes = document.querySelectorAll('.aspirasi-checkbox');
        const allChecked = checkboxes.length > 0 && Array.from(checkboxes).every(cb => cb.checked);
        const someChecked = Array.from(checkboxes).some(cb => cb.checked);
        
        const selectAllCheckbox = document.getElementById('select-all');
        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = someChecked && !allChecked;
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

    // Fungsi untuk menampilkan detail aspirasi
    function showAspirasiDetail(button) {
        const nama = button.getAttribute('data-nama');
        const nisn = button.getAttribute('data-nisn');
        const kelas = button.getAttribute('data-kelas');
        const jurusan = button.getAttribute('data-jurusan');
        const kategori = button.getAttribute('data-kategori');
        const keterangan = button.getAttribute('data-keterangan');
        const lokasi = button.getAttribute('data-lokasi');
        const status = button.getAttribute('data-status');
        const foto = button.getAttribute('data-foto');
        const hasFoto = button.getAttribute('data-has_foto') === '1';
        const createdAt = button.getAttribute('data-created_at');
        const updatedAt = button.getAttribute('data-updated_at');
        const feedback = JSON.parse(button.getAttribute('data-feedback') || '[]');

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Isi data ke modal
        document.getElementById('detail-nama').textContent = nama || 'N/A';
        document.getElementById('detail-nisn').textContent = nisn || 'N/A';
        document.getElementById('detail-kelas').textContent = kelas || '-';
        document.getElementById('detail-jurusan').textContent = jurusan || '-';
        document.getElementById('detail-kategori-badge').textContent = kategori || 'N/A';
        document.getElementById('detail-lokasi').textContent = lokasi || 'Tidak ditentukan';
        document.getElementById('detail-keterangan').textContent = keterangan || 'N/A';
        document.getElementById('detail-tanggal-pengajuan').textContent = formatDate(createdAt);
        document.getElementById('detail-tanggal-ajukan').textContent = formatDate(createdAt);
        document.getElementById('detail-tanggal-update').textContent = formatDate(updatedAt);

        // Status badge
        const statusText = status.charAt(0).toUpperCase() + status.slice(1);
        const statusBadge = document.getElementById('detail-status-badge');
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
        renderFeedbackHistory(feedback);
        
        // Show modal
        const modal = document.getElementById('detail-modal');
        modal.classList.add('show');
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
            const userName = feedback.user_name || 'Unknown';
            const role = feedback.role || 'unknown';
            const isAdmin = role === 'admin';
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
            const borderColor = isAdmin ? 'border-blue-100' : 'border-gray-100';
            const textColor = isAdmin ? 'text-blue-800' : 'text-gray-800';
            const icon = isAdmin ? '👨‍💼' : '👨‍🎓';
            const label = isAdmin ? 'Admin' : 'Siswa';
            
            html += `
                <div class="border ${borderColor} rounded-lg p-3 mb-2 ${bgColor}">
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
        document.getElementById('lightbox').classList.remove('hidden');
        document.getElementById('lightbox').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.add('hidden');
        document.getElementById('lightbox').classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    // Initialize saat DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        // Event delegation untuk tombol detail
        document.addEventListener('click', function(e) {
            const button = e.target.closest('.view-detail-btn');
            if (button) {
                showAspirasiDetail(button);
            }
            
            // Klik foto untuk lightbox
            if (e.target.id === 'detail-foto') {
                const src = e.target.src;
                if (src) {
                    openLightbox(src);
                }
            }
        });

        // Tombol close modal detail
        const detailModal = document.getElementById('detail-modal');
        const closeDetailModal = document.getElementById('close-detail-modal');

        if (closeDetailModal) {
            closeDetailModal.addEventListener('click', function() {
                detailModal.classList.remove('show');
            });
        }

        // Klik luar modal untuk close
        if (detailModal) {
            detailModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('show');
                }
            });
        }

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

        // Event delegation untuk checkbox per baris
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('aspirasi-checkbox')) {
                updateSelectedAspirasi(e.target);
            }
        });
        
        // Close modal dengan ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                detailModal?.classList.remove('show');
                closeLightbox();
            }
        });
        
        // Close lightbox when clicking outside image
        document.getElementById('lightbox')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLightbox();
            }
        });
    });
</script>
@endpush