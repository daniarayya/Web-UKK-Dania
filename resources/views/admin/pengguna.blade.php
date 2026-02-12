@extends('layouts.admin')

@section('title', 'SUARAKITA')

@section('page-title', 'Pengguna')
@section('page-description', 'Kelola data pengguna sistem')

@push('styles')
<style>
    /* Custom CSS for Pengguna Page */
    .table-row:hover {
        background-color: #f9fafb;
    }
    
    /* Custom styles untuk readonly input */
    input[readonly] {
        background-color: #f9fafb;
        cursor: not-allowed;
    }
    
    /* Loading spinner */
    .fa-spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Validation styles */
    .border-red-500 {
        border-color: #ef4444 !important;
    }
    
    .validation-error {
        font-size: 0.75rem;
        margin-top: 0.25rem;
        color: #ef4444;
    }
    
    /* Search input style */
    .search-input-container {
        position: relative;
        width: 100%;
        max-width: 300px;
    }
    
    .search-input-container i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }
    
    .search-input {
        padding-left: 40px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        width: 100%;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Tombol aksi style */
    .edit-btn {
        color: #3b82f6;
        background-color: white;
        border: none;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: all 0.3s;
    }
    
    .edit-btn:hover {
        background-color: #eff6ff;
    }
    
    .delete-btn {
        color: #ef4444;
        background-color: white;
        border: none;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: all 0.3s;
    }
    
    .delete-btn:hover {
        background-color: #fef2f2;
    }
    
    .pagination-btn {
        padding: 6px 12px;
        border: 1px solid #d1d5db;
        background-color: white;
        color: #374151;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
    }
    
    .pagination-btn:hover:not(:disabled) {
        background-color: #22c55e;
        color: white;
        border-color: #22c55e;
    }
    
    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .pagination-btn.active {
        background-color: #22c55e;
        color: white;
        border-color: #22c55e;
    }
    
    .no-results {
        text-align: center;
        padding: 40px 20px;
        color: #6b7280;
    }
    
    /* Tombol dengan icon saja */
    .icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        transition: all 0.3s;
    }
    
    .icon-btn:hover {
        transform: translateY(-1px);
    }
    
    /* Modal styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s, visibility 0.3s;
    }
    
    .modal-overlay.show {
        opacity: 1;
        visibility: visible;
    }
    
    .modal-content {
        background-color: white;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        transform: translateY(-20px);
        transition: transform 0.3s;
    }
    
    .modal-overlay.show .modal-content {
        transform: translateY(0);
    }
    
    /* Confirmation modal khusus */
    .confirmation-modal {
        max-width: 400px;
    }
    
    /* CUSTOM SCROLLBAR - WARNA HIJAU */
    .modal-content::-webkit-scrollbar {
        width: 8px;
    }
    
    .modal-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .modal-content::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #22c55e 0%, #16a34a 100%);
        border-radius: 10px;
    }
    
    .modal-content::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #16a34a 0%, #15803d 100%);
    }
    
    .modal-content {
        scrollbar-width: thin;
        scrollbar-color: #22c55e #f1f1f1;
    }
    
    /* Scrollbar untuk tabel */
    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);
        border-radius: 10px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(90deg, #16a34a 0%, #15803d 100%);
    }
    
    /* Style untuk option yang disabled */
    select option:disabled {
        color: #9ca3af;
        background-color: #f3f4f6;
        font-style: italic;
    }

    /* Notification Styles */
    .alert {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 10000;
        max-width: 400px;
        transform: translateX(120%);
        transition: transform 0.3s ease-in-out;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-radius: 0.75rem;
        overflow: hidden;
    }

    .alert.show {
        transform: translateX(0);
    }

    .alert-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        color: white;
    }

    .alert-error {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        color: white;
    }

    .alert-content {
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .alert-message {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
    }

    .alert-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        margin-left: 1rem;
        transition: all 0.2s ease;
    }

    .alert-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<!-- Content Section -->
<div class="p-4 md:p-6">
    <!-- Header with Search and Button -->
    <div class="flex items-center mb-6">
        <!-- Spacer kiri (biar kanan semua) -->
        <div class="ml-auto flex items-center gap-4">
            <!-- Search Input -->
            <div class="search-input-container">
                <i class="fas fa-search"></i>
                <input type="text" id="search-input" 
                    class="search-input py-2.5" 
                    placeholder="Cari pengguna...">
            </div>
            
            <button id="tambah-pengguna-btn" 
            class="px-3 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:scale-105" title="Tambah Pengguna">
                <i class="fas fa-user-plus text-xl"></i>
            </button>
        </div>
    </div>
                
    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">No</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Username</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Password</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Role</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">NISN</th>
                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="pengguna-table-body">
                    @foreach($users as $index => $user)
                    <tr class="table-row hover:bg-gray-50 transition duration-150 pengguna-row" 
                        data-id="{{ $user->id_user }}"
                        data-nama="{{ strtolower($user->nama ?? ($user->siswa ? $user->siswa->nama : '')) }}"
                        data-username="{{ strtolower($user->username) }}"
                        data-role="{{ strtolower($user->role) }}"
                        data-nisn="{{ $user->nisn ?? '' }}">
                        <td class="py-4 px-6 text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                        <td class="py-4 px-6">
                            @if($user->nama)
                                <div class="flex items-center">
                                    <div class="font-medium text-gray-800">{{ $user->nama }}</div>
                                </div>
                            @elseif($user->siswa)
                                <div class="flex items-center">
                                    <div class="font-medium text-gray-800">{{ $user->siswa->nama }}</div>
                                </div>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-sm font-medium text-gray-900">{{ $user->username }}</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-key mr-1"></i>
                                    ••••••••
                                </span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 
                                   ($user->role == 'kepsek' ? 'bg-blue-100 text-blue-800' : 
                                   'bg-green-100 text-green-800') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-700">
                            {{ $user->nisn ?? '-' }}
                        </td>
                        <!-- TOMBOL AKSI -->
                        <td class="py-4 px-4">
                            <div class="flex items-center space-x-2">
                                <!-- Edit Button -->
                                <button class="edit-btn icon-btn edit-pengguna-btn" 
                                        data-id="{{ $user->id_user }}"
                                        data-username="{{ $user->username }}"
                                        data-role="{{ $user->role }}"
                                        data-nisn="{{ $user->nisn ?? '' }}"
                                        data-nama="{{ $user->nama ?? ($user->siswa ? $user->siswa->nama : '') }}">
                                    <i class="fas fa-edit"></i>
                                    <span class="sr-only">Edit</span>
                                </button>
                                <!-- Delete Button -->
                                <button class="delete-btn icon-btn delete-pengguna-btn" 
                                        data-id="{{ $user->id_user }}"
                                        data-username="{{ $user->username }}">
                                    <i class="fas fa-trash-alt"></i>
                                    <span class="sr-only">Hapus</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- No Results Message -->
            <div id="no-results" class="no-results hidden">
                <i class="fas fa-search text-3xl mb-3 text-gray-300"></i>
                <p class="text-lg font-medium text-gray-500">Tidak ada data yang ditemukan</p>
                <p class="text-sm text-gray-400">Coba dengan kata kunci lain</p>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="flex flex-col sm:flex-row items-center justify-between px-6 py-4 border-t border-gray-200 gap-4">
            <div class="text-sm text-gray-700">
                <span id="pagination-info">Menampilkan 1-{{ min(5, count($users)) }} dari {{ count($users) }} pengguna</span>
            </div>
            <div class="flex items-center space-x-2" id="pagination-controls">
                <!-- Pagination buttons akan di-generate oleh JavaScript -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
<!-- Modal Tambah Pengguna -->
<div id="tambah-pengguna-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Tambah Pengguna Baru</h3>
                <button id="close-tambah-modal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Form -->
            <form id="form-tambah-pengguna">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="tambah-username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" id="tambah-username" name="username" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300" required>
                        <div id="tambah-username-error" class="validation-error hidden"></div>
                        <p class="mt-1 text-sm text-gray-500">Username harus unik dan tidak boleh mengandung spasi</p>
                    </div>
                    
                    <div>
                        <label for="tambah-password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" id="tambah-password" name="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300" required minlength="5">
                        <div id="tambah-password-error" class="validation-error hidden"></div>
                        <p class="mt-1 text-sm text-gray-500">Password minimal 5 karakter</p>
                    </div>
                    
                    <div>
                        <label for="tambah-role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select id="tambah-role" name="role" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300" required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="kepsek">Kepala Sekolah</option>
                            <option value="siswa">Siswa</option>
                        </select>
                        <div id="tambah-role-error" class="validation-error hidden"></div>
                    </div>
                    
                    <!-- NISN dropdown untuk role siswa -->
                    <div id="tambah-nisn-container" class="hidden">
                        <label for="tambah-nisn" class="block text-sm font-medium text-gray-700 mb-2">Pilih NISN</label>
                        <div class="relative">
                            <select id="tambah-nisn" name="nisn" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300">
                                <option value="">Pilih NISN</option>
                                @foreach($siswas as $siswa)
                                    @php
                                        $alreadyUsed = in_array($siswa->nisn, $usedNisn ?? []);
                                    @endphp
                                    <option value="{{ $siswa->nisn }}" 
                                            data-nama="{{ $siswa->nama }}"
                                            {{ $alreadyUsed ? 'disabled' : '' }}>
                                        {{ $siswa->nisn }} - {{ $siswa->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="tambah-nisn-error" class="validation-error hidden"></div>
                        <p class="mt-1 text-sm text-gray-500">NISN yang sudah memiliki akun tidak dapat dipilih</p>
                    </div>
                    
                    <!-- Nama untuk semua role -->
                    <div id="tambah-nama-container">
                        <label for="tambah-nama" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                        <input type="text" id="tambah-nama" name="nama" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300" required>
                        <div id="tambah-nama-error" class="validation-error hidden"></div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex justify-end space-x-3 mt-8">
                    <button type="button" id="cancel-tambah" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-300">
                        Batal
                    </button>
                    <button type="submit" id="submit-tambah" class="px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:opacity-90 transition duration-300">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Pengguna -->
<div id="edit-pengguna-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Edit Pengguna</h3>
                <button id="close-edit-modal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Form -->
            <form id="form-edit-pengguna">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-id" name="id_user">
                
                <div class="space-y-4">
                    <div>
                        <label for="edit-username" class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                        <input type="text" id="edit-username" name="username" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300" required>
                        <div id="edit-username-error" class="validation-error hidden"></div>
                    </div>
                    
                    <div>
                        <label for="edit-password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" id="edit-password" name="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300">
                        <div id="edit-password-error" class="validation-error hidden"></div>
                        <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                    </div>
                    
                    <div>
                        <label for="edit-role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                        <select id="edit-role" name="role" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300" required>
                            <option value="admin">Admin</option>
                            <option value="kepsek">Kepala Sekolah</option>
                            <option value="siswa">Siswa</option>
                        </select>
                        <div id="edit-role-error" class="validation-error hidden"></div>
                    </div>
                    
                    <!-- NISN dropdown untuk role siswa -->
                    <div id="edit-nisn-container" class="hidden">
                        <label for="edit-nisn" class="block text-sm font-medium text-gray-700 mb-2">Pilih NISN Siswa</label>
                        <div class="relative">
                            <select id="edit-nisn" name="nisn" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300">
                                <option value="">Pilih NISN</option>
                                @foreach($siswas as $siswa)
                                    @php
                                        $alreadyUsed = in_array($siswa->nisn, $usedNisn ?? []);
                                    @endphp
                                    <option value="{{ $siswa->nisn }}" 
                                            data-nama="{{ $siswa->nama }}"
                                            {{ $alreadyUsed ? 'disabled' : '' }}>
                                        {{ $siswa->nisn }} - {{ $siswa->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="edit-nisn-error" class="validation-error hidden"></div>
                        <p class="mt-1 text-sm text-gray-500">NISN yang sudah memiliki akun tidak dapat dipilih</p>
                    </div>
                    
                    <!-- Nama untuk semua role -->
                    <div id="edit-nama-container">
                        <label for="edit-nama" class="block text-sm font-medium text-gray-700 mb-2">Nama *</label>
                        <input type="text" id="edit-nama" name="nama" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300" required>
                        <div id="edit-nama-error" class="validation-error hidden"></div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex justify-end space-x-3 mt-8">
                    <button type="button" id="cancel-edit" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-300">
                        Batal
                    </button>
                    <button type="submit" id="submit-edit" class="px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:opacity-90 transition duration-300">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="delete-confirm-modal" class="modal-overlay">
    <div class="modal-content confirmation-modal">
        <div class="p-6">
            <!-- Icon -->
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
            </div>
            
            <!-- Message -->
            <div class="text-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Hapus Pengguna</h3>
                <p class="text-gray-600" id="delete-message">Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-center space-x-3">
                <button type="button" id="cancel-delete" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-300">
                    Batal
                </button>
                <button type="button" id="confirm-delete" class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Data siswa untuk informasi tambahan
    const siswaData = @json($siswaData);
    let currentAlertTimeout = null;
    
    // Variabel untuk pagination
    let currentPage = 1;
    const itemsPerPage = 5;
    let allRows = [];
    let filteredRows = [];
    
    // ========== FUNGSI NOTIFIKASI ==========
    // Fungsi untuk menampilkan notifikasi
    function showNotification(message, type = 'success', duration = 5000) {
        // Hapus notifikasi lama jika ada
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => {
            alert.style.transform = 'translateX(120%)';
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 300);
        });
        
        if (currentAlertTimeout) {
            clearTimeout(currentAlertTimeout);
        }
        
        // Buat elemen notifikasi
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.style.zIndex = '99999';
        
        // Tentukan icon berdasarkan type
        let icon = 'fa-check-circle';
        if (type === 'error') icon = 'fa-exclamation-circle';
        if (type === 'warning') icon = 'fa-exclamation-triangle';
        if (type === 'info') icon = 'fa-info-circle';
        
        alert.innerHTML = `
            <div class="alert-content">
                <div class="alert-message">
                    <i class="fas ${icon} text-xl"></i>
                    <span>${message}</span>
                </div>
                <button class="alert-close close-notification" aria-label="Tutup notifikasi">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(alert);
        
        // Animasikan masuk
        setTimeout(() => {
            alert.classList.add('show');
            alert.style.transform = 'translateX(0)';
        }, 10);
        
        // Event listener untuk tombol close
        const closeBtn = alert.querySelector('.close-notification');
        closeBtn.addEventListener('click', function() {
            closeNotification(alert);
        });
        
        // Auto-hide
        currentAlertTimeout = setTimeout(() => {
            closeNotification(alert);
        }, duration);
        
        // Fungsi untuk menutup notifikasi
        function closeNotification(alertElement) {
            alertElement.style.transform = 'translateX(120%)';
            setTimeout(() => {
                if (alertElement.parentNode) {
                    alertElement.remove();
                }
            }, 300);
            if (currentAlertTimeout) {
                clearTimeout(currentAlertTimeout);
            }
        }
        
        // Close dengan ESC
        document.addEventListener('keydown', function closeOnEsc(e) {
            if (e.key === 'Escape') {
                closeNotification(alert);
                document.removeEventListener('keydown', closeOnEsc);
            }
        });
    }
    
    // ========== FUNGSI FORM ==========
    // Fungsi untuk mengatur form berdasarkan role (TAMBAH)
    function setupRoleBasedFormTambah() {
        const roleSelect = document.getElementById('tambah-role');
        const nisnContainer = document.getElementById('tambah-nisn-container');
        const nisnSelect = document.getElementById('tambah-nisn');
        const namaInput = document.getElementById('tambah-nama');
        
        roleSelect.addEventListener('change', function() {
            // Clear semua fields terkait
            nisnSelect.value = '';
            namaInput.value = '';
            namaInput.readOnly = false;
            
            if (this.value === 'siswa') {
                // Tampilkan dropdown NISN
                nisnContainer.classList.remove('hidden');
                nisnContainer.classList.add('block');
                
                // Add event listener untuk NISN select
                nisnSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value && !selectedOption.disabled) {
                        namaInput.value = selectedOption.getAttribute('data-nama') || '';
                        namaInput.readOnly = true;
                    } else {
                        namaInput.value = '';
                        namaInput.readOnly = false;
                    }
                });
            } else {
                // Sembunyikan dropdown NISN
                nisnContainer.classList.add('hidden');
                nisnContainer.classList.remove('block');
                
                // Nama bisa diisi manual
                namaInput.readOnly = false;
            }
        });
    }
    
    // Fungsi untuk mengatur form berdasarkan role (EDIT)
    function setupRoleBasedFormEdit() {
        const roleSelect = document.getElementById('edit-role');
        const nisnContainer = document.getElementById('edit-nisn-container');
        const nisnSelect = document.getElementById('edit-nisn');
        const namaInput = document.getElementById('edit-nama');
        
        roleSelect.addEventListener('change', function() {
            if (this.value === 'siswa') {
                // Tampilkan dropdown NISN
                nisnContainer.classList.remove('hidden');
                nisnContainer.classList.add('block');
                
                // Add event listener untuk NISN select
                nisnSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value && !selectedOption.disabled) {
                        namaInput.value = selectedOption.getAttribute('data-nama') || '';
                        namaInput.readOnly = true;
                    } else {
                        namaInput.value = '';
                        namaInput.readOnly = false;
                    }
                });
            } else {
                // Sembunyikan dropdown NISN
                nisnContainer.classList.add('hidden');
                nisnContainer.classList.remove('block');
                
                // Clear NISN dan enable nama
                nisnSelect.value = '';
                namaInput.readOnly = false;
                namaInput.placeholder = "Masukkan nama lengkap";
            }
        });
    }
    
    // Fungsi untuk reset error pada form
    function resetFormErrors(formId) {
        const form = document.getElementById(formId);
        const errorElements = form.querySelectorAll('.validation-error');
        errorElements.forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        const inputElements = form.querySelectorAll('input, select');
        inputElements.forEach(el => {
            el.classList.remove('border-red-500');
        });
    }
    
    // Fungsi untuk menampilkan error pada field tertentu
    function showFieldError(fieldId, message) {
        const input = document.getElementById(fieldId);
        const errorId = `${fieldId}-error`;
        const errorElement = document.getElementById(errorId);
        
        if (input && errorElement) {
            input.classList.add('border-red-500');
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
            input.focus();
        }
    }
    
    // ========== FUNGSI MODAL ==========
    // Fungsi untuk menampilkan modal
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }
    }
    
    // Fungsi untuk menyembunyikan modal
    function hideModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = ''; // Enable scrolling
        }
    }
    
    // ========== FUNGSI DELETE ==========
    let penggunaToDelete = null;
    
    // Fungsi untuk menampilkan modal konfirmasi hapus
    function showDeleteModal(penggunaId, username) {
        penggunaToDelete = { id: penggunaId, username: username };
        
        // Set message in delete modal
        const deleteMessage = document.getElementById('delete-message');
        if (deleteMessage) {
            deleteMessage.textContent = `Apakah Anda yakin ingin menghapus pengguna "${username}"? Tindakan ini tidak dapat dibatalkan.`;
        }
        
        // Open delete modal
        showModal('delete-confirm-modal');
    }
    
    // Fungsi untuk menghapus pengguna
    function deletePengguna(id) {
        const deleteBtn = document.getElementById('confirm-delete');
        if (!deleteBtn) return;
        
        const originalText = deleteBtn.innerHTML;
        
        // Disable button and show loading
        deleteBtn.disabled = true;
        deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';
        
        fetch("{{ url('admin/pengguna') }}/" + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-HTTP-Method-Override': 'DELETE',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification('Data pengguna berhasil dihapus!', 'success');
                
                // Close modal
                hideModal('delete-confirm-modal');
                penggunaToDelete = null;
                
                // Remove row from table
                const row = document.querySelector(`tr[data-id="${id}"]`);
                if (row) {
                    row.remove();
                    // Update pagination
                    collectAllRows();
                    renderTable();
                } else {
                    // Reload page if row not found
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            } else {
                showNotification(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat menghapus data', 'error');
        })
        .finally(() => {
            // Re-enable button
            deleteBtn.disabled = false;
            deleteBtn.innerHTML = originalText;
        });
    }
    
    // ========== FUNGSI TABEL & PAGINATION ==========
    // Fungsi untuk mengumpulkan semua baris data
    function collectAllRows() {
        allRows = Array.from(document.querySelectorAll('.pengguna-row'));
        filteredRows = [...allRows];
    }
    
    // Fungsi untuk melakukan live search
    function performSearch(searchTerm) {
        searchTerm = searchTerm.toLowerCase().trim();
        
        if (searchTerm === '') {
            filteredRows = [...allRows];
        } else {
            filteredRows = allRows.filter(row => {
                const nama = row.getAttribute('data-nama') || '';
                const username = row.getAttribute('data-username') || '';
                const role = row.getAttribute('data-role') || '';
                const nisn = row.getAttribute('data-nisn') || '';
                
                return nama.includes(searchTerm) || 
                       username.includes(searchTerm) || 
                       role.includes(searchTerm) || 
                       nisn.includes(searchTerm);
            });
        }
        
        currentPage = 1; // Reset ke halaman pertama saat search
        renderTable();
    }
    
    // Fungsi untuk merender tabel berdasarkan data yang difilter
    function renderTable() {
        const tbody = document.getElementById('pengguna-table-body');
        const noResultsDiv = document.getElementById('no-results');
        
        // Kosongkan tbody
        tbody.innerHTML = '';
        
        if (filteredRows.length === 0) {
            // Tampilkan pesan tidak ada hasil
            noResultsDiv.classList.remove('hidden');
            updatePaginationInfo(0);
            renderPagination(0);
            return;
        }
        
        // Sembunyikan pesan tidak ada hasil
        noResultsDiv.classList.add('hidden');
        
        // Hitung indeks awal dan akhir untuk halaman saat ini
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, filteredRows.length);
        
        // Ambil baris untuk halaman saat ini
        const rowsToShow = filteredRows.slice(startIndex, endIndex);
        
        // Tambahkan baris ke tabel
        rowsToShow.forEach((row, index) => {
            const newRow = row.cloneNode(true);
            
            // Update nomor urut
            const noCell = newRow.querySelector('td:first-child');
            if (noCell) {
                noCell.textContent = startIndex + index + 1;
            }
            
            tbody.appendChild(newRow);
        });
        
        // Update info pagination
        updatePaginationInfo(filteredRows.length);
        
        // Render pagination controls
        renderPagination(filteredRows.length);
        
        // Re-attach event listeners
        attachRowEventListeners();
    }
    
    // Fungsi untuk update info pagination
    function updatePaginationInfo(totalItems) {
        const startIndex = (currentPage - 1) * itemsPerPage + 1;
        const endIndex = Math.min(currentPage * itemsPerPage, totalItems);
        const infoElement = document.getElementById('pagination-info');
        
        if (totalItems === 0) {
            infoElement.textContent = `Tidak ada data`;
        } else {
            infoElement.textContent = `Menampilkan ${startIndex}-${endIndex} dari ${totalItems} pengguna`;
        }
    }
    
    // Fungsi untuk render pagination controls
    function renderPagination(totalItems) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const paginationContainer = document.getElementById('pagination-controls');
        
        if (totalPages <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }
        
        let paginationHTML = '';
        
        // Previous button
        paginationHTML += `
            <button class="pagination-btn" id="prev-page" ${currentPage === 1 ? 'disabled' : ''}>
                <i class="fas fa-chevron-left"></i>
            </button>
        `;
        
        // Page numbers
        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
        
        // Adjust if we're at the end
        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            paginationHTML += `
                <button class="pagination-btn ${i === currentPage ? 'active' : ''}" data-page="${i}">
                    ${i}
                </button>
            `;
        }
        
        // Next button
        paginationHTML += `
            <button class="pagination-btn" id="next-page" ${currentPage === totalPages ? 'disabled' : ''}>
                <i class="fas fa-chevron-right"></i>
            </button>
        `;
        
        paginationContainer.innerHTML = paginationHTML;
        
        // Add event listeners
        document.getElementById('prev-page')?.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        });
        
        document.getElementById('next-page')?.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
            }
        });
        
        document.querySelectorAll('.pagination-btn[data-page]').forEach(btn => {
            btn.addEventListener('click', () => {
                currentPage = parseInt(btn.getAttribute('data-page'));
                renderTable();
            });
        });
    }
    
    // Fungsi untuk re-attach event listeners
    function attachRowEventListeners() {
        // Edit button handlers
        document.querySelectorAll('.edit-pengguna-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const username = this.getAttribute('data-username');
                const role = this.getAttribute('data-role');
                const nisn = this.getAttribute('data-nisn');
                const nama = this.getAttribute('data-nama');
                
                // Set values in edit form
                document.getElementById('edit-id').value = id;
                document.getElementById('edit-username').value = username;
                document.getElementById('edit-role').value = role;
                document.getElementById('edit-nama').value = nama;
                
                // Handle NISN field berdasarkan role
                const editNisnContainer = document.getElementById('edit-nisn-container');
                const editNisnSelect = document.getElementById('edit-nisn');
                const editNamaInput = document.getElementById('edit-nama');
                
                if (role === 'siswa' && nisn) {
                    editNisnContainer.classList.remove('hidden');
                    editNisnContainer.classList.add('block');
                    editNisnSelect.value = nisn;
                    editNamaInput.readOnly = true;
                    
                    // Enable option untuk NISN yang sedang diedit
                    const optionToSelect = editNisnSelect.querySelector(`option[value="${nisn}"]`);
                    if (optionToSelect && optionToSelect.disabled) {
                        optionToSelect.disabled = false;
                    }
                } else {
                    editNisnContainer.classList.add('hidden');
                    editNisnContainer.classList.remove('block');
                    editNisnSelect.value = '';
                    editNamaInput.readOnly = false;
                }
                
                // Clear password field
                document.getElementById('edit-password').value = '';
                
                // Reset errors
                resetFormErrors('form-edit-pengguna');
                
                // Open edit modal
                showModal('edit-pengguna-modal');
                document.getElementById('edit-username').focus();
            });
        });
    }
    
    // ========== EVENT LISTENERS UTAMA ==========
    // Event listener ketika DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Pengguna page loaded');
        
        // Setup form
        setupRoleBasedFormTambah();
        setupRoleBasedFormEdit();
        
        // Kumpulkan semua baris data
        collectAllRows();
        
        // Render tabel awal
        renderTable();
        
        // Setup live search
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                performSearch(this.value);
            });
            
            // Juga bisa dengan tombol enter
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch(this.value);
                }
            });
        }
        
        // Modal elements
        const tambahBtn = document.getElementById('tambah-pengguna-btn');
        const closeTambahModal = document.getElementById('close-tambah-modal');
        const cancelTambah = document.getElementById('cancel-tambah');
        const closeEditModal = document.getElementById('close-edit-modal');
        const cancelEdit = document.getElementById('cancel-edit');
        const cancelDelete = document.getElementById('cancel-delete');
        const confirmDelete = document.getElementById('confirm-delete');
        
        // Open tambah modal
        if (tambahBtn) {
            tambahBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Reset form dan error
                document.getElementById('form-tambah-pengguna').reset();
                resetFormErrors('form-tambah-pengguna');
                
                // Reset state
                document.getElementById('tambah-nisn-container').classList.add('hidden');
                document.getElementById('tambah-nama').readOnly = false;
                
                // Open modal
                showModal('tambah-pengguna-modal');
                document.getElementById('tambah-username').focus();
            });
        }
        
        // Close tambah modal
        if (closeTambahModal) {
            closeTambahModal.addEventListener('click', function() {
                hideModal('tambah-pengguna-modal');
                document.getElementById('form-tambah-pengguna').reset();
                resetFormErrors('form-tambah-pengguna');
                document.getElementById('tambah-nisn-container').classList.add('hidden');
                document.getElementById('tambah-nama').readOnly = false;
            });
        }
        
        if (cancelTambah) {
            cancelTambah.addEventListener('click', function() {
                hideModal('tambah-pengguna-modal');
                document.getElementById('form-tambah-pengguna').reset();
                resetFormErrors('form-tambah-pengguna');
                document.getElementById('tambah-nisn-container').classList.add('hidden');
                document.getElementById('tambah-nama').readOnly = false;
            });
        }
        
        // Close edit modal
        if (closeEditModal) {
            closeEditModal.addEventListener('click', function() {
                hideModal('edit-pengguna-modal');
                document.getElementById('form-edit-pengguna').reset();
                resetFormErrors('form-edit-pengguna');
                document.getElementById('edit-nisn-container').classList.add('hidden');
                document.getElementById('edit-nama').readOnly = false;
            });
        }
        
        if (cancelEdit) {
            cancelEdit.addEventListener('click', function() {
                hideModal('edit-pengguna-modal');
                document.getElementById('form-edit-pengguna').reset();
                resetFormErrors('form-edit-pengguna');
                document.getElementById('edit-nisn-container').classList.add('hidden');
                document.getElementById('edit-nama').readOnly = false;
            });
        }
        
        // Event listener untuk delete menggunakan event delegation
        document.addEventListener('click', function(e) {
            // Handle delete button clicks
            if (e.target.closest('.delete-pengguna-btn')) {
                e.preventDefault();
                const button = e.target.closest('.delete-pengguna-btn');
                const id = button.getAttribute('data-id');
                const username = button.getAttribute('data-username');
                showDeleteModal(id, username);
            }
        });
        
        // Event listener untuk konfirmasi hapus
        if (confirmDelete) {
            confirmDelete.addEventListener('click', function() {
                if (penggunaToDelete) {
                    deletePengguna(penggunaToDelete.id);
                }
            });
        }
        
        // Event listener untuk batal hapus
        if (cancelDelete) {
            cancelDelete.addEventListener('click', function() {
                hideModal('delete-confirm-modal');
                penggunaToDelete = null;
            });
        }
        
        // Form submission for tambah (AJAX)
        const formTambah = document.getElementById('form-tambah-pengguna');
        if (formTambah) {
            formTambah.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = document.getElementById('submit-tambah');
                const originalText = submitBtn.innerHTML;
                
                // Reset errors
                resetFormErrors('form-tambah-pengguna');
                
                // Validasi client-side
                const role = document.getElementById('tambah-role').value;
                const nisn = document.getElementById('tambah-nisn').value;
                const username = document.getElementById('tambah-username').value.trim();
                const password = document.getElementById('tambah-password').value;
                const nama = document.getElementById('tambah-nama').value.trim();
                
                let hasError = false;
                
                if (!username) {
                    showFieldError('tambah-username', 'Username wajib diisi');
                    hasError = true;
                }
                
                if (!password) {
                    showFieldError('tambah-password', 'Password wajib diisi');
                    hasError = true;
                } else if (password.length < 5) {
                    showFieldError('tambah-password', 'Password minimal 5 karakter');
                    hasError = true;
                }
                
                if (!role) {
                    showFieldError('tambah-role', 'Role wajib dipilih');
                    hasError = true;
                }
                
                if (role === 'siswa') {
                    // Validasi khusus untuk siswa
                    if (!nisn) {
                        showFieldError('tambah-nisn', 'NISN wajib dipilih untuk role siswa');
                        hasError = true;
                    } else {
                        // Cek apakah NISN sudah digunakan (client-side)
                        const nisnSelect = document.getElementById('tambah-nisn');
                        const selectedOption = nisnSelect.options[nisnSelect.selectedIndex];
                        if (selectedOption.disabled) {
                            showFieldError('tambah-nisn', 'NISN sudah memiliki akun');
                            hasError = true;
                        }
                    }
                }
                
                if (!nama) {
                    showFieldError('tambah-nama', 'Nama wajib diisi');
                    hasError = true;
                }
                
                if (hasError) {
                    showNotification('Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.', 'error');
                    return;
                }
                
                // Disable button and show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                
                fetch("{{ route('admin.pengguna.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Data pengguna berhasil ditambahkan!', 'success');
                        
                        // Reset form and close modal
                        formTambah.reset();
                        hideModal('tambah-pengguna-modal');
                        document.getElementById('tambah-nisn-container').classList.add('hidden');
                        document.getElementById('tambah-nama').readOnly = false;
                        
                        // Reload page to show updated data
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        // Handle validation errors from server
                        if (data.errors) {
                            for (const [field, messages] of Object.entries(data.errors)) {
                                showFieldError(`tambah-${field}`, messages[0]);
                            }
                            showNotification('Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.', 'error');
                        } else {
                            showNotification(data.message || 'Terjadi kesalahan', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat menyimpan data', 'error');
                })
                .finally(() => {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        }
        
        // Form submission for edit (AJAX)
        const formEdit = document.getElementById('form-edit-pengguna');
        if (formEdit) {
            formEdit.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const id = document.getElementById('edit-id').value;
                const formData = new FormData(this);
                const submitBtn = document.getElementById('submit-edit');
                const originalText = submitBtn.innerHTML;
                
                // Reset errors
                resetFormErrors('form-edit-pengguna');
                
                // Validasi client-side
                const username = document.getElementById('edit-username').value.trim();
                const nama = document.getElementById('edit-nama').value.trim();
                const role = document.getElementById('edit-role').value;
                const nisn = document.getElementById('edit-nisn').value;
                
                let hasError = false;
                
                if (!username) {
                    showFieldError('edit-username', 'Username wajib diisi');
                    hasError = true;
                }
                
                if (!nama) {
                    showFieldError('edit-nama', 'Nama wajib diisi');
                    hasError = true;
                }
                
                if (role === 'siswa') {
                    // Validasi khusus untuk siswa
                    if (!nisn) {
                        showFieldError('edit-nisn', 'NISN wajib dipilih untuk role siswa');
                        hasError = true;
                    } else {
                        // Cek apakah NISN sudah digunakan (client-side)
                        const nisnSelect = document.getElementById('edit-nisn');
                        const selectedOption = nisnSelect.options[nisnSelect.selectedIndex];
                        if (selectedOption.disabled) {
                            // Kecuali jika ini adalah NISN yang sedang diedit
                            const currentNisn = document.querySelector(`.edit-pengguna-btn[data-id="${id}"]`).getAttribute('data-nisn');
                            if (nisn !== currentNisn) {
                                showFieldError('edit-nisn', 'NISN sudah memiliki akun');
                                hasError = true;
                            }
                        }
                    }
                }
                
                if (hasError) {
                    showNotification('Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.', 'error');
                    return;
                }
                
                // Disable button and show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memperbarui...';
                
                fetch("{{ url('admin/pengguna') }}/" + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-HTTP-Method-Override': 'PUT',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Data pengguna berhasil diperbarui!', 'success');
                        
                        // Close modal
                        hideModal('edit-pengguna-modal');
                        
                        // Reload page to show updated data
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        // Handle validation errors from server
                        if (data.errors) {
                            for (const [field, messages] of Object.entries(data.errors)) {
                                showFieldError(`edit-${field}`, messages[0]);
                            }
                            showNotification('Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.', 'error');
                        } else {
                            showNotification(data.message || 'Terjadi kesalahan', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan saat memperbarui data', 'error');
                })
                .finally(() => {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        }
        
        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                hideModal(event.target.id);
                if (event.target.id === 'tambah-pengguna-modal') {
                    document.getElementById('form-tambah-pengguna').reset();
                    resetFormErrors('form-tambah-pengguna');
                    document.getElementById('tambah-nisn-container').classList.add('hidden');
                    document.getElementById('tambah-nama').readOnly = false;
                }
                if (event.target.id === 'edit-pengguna-modal') {
                    document.getElementById('form-edit-pengguna').reset();
                    resetFormErrors('form-edit-pengguna');
                    document.getElementById('edit-nisn-container').classList.add('hidden');
                    document.getElementById('edit-nama').readOnly = false;
                }
                if (event.target.id === 'delete-confirm-modal') {
                    penggunaToDelete = null;
                }
            }
        });
        
        // Remove error highlighting when user starts typing
        document.querySelectorAll('#form-tambah-pengguna input, #form-tambah-pengguna select').forEach(el => {
            el.addEventListener('input', function() {
                this.classList.remove('border-red-500');
                const errorId = this.id + '-error';
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.classList.add('hidden');
                }
            });
        });
        
        document.querySelectorAll('#form-edit-pengguna input, #form-edit-pengguna select').forEach(el => {
            el.addEventListener('input', function() {
                this.classList.remove('border-red-500');
                const errorId = this.id + '-error';
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.classList.add('hidden');
                }
            });
        });
        
        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideModal('tambah-pengguna-modal');
                hideModal('edit-pengguna-modal');
                hideModal('delete-confirm-modal');
                penggunaToDelete = null;
            }
        });
        
        // Show session messages if any
        @if(session('success'))
            showNotification('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showNotification('{{ session('error') }}', 'error');
        @endif
    });
</script>
@endpush