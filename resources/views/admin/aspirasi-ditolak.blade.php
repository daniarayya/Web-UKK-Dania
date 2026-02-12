@extends('layouts.admin')

@section('title', 'SUARAKITA')
@section('page-title', 'Arsip Aspirasi Ditolak')
@section('page-description', 'Daftar aspirasi yang telah ditolak')

@push('styles')
<style>
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

    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 100px;
    }

    .status-ditolak {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

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

    .btn-detail {
        background-color: #3b82f6;
        color: white;
    }

    .btn-detail:hover {
        background-color: #2563eb;
    }

    .pagination-blue .page-item.active .page-link {
        background-color: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }

    .pagination-blue .page-link {
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        line-height: 1.25;
        color: #3b82f6;
        background-color: white;
        border: 1px solid #d1d5db;
        transition: all 0.2s;
    }

    .pagination-blue .page-link:hover {
        background-color: #f3f4f6;
        border-color: #d1d5db;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px rgba(0,0,0,0.07);
    }

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

    /* Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 1rem;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        width: 100%;
        max-width: 700px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .confirmation-modal-content {
        background: white;
        border-radius: 12px;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .confirmation-icon {
        width: 60px;
        height: 60px;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem auto;
        font-size: 1.5rem;
    }

    .confirmation-icon.success {
        background: #dcfce7;
        color: #16a34a;
    }

    .confirmation-title {
        text-align: center;
        font-size: 1.2rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .confirmation-message {
        text-align: center;
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
    }

    .confirmation-actions {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
    }

    .confirmation-btn {
        padding: 0.6rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .confirmation-btn-cancel {
        background: #f3f4f6;
        color: #374151;
    }

    .confirmation-btn-cancel:hover {
        background: #e5e7eb;
    }

    .confirmation-btn-confirm {
        background: #10b981;
        color: white;
    }

    .confirmation-btn-confirm:hover {
        background: #059669;
    }
    
    .status-column {
        min-width: 120px;
    }
    
    .alasan-penolakan {
        font-size: 0.875rem;
        color: #991b1b;
        background: #fef2f2;
        padding: 8px 12px;
        border-radius: 6px;
        margin-top: 4px;
        border-left: 3px solid #dc2626;
    }
</style>
@endpush

@section('content')
    <!-- Notification -->
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

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <div></div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <input type="text" id="search-input" placeholder="Cari aspirasi..."
                       value="{{ request('search') }}"
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>

            @if(request()->has('search'))
            <a href="{{ route('admin.arsip.index') }}"
               class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 text-gray-700">
                Reset Filter
            </a>
            @endif
        </div>
    </div>

    <!-- Table -->
    <div id="aspirasi-table-container">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="table-container">
                <table class="w-full">
                    <thead>
                        <tr class="table-header">
                            <th class="py-3 px-4 text-left">No</th>
                            <th class="py-3 px-4 text-left">Nama Siswa</th>
                            <th class="py-3 px-4 text-left">Kategori</th>
                            <th class="py-3 px-4 text-left">Keterangan</th>
                            <th class="py-3 px-4 text-left status-column">Status</th>
                            <th class="py-3 px-4 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="aspirasi-table-body">
                        @php
                            $no = ($pengaduans->currentPage() - 1) * $pengaduans->perPage() + 1;
                        @endphp

                        @forelse ($pengaduans as $p)
                            @php
                                $feedbackTerbaru = $p->aspirasi?->feedbacks?->sortByDesc('created_at')->first();
                                $alasanPenolakan = $feedbackTerbaru->isi ?? 'Tidak ada alasan';
                                $adminPenolak = $feedbackTerbaru?->user?->nama ?? 'N/A';
                                $tanggalDitolak = $feedbackTerbaru?->created_at ?? $p->aspirasi?->created_at;
                            @endphp

                            <tr class="table-row hover:bg-gray-50 transition-colors duration-200"
                                id="row-{{ $p->id_input_aspirasi }}">

                                <td class="py-3 px-4">{{ $no++ }}</td>

                                <td class="py-3 px-4">
                                    <div class="font-medium text-gray-800">{{ $p->siswa->nama ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">NISN: {{ $p->siswa->nisn ?? 'N/A' }}</div>

                                    @if($p->foto && $p->foto !== 'null' && trim($p->foto) !== '')
                                        <div class="mt-1">
                                            <span class="foto-badge">
                                                <i class="fas fa-camera mr-1"></i> Foto Tersedia
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                <td class="py-3 px-4">
                                    <span class="kategori-badge">
                                        {{ $p->kategori->nama_kategori ?? 'N/A' }}
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
                                    <span class="status-badge status-ditolak">Ditolak</span>

                                    @if($tanggalDitolak)
                                        <div class="text-xs text-gray-500 mt-1">
                                            Ditolak: {{ \Carbon\Carbon::parse($tanggalDitolak)->format('d/m/Y') }}
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
                                                data-kategori="{{ $p->kategori->nama_kategori ?? 'N/A' }}"
                                                data-keterangan="{{ $p->keterangan }}"
                                                data-lokasi="{{ $p->lokasi ?? 'Tidak ditentukan' }}"
                                                data-foto="{{ $p->foto ?? '' }}"
                                                data-alasan_penolakan="{{ $alasanPenolakan }}"
                                                data-admin="{{ $adminPenolak }}"
                                                data-tanggal_ditolak="{{ $tanggalDitolak ? \Carbon\Carbon::parse($tanggalDitolak)->format('Y-m-d H:i:s') : '' }}"
                                                data-created_at="{{ $p->created_at->format('Y-m-d H:i:s') }}"
                                                title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 px-4 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fas fa-archive text-4xl mb-3"></i>
                                        <p class="text-lg">Tidak ada aspirasi yang ditolak</p>
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
            <div class="flex flex-col md:flex-row justify-between items-center p-4 border-t border-gray-100">
                <div class="text-sm text-gray-600 mb-4 md:mb-0">
                    Menampilkan {{ $pengaduans->firstItem() ?? 0 }}-{{ $pengaduans->lastItem() ?? 0 }}
                    dari {{ $pengaduans->total() }} aspirasi ditolak
                    <span class="text-xs text-gray-500">(5 data per halaman)</span>
                </div>

                <div class="flex items-center space-x-2 pagination-blue">
                    @if ($pengaduans->onFirstPage())
                        <span class="px-3 py-1 border border-gray-300 rounded text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $pengaduans->previousPageUrl() }}&search={{ request('search') }}"
                           class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 text-gray-700 transition-colors duration-200 pagination-link">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    @php
                        $current = $pengaduans->currentPage();
                        $last = $pengaduans->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);
                    @endphp

                    @for ($i = $start; $i <= $end; $i++)
                        @if ($i == $current)
                            <span class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors duration-200">{{ $i }}</span>
                        @else
                            <a href="{{ $pengaduans->url($i) }}&search={{ request('search') }}"
                               class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 text-gray-700 transition-colors duration-200 pagination-link">{{ $i }}</a>
                        @endif
                    @endfor

                    @if ($pengaduans->hasMorePages())
                        <a href="{{ $pengaduans->nextPageUrl() }}&search={{ request('search') }}"
                           class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 text-gray-700 transition-colors duration-200 pagination-link">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="px-3 py-1 border border-gray-300 rounded text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('modals')
    <!-- Modal Detail -->
    <div id="detail-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Detail Aspirasi Ditolak</h3>
                    <button id="close-detail-modal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                        <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                            <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-times-circle mr-2 text-red-500"></i>
                                Status Aspirasi
                            </h4>

                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-gray-600">Status</p>
                                    <p class="font-medium text-sm">
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">DITOLAK</span>
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-xs text-gray-600">Ditolak Oleh</p>
                                    <p class="font-medium text-sm" id="detail-admin">Admin</p>
                                </div>
                                
                                <div>
                                    <p class="text-xs text-gray-600">Tanggal Ditolak</p>
                                    <p class="font-medium text-sm" id="detail-tanggal-ditolak">N/A</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-file-alt mr-2 text-gray-500"></i>
                            Isi Aspirasi
                        </h4>

                        <div class="bg-white p-3 rounded border border-gray-200">
                            <p class="text-gray-700 text-sm whitespace-pre-line" id="detail-keterangan">N/A</p>
                        </div>

                        <div class="mt-2 text-sm text-gray-600">
                            <i class="far fa-clock mr-1"></i> Diajukan:
                            <span id="detail-created-date">N/A</span>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-purple-500"></i>
                            Informasi Tambahan
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500">Kategori</p>
                                <p class="font-medium text-sm">
                                    <span id="detail-kategori" class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">N/A</span>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Lokasi</p>
                                <p class="font-medium text-sm" id="detail-lokasi">Tidak ditentukan</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-400">
                        <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-comment-times mr-2 text-red-500"></i>
                            Alasan Penolakan
                        </h4>

                        <div class="bg-white p-4 rounded border border-red-200">
                            <p class="text-gray-700 text-sm whitespace-pre-line" id="detail-alasan-penolakan">
                                Tidak ada alasan penolakan
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Notification
function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    const messageEl = document.getElementById('notification-message');
    const icon = notification.querySelector('i');

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

    messageEl.textContent = message;

    setTimeout(hideNotification, 3000);
}

function hideNotification() {
    document.getElementById('notification').classList.remove('show');
}

// Modal Detail
function showAspirasiDetail(button) {
    function formatDate(dateString) {
        if (!dateString || dateString === '') return 'N/A';
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        } catch (e) {
            return dateString;
        }
    }

    // Set data dari button attributes
    document.getElementById('detail-nama').textContent = button.getAttribute('data-nama') || 'N/A';
    document.getElementById('detail-nisn').textContent = button.getAttribute('data-nisn') || 'N/A';
    document.getElementById('detail-kelas').textContent = button.getAttribute('data-kelas') || '-';
    document.getElementById('detail-jurusan').textContent = button.getAttribute('data-jurusan') || '-';
    document.getElementById('detail-kategori').textContent = button.getAttribute('data-kategori') || 'N/A';
    document.getElementById('detail-lokasi').textContent = button.getAttribute('data-lokasi') || 'Tidak ditentukan';
    document.getElementById('detail-keterangan').textContent = button.getAttribute('data-keterangan') || 'N/A';
    document.getElementById('detail-alasan-penolakan').textContent = button.getAttribute('data-alasan_penolakan') || 'Tidak ada alasan';
    document.getElementById('detail-admin').textContent = button.getAttribute('data-admin') || 'N/A';
    document.getElementById('detail-tanggal-ditolak').textContent = formatDate(button.getAttribute('data-tanggal_ditolak'));
    document.getElementById('detail-created-date').textContent = formatDate(button.getAttribute('data-created_at'));

    // Tampilkan modal
    document.getElementById('detail-modal').classList.add('show');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Event listener untuk tombol detail
    document.querySelectorAll('.detail-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            showAspirasiDetail(this);
        });
    });

    // Event listener untuk menutup modal detail
    const closeDetailBtn = document.getElementById('close-detail-modal');
    const detailModal = document.getElementById('detail-modal');

    closeDetailBtn?.addEventListener('click', () => {
        detailModal?.classList.remove('show');
    });

    document.getElementById('close-detail-modal-btn')?.addEventListener('click', function() {
        detailModal?.classList.remove('show');
    });

    detailModal?.addEventListener('click', (e) => {
        if (e.target === detailModal) {
            detailModal.classList.remove('show');
        }
    });

    // Search functionality
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = this.value.trim();

            searchTimeout = setTimeout(() => {
                if (searchTerm) {
                    window.location.href = `{{ route('admin.arsip.index') }}?search=${encodeURIComponent(searchTerm)}`;
                } else {
                    window.location.href = `{{ route('admin.arsip.index') }}`;
                }
            }, 500);
        });
    }

    // Close on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.getElementById('detail-modal')?.classList.remove('show');
            hideNotification();
        }
    });
});

// Global functions
window.hideNotification = hideNotification;
window.showAspirasiDetail = showAspirasiDetail;
</script>
@endpush