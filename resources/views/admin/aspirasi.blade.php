@extends('layouts.admin')

@section('title', 'SUARAKITA')
@section('page-title', 'Data Aspirasi')
@section('page-description', 'Kelola aspirasi dari siswa dengan mudah')

@push('styles')
<style>
    /* ===== MODAL BASE STYLING ===== */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-content {
        background-color: white;
        border-radius: 12px;
        width: 100%;
        max-width: 500px;
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

    /* ===== NOTIFICATION STYLING ===== */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
        animation: slideIn 0.3s ease-out;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        overflow: hidden;
        display: none;
    }

    .notification.show {
        display: block;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .notification-success {
        background: linear-gradient(90deg, #10b981, #059669);
    }

    .notification-error {
        background: linear-gradient(90deg, #ef4444, #dc2626);
    }

    .notification-info {
        background: linear-gradient(90deg, #3b82f6, #2563eb);
    }

    /* Kategori badge styling */
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
    
    /* Status badge styling */
    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 100px;
    }
    
    .status-menunggu {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .status-proses {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .status-selesai {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    /* Foto badge styling */
    .foto-badge {
        display: inline-block;
        padding: 2px 8px;
        background-color: #3b82f6;
        color: white;
        border-radius: 12px;
        font-size: 0.65rem;
        font-weight: 500;
        margin-top: 4px;
    }
    
    /* Action buttons */
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
    
    .btn-reply {
        background-color: #10b981;
        color: white;
    }
    
    .btn-reply:hover {
        background-color: #059669;
    }
    
    /* Table styling */
    .table-container {
        overflow-x: auto;
    }
    
    .table-container::-webkit-scrollbar {
        height: 6px;
    }
    
    .table-header {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        position: sticky;
        top: 0;
    }
    
    .table-row:hover {
        background-color: #f8fafc;
    }
    
    /* Form styling */
    .form-control {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Loading spinner */
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .hide-on-mobile {
            display: none;
        }
    }
</style>
@endpush

@section('content')
    <!-- Notification Area -->
    <div id="notification" class="notification">
        <div class="p-4 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-info-circle text-lg"></i>
                    <span id="notification-message">Notification message</span>
                </div>
                <button type="button" onclick="hideNotification()" class="text-white hover:text-gray-200 ml-4">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-800" id="success-alert">
        <div class="flex items-center">
            <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="ml-auto text-lg" onclick="document.getElementById('success-alert').remove()">&times;</button>
    </div>
    <script>
        setTimeout(() => {
            const alert = document.getElementById('success-alert');
            if (alert) alert.remove();
        }, 5000);
    </script>
    @endif

    @if(session('error'))
    <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-800" id="error-alert">
        <div class="flex items-center">
            <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        <button type="button" class="ml-auto text-lg" onclick="document.getElementById('error-alert').remove()">&times;</button>
    </div>
    <script>
        setTimeout(() => {
            const alert = document.getElementById('error-alert');
            if (alert) alert.remove();
        }, 5000);
    </script>
    @endif
    
    <!-- Header dengan filter -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <div></div>
        <div class="flex flex-wrap items-center gap-3">
            <!-- Search Input -->
            <div class="relative">
                <input type="text" id="search-input" placeholder="Cari aspirasi..." 
                       value="{{ request('search') }}"
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-64">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div id="aspirasi-table-container">
        <!-- Tabel Container -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="table-container">
                <table class="w-full">
                    <thead>
                        <tr class="table-header">
                            <th class="py-3 px-4 text-left">No</th>
                            <th class="py-3 px-4 text-left">Nama Siswa</th>
                            <th class="py-3 px-4 text-left">Kategori</th>
                            <th class="py-3 px-4 text-left hide-on-mobile">Keterangan</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="aspirasi-table-body">
                        @php
                            $no = ($pengaduans->currentPage() - 1) * $pengaduans->perPage() + 1;
                        @endphp
                        @forelse ($pengaduans as $p)
                            @if($p->aspirasi && $p->aspirasi->status == 'menunggu')
                                <tr class="table-row hover:bg-gray-50 transition-colors duration-200" id="row-{{ $p->id_input_aspirasi }}">
                                    <td class="py-3 px-4">{{ $no++ }}</td>
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-gray-800">{{ $p->siswa->nama ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">NISN: {{ $p->siswa->nisn ?? 'N/A' }}</div>
                                        <!-- Badge foto jika ada -->
                                        @if($p->foto && $p->foto !== 'null' && trim($p->foto) !== '')
                                        <div>
                                            <span class="foto-badge">
                                                <i class="fas fa-camera mr-1"></i> Foto
                                            </span>
                                        </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="kategori-badge">
                                            {{ $p->kategori->nama_kategori }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 hide-on-mobile">
                                        <div class="max-w-xs truncate">{{ $p->keterangan }}</div>
                                        @if($p->lokasi)
                                        <div class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $p->lokasi }}
                                        </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="status-badge status-menunggu">
                                            Menunggu
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="action-buttons">
                                            <!-- Tombol Lihat Detail -->
                                            <button class="btn-action btn-view view-detail-btn" 
                                                    data-id="{{ $p->id_input_aspirasi }}" 
                                                    data-nama="{{ $p->siswa->nama ?? 'N/A' }}"
                                                    data-nisn="{{ $p->siswa->nisn ?? 'N/A' }}"
                                                    data-kelas="{{ $p->siswa->kelas ?? '-' }}"
                                                    data-jurusan="{{ $p->siswa->jurusan ?? '-' }}"
                                                    data-kategori="{{ $p->kategori->nama_kategori }}"
                                                    data-keterangan="{{ $p->keterangan }}"
                                                    data-lokasi="{{ $p->lokasi ?? 'Tidak ditentukan' }}"
                                                    data-status="{{ $p->aspirasi->status ?? 'menunggu' }}"
                                                    data-created_at="{{ $p->created_at }}"
                                                    data-foto="{{ $p->foto ?? '' }}"
                                                    data-id-aspirasi="{{ $p->aspirasi->id_aspirasi ?? '' }}"
                                                    title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <!-- Tombol Balas/Update Status -->
                                            <button class="btn-action btn-reply reply-btn"
                                                    data-id="{{ $p->aspirasi->id_aspirasi ?? 'new' }}"
                                                    data-id-input="{{ $p->id_input_aspirasi }}"
                                                    data-nama="{{ $p->siswa->nama ?? 'N/A' }}"
                                                    data-nisn="{{ $p->siswa->nisn ?? 'N/A' }}"
                                                    data-kelas="{{ $p->siswa->kelas ?? '-' }}"
                                                    data-jurusan="{{ $p->siswa->jurusan ?? '-' }}"
                                                    data-kategori="{{ $p->kategori->nama_kategori }}"
                                                    data-keterangan="{{ $p->keterangan }}"
                                                    data-lokasi="{{ $p->lokasi ?? 'Tidak ditentukan' }}"
                                                    data-status="{{ $p->aspirasi->status ?? 'menunggu' }}"
                                                    data-foto="{{ $p->foto ?? '' }}"
                                                    data-created_at="{{ $p->created_at }}"
                                                    title="Balas & Update Status">
                                                <i class="fas fa-reply"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 px-4 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3"></i>
                                        <p class="text-lg">Tidak ada data aspirasi</p>
                                        <p class="text-sm">@if(request()->has('search')) Tidak ditemukan hasil pencarian untuk "{{ request('search') }}" @else Tidak ada aspirasi dengan status "Menunggu" @endif</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if ($pengaduans->hasPages())
            <div class="flex flex-col md:flex-row justify-between items-center p-4 border-t border-gray-100">
                <div class="text-sm text-gray-600 mb-4 md:mb-0">
                    Menampilkan {{ $pengaduans->firstItem() ?? 0 }}-{{ $pengaduans->lastItem() ?? 0 }} dari {{ $pengaduans->total() }} aspirasi menunggu
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
        </div>
    </div>
@endsection

@section('modals')
    <!-- Modal Detail Aspirasi -->
    <div id="detail-modal" class="modal-overlay">
        <div class="modal-content" style="max-width: 700px;">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Detail Aspirasi</h3>
                    <button type="button" id="close-detail-modal" class="text-gray-500 hover:text-gray-700">
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
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tanggal -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>
                                Waktu
                            </h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-gray-500">Tanggal Diajukan</p>
                                    <p class="font-medium text-sm" id="detail-tanggal-ajukan">N/A</p>
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
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Feedback -->
    <div id="feedback-modal" class="modal-overlay">
        <div class="modal-content max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Update Status Aspirasi</h3>
                    <button type="button" id="close-feedback-modal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Form Feedback -->
                <form id="feedback-form" method="POST">
                    @csrf
                    <div id="method-spoofing"></div>
                    
                    <input type="hidden" name="id_input_aspirasi" id="form-id-input">
                    <input type="hidden" name="id_aspirasi" id="form-id-aspirasi">
                    
                    <div class="space-y-4">
                        <div>
                            <label for="form-status" class="block text-sm font-medium text-gray-700 mb-1">
                                Update Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="form-status" class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Pilih Status</option>
                                <option value="proses">Proses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="form-isi" class="block text-sm font-medium text-gray-700 mb-1">
                                Feedback Admin <span class="text-red-500">*</span>
                            </label>
                            <textarea name="isi" id="form-isi" rows="4" 
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" id="cancel-feedback-btn" 
                                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200" 
                                    id="submit-feedback-btn">
                                <i class="fas fa-paper-plane mr-2"></i> Kirim
                            </button>
                        </div>
                    </div>
                </form>
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
        // CSRF Token untuk AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // ========== NOTIFICATION FUNCTIONS ==========
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const messageEl = document.getElementById('notification-message');
            const icon = notification.querySelector('i');
            
            // Set type
            notification.className = 'notification';
            notification.classList.add('show');
            
            if (type === 'success') {
                notification.classList.add('notification-success');
                icon.className = 'fas fa-check-circle text-lg';
            } else if (type === 'error') {
                notification.classList.add('notification-error');
                icon.className = 'fas fa-exclamation-circle text-lg';
            } else {
                notification.classList.add('notification-info');
                icon.className = 'fas fa-info-circle text-lg';
            }
            
            // Set message
            messageEl.textContent = message;
            
            // Auto hide after 3 seconds
            setTimeout(hideNotification, 3000);
        }

        function hideNotification() {
            const notification = document.getElementById('notification');
            notification.classList.remove('show');
        }

        // ========== EVENT DELEGATION ==========
        document.addEventListener('click', function(e) {
            // Tombol View Detail
            if (e.target.closest('.view-detail-btn')) {
                e.preventDefault();
                e.stopPropagation();
                const button = e.target.closest('.view-detail-btn');
                showAspirasiDetail(button);
            }
            
            // Tombol Reply
            if (e.target.closest('.reply-btn')) {
                e.preventDefault();
                e.stopPropagation();
                const button = e.target.closest('.reply-btn');
                showFeedbackModal(button);
            }
            
            // Pagination links
            if (e.target.closest('.pagination-link')) {
                e.preventDefault();
                const link = e.target.closest('.pagination-link');
                if (link && link.href) {
                    loadPage(link.href);
                }
            }
            
            // Klik foto untuk lightbox
            if (e.target.id === 'detail-foto') {
                const src = e.target.src;
                if (src) {
                    openLightbox(src);
                }
            }
        });

        // ========== MODAL FUNCTIONS ==========
        function showAspirasiDetail(button) {
            const nama = button.getAttribute('data-nama');
            const nisn = button.getAttribute('data-nisn');
            const kelas = button.getAttribute('data-kelas');
            const jurusan = button.getAttribute('data-jurusan');
            const kategori = button.getAttribute('data-kategori');
            const keterangan = button.getAttribute('data-keterangan');
            const lokasi = button.getAttribute('data-lokasi');
            const status = button.getAttribute('data-status');
            const createdAt = button.getAttribute('data-created_at');
            const foto = button.getAttribute('data-foto');
            const idAspirasi = button.getAttribute('data-id-aspirasi');
            
            // Format tanggal
            let formattedDateAjukan = 'N/A';
            let formattedDateUpdate = 'N/A';
            
            if (createdAt) {
                const date = new Date(createdAt);
                formattedDateAjukan = date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                formattedDateUpdate = formattedDateAjukan;
            }
            
            // Isi data ke modal
            document.getElementById('detail-nama').textContent = nama || 'N/A';
            document.getElementById('detail-nisn').textContent = nisn || 'N/A';
            document.getElementById('detail-kelas').textContent = kelas || '-';
            document.getElementById('detail-jurusan').textContent = jurusan || '-';
            document.getElementById('detail-kategori-badge').textContent = kategori || 'N/A';
            document.getElementById('detail-lokasi').textContent = lokasi || 'Tidak ditentukan';
            
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
            }
            
            document.getElementById('detail-keterangan').textContent = keterangan || 'N/A';
            document.getElementById('detail-tanggal-ajukan').textContent = formattedDateAjukan;
            document.getElementById('detail-tanggal-update').textContent = formattedDateUpdate;
            
            // Handle foto
            const fotoSection = document.getElementById('detail-foto-section');
            const fotoImg = document.getElementById('detail-foto');
            
            if (foto && foto !== '' && foto !== 'null') {
                fotoSection.classList.remove('hidden');
                
                // Coba beberapa path
                const paths = [
                    `{{ asset('storage/pengaduan/') }}/${foto}`,
                    `{{ asset('storage/') }}/${foto}`,
                    `{{ asset('') }}${foto}`,
                    `{{ url('storage/pengaduan/') }}/${foto}`
                ];
                
                // Set foto dengan fallback
                let imageLoaded = false;
                fotoImg.onerror = function() {
                    if (!imageLoaded) {
                        const currentSrc = this.src;
                        const currentIndex = paths.findIndex(path => path.includes(currentSrc));
                        if (currentIndex < paths.length - 1) {
                            this.src = paths[currentIndex + 1];
                        } else {
                            fotoSection.classList.add('hidden');
                        }
                    }
                };
                
                fotoImg.onload = function() {
                    imageLoaded = true;
                };
                
                fotoImg.src = paths[0];
            } else {
                fotoSection.classList.add('hidden');
            }
            
            // Show modal
            const modal = document.getElementById('detail-modal');
            modal.classList.add('show');
        }

        function showFeedbackModal(button) {
            const idAspirasi = button.getAttribute('data-id');
            const idInputAspirasi = button.getAttribute('data-id-input');
            const form = document.getElementById('feedback-form');
            const methodSpoofing = document.getElementById('method-spoofing');
            
            // Isi hidden fields
            document.getElementById('form-id-input').value = idInputAspirasi;
            document.getElementById('form-id-aspirasi').value = idAspirasi;
            
            // Set action dan method spoofing berdasarkan kondisi
            if (idAspirasi && idAspirasi !== 'new' && idAspirasi !== '') {
                // Jika sudah ada aspirasi, gunakan route update (PUT)
                form.action = `{{ url('admin/aspirasi') }}/${idAspirasi}`;
                methodSpoofing.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            } else {
                // Jika baru, gunakan route store (POST)
                form.action = `{{ url('admin/pengaduan') }}/${idInputAspirasi}/feedback`;
                methodSpoofing.innerHTML = ''; // Kosongkan untuk POST
            }
            
            // Reset form
            document.getElementById('form-status').value = '';
            document.getElementById('form-isi').value = '';
            
            // Show modal
            const modal = document.getElementById('feedback-modal');
            modal.classList.add('show');
            
            // Focus
            setTimeout(() => {
                document.getElementById('form-status').focus();
            }, 100);
        }

        function loadFeedbackHistory(aspirasiId) {
            const feedbackContainer = document.getElementById('feedback-history');
            feedbackContainer.innerHTML = '<div class="text-center py-4 text-gray-500 text-sm">Memuat feedback...</div>';

            fetch(`{{ url('admin/aspirasi') }}/${aspirasiId}/feedbacks`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.success && data.feedbacks && data.feedbacks.length > 0) {
                    renderFeedbackHistory(data.feedbacks);
                } else {
                    feedbackContainer.innerHTML =
                        '<div class="text-center py-4 text-gray-500 text-sm">Belum ada feedback</div>';
                }
            })
            .catch(error => {
                console.error('Error loading feedback:', error);
                feedbackContainer.innerHTML =
                    '<div class="text-center py-4 text-gray-500 text-sm">Gagal memuat feedback</div>';
            });
        }

        function renderFeedbackHistory(feedbacks) {
            const container = document.getElementById('feedback-history');
            
            if (!feedbacks || feedbacks.length === 0) {
                container.innerHTML = '<div class="text-center py-4 text-gray-500 text-sm">Belum ada feedback</div>';
                return;
            }
            
            let html = '';
            feedbacks.forEach(feedback => {
                const adminName = feedback.user ? feedback.user.nama : 'Admin';
                const createdAt = feedback.created_at || 'N/A';
                const feedbackText = feedback.isi || '-';
                const date = new Date(createdAt);
                const formattedDate = date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                html += `
                    <div class="border border-gray-200 rounded-lg p-3 mb-2 bg-white">
                        <div class="flex justify-between items-start mb-1">
                            <div class="font-medium text-xs">
                                👨‍💼 ${adminName}
                            </div>
                            <div class="text-xs text-gray-500">${formattedDate}</div>
                        </div>
                        <p class="text-gray-700 text-sm mt-2">${feedbackText}</p>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

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

        // ========== FORM SUBMISSION HANDLING ==========
        document.addEventListener('DOMContentLoaded', function() {
            // Submit form feedback
            const feedbackForm = document.getElementById('feedback-form');
            if (feedbackForm) {
                feedbackForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const submitBtn = document.getElementById('submit-feedback-btn');
                    const originalBtnHTML = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
                    submitBtn.disabled = true;
                    
                    const formData = new FormData(this);
                    const method = formData.get('_method') || 'POST';
                    const actionUrl = this.action;
                    
                    // Konversi FormData ke object biasa untuk JSON
                    const data = {};
                    formData.forEach((value, key) => {
                        if (key !== '_method') {
                            data[key] = value;
                        }
                    });
                    
                    fetch(actionUrl, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Tutup modal
                            document.getElementById('feedback-modal').classList.remove('show');
                            
                            // Tampilkan notifikasi
                            showNotification(data.message || 'Status berhasil diperbarui!', 'success');
                            
                            // HAPUS ROW DARI TABEL untuk SEMUA STATUS (baik 'proses' maupun 'selesai')
                            const idInputAspirasi = data.id_input_aspirasi || formData.get('id_input_aspirasi');
                            removeRowFromTable(idInputAspirasi);
                            
                        } else {
                            showNotification(data.message || 'Gagal memperbarui status', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Terjadi kesalahan jaringan', 'error');
                    })
                    .finally(() => {
                        submitBtn.innerHTML = originalBtnHTML;
                        submitBtn.disabled = false;
                    });
                });
            }
            
            // Modal close events
            document.getElementById('close-detail-modal')?.addEventListener('click', function() {
                document.getElementById('detail-modal').classList.remove('show');
            });
            
            document.getElementById('detail-modal')?.addEventListener('click', function(e) {
                if (e.target === this) this.classList.remove('show');
            });
            
            document.getElementById('close-feedback-modal')?.addEventListener('click', function() {
                document.getElementById('feedback-modal').classList.remove('show');
            });
            
            document.getElementById('cancel-feedback-btn')?.addEventListener('click', function() {
                document.getElementById('feedback-modal').classList.remove('show');
            });
            
            document.getElementById('feedback-modal')?.addEventListener('click', function(e) {
                if (e.target === this) this.classList.remove('show');
            });
            
            // ESC key to close modals and notification
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.getElementById('detail-modal')?.classList.remove('show');
                    document.getElementById('feedback-modal')?.classList.remove('show');
                    closeLightbox();
                    hideNotification();
                }
            });
            
            // Close lightbox when clicking outside image
            document.getElementById('lightbox')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeLightbox();
                }
            });
        });

        // Function untuk menghapus row dari tabel (untuk semua status)
        function removeRowFromTable(idInputAspirasi) {
            // Cari row berdasarkan ID
            const row = document.getElementById(`row-${idInputAspirasi}`);
            
            if (row) {
                // Animasi fade out
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    row.remove();
                    
                    // Update nomor urut
                    updateRowNumbers();
                    
                    // Cek jika tabel kosong
                    const tbody = document.getElementById('aspirasi-table-body');
                    if (tbody && tbody.children.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="py-8 px-4 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3"></i>
                                        <p class="text-lg">Tidak ada data aspirasi</p>
                                        <p class="text-sm">Tidak ada aspirasi dengan status "Menunggu"</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                    }
                }, 300);
            }
        }

        // Function untuk update nomor urut
        function updateRowNumbers() {
            const rows = document.querySelectorAll('#aspirasi-table-body tr');
            let counter = 1;
            
            rows.forEach(row => {
                const firstCell = row.querySelector('td:first-child');
                if (firstCell) {
                    firstCell.textContent = counter;
                    counter++;
                }
            });
        }

        // ========== LIVE SEARCH & AJAX ==========
        let searchTimeout;
        const searchInput = document.getElementById('search-input');
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchTerm = this.value.trim();
                
                if (!searchTerm) {
                    loadPage('{{ route("admin.aspirasi.index") }}');
                    return;
                }
                
                searchTimeout = setTimeout(() => {
                    performLiveSearch(searchTerm);
                }, 500);
            });
        }

        function performLiveSearch(searchTerm) {
            const tableContainer = document.getElementById('aspirasi-table-container');
            
            if (!tableContainer) return;
            
            tableContainer.innerHTML = `
                <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="spinner mb-3"></div>
                        <p class="text-gray-600">Mencari data...</p>
                    </div>
                </div>
            `;
            
            fetch(`{{ route('admin.aspirasi.index') }}?search=${encodeURIComponent(searchTerm)}&ajax=1`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTableContainer = doc.getElementById('aspirasi-table-container');
                
                if (newTableContainer) {
                    tableContainer.innerHTML = newTableContainer.innerHTML;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                tableContainer.innerHTML = `
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                        <div class="text-red-500">
                            <i class="fas fa-exclamation-circle text-3xl mb-3"></i>
                            <p>Terjadi kesalahan saat mencari data</p>
                        </div>
                    </div>
                `;
            });
        }

        function loadPage(url) {
            const tableContainer = document.getElementById('aspirasi-table-container');
            const searchTerm = searchInput ? searchInput.value : '';
            
            if (!tableContainer) return;
            
            tableContainer.innerHTML = `
                <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="spinner mb-3"></div>
                        <p class="text-gray-600">Memuat data...</p>
                    </div>
                </div>
            `;
            
            let fetchUrl = url;
            if (searchTerm) {
                const separator = url.includes('?') ? '&' : '?';
                fetchUrl = `${url}${separator}search=${encodeURIComponent(searchTerm)}`;
            }
            
            fetchUrl += (fetchUrl.includes('?') ? '&' : '?') + 'ajax=1';
            
            fetch(fetchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTableContainer = doc.getElementById('aspirasi-table-container');
                
                if (newTableContainer) {
                    tableContainer.innerHTML = newTableContainer.innerHTML;
                }
                
                window.history.pushState({}, '', fetchUrl.replace('&ajax=1', '').replace('?ajax=1', ''));
            })
            .catch(error => {
                console.error('Error:', error);
                window.location.href = url;
            });
        }
    </script>
@endpush