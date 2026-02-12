@extends('layouts.admin')

@section('title', 'SUARAKITA')
@section('page-title', 'Kategori')
@section('page-description', 'Kelola kategori aspirasi siswa')

@push('styles')
<style>
    /* Kategori page specific styles */
    .btn-glow:hover {
        box-shadow: 0 5px 20px rgba(34, 197, 94, 0.4);
    }

    .table-container {
        overflow-x: auto;
    }

    .table-header {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
    }

    .table-row:hover {
        background-color: #f8fafc;
    }

    .border-red-500 {
        border-color: #ef4444 !important;
    }

    .alert {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10001;
        max-width: 400px;
        animation: slideIn 0.3s ease-out;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
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

    .fa-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
    <!-- Header dengan tombol tambah dan filter -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0"> 
        <div></div>                  
        <div class="flex flex-wrap items-center gap-3">
            <!-- Search Input -->
            <div class="relative">
                <input type="text" id="search-input" placeholder="Cari kategori..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       value="{{ request('search', '') }}">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            
            <button id="tambah-kategori-btn" class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition duration-300 flex items-center justify-center btn-glow shadow-lg hover:shadow-xl">
                <i class="fas fa-folder-plus text-lg"></i>
            </button>
        </div>
    </div>
    
    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden relative">
        <div class="table-container">
            <table class="w-full">
                <thead class="sticky top-0 z-10">
                    <tr class="table-header">
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Nama Kategori</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody id="kategori-table-body">
                    @if($kategori->count() > 0)
                    @foreach($kategori as $index => $kat)
                        <tr class="table-row border-t border-gray-100 hover:bg-gray-50">
                            <td class="py-4 px-4">{{ ($kategori->currentPage() - 1) * $kategori->perPage() + $index + 1 }}</td>
                            <td class="py-4 px-4">
                                <span class="kategori-badge">
                                    {{ $kat->nama_kategori }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-2">
                                    <button class="edit-btn p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition duration-300" 
                                            data-id="{{ $kat->id_kategori }}" 
                                            data-nama="{{ $kat->nama_kategori }}">
                                        <i class="fas fa-edit"></i>
                                        <span class="sr-only">Edit</span>
                                    </button>
                                    <button class="delete-btn p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-300" 
                                            data-id="{{ $kat->id_kategori }}" 
                                            data-nama="{{ $kat->nama_kategori }}">
                                        <i class="fas fa-trash-alt"></i>
                                        <span class="sr-only">Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="3" class="py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p class="text-lg">Tidak ada data kategori ditemukan</p>
                            @if(request()->has('search') && request()->get('search'))
                                <p class="text-sm mt-1">Coba dengan kata kunci lain</p>
                            @endif
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div id="pagination-container" class="border-t border-gray-100">
            @if($kategori->hasPages())
            <div class="flex flex-col md:flex-row justify-between items-center p-4">
                <div class="text-sm text-gray-600 mb-4 md:mb-0">
                    Menampilkan <span class="font-semibold">{{ $kategori->firstItem() ?? 0 }}-{{ $kategori->lastItem() ?? 0 }}</span> 
                    dari <span class="font-semibold">{{ $kategori->total() }}</span> kategori
                </div>
                
                <div class="flex items-center space-x-2">
                    @if($kategori->onFirstPage())
                        <span class="px-3 py-1 rounded border border-gray-300 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <button onclick="loadPage({{ $kategori->currentPage() - 1 }})" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50 transition duration-300 pagination-btn" data-page="{{ $kategori->currentPage() - 1 }}">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @endif
                    
                    @foreach ($kategori->getUrlRange(1, $kategori->lastPage()) as $page => $url)
                        @if($page == $kategori->currentPage())
                            <span class="px-3 py-1 rounded bg-gradient-to-r from-green-500 to-green-600 text-white font-medium">
                                {{ $page }}
                            </span>
                        @else
                            <button onclick="loadPage({{ $page }})" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50 transition duration-300 pagination-btn" data-page="{{ $page }}">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                    
                    @if($kategori->hasMorePages())
                        <button onclick="loadPage({{ $kategori->currentPage() + 1 }})" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50 transition duration-300 pagination-btn" data-page="{{ $kategori->currentPage() + 1 }}">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @else
                        <span class="px-3 py-1 rounded border border-gray-300 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            </div>
            @else
            <div class="flex flex-col md:flex-row justify-between items-center p-4">
                <div class="text-sm text-gray-600">
                    Menampilkan <span class="font-semibold">{{ $kategori->count() }}</span> dari <span class="font-semibold">{{ $kategori->total() }}</span> kategori
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('modals')
    <!-- Modal Tambah Kategori -->
    <div id="tambahKategoriModal" class="modal-overlay">
        <div class="modal-content">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Tambah Kategori Baru</h3>
                    <button id="close-tambah-modal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="tambah-kategori-form">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                            <input type="text" id="nama_kategori" name="nama_kategori" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-8">
                        <button type="button" id="cancel-tambah" class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-300">
                            Batal
                        </button>
                        <button type="submit" id="submit-tambah" class="px-5 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition duration-300">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kategori -->
    <div id="editKategoriModal" class="modal-overlay">
        <div class="modal-content">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Edit Kategori</h3>
                    <button id="close-edit-modal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="edit-kategori-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit-id" name="id_kategori">
                    
                    <div class="space-y-4">
                        <div>
                            <label for="edit-nama_kategori" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                            <input type="text" id="edit-nama_kategori" name="nama_kategori" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-8">
                        <button type="button" id="cancel-edit" class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-300">
                            Batal
                        </button>
                        <button type="submit" id="submit-edit" class="px-5 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition duration-300">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteKategoriModal" class="modal-overlay">
        <div class="modal-content max-w-md">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                
                <h3 class="text-xl font-bold text-gray-800 text-center mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-600 text-center mb-6" id="delete-message">Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.</p>
                
                <div class="flex justify-center space-x-3">
                    <button id="cancel-delete" class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-300">
                        Batal
                    </button>
                    <button id="confirm-delete" class="px-5 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition duration-300">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Variabel global
    let kategoriToDelete = null;
    let currentAlertTimeout = null;
    let searchTimeout = null;
    let currentPage = {{ $kategori->currentPage() }};
    let currentSearch = "{{ request('search', '') }}";
    
    // ========== FUNGSI UTILITAS ==========
    // Fungsi untuk menampilkan notifikasi
    function showAlert(message, type = 'success') {
        const existingAlert = document.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
            if (currentAlertTimeout) {
                clearTimeout(currentAlertTimeout);
            }
        }
        
        const alert = document.createElement('div');
        alert.className = `alert px-6 py-4 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'}`;
        
        alert.innerHTML = `
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-3"></i>
                    <span>${message}</span>
                </div>
                <button class="close-alert text-gray-500 hover:text-gray-700 ml-4">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(alert);
        
        const closeBtn = alert.querySelector('.close-alert');
        closeBtn.addEventListener('click', function() {
            alert.remove();
            if (currentAlertTimeout) {
                clearTimeout(currentAlertTimeout);
            }
        });
        
        currentAlertTimeout = setTimeout(() => {
            if (document.body.contains(alert)) {
                alert.remove();
            }
        }, 5000);
    }
    
    // ========== FUNGSI MODAL ==========
    // Fungsi untuk menampilkan modal
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            
            // Tutup dropdown profil jika terbuka
            const profileMenu = document.getElementById('profile-dropdown');
            if (profileMenu && profileMenu.classList.contains('show')) {
                profileMenu.classList.add('hidden');
                profileMenu.classList.remove('show');
            }
        }
    }
    
    // Fungsi untuk menyembunyikan modal
    function hideModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
        
        // Reset data hapus
        if (modalId === 'deleteKategoriModal') {
            kategoriToDelete = null;
        }
    }
    
    // ========== FUNGSI AJAX & TABLE ==========
    // Function untuk render table dari data AJAX
    function renderTable(data) {
        const tableBody = document.getElementById('kategori-table-body');
        const paginationContainer = document.getElementById('pagination-container');
        
        // Render table rows
        if (data.kategori && data.kategori.data && data.kategori.data.length > 0) {
            let tableHTML = '';
            data.kategori.data.forEach((item, index) => {
                tableHTML += `
                <tr class="table-row border-t border-gray-100 hover:bg-gray-50">
                    <td class="py-4 px-4">${(data.kategori.current_page - 1) * data.kategori.per_page + index + 1}</td>
                    <td class="py-4 px-4">
                        <span class="kategori-badge">
                            ${item.nama_kategori}
                        </span>
                    </td>
                    <td class="py-4 px-4">
                        <div class="flex items-center space-x-2">
                            <button class="edit-btn p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition duration-300" 
                                    data-id="${item.id_kategori}" 
                                    data-nama="${item.nama_kategori}">
                                <i class="fas fa-edit"></i>
                                <span class="sr-only">Edit</span>
                            </button>
                            <button class="delete-btn p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-300" 
                                    data-id="${item.id_kategori}" 
                                    data-nama="${item.nama_kategori}">
                                <i class="fas fa-trash-alt"></i>
                                <span class="sr-only">Hapus</span>
                            </button>
                        </div>
                    </td>
                </tr>
                `;
            });
            tableBody.innerHTML = tableHTML;
        } else {
            tableBody.innerHTML = `
            <tr>
                <td colspan="3" class="py-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-3xl mb-2"></i>
                    <p class="text-lg">Tidak ada data kategori ditemukan</p>
                    ${currentSearch ? '<p class="text-sm mt-1">Coba dengan kata kunci lain</p>' : ''}
                </td>
            </tr>
            `;
        }
        
        // Render pagination
        let paginationHTML = '';
        if (data.kategori && data.kategori.last_page && data.kategori.last_page > 1) {
            paginationHTML = `
            <div class="flex flex-col md:flex-row justify-between items-center p-4">
                <div class="text-sm text-gray-600 mb-4 md:mb-0">
                    Menampilkan <span class="font-semibold">${data.kategori.from || 0}-${data.kategori.to || 0}</span> 
                    dari <span class="font-semibold">${data.kategori.total}</span> kategori
                </div>
                
                <div class="flex items-center space-x-2">
                    ${data.kategori.current_page === 1 ? 
                        '<span class="px-3 py-1 rounded border border-gray-300 text-gray-400 cursor-not-allowed"><i class="fas fa-chevron-left"></i></span>' : 
                        `<button onclick="loadPage(${data.kategori.current_page - 1})" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50 transition duration-300 pagination-btn" data-page="${data.kategori.current_page - 1}"><i class="fas fa-chevron-left"></i></button>`
                    }
                    
                    ${Array.from({length: data.kategori.last_page}, (_, i) => i + 1).map(page => 
                        page === data.kategori.current_page ?
                        `<span class="px-3 py-1 rounded bg-gradient-to-r from-green-500 to-green-600 text-white font-medium">${page}</span>` :
                        `<button onclick="loadPage(${page})" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50 transition duration-300 pagination-btn" data-page="${page}">${page}</button>`
                    ).join('')}
                    
                    ${!data.kategori.next_page_url ? 
                        '<span class="px-3 py-1 rounded border border-gray-300 text-gray-400 cursor-not-allowed"><i class="fas fa-chevron-right"></i></span>' : 
                        `<button onclick="loadPage(${data.kategori.current_page + 1})" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50 transition duration-300 pagination-btn" data-page="${data.kategori.current_page + 1}"><i class="fas fa-chevron-right"></i></button>`
                    }
                </div>
            </div>
            `;
        } else {
            paginationHTML = `
            <div class="flex flex-col md:flex-row justify-between items-center p-4">
                <div class="text-sm text-gray-600">
                    Menampilkan <span class="font-semibold">${data.kategori && data.kategori.data ? data.kategori.data.length : 0}</span> dari <span class="font-semibold">${data.kategori ? data.kategori.total : 0}</span> kategori
                </div>
            </div>
            `;
        }
        paginationContainer.innerHTML = paginationHTML;
        
        // Re-attach event listeners
        attachRowEventListeners();
    }
    
    // Function untuk AJAX search dan pagination
    async function performSearch(searchTerm, page = 1) {
        currentSearch = searchTerm;
        currentPage = page;
        
        try {
            const url = new URL('{{ route("admin.kategori.index") }}', window.location.origin);
            url.searchParams.append('search', searchTerm);
            url.searchParams.append('page', page);
            
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                renderTable(data);
                
                // Update browser URL tanpa reload
                const newUrl = new URL(window.location);
                if (searchTerm) {
                    newUrl.searchParams.set('search', searchTerm);
                } else {
                    newUrl.searchParams.delete('search');
                }
                if (page > 1) {
                    newUrl.searchParams.set('page', page);
                } else {
                    newUrl.searchParams.delete('page');
                }
                window.history.pushState({}, '', newUrl);
            } else {
                showAlert('Terjadi kesalahan saat memuat data', 'error');
            }
        } catch (error) {
            console.error('Fetch Error:', error);
            showAlert('Terjadi kesalahan saat memuat data', 'error');
        }
    }

    // Function untuk load page tertentu
    async function loadPage(page) {
        const searchTerm = document.getElementById('search-input').value.trim();
        await performSearch(searchTerm, page);
    }
    
    // Function untuk re-attach event listeners ke table rows
    function attachRowEventListeners() {
        // Edit button handlers
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                
                // Set values in edit form
                document.getElementById('edit-id').value = id;
                document.getElementById('edit-nama_kategori').value = nama;
                
                // Open edit modal
                showModal('editKategoriModal');
                document.getElementById('edit-nama_kategori').focus();
            });
        });
        
        // Delete button handlers
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                
                kategoriToDelete = { id: id, nama: nama };
                
                document.getElementById('delete-message').textContent = 
                    `Apakah Anda yakin ingin menghapus kategori "${nama}"? Tindakan ini tidak dapat dibatalkan.`;
                
                showModal('deleteKategoriModal');
            });
        });
        
        // Pagination button handlers
        document.querySelectorAll('.pagination-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.getAttribute('data-page');
                loadPage(page);
            });
        });
    }
    
    // ========== EVENT LISTENERS ==========
    // Event listener ketika DOM siap
    document.addEventListener('DOMContentLoaded', function() {            
        // 1. Tombol tambah kategori
        const tambahBtn = document.getElementById('tambah-kategori-btn');
        if (tambahBtn) {
            tambahBtn.addEventListener('click', function(e) {
                e.preventDefault();
                showModal('tambahKategoriModal');
                document.getElementById('nama_kategori').focus();
            });
        }
        
        // 2. Tombol close modal tambah
        const closeTambahModal = document.getElementById('close-tambah-modal');
        if (closeTambahModal) {
            closeTambahModal.addEventListener('click', function() {
                hideModal('tambahKategoriModal');
                document.getElementById('tambah-kategori-form').reset();
            });
        }
        
        const cancelTambah = document.getElementById('cancel-tambah');
        if (cancelTambah) {
            cancelTambah.addEventListener('click', function() {
                hideModal('tambahKategoriModal');
                document.getElementById('tambah-kategori-form').reset();
            });
        }
        
        // 3. Tombol close modal edit
        const closeEditModal = document.getElementById('close-edit-modal');
        if (closeEditModal) {
            closeEditModal.addEventListener('click', function() {
                hideModal('editKategoriModal');
            });
        }
        
        const cancelEdit = document.getElementById('cancel-edit');
        if (cancelEdit) {
            cancelEdit.addEventListener('click', function() {
                hideModal('editKategoriModal');
            });
        }
        
        // 4. Tombol cancel hapus
        const cancelDelete = document.getElementById('cancel-delete');
        if (cancelDelete) {
            cancelDelete.addEventListener('click', function() {
                hideModal('deleteKategoriModal');
                kategoriToDelete = null;
            });
        }
        
        // 5. Live search
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                const searchTerm = e.target.value.trim();
                
                searchTimeout = setTimeout(function() {
                    performSearch(searchTerm, 1);
                }, 500);
            });
        }
        
        // 6. Tutup modal dengan klik di luar
        document.addEventListener('click', function(e) {
            // For tambah modal
            const tambahModal = document.getElementById('tambahKategoriModal');
            const tambahModalContent = document.querySelector('#tambahKategoriModal .modal-content');
            
            if (tambahModal && tambahModal.classList.contains('show')) {
                if (!tambahModalContent.contains(e.target) && !e.target.closest('#tambah-kategori-btn') && 
                    !e.target.closest('.modal-content')) {
                    hideModal('tambahKategoriModal');
                    document.getElementById('tambah-kategori-form').reset();
                }
            }
            
            // For edit modal
            const editModal = document.getElementById('editKategoriModal');
            const editModalContent = document.querySelector('#editKategoriModal .modal-content');
            
            if (editModal && editModal.classList.contains('show')) {
                if (!editModalContent.contains(e.target) && !e.target.closest('.edit-btn') && 
                    !e.target.closest('.modal-content')) {
                    hideModal('editKategoriModal');
                }
            }
            
            // For delete modal
            const deleteModal = document.getElementById('deleteKategoriModal');
            const deleteModalContent = document.querySelector('#deleteKategoriModal .modal-content');
            
            if (deleteModal && deleteModal.classList.contains('show')) {
                if (!deleteModalContent.contains(e.target) && !e.target.closest('.delete-btn') && 
                    !e.target.closest('.modal-content')) {
                    hideModal('deleteKategoriModal');
                }
            }
        });
        
        // 7. Submit form tambah kategori
        const tambahForm = document.getElementById('tambah-kategori-form');
        if (tambahForm) {
            tambahForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = document.getElementById('submit-tambah');
                const originalText = submitBtn.innerHTML;
                
                // Validasi required
                const namaKategori = document.getElementById('nama_kategori').value.trim();
                if (!namaKategori) {
                    showAlert('Nama kategori wajib diisi', 'error');
                    document.getElementById('nama_kategori').focus();
                    return;
                }
                
                // Disable button dan show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                
                try {
                    const response = await fetch("{{ route('admin.kategori.store') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showAlert(data.message, 'success');
                        hideModal('tambahKategoriModal');
                        tambahForm.reset();
                        
                        // Refresh table dengan data terbaru
                        await performSearch(currentSearch, 1); // Kembali ke halaman 1
                    } else {
                        // Handle validation errors
                        if (data.errors && data.errors.nama_kategori) {
                            showAlert(data.errors.nama_kategori[0], 'error');
                        } else {
                            showAlert(data.message || 'Terjadi kesalahan', 'error');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('Terjadi kesalahan saat menyimpan data', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }

        // 8. Submit form edit kategori
        const editForm = document.getElementById('edit-kategori-form');
        if (editForm) {
            editForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const id = document.getElementById('edit-id').value;
                const submitBtn = document.getElementById('submit-edit');
                const originalText = submitBtn.innerHTML;
                
                // Validasi required
                const namaKategori = document.getElementById('edit-nama_kategori').value.trim();
                if (!namaKategori) {
                    showAlert('Nama kategori wajib diisi', 'error');
                    document.getElementById('edit-nama_kategori').focus();
                    return;
                }
                
                // Disable button dan show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memperbarui...';
                
                const formData = new FormData(this);
                
                try {
                    const response = await fetch("{{ url('admin/kategori') }}/" + id, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-HTTP-Method-Override': 'PUT',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showAlert(data.message, 'success');
                        hideModal('editKategoriModal');
                        
                        // Refresh table dengan data terbaru
                        await performSearch(currentSearch, currentPage);
                    } else {
                        // Handle validation errors
                        if (data.errors && data.errors.nama_kategori) {
                            showAlert(data.errors.nama_kategori[0], 'error');
                        } else {
                            showAlert(data.message || 'Terjadi kesalahan', 'error');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('Terjadi kesalahan saat memperbarui data', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }

        // 9. Confirm delete
        const confirmDeleteBtn = document.getElementById('confirm-delete');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                
                if (kategoriToDelete) {
                    const deleteBtn = document.getElementById('confirm-delete');
                    const originalText = deleteBtn.innerHTML;
                    
                    deleteBtn.disabled = true;
                    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';
                    
                    try {
                        const response = await fetch("{{ url('admin/kategori') }}/" + kategoriToDelete.id, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-HTTP-Method-Override': 'DELETE',
                                'Accept': 'application/json'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            showAlert(data.message, 'success');
                            hideModal('deleteKategoriModal');
                            
                            // Refresh table dengan data terbaru
                            await performSearch(currentSearch, currentPage);
                        } else {
                            showAlert(data.message || 'Terjadi kesalahan', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showAlert('Terjadi kesalahan saat menghapus data', 'error');
                    } finally {
                        deleteBtn.disabled = false;
                        deleteBtn.innerHTML = originalText;
                        kategoriToDelete = null;
                    }
                }
            });
        }
        
        // 10. Tutup modal dengan ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const tambahModal = document.getElementById('tambahKategoriModal');
                const editModal = document.getElementById('editKategoriModal');
                const deleteModal = document.getElementById('deleteKategoriModal');
                
                if (tambahModal && tambahModal.classList.contains('show')) {
                    hideModal('tambahKategoriModal');
                }
                
                if (editModal && editModal.classList.contains('show')) {
                    hideModal('editKategoriModal');
                }
                
                if (deleteModal && deleteModal.classList.contains('show')) {
                    hideModal('deleteKategoriModal');
                }
            }
        });
        
        // 11. Handle browser back/forward buttons
        window.addEventListener('popstate', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const searchParam = urlParams.get('search') || '';
            const pageParam = parseInt(urlParams.get('page')) || 1;
            
            document.getElementById('search-input').value = searchParam;
            performSearch(searchParam, pageParam);
        });
        
        // 12. Initial attach event listeners
        attachRowEventListeners();
        
        // 13. Show session messages
        @if(session('success'))
            showAlert('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showAlert('{{ session('error') }}', 'error');
        @endif
    });
    
    // Helper function untuk mendapatkan data saat ini
    async function getCurrentData() {
        try {
            const url = new URL('{{ route("admin.kategori.index") }}', window.location.origin);
            url.searchParams.append('search', currentSearch);
            url.searchParams.append('page', currentPage);
            
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            return await response.json();
        } catch (error) {
            console.error('Error getting current data:', error);
            return { kategori: { data: [] } };
        }
    }
</script>
@endpush