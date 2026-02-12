@extends('layouts.admin')

@section('title', 'SUARAKITA')
@section('page-title', 'History Aspirasi')
@section('page-description', 'Riwayat aspirasi yang sudah selesai')

@section('content')
    <div class="p-4 md:p-6">
        <!-- Header dengan search -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
            <div></div>
            
            <div class="flex flex-wrap items-center gap-3">
                <!-- Search Input -->
                <div class="relative">
                    <input type="text" id="search-input" placeholder="Cari aspirasi..." 
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent w-64">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
        </div>
        
        <!-- Table Container -->
        <div id="history-table-container">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="table-container">
                    <table class="w-full">
                        <thead class="sticky top-0 z-10">
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Isi Aspirasi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-8 text-center" colspan="6">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fas fa-history text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg">Riwayat aspirasi akan muncul di sini</p>
                                        <p class="text-sm">Mulai pencarian untuk melihat data</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <!-- Modal Detail History -->
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
                                        <span id="detail-kategori" class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">N/A</span>
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
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Selesai</span>
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
                                    <p class="text-xs text-gray-500">Tanggal Selesai</p>
                                    <p class="font-medium text-sm" id="detail-tanggal-selesai">N/A</p>
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
                                    class="w-full h-48 object-cover rounded-lg border border-gray-200">
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
                            <p class="text-gray-700 text-sm" id="detail-keterangan">N/A</p>
                        </div>
                    </div>
                    
                    <!-- Riwayat Feedback -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-exchange-alt mr-2 text-indigo-500"></i>
                            Riwayat Feedback
                        </h4>
                        <div id="feedback-history" class="space-y-3 max-h-40 overflow-y-auto p-2">
                            <div class="text-center py-4 text-gray-500 text-sm">Belum ada feedback</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* ===== TABLE STYLES ===== */
        .table-header {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .table-row:hover {
            background-color: #f8fafc;
        }
        
        /* ===== STATUS BADGES ===== */
        .status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-selesai {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        /* ===== KATEGORI BADGES ===== */
        .kategori-badge {
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }
        
        /* ===== PAGINATION - WARNA HIJAU ===== */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
            background-color: #f8fafc;
        }
        
        .pagination-info {
            font-size: 0.875rem;
            color: #4b5563;
            font-weight: 500;
        }
        
        .pagination-button {
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background: white;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
            min-width: 40px;
            text-align: center;
        }
        
        .pagination-button:hover:not(:disabled) {
            background-color: #f3f4f6;
            border-color: #9ca3af;
        }
        
        /* WARNA HIJAU UNTUK PAGINATION */
        .pagination-button.active {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            border-color: #22c55e;
            box-shadow: 0 2px 5px rgba(34, 197, 94, 0.3);
        }
        
        .pagination-button:disabled {
            color: #9ca3af;
            border-color: #d1d5db;
            cursor: not-allowed;
            background-color: #f9fafb;
        }
        
        .pagination-ellipsis {
            padding: 0.5rem;
            color: #6b7280;
            font-weight: 600;
        }
        
        /* Tombol Previous/Next hijau saat hover */
        .pagination-button:not(.active):not(:disabled):hover {
            background-color: #f0fdf4;
            border-color: #22c55e;
            color: #16a34a;
        }
        
        /* ===== MODAL STYLES ===== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
        }
        
        .modal-overlay.show {
            display: flex !important;
            animation: fadeIn 0.3s ease;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 12px;
            width: 90%;
            max-width: 700px;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: slideIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        /* ===== COMMENT/FEEDBACK BUBBLES ===== */
        .comment-bubble {
            position: relative;
            border-radius: 12px;
            padding: 10px 14px;
            margin-bottom: 8px;
            word-wrap: break-word;
            font-size: 0.875rem;
        }
        
        .comment-bubble.admin {
            background-color: #e0f2fe;
            margin-left: auto;
            margin-right: 0;
            border-bottom-right-radius: 4px;
            border: 1px solid #bae6fd;
            max-width: 85%;
        }
        
        .comment-bubble.student {
            background-color: #dcfce7;
            margin-right: auto;
            margin-left: 0;
            border-bottom-left-radius: 4px;
            border: 1px solid #bbf7d0;
            max-width: 85%;
        }
        
        /* ===== LOADING SPINNER ===== */
        .loading-spinner {
            display: inline-block;
            width: 2rem;
            height: 2rem;
            border: 3px solid rgba(59, 130, 246, 0.3);
            border-radius: 50%;
            border-top-color: #3b82f6;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                margin: 10px;
            }
            
            .pagination-container {
                flex-direction: column;
                gap: 12px;
                padding: 12px;
            }
            
            .pagination-info {
                text-align: center;
            }
            
            .comment-bubble {
                max-width: 90%;
            }
        }
        
        @media (max-width: 640px) {
            .modal-content {
                border-radius: 8px;
                max-height: 80vh;
            }
        }
        
        /* ===== UTILITY CLASSES ===== */
        .cursor-pointer {
            cursor: pointer;
        }
        
        .transition-all {
            transition: all 0.2s ease;
        }
        
        /* ===== SCROLLBAR STYLING ===== */
        .modal-content::-webkit-scrollbar {
            width: 6px;
        }
        
        .modal-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .modal-content::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        .modal-content::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* ===== FEEDBACK CONTAINER ===== */
        .feedback-container {
            max-height: 200px;
            overflow-y: auto;
            padding: 8px;
            background-color: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        
        .feedback-container::-webkit-scrollbar {
            width: 4px;
        }
        
        .feedback-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 2px;
        }
        
        .feedback-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }
        
        /* ===== TABLE LAYOUT FIXES ===== */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* ===== GRID LAYOUTS ===== */
        .grid-cols-auto {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }
        
        /* ===== FOTO PREVIEW ===== */
        .foto-preview {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let currentPage = 1;
        let currentSearchTerm = '';
        let searchTimeout;
        let currentData = null; // Tambahkan variabel ini

        // Fungsi untuk load data via AJAX
        function loadData(page = 1, search = '') {
            currentPage = page;
            currentSearchTerm = search;
            
            const params = new URLSearchParams();
            params.append('page', page);
            params.append('per_page', 5);
            params.append('ajax', '1');
            
            if (search) {
                params.append('search', search);
            }
            
            const url = `/admin/history?${params.toString()}`;
            
            // Tampilkan loading
            const container = document.getElementById('history-table-container');
            container.innerHTML = `
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="flex justify-center items-center py-16">
                        <div class="text-center">
                            <div class="loading-spinner mb-3"></div>
                            <p class="text-gray-600">Memuat data...</p>
                        </div>
                    </div>
                </div>`;
            
            fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentData = data; // Simpan data
                    renderTable(data);
                } else {
                    throw new Error(data.message || 'Gagal memuat data');
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                showEmptyTable('Gagal memuat data');
            });
        }

        // Fungsi untuk menampilkan tabel kosong
        function showEmptyTable(message = 'Tidak ditemukan data') {
            const container = document.getElementById('history-table-container');
            container.innerHTML = `
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="flex justify-center items-center py-12">
                        <div class="text-center">
                            <i class="fas fa-search text-3xl text-gray-300 mb-3"></i>
                            <p class="text-gray-600">${message}</p>
                        </div>
                    </div>
                </div>`;
        }

        // Render table dari data
        function renderTable(data) {
            const container = document.getElementById('history-table-container');
            
            if (!data || !data.aspirasis || !data.aspirasis.data || data.aspirasis.data.length === 0) {
                showEmptyTable('Tidak ada data history');
                return;
            }
            
            let tableHtml = `
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Siswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Isi Aspirasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">`;
            
            data.aspirasis.data.forEach((aspirasi, index) => {
                const startNumber = (data.aspirasis.current_page - 1) * data.aspirasis.per_page + index + 1;
                
                // Akses data dengan cara yang lebih aman
                const siswa = aspirasi.pengaduan ? (aspirasi.pengaduan.siswa || {}) : {};
                const pengaduan = aspirasi.pengaduan || {};
                const kategori = aspirasi.kategori || {};
                
                const namaSiswa = siswa.nama || 'N/A';
                const nisn = siswa.nisn || 'N/A';
                const kategoriNama = kategori.nama_kategori || 'N/A';
                const keterangan = pengaduan.keterangan || 'Tidak ada keterangan';
                const hasFoto = pengaduan.foto ? true : false;
                const aspirasiId = aspirasi.id || aspirasi.id_aspirasi || index;
                
                tableHtml += `
                    <tr class="hover:bg-gray-50" data-id="${aspirasiId}">
                        <td class="px-6 py-4 whitespace-nowrap text-center">${startNumber}</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <div class="font-medium text-gray-900">${namaSiswa}</div>
                                <div class="text-sm text-gray-500">NISN: ${nisn}</div>
                                ${hasFoto ? `
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                        <i class="fas fa-camera mr-1"></i>
                                        Foto
                                    </span>
                                </div>
                                ` : ''}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                ${kategoriNama}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-md" title="${keterangan}">
                                ${keterangan.length > 100 ? keterangan.substring(0, 100) + '...' : keterangan}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                Selesai
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button class="detail-btn px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 flex items-center"
                                    data-id="${aspirasiId}">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>`;
            });
            
            tableHtml += `
                            </tbody>
                        </table>
                    </div>`;
            
            // Pagination dengan warna hijau
            if (data.aspirasis.last_page > 1) {
                tableHtml += `
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                            <div class="text-sm text-gray-700">
                                Menampilkan <span class="font-medium">${data.aspirasis.from}</span> sampai 
                                <span class="font-medium">${data.aspirasis.to}</span> dari 
                                <span class="font-medium">${data.aspirasis.total}</span> hasil
                            </div>
                            <div class="flex flex-wrap items-center justify-center gap-1">`;
                
                // Previous button
                if (data.aspirasis.current_page > 1) {
                    tableHtml += `
                        <button class="pagination-prev pagination-button flex items-center hover:text-green-600">
                            <i class="fas fa-chevron-left mr-1"></i>
                        </button>`;
                } else {
                    tableHtml += `
                        <button disabled class="pagination-prev pagination-button">
                            <i class="fas fa-chevron-left mr-1"></i>
                        </button>`;
                }
                
                // Page numbers
                const totalPages = data.aspirasis.last_page;
                const currentPageNum = data.aspirasis.current_page;
                
                // Always show first page
                tableHtml += `
                    <button class="pagination-page pagination-button ${currentPageNum === 1 ? 'active' : ''}" data-page="1">
                        1
                    </button>`;
                
                // Show ellipsis if needed
                if (currentPageNum > 3) {
                    tableHtml += `<span class="pagination-ellipsis">...</span>`;
                }
                
                // Show pages around current page
                for (let i = Math.max(2, currentPageNum - 1); i <= Math.min(totalPages - 1, currentPageNum + 1); i++) {
                    if (i !== 1 && i !== totalPages) {
                        tableHtml += `
                            <button class="pagination-page pagination-button ${currentPageNum === i ? 'active' : ''}" data-page="${i}">
                                ${i}
                            </button>`;
                    }
                }
                
                // Show ellipsis if needed
                if (currentPageNum < totalPages - 2) {
                    tableHtml += `<span class="pagination-ellipsis">...</span>`;
                }
                
                // Always show last page if more than 1 page
                if (totalPages > 1) {
                    tableHtml += `
                        <button class="pagination-page pagination-button ${currentPageNum === totalPages ? 'active' : ''}" data-page="${totalPages}">
                            ${totalPages}
                        </button>`;
                }
                
                // Next button
                if (data.aspirasis.current_page < data.aspirasis.last_page) {
                    tableHtml += `
                        <button class="pagination-next pagination-button flex items-center hover:text-green-600">
                            <i class="fas fa-chevron-right ml-1"></i>
                        </button>`;
                } else {
                    tableHtml += `
                        <button disabled class="pagination-next pagination-button flex items-center">
                            <i class="fas fa-chevron-right ml-1"></i>
                        </button>`;
                }
                
                tableHtml += `
                            </div>
                        </div>
                    </div>`;
            }
            
            tableHtml += `</div>`;
            container.innerHTML = tableHtml;
            
            // Attach event listeners setelah render
            attachEventListeners();
        }

        // Fungsi untuk attach event listeners
        function attachEventListeners() {
            // Tombol detail
            document.querySelectorAll('.detail-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const aspirasiId = this.getAttribute('data-id');
                    console.log('Detail button clicked for ID:', aspirasiId);
                    
                    if (aspirasiId) {
                        showDetailModal(parseInt(aspirasiId));
                    }
                });
            });
            
            // Pagination buttons
            document.querySelectorAll('.pagination-prev:not(:disabled)').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (currentPage > 1) {
                        loadData(currentPage - 1, currentSearchTerm);
                    }
                });
            });
            
            document.querySelectorAll('.pagination-next:not(:disabled)').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (currentData && currentData.aspirasis && currentData.aspirasis.current_page < currentData.aspirasis.last_page) {
                        loadData(currentPage + 1, currentSearchTerm);
                    }
                });
            });
            
            document.querySelectorAll('.pagination-page').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    if (page) {
                        loadData(parseInt(page), currentSearchTerm);
                    }
                });
            });
        }

        // Load page tertentu
        function loadPage(page) {
            const searchInput = document.getElementById('search-input');
            const searchTerm = searchInput ? searchInput.value : '';
            loadData(page, searchTerm);
        }

        // Tampilkan modal detail - FIXED FUNCTION
        function showDetailModal(id) {
            console.log('Opening detail modal for ID:', id);
            
            // Reset modal content
            document.getElementById('detail-nama').textContent = 'Memuat...';
            document.getElementById('detail-nisn').textContent = 'Memuat...';
            document.getElementById('detail-kelas').textContent = 'Memuat...';
            document.getElementById('detail-jurusan').textContent = 'Memuat...';
            document.getElementById('detail-kategori').textContent = 'Memuat...';
            document.getElementById('detail-lokasi').textContent = 'Memuat...';
            document.getElementById('detail-keterangan').textContent = 'Memuat...';
            document.getElementById('detail-tanggal-ajukan').textContent = 'Memuat...';
            document.getElementById('detail-tanggal-selesai').textContent = 'Memuat...';
            
            // Sembunyikan foto section
            document.getElementById('detail-foto-section').classList.add('hidden');
            
            // Tampilkan modal terlebih dahulu
            const modal = document.getElementById('detail-modal');
            modal.classList.add('show');
            
            // Load data detail
            fetch(`/admin/history/${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Detail API Response:', data);
                    
                    if (data.success && data.data) {
                        const aspirasi = data.data;
                        
                        // Isi data ke modal
                        document.getElementById('detail-nama').textContent = aspirasi.nama_siswa || 'N/A';
                        document.getElementById('detail-nisn').textContent = aspirasi.nisn || 'N/A';
                        document.getElementById('detail-kelas').textContent = aspirasi.kelas || 'N/A';
                        document.getElementById('detail-jurusan').textContent = aspirasi.jurusan || 'N/A';
                        document.getElementById('detail-kategori').textContent = aspirasi.kategori || 'N/A';
                        document.getElementById('detail-lokasi').textContent = aspirasi.lokasi || 'Tidak ditentukan';
                        document.getElementById('detail-keterangan').textContent = aspirasi.keterangan || 'N/A';
                        document.getElementById('detail-tanggal-ajukan').textContent = aspirasi.tanggal_ajukan || 'N/A';
                        document.getElementById('detail-tanggal-selesai').textContent = aspirasi.tanggal_selesai || 'N/A';
                        
                        // Handle foto
                        if (aspirasi.foto_url) {
                            console.log('✅ Using foto_url from API:', aspirasi.foto_url);
                            document.getElementById('detail-foto-section').classList.remove('hidden');
                            document.getElementById('detail-foto').src = aspirasi.foto_url;
                        } else if (aspirasi.foto) {
                            console.log('⚠️ Using foto path');
                            // Coba beberapa URL
                            const possibleUrls = [
                                `/storage/pengaduan/${aspirasi.foto}`,
                                `/storage/${aspirasi.foto}`,
                                aspirasi.foto,
                                `/${aspirasi.foto}`,
                                `/storage/app/public/pengaduan/${aspirasi.foto}`
                            ];
                            
                            const img = document.getElementById('detail-foto');
                            let currentUrlIndex = 0;
                            
                            function tryNextUrl() {
                                if (currentUrlIndex >= possibleUrls.length) {
                                    console.log('❌ All URLs failed');
                                    document.getElementById('detail-foto-section').classList.add('hidden');
                                    return;
                                }
                                
                                const url = possibleUrls[currentUrlIndex];
                                const testImg = new Image();
                                
                                testImg.onload = function() {
                                    console.log('✅ Image loaded from:', url);
                                    document.getElementById('detail-foto-section').classList.remove('hidden');
                                    img.src = url;
                                };
                                
                                testImg.onerror = function() {
                                    console.log('❌ Failed to load from:', url);
                                    currentUrlIndex++;
                                    setTimeout(tryNextUrl, 100);
                                };
                                
                                testImg.src = url + '?t=' + new Date().getTime();
                            }
                            
                            tryNextUrl();
                        } else {
                            console.log('❌ No photo data');
                            document.getElementById('detail-foto-section').classList.add('hidden');
                        }
                        
                        // Handle feedback
                        if (aspirasi.feedbacks && aspirasi.feedbacks.length > 0) {
                            console.log('Feedbacks:', aspirasi.feedbacks);
                            renderFeedbackHistory(aspirasi.feedbacks);
                        } else {
                            document.getElementById('feedback-history').innerHTML = 
                                '<div class="text-center py-4 text-gray-500 text-sm">Belum ada feedback</div>';
                        }
                    } else {
                        throw new Error(data.message || 'Data tidak valid');
                    }
                })
                .catch(error => {
                    console.error('Error loading detail:', error);
                    document.getElementById('detail-keterangan').textContent = 'Gagal memuat data';
                });
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
                const isAdmin = feedback.is_admin || false;
                const adminName = feedback.user ? feedback.user.nama : 
                                feedback.admin ? feedback.admin.nama : 
                                feedback.created_by || 'Admin';
                const createdAt = feedback.created_at || 'N/A';
                const feedbackText = feedback.isi || feedback.pesan || feedback.feedback || '-';
                
                html += `
                    <div class="comment-bubble ${isAdmin ? 'admin' : 'student'}">
                        <div class="flex justify-between items-start mb-1">
                            <div class="font-medium text-xs">
                                ${isAdmin ? '👨‍💼 ' + adminName : '👨‍🎓 Siswa'}
                            </div>
                            <div class="text-xs text-gray-500">${createdAt}</div>
                        </div>
                        <p class="text-gray-700 text-sm">${feedbackText}</p>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        // Event listener ketika DOM siap
        document.addEventListener('DOMContentLoaded', function() {
            // Load initial data
            loadData(1, '');
            
            // Live search
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        loadData(1, this.value);
                    }, 500);
                });
            }
            
            // Modal close button
            const closeBtn = document.getElementById('close-detail-modal');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    document.getElementById('detail-modal').classList.remove('show');
                });
            }
            
            // Close modal when clicking outside
            const modal = document.getElementById('detail-modal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.remove('show');
                    }
                });
            }
            
            // Tutup modal dengan ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const detailModal = document.getElementById('detail-modal');
                    if (detailModal && detailModal.classList.contains('show')) {
                        detailModal.classList.remove('show');
                    }
                }
            });
            
            // Handle modal visibility untuk bisa ditekan berulang
            document.addEventListener('click', function(e) {
                // Jika modal sudah tertutup, reset state
                const modal = document.getElementById('detail-modal');
                if (!modal.classList.contains('show')) {
                    // Reset apa saja jika diperlukan
                }
            });
        });
        
        // Expose functions globally
        window.loadPage = loadData;
        window.showDetailModal = showDetailModal;
    </script>
@endpush