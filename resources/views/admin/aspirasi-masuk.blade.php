@extends('layouts.admin')

@section('title', 'SUARAKITA')
@section('page-title', 'Aspirasi Masuk')
@section('page-description', 'Kelola aspirasi baru dari siswa')

@push('styles')
<style>
    /* Table styling */
    .table-container {
        overflow-x: auto;
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
    
    .status-baru, .status-menunggu {
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
    
    .btn-action:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .btn-detail {
        background-color: #3b82f6;
        color: white;
    }
    
    .btn-detail:hover:not(:disabled) {
        background-color: #2563eb;
    }
    
    .btn-terima {
        background-color: #10b981;
        color: white;
    }
    
    .btn-terima:hover:not(:disabled) {
        background-color: #059669;
    }
    
    .btn-tolak {
        background-color: #ef4444;
        color: white;
    }
    
    .btn-tolak:hover:not(:disabled) {
        background-color: #dc2626;
    }
    
    /* Loading state for buttons */
    .btn-action.loading {
        position: relative;
        color: transparent !important;
    }
    
    .btn-action.loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
    }
    
    /* Modal styling */
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
        animation: fadeIn 0.2s ease-out;
    }

    .modal-content {
        background-color: white;
        border-radius: 12px;
        width: 100%;
        max-width: 700px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        animation: modalSlideIn 0.3s ease-out;
    }
    
    /* Confirmation Modal Styling */
    .confirmation-modal-content {
        background-color: white;
        border-radius: 12px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        animation: modalSlideIn 0.3s ease-out;
        overflow: hidden;
    }
    
    /* Modal Tolak dengan Textarea */
    .tolak-modal-content {
        background-color: white;
        border-radius: 12px;
        width: 100%;
        max-width: 600px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        animation: modalSlideIn 0.3s ease-out;
        overflow: hidden;
    }
    
    .confirmation-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2rem;
    }
    
    .confirmation-icon.success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #059669;
    }
    
    .confirmation-icon.danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #dc2626;
    }
    
    .confirmation-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 10px;
        text-align: center;
    }
    
    .confirmation-message {
        color: #6b7280;
        text-align: center;
        margin-bottom: 30px;
        line-height: 1.6;
    }
    
    .confirmation-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
    }
    
    .confirmation-btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        min-width: 120px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .confirmation-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .confirmation-btn-confirm {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .confirmation-btn-confirm:hover:not(:disabled) {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .confirmation-btn-cancel {
        background: #f3f4f6;
        color: #4b5563;
        border: 1px solid #d1d5db;
    }
    
    .confirmation-btn-cancel:hover:not(:disabled) {
        background: #e5e7eb;
        transform: translateY(-2px);
    }
    
    .confirmation-btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
    
    .confirmation-btn-danger:hover:not(:disabled) {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Loading spinner for table */
    .spinner-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem;
    }
    
    .table-spinner {
        width: 40px;
        height: 40px;
        border: 3px solid rgba(34, 197, 94, 0.3);
        border-radius: 50%;
        border-top-color: #22c55e;
        animation: spin 1s ease-in-out infinite;
        margin-bottom: 1rem;
    }
    
    /* Notification styling */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
        animation: slideInRight 0.3s ease-out;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        overflow: hidden;
        display: none;
    }
    
    .notification.show {
        display: block;
        animation: slideInRight 0.3s ease-out;
    }
    
    @keyframes slideInRight {
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
    
    /* Textarea styling */
    .feedback-textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        resize: vertical;
        min-height: 100px;
        margin-bottom: 20px;
        transition: all 0.2s;
    }
    
    .feedback-textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .feedback-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #374151;
    }
    
    .optional-badge {
        display: inline-block;
        padding: 2px 8px;
        background-color: #f3f4f6;
        color: #6b7280;
        border-radius: 12px;
        font-size: 0.75rem;
        margin-left: 8px;
        font-weight: 400;
    }
    
    /* Lightbox */
    .lightbox-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.9);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    }
    
    .lightbox-overlay.show {
        display: flex;
        animation: fadeIn 0.2s ease-out;
    }
    
    .lightbox-content {
        max-width: 90%;
        max-height: 90vh;
        position: relative;
    }
    
    .lightbox-image {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
    }
    
    .lightbox-close {
        position: absolute;
        top: -40px;
        right: 0;
        color: white;
        font-size: 30px;
        cursor: pointer;
        background: none;
        border: none;
    }
    
    /* Pagination */
    .pagination-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border-top: 1px solid #e5e7eb;
        background: white;
    }
    
    @media (min-width: 768px) {
        .pagination-container {
            flex-direction: row;
        }
    }
    
    .pagination-info {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }
    
    @media (min-width: 768px) {
        .pagination-info {
            margin-bottom: 0;
        }
    }
    
    .pagination-links {
        display: flex;
        gap: 0.25rem;
    }
    
    .pagination-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        padding: 0 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        background: white;
        color: #374151;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .pagination-link:hover:not(.disabled):not(.active) {
        background: #f3f4f6;
        border-color: #9ca3af;
    }
    
    .pagination-link.active {
        background: #10b981;
        border-color: #10b981;
        color: white;
    }
    
    .pagination-link.disabled {
        opacity: 0.5;
        cursor: not-allowed;
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

    <!-- Header dengan filter -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
       <div></div>
        <div class="flex flex-wrap items-center gap-3">            
            <!-- Search Input -->
            <div class="relative">
                <input type="text" id="search-input" placeholder="Cari aspirasi..." 
                       value="{{ request('search') }}"
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64">
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
                            <th class="py-3 px-4 text-left">Keterangan</th>
                            <th class="py-3 px-4 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="aspirasi-table-body">
                        @php
                            $no = ($pengaduans->currentPage() - 1) * $pengaduans->perPage() + 1;
                        @endphp
                        @forelse ($pengaduans as $p)
                            <tr class="table-row hover:bg-gray-50 transition-colors duration-200" id="row-{{ $p->id_input_aspirasi }}">
                                <td class="py-3 px-4">{{ $no++ }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-gray-800">{{ $p->siswa->nama ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">NISN: {{ $p->siswa->nisn ?? 'N/A' }}</div>
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
                                <td class="py-3 px-4">
                                    <div class="max-w-xs">
                                        @if(strlen($p->keterangan) > 100)
                                            {{ substr($p->keterangan, 0, 100) }}...
                                        @else
                                            {{ $p->keterangan }}
                                        @endif
                                    </div>
                                    @if($p->lokasi)
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-map-marker-alt mr-1"></i> {{ $p->lokasi }}
                                    </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="action-buttons">
                                        <button class="btn-action btn-detail detail-btn" 
                                                data-id="{{ $p->id_input_aspirasi }}"
                                                data-nama="{{ $p->siswa->nama ?? 'N/A' }}"
                                                data-nisn="{{ $p->siswa->nisn ?? 'N/A' }}"
                                                data-kelas="{{ $p->siswa->kelas ?? '-' }}"
                                                data-jurusan="{{ $p->siswa->jurusan ?? '-' }}"
                                                data-kategori="{{ $p->kategori->nama_kategori }}"
                                                data-keterangan="{{ $p->keterangan }}"
                                                data-lokasi="{{ $p->lokasi ?? 'Tidak ditentukan' }}"
                                                data-status="baru"
                                                data-foto="{{ $p->foto ?? '' }}"
                                                data-created_at="{{ $p->created_at->format('Y-m-d H:i:s') }}"
                                                title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <button class="btn-action btn-terima terima-btn" 
                                                data-id="{{ $p->id_input_aspirasi }}" 
                                                data-nama="{{ $p->siswa->nama ?? 'N/A' }}"
                                                title="Terima aspirasi">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        
                                        <button class="btn-action btn-tolak tolak-btn"
                                                data-id="{{ $p->id_input_aspirasi }}"
                                                data-nama="{{ $p->siswa->nama ?? 'N/A' }}"
                                                title="Tolak aspirasi">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 px-4 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3"></i>
                                        <p class="text-lg">Tidak ada aspirasi baru</p>
                                        <p class="text-sm">
                                            @if(request()->has('search')) 
                                                Tidak ditemukan hasil pencarian untuk "{{ request('search') }}" 
                                            @else 
                                                Semua aspirasi sudah diproses 
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if ($pengaduans->hasPages())
            <div class="pagination-container">
                <div class="pagination-info">
                    Menampilkan {{ $pengaduans->firstItem() ?? 0 }}-{{ $pengaduans->lastItem() ?? 0 }} dari {{ $pengaduans->total() }} aspirasi baru
                </div>
                
                <div class="pagination-links">
                    {{-- Previous Page Link --}}
                    @if ($pengaduans->onFirstPage())
                        <span class="pagination-link disabled">
                            &laquo;
                        </span>
                    @else
                        <a href="{{ $pengaduans->previousPageUrl() }}" 
                           class="pagination-link pagination-nav">
                            &laquo;
                        </a>
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
                            <span class="pagination-link active">{{ $i }}</span>
                        @else
                            <a href="{{ $pengaduans->url($i) }}" 
                               class="pagination-link">{{ $i }}</a>
                        @endif
                    @endfor
                    
                    {{-- Next Page Link --}}
                    @if ($pengaduans->hasMorePages())
                        <a href="{{ $pengaduans->nextPageUrl() }}" 
                           class="pagination-link pagination-nav">
                            &raquo;
                        </a>
                    @else
                        <span class="pagination-link disabled">
                            &raquo;
                        </span>
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
                                        <p class="font-medium text-sm" id="detail-kelas">N/A</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Jurusan</p>
                                    <p class="font-medium text-sm" id="detail-jurusan">N/A</p>
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
                                        <span id="detail-kategori" class="kategori-badge">N/A</span>
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
                                            <span id="detail-status" class="status-badge status-baru">BARU</span>
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
                                <div class="text-xs text-gray-500 text-center mt-1">
                                    <i class="fas fa-expand mr-1"></i> Klik untuk memperbesar
                                </div>
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

    <!-- Modal Konfirmasi Terima -->
    <div id="confirmation-terima-modal" class="modal-overlay">
        <div class="confirmation-modal-content">
            <div class="p-6">
                <div class="confirmation-icon success">
                    <i class="fas fa-check"></i>
                </div>
                
                <h3 class="confirmation-title" id="confirmation-terima-title">Terima Aspirasi</h3>
                <p class="confirmation-message" id="confirmation-terima-message">
                    Apakah Anda yakin ingin menerima aspirasi ini?
                </p>
                
                <div class="confirmation-actions">
                    <button type="button" id="cancel-terima-btn" class="confirmation-btn confirmation-btn-cancel">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="button" id="confirm-terima-btn" class="confirmation-btn confirmation-btn-confirm">
                        <i class="fas fa-check mr-1"></i> Ya, Terima
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tolak dengan Textarea Feedback -->
    <div id="tolak-modal" class="modal-overlay">
        <div class="tolak-modal-content">
            <div class="p-6">
                <div class="confirmation-icon danger">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                
                <h3 class="confirmation-title" id="tolak-title">Tolak Aspirasi</h3>
                <p class="confirmation-message" id="tolak-message">
                    Apakah Anda yakin ingin menolak aspirasi ini? Anda dapat memberikan alasan penolakan (opsional).
                </p>
                
                <!-- Textarea untuk feedback -->
                <div class="mb-6">
                    <label for="alasan-penolakan" class="feedback-label">
                        Alasan Penolakan (Opsional)
                        <span class="optional-badge">Opsional</span>
                    </label>
                    <textarea 
                        id="alasan-penolakan" 
                        class="feedback-textarea" 
                        maxlength="500"
                        rows="4"></textarea>
                    <div class="text-xs text-gray-500 text-right">
                        <span id="char-count">0</span>/500 karakter
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-info-circle mr-1"></i> Alasan ini akan dikirim sebagai feedback ke siswa
                    </div>
                </div>
                
                <div class="confirmation-actions">
                    <button type="button" id="cancel-tolak-btn" class="confirmation-btn confirmation-btn-cancel">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="button" id="confirm-tolak-btn" class="confirmation-btn confirmation-btn-danger">
                        <i class="fas fa-trash-alt mr-1"></i> Ya, Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox untuk foto -->
    <div id="lightbox" class="lightbox-overlay">
        <button class="lightbox-close">&times;</button>
        <div class="lightbox-content">
            <img id="lightbox-image" class="lightbox-image" src="" alt="">
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // CSRF Token untuk AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // State untuk menyimpan data sementara
    let currentAspirasiId = null;
    let currentAspirasiNama = null;

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

    // ========== HELPER FUNCTIONS ==========
    function resetModalState() {
        currentAspirasiId = null;
        currentAspirasiNama = null;
        const textarea = document.getElementById('alasan-penolakan');
        if (textarea) {
            textarea.value = '';
            document.getElementById('char-count').textContent = '0';
        }
    }

    function openLightbox(imageSrc) {
        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightbox-image');
        
        lightboxImage.src = imageSrc;
        lightbox.classList.add('show');
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('show');
    }

    // ========== MODAL DETAIL FUNCTION ==========
    function showAspirasiDetail(button) {
        // Ambil data dari atribut button
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
        
        // Format tanggal
        let formattedDateAjukan = 'N/A';
        
        if (createdAt) {
            try {
                const date = new Date(createdAt);
                formattedDateAjukan = date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (e) {
                console.error('Error formatting date:', e);
            }
        }
        
        // Isi data ke modal
        document.getElementById('detail-nama').textContent = nama || 'N/A';
        document.getElementById('detail-nisn').textContent = nisn || 'N/A';
        document.getElementById('detail-kelas').textContent = kelas || '-';
        document.getElementById('detail-jurusan').textContent = jurusan || '-';
        document.getElementById('detail-kategori').textContent = kategori || 'N/A';
        document.getElementById('detail-lokasi').textContent = lokasi || 'Tidak ditentukan';
        document.getElementById('detail-tanggal-ajukan').textContent = formattedDateAjukan;
        
        // Status badge
        const statusBadge = document.getElementById('detail-status');
        const statusText = status ? status.toUpperCase() : 'BARU';
        statusBadge.textContent = statusText;
        statusBadge.className = 'status-badge status-baru';
        
        // Isi keterangan dengan line breaks
        const keteranganElement = document.getElementById('detail-keterangan');
        keteranganElement.textContent = keterangan || 'N/A';
        
        // Handle foto
        const fotoSection = document.getElementById('detail-foto-section');
        const fotoImg = document.getElementById('detail-foto');
        
        if (foto && foto !== '' && foto !== 'null' && foto !== 'undefined') {
            fotoSection.classList.remove('hidden');
            
            // Coba beberapa path untuk foto
            const paths = [
                `/storage/pengaduan/${foto}`,
                `/storage/${foto}`,
                `/${foto}`,
                `{{ asset('storage/pengaduan/') }}/${foto}`,
                `{{ asset('storage/') }}/${foto}`
            ];
            
            let imageLoaded = false;
            fotoImg.onerror = function() {
                if (!imageLoaded) {
                    const currentSrc = this.src;
                    const baseSrc = currentSrc.split('?')[0];
                    this.src = baseSrc + '?t=' + new Date().getTime();
                    
                    setTimeout(() => {
                        if (!imageLoaded) {
                            fotoSection.classList.add('hidden');
                        }
                    }, 1000);
                }
            };
            
            fotoImg.onload = function() {
                imageLoaded = true;
            };
            
            // Coba path pertama
            fotoImg.src = paths[0];
        } else {
            fotoSection.classList.add('hidden');
        }
        
        // Show modal
        document.getElementById('detail-modal').classList.add('show');
    }

    // ========== CONFIRMATION MODAL FUNCTIONS ==========
    function showTerimaConfirmation(button) {
        currentAspirasiId = button.getAttribute('data-id');
        currentAspirasiNama = button.getAttribute('data-nama');
        
        // Update pesan konfirmasi
        document.getElementById('confirmation-terima-title').textContent = `Terima Aspirasi dari ${currentAspirasiNama}`;
        document.getElementById('confirmation-terima-message').textContent = 
            `Aspirasi akan dipindahkan ke status "menunggu" dan dapat dilihat di halaman daftar aspirasi.`;
        
        // Tampilkan modal
        document.getElementById('confirmation-terima-modal').classList.add('show');
    }

    function showTolakConfirmation(button) {
        currentAspirasiId = button.getAttribute('data-id');
        currentAspirasiNama = button.getAttribute('data-nama');
        
        // Update pesan konfirmasi
        document.getElementById('tolak-title').textContent = `Tolak Aspirasi dari ${currentAspirasiNama}`;
        document.getElementById('tolak-message').textContent = 
            `Aspirasi akan dipindahkan ke status "ditolak" dan dapat dilihat di halaman arsip ditolak.`;
        
        // Reset textarea
        const textarea = document.getElementById('alasan-penolakan');
        if (textarea) {
            textarea.value = '';
            document.getElementById('char-count').textContent = '0';
            
            // Character counter
            textarea.addEventListener('input', function() {
                const charCount = this.value.length;
                document.getElementById('char-count').textContent = charCount;
            });
        }
        
        // Tampilkan modal
        document.getElementById('tolak-modal').classList.add('show');
    }

    // ========== FUNGSI BANTUAN AJAX ==========
    async function handleAjaxResponse(response) {
        try {
            // Coba parse sebagai JSON dulu
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return await response.json();
            } else {
                // Jika bukan JSON, coba parse sebagai text
                const text = await response.text();
                console.log('Response text:', text);
                
                // Coba convert text ke JSON jika mungkin
                try {
                    return JSON.parse(text);
                } catch (e) {
                    // Jika tidak bisa di-parse sebagai JSON, return object dengan message
                    return {
                        success: response.ok,
                        message: text || 'Permintaan berhasil',
                        status: response.status
                    };
                }
            }
        } catch (error) {
            console.error('Error parsing response:', error);
            return {
                success: false,
                message: 'Terjadi kesalahan saat memproses respons server',
                status: response.status
            };
        }
    }

    async function terimaAspirasi() {
        const confirmBtn = document.getElementById('confirm-terima-btn');
        const originalBtnHTML = confirmBtn.innerHTML;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...';
        confirmBtn.disabled = true;
        
        // Juga nonaktifkan tombol di row
        const rowButton = document.querySelector(`button.terima-btn[data-id="${currentAspirasiId}"]`);
        if (rowButton) {
            rowButton.classList.add('loading');
            rowButton.disabled = true;
        }
        
        try {
            const response = await fetch(`/admin/aspirasi-masuk/${currentAspirasiId}/terima`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await handleAjaxResponse(response);
            
            // Tutup modal konfirmasi
            document.getElementById('confirmation-terima-modal').classList.remove('show');
            
            if (data.success) {
                // Hapus baris dari tabel dengan animasi
                const row = document.getElementById(`row-${currentAspirasiId}`);
                if (row) {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';
                    
                    setTimeout(() => {
                        row.remove();
                        
                        // Update nomor urut untuk baris yang tersisa
                        updateRowNumbers();
                        
                        // Cek apakah tabel kosong
                        const tbody = document.getElementById('aspirasi-table-body');
                        if (tbody && tbody.children.length === 0) {
                            // Tampilkan pesan tabel kosong
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="5" class="py-8 px-4 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3"></i>
                                            <p class="text-lg">Tidak ada aspirasi baru</p>
                                            <p class="text-sm">Semua aspirasi sudah diproses</p>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }
                    }, 300);
                }
                
                showNotification(data.message || 'Aspirasi berhasil diterima!', 'success');
                
            } else {
                // Reset button
                confirmBtn.innerHTML = originalBtnHTML;
                confirmBtn.disabled = false;
                
                // Reset row button
                if (rowButton) {
                    rowButton.classList.remove('loading');
                    rowButton.disabled = false;
                }
                
                showNotification(data.message || 'Gagal menerima aspirasi', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            
            // Reset buttons
            confirmBtn.innerHTML = originalBtnHTML;
            confirmBtn.disabled = false;
            
            if (rowButton) {
                rowButton.classList.remove('loading');
                rowButton.disabled = false;
            }
            
            // Tutup modal
            document.getElementById('confirmation-terima-modal').classList.remove('show');
            
            showNotification('Terjadi kesalahan: ' + error.message, 'error');
        } finally {
            resetModalState();
        }
    }

    async function tolakAspirasi() {
        const confirmBtn = document.getElementById('confirm-tolak-btn');
        const originalBtnHTML = confirmBtn.innerHTML;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...';
        confirmBtn.disabled = true;
        
        // Juga nonaktifkan tombol di row
        const rowButton = document.querySelector(`button.tolak-btn[data-id="${currentAspirasiId}"]`);
        if (rowButton) {
            rowButton.classList.add('loading');
            rowButton.disabled = true;
        }
        
        const alasanPenolakan = document.getElementById('alasan-penolakan').value.trim();
        
        try {
            const response = await fetch(`/admin/aspirasi-masuk/${currentAspirasiId}/tolak`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    isi: alasanPenolakan
                })
            });

            const data = await handleAjaxResponse(response);
            
            // Tutup modal konfirmasi
            document.getElementById('tolak-modal').classList.remove('show');
            
            if (data.success) {
                // Hapus baris dari tabel dengan animasi
                const row = document.getElementById(`row-${currentAspirasiId}`);
                if (row) {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-20px)';
                    
                    setTimeout(() => {
                        row.remove();
                        
                        // Update nomor urut untuk baris yang tersisa
                        updateRowNumbers();
                        
                        // Cek apakah tabel kosong
                        const tbody = document.getElementById('aspirasi-table-body');
                        if (tbody && tbody.children.length === 0) {
                            // Tampilkan pesan tabel kosong
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="5" class="py-8 px-4 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3"></i>
                                            <p class="text-lg">Tidak ada aspirasi baru</p>
                                            <p class="text-sm">Semua aspirasi sudah diproses</p>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }
                    }, 300);
                }
                
                showNotification(data.message || 'Aspirasi berhasil ditolak', 'success');
            } else {
                showNotification(data.message || 'Gagal menolak aspirasi', 'error');
                
                // Reset row button
                if (rowButton) {
                    rowButton.classList.remove('loading');
                    rowButton.disabled = false;
                }
            }
        } catch (error) {
            console.error('Error:', error);
            
            // Tutup modal
            document.getElementById('tolak-modal').classList.remove('show');
            
            // Reset buttons
            confirmBtn.innerHTML = originalBtnHTML;
            confirmBtn.disabled = false;
            
            if (rowButton) {
                rowButton.classList.remove('loading');
                rowButton.disabled = false;
            }
            
            showNotification('Terjadi kesalahan: ' + error.message, 'error');
        } finally {
            resetModalState();
        }
    }

    // ========== UPDATE ROW NUMBERS ==========
    function updateRowNumbers() {
        const rows = document.querySelectorAll('#aspirasi-table-body tr');
        rows.forEach((row, index) => {
            const td = row.querySelector('td:first-child');
            if (td) {
                td.textContent = index + 1;
            }
        });
    }

    // ========== EVENT DELEGATION FOR DYNAMIC CONTENT ==========
    document.addEventListener('click', function(e) {
        // Detail button
        if (e.target.closest('.detail-btn')) {
            e.preventDefault();
            const button = e.target.closest('.detail-btn');
            showAspirasiDetail(button);
        }
        
        // Terima button
        if (e.target.closest('.terima-btn')) {
            e.preventDefault();
            const button = e.target.closest('.terima-btn');
            showTerimaConfirmation(button);
        }
        
        // Tolak button
        if (e.target.closest('.tolak-btn')) {
            e.preventDefault();
            const button = e.target.closest('.tolak-btn');
            showTolakConfirmation(button);
        }
        
        // Pagination links
        if (e.target.closest('.pagination-link:not(.disabled):not(.active)')) {
            e.preventDefault();
            const link = e.target.closest('a');
            loadPage(link.href);
        }
    });

    // ========== MODAL CLOSE HANDLERS ==========
    document.addEventListener('DOMContentLoaded', function() {
        // Modal close events
        document.getElementById('close-detail-modal')?.addEventListener('click', () => {
            document.getElementById('detail-modal').classList.remove('show');
        });
        
        document.getElementById('detail-modal')?.addEventListener('click', (e) => {
            if (e.target === document.getElementById('detail-modal')) {
                document.getElementById('detail-modal').classList.remove('show');
            }
        });
        
        // Confirmation modal events
        document.getElementById('cancel-terima-btn')?.addEventListener('click', () => {
            document.getElementById('confirmation-terima-modal').classList.remove('show');
            resetModalState();
        });
        
        document.getElementById('confirm-terima-btn')?.addEventListener('click', terimaAspirasi);
        
        document.getElementById('confirmation-terima-modal')?.addEventListener('click', (e) => {
            if (e.target === document.getElementById('confirmation-terima-modal')) {
                document.getElementById('confirmation-terima-modal').classList.remove('show');
                resetModalState();
            }
        });
        
        document.getElementById('cancel-tolak-btn')?.addEventListener('click', () => {
            document.getElementById('tolak-modal').classList.remove('show');
            resetModalState();
        });
        
        document.getElementById('confirm-tolak-btn')?.addEventListener('click', tolakAspirasi);
        
        document.getElementById('tolak-modal')?.addEventListener('click', (e) => {
            if (e.target === document.getElementById('tolak-modal')) {
                document.getElementById('tolak-modal').classList.remove('show');
                resetModalState();
            }
        });
        
        // Lightbox events
        document.querySelector('.lightbox-close')?.addEventListener('click', closeLightbox);
        document.getElementById('lightbox')?.addEventListener('click', (e) => {
            if (e.target === document.getElementById('lightbox')) closeLightbox();
        });
        
        // Character counter untuk textarea penolakan
        const textarea = document.getElementById('alasan-penolakan');
        if (textarea) {
            textarea.addEventListener('input', function() {
                const charCount = this.value.length;
                document.getElementById('char-count').textContent = charCount;
            });
        }
        
        // ESC key untuk close semua modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.getElementById('detail-modal')?.classList.remove('show');
                document.getElementById('confirmation-terima-modal')?.classList.remove('show');
                document.getElementById('tolak-modal')?.classList.remove('show');
                closeLightbox();
                hideNotification();
                resetModalState();
            }
        });
    });

    // ========== LIVE SEARCH & AJAX ==========
    let searchTimeout;
    const searchInput = document.getElementById('search-input');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = this.value.trim();
            
            if (!searchTerm) {
                loadPage('{{ route("admin.aspirasi.masuk.index") }}');
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
                <div class="spinner-container">
                    <div class="table-spinner"></div>
                    <p class="text-gray-600">Mencari data...</p>
                </div>
            </div>
        `;
        
        fetch(`{{ route('admin.aspirasi.masuk.index') }}?search=${encodeURIComponent(searchTerm)}&ajax=1`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                tableContainer.innerHTML = data.html;
            } else {
                throw new Error('Invalid response format');
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
                <div class="spinner-container">
                    <div class="table-spinner"></div>
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
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                tableContainer.innerHTML = data.html;
                window.history.pushState({}, '', fetchUrl.replace('&ajax=1', '').replace('?ajax=1', ''));
            } else {
                throw new Error('Invalid response format');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.location.href = url;
        });
    }

    // Handle browser back/forward
    window.addEventListener('popstate', function() {
        loadPage(window.location.href);
    });

    // ========== GLOBAL FUNCTIONS ==========
    window.openLightbox = openLightbox;
    window.closeLightbox = closeLightbox;
    window.hideNotification = hideNotification;
</script>
@endpush