@extends('layouts.admin')

@section('title', 'SUARAKITA')
@section('page-title', 'Data Siswa')
@section('page-description', 'Kelola data siswa dengan mudah')

@push('styles')
<style>
    /* Siswa page specific styles */
    .btn-glow:hover {
        box-shadow: 0 5px 20px rgba(34, 197, 94, 0.4);
    }
    
    .hide-on-mobile {
        display: none;
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
    
    .validation-error {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        display: none;
    }
    
    .validation-error.show {
        display: block;
    }
    
    .custom-checkbox {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db;
        border-radius: 4px;
        background-color: white;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
    }
    
    .custom-checkbox:checked {
        background-color: #22c55e;
        border-color: #22c55e;
    }
    
    .custom-checkbox:checked::after {
        content: '✓';
        position: absolute;
        color: white;
        font-size: 14px;
        font-weight: bold;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999; /* Tingkatkan z-index */
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.show {
        display: flex;
        opacity: 1;
    }

    .modal-content {
        background-color: white;
        border-radius: 0.75rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: translateY(-20px);
        opacity: 0;
        transition: all 0.3s ease;
        max-height: 90vh;
        overflow-y: auto;
        max-width: 500px;
        width: 90%;
    }

    .modal-overlay.show .modal-content {
        transform: translateY(0);
        opacity: 1;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideIn {
        from { 
            opacity: 0;
            transform: translateY(-20px); 
        }
        to { 
            opacity: 1;
            transform: translateY(0); 
        }
    }
    
    @media (min-width: 768px) {
        .hide-on-mobile {
            display: table-cell;
        }
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
                <input type="text" id="search-input" placeholder="Cari siswa..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       value="{{ request('search', '') }}">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>

            <!-- Tambah Siswa Button -->
            <button id="tambah-siswa-btn" class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition duration-300 flex items-center justify-center btn-glow shadow-lg hover:shadow-xl" title="Tambah Siswa Baru">
                <i class="fas fa-user-plus text-lg"></i>
                <span class="hidden md:inline ml-2">Tambah Siswa</span>
            </button>
            
            <!-- Template Download Button -->
            <button id="download-template-btn" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition duration-300 flex items-center justify-center btn-glow shadow-lg hover:shadow-xl" title="Download Template">
                <i class="fas fa-file-download text-lg"></i>
                <span class="hidden md:inline ml-2">Template</span>
            </button>
            
            <!-- Import Excel Button -->
            <button id="import-excel-btn" class="px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition duration-300 flex items-center justify-center btn-glow shadow-lg hover:shadow-xl" title="Import Excel">
                <i class="fas fa-file-import text-lg"></i>
                <span class="hidden md:inline ml-2">Import Excel</span>
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
                        <th class="py-3 px-4 text-left">NISN</th>
                        <th class="py-3 px-4 text-left">Nama</th>
                        <th class="py-3 px-4 text-left">Kelas</th>
                        <th class="py-3 px-4 text-left">Jurusan</th>
                        <th class="py-3 px-4 text-left">Username</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody id="siswa-table-body">
                    @if($siswa->count() > 0)
                    @foreach($siswa as $index => $item)
                        @php
                            $user = \App\Models\User::where('nisn', $item->nisn)->first();
                        @endphp
                        <tr class="table-row border-t border-gray-100 hover:bg-gray-50">
                            <td class="py-4 px-4">{{ ($siswa->currentPage() - 1) * $siswa->perPage() + $index + 1 }}</td>
                            <td class="py-4 px-4 font-medium">{{ $item->nisn }}</td>
                            <td class="py-4 px-4">
                                <div class="font-medium text-gray-800">{{ $item->nama }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-md text-xs font-medium">{{ $item->kelas }}</span>
                            </td>
                            <td class="py-4 px-4 hide-on-mobile">
                                <span class="px-2 py-1 {{ $item->jurusan == 'RPL' ? 'bg-purple-100 text-purple-800' : ($item->jurusan == 'FKK' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }} rounded-md text-xs font-medium">
                                    {{ $item->jurusan }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                @if($user)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-md text-xs font-medium">{{ $user->username }}</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-medium">Belum ada akun</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-2">
                                    @if(!$user)
                                        <button class="create-account-btn p-2 text-green-600 hover:bg-green-50 rounded-lg transition duration-300" 
                                                data-nisn="{{ $item->nisn }}" 
                                                data-nama="{{ $item->nama }}"
                                                title="Buat Akun">
                                            <i class="fas fa-user-plus"></i>
                                            <span class="sr-only">Buat Akun</span>
                                        </button>
                                    @endif
                                    <button class="edit-btn p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition duration-300" 
                                            data-nisn="{{ $item->nisn }}" 
                                            data-nama="{{ $item->nama }}" 
                                            data-kelas="{{ $item->kelas }}" 
                                            data-jurusan="{{ $item->jurusan }}"
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                        <span class="sr-only">Edit</span>
                                    </button>
                                    <button class="delete-btn p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-300" 
                                            data-nisn="{{ $item->nisn }}" 
                                            data-nama="{{ $item->nama }}"
                                            title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                        <span class="sr-only">Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500">
                            <i class="fas fa-user-slash text-3xl mb-2"></i>
                            <p class="text-lg">Tidak ada data siswa ditemukan</p>
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
            @if($siswa->hasPages())
            <div class="flex flex-col md:flex-row justify-between items-center p-4">
                <div class="text-sm text-gray-600 mb-4 md:mb-0">
                    Menampilkan <span class="font-semibold">{{ $siswa->firstItem() ?? 0 }}-{{ $siswa->lastItem() ?? 0 }}</span> 
                    dari <span class="font-semibold">{{ $siswa->total() }}</span> siswa
                </div>
                
                <div class="flex items-center space-x-2">
                    @if($siswa->onFirstPage())
                        <span class="px-3 py-1 rounded border border-gray-300 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <button onclick="loadPage({{ $siswa->currentPage() - 1 }})" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50 transition duration-300 pagination-btn" data-page="{{ $siswa->currentPage() - 1 }}">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @endif
                    
                    @foreach ($siswa->getUrlRange(1, $siswa->lastPage()) as $page => $url)
                        @if($page == $siswa->currentPage())
                            <span class="px-3 py-1 rounded bg-gradient-to-r from-green-500 to-green-600 text-white font-medium">
                                {{ $page }}
                            </span>
                        @else
                            <button onclick="loadPage({{ $page }})" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50 transition duration-300 pagination-btn" data-page="{{ $page }}">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                    
                    @if($siswa->hasMorePages())
                        <button onclick="loadPage({{ $siswa->currentPage() + 1 }})" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50 transition duration-300 pagination-btn" data-page="{{ $siswa->currentPage() + 1 }}">
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
                    Menampilkan <span class="font-semibold">{{ $siswa->count() }}</span> dari <span class="font-semibold">{{ $siswa->total() }}</span> siswa
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('modals')
    <!-- Modal Tambah/Edit Siswa -->
    <div id="siswaModal" class="modal-overlay">
        <div class="modal-content">
            <!-- Header Modal -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-800" id="modal-title">Tambah Siswa Baru</h3>
                    <button type="button" 
                            class="text-gray-500 hover:text-gray-700 transition duration-300"
                            onclick="hideModal('siswaModal')">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Body Modal -->
            <div class="p-6">
                <form id="siswa-form">
                    @csrf
                    <input type="hidden" id="edit-mode" value="false">
                    <input type="hidden" id="current-nisn" name="nisn_old">
                    
                    <div class="space-y-4">
                        <!-- NISN - Text Field -->
                        <div id="nisn-input-container">
                            <label for="nisn" class="block text-sm font-medium text-gray-700 mb-1">NISN</label>
                            <input type="text"
                                id="nisn"
                                name="nisn"
                                required
                                maxlength="10"
                                pattern="[0-9]{10}"
                                inputmode="numeric"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent font-mono"
                                placeholder="NISN harus berisi 10 digit angka">
                            <div id="nisn-error" class="validation-error"></div>
                        </div>
                        
                        <!-- Nama - Text Field -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <div id="nama-error" class="validation-error"></div>
                        </div>
                        
                        <!-- Kelas - Dropdown -->
                        <div>
                            <label for="kelas" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                            <select id="kelas" name="kelas" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="" disabled selected>Pilih Kelas</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                            <div id="kelas-error" class="validation-error"></div>
                        </div>
                            
                        <!-- Jurusan - Dropdown -->
                        <div>
                            <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                            <select id="jurusan" name="jurusan" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="" disabled selected>Pilih Jurusan</option>
                                <option value="RPL">RPL</option>
                                <option value="FKK">FKK</option>
                                <option value="BDP">BDP</option>
                            </select>
                            <div id="jurusan-error" class="validation-error"></div>
                        </div>
                        
                        <!-- Checkbox untuk membuat akun pengguna -->
                        <div id="create-account-section" class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex items-start">
                                <input type="checkbox" id="create_user_account" name="create_user_account" value="1"
                                    class="custom-checkbox h-5 w-5 text-green-600 rounded focus:ring-green-500 border-gray-300 mt-1">
                                <div class="ml-3">
                                    <label for="create_user_account" class="text-sm font-medium text-gray-700">
                                        Buat akun pengguna juga untuk siswa ini
                                    </label>
                                    <!-- PESAN AKUN HANYA MUNCUL JIKA CHECKBOX DICENTANG -->
                                    <div id="account-info-message" class="hidden mt-2 p-3 rounded-md bg-blue-50 border border-blue-100">
                                        <p class="text-xs text-gray-600 mb-2">Akun pengguna akan dibuat dengan:</p>
                                        <ul class="text-xs text-gray-600 space-y-1">
                                            <li class="flex items-center">
                                                <i class="fas fa-user text-xs mr-2 text-blue-500"></i>
                                                <span>Username: <span class="font-medium" id="preview-username">-</span></span>
                                            </li>
                                            <li class="flex items-center">
                                                <i class="fas fa-key text-xs mr-2 text-blue-500"></i>
                                                <span>Password: <span class="font-medium" id="preview-password">-</span></span>
                                            </li>
                                            <li class="flex items-center">
                                                <i class="fas fa-shield-alt text-xs mr-2 text-blue-500"></i>
                                                <span>Role: <span class="font-medium">Siswa</span></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer Modal -->
                    <div class="flex justify-end space-x-3 mt-8 pt-4 border-t border-gray-200">
                        <button type="button" 
                                class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-300"
                                onclick="hideModal('siswaModal')">
                            Batal
                        </button>
                        <button type="submit" id="submit-btn" 
                                class="px-5 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition duration-300">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Import Excel -->
    <div id="importModal" class="modal-overlay">
        <div class="modal-content max-w-md">
            <!-- Header Modal -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-file-import text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Import Data Siswa</h3>
                    </div>
                    <button type="button" 
                            class="text-gray-500 hover:text-gray-700 transition duration-300"
                            onclick="hideModal('importModal')">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Body Modal -->
            <div class="p-6">
                <form id="import-form" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <!-- File Upload -->
                        <div>
                            <label for="excel-file" class="block text-sm font-medium text-gray-700 mb-1">File Excel</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                                <div class="space-y-1 text-center">
                                    <i class="fas fa-file-excel text-4xl text-green-500 mx-auto"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="excel-file" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                            <span>Upload file Excel</span>
                                            <input id="excel-file" name="file" type="file" accept=".xlsx,.xls,.csv" class="sr-only">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        XLSX, XLS, CSV (max 5MB)
                                    </p>
                                </div>
                            </div>
                            <div id="file-name" class="text-sm text-gray-600 mt-2"></div>
                            <div id="file-error" class="validation-error"></div>
                        </div>
                        
                        <!-- Checkbox untuk membuat akun -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-start">
                                <input type="checkbox" id="create_accounts_import" name="create_accounts" value="1"
                                    class="custom-checkbox h-5 w-5 text-green-600 rounded focus:ring-green-500 border-gray-300 mt-1">
                                <div class="ml-3">
                                    <label for="create_accounts_import" class="text-sm font-medium text-gray-700">
                                        Buat akun pengguna untuk semua siswa yang diimport
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Username akan dibuat dari nama depan, password menggunakan NISN
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi template -->
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-100 rounded-md">
                            <p class="text-xs text-gray-600 mb-2 font-medium">Format kolom yang dibutuhkan:</p>
                            <ol class="text-xs text-gray-600 space-y-1 ml-2">
                                <li>1. <span class="font-medium">nisn</span> - 10 digit angka</li>
                                <li>2. <span class="font-medium">nama</span> - Nama lengkap siswa</li>
                                <li>3. <span class="font-medium">kelas</span> - 10, 11, atau 12</li>
                                <li>4. <span class="font-medium">jurusan</span> - RPL, FKK, atau BDP</li>
                            </ol>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Gunakan tombol "Template" untuk download format Excel yang benar
                            </p>
                        </div>
                    </div>
                    
                    <!-- Footer Modal -->
                    <div class="flex justify-end space-x-3 mt-8 pt-4 border-t border-gray-200">
                        <button type="button" 
                                class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-300"
                                onclick="hideModal('importModal')">
                            Batal
                        </button>
                        <button type="submit" id="import-submit-btn" 
                                class="px-5 py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition duration-300">
                            Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-content max-w-md">
            <!-- Header Modal -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h3>
                    </div>
                    <button type="button" 
                            class="text-gray-500 hover:text-gray-700 transition duration-300"
                            onclick="hideModal('deleteModal')">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Body Modal -->
            <div class="p-6">
                <p class="text-gray-600 mb-6" id="delete-message">
                    Apakah Anda yakin ingin menghapus data siswa ini? Tindakan ini tidak dapat dibatalkan.
                </p>
                
                <!-- Footer Modal -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" 
                            class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-300"
                            onclick="hideModal('deleteModal')">
                        Batal
                    </button>
                    <button type="button" id="confirm-delete" 
                            class="px-5 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition duration-300">
                        Hapus Data
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Variabel global
    let siswaToDelete = null;
    let siswaToCreateAccount = null;
    let currentAlertTimeout = null;
    let searchTimeout = null;
    let currentPage = {{ $siswa->currentPage() }};
    let currentSearch = "{{ request('search', '') }}";
    
    // ========== FUNGSI UTILITAS ==========
    // Fungsi untuk membuat username dari nama depan
    function generateUsernameFromName(nama) {
        const namaDepan = nama.split(' ')[0];
        return namaDepan.toLowerCase().replace(/[^a-z0-9]/g, '');
    }
    
    // Fungsi untuk update preview akun (username & password)
    function updateAccountPreview() {
        const checkbox = document.getElementById('create_user_account');
        const accountInfo = document.getElementById('account-info-message');
        const nama = document.getElementById('nama').value.trim();
        const nisn = document.getElementById('nisn').value.trim();
        
        // Jika checkbox dicentang, tampilkan info akun
        if (checkbox.checked) {
            accountInfo.classList.remove('hidden');
            
            // Update username jika ada nama
            if (nama) {
                const username = generateUsernameFromName(nama);
                document.getElementById('preview-username').textContent = username;
            } else {
                document.getElementById('preview-username').textContent = '-';
            }
            
            // Update password jika ada NISN
            if (nisn) {
                document.getElementById('preview-password').textContent = nisn;
            } else {
                document.getElementById('preview-password').textContent = '-';
            }
        } else {
            // Jika checkbox tidak dicentang, sembunyikan info akun
            accountInfo.classList.add('hidden');
        }
    }
    
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
        alert.className = `alert fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ${type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'}`;
        
        alert.innerHTML = `
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-3 text-lg"></i>
                    <span class="font-medium">${message}</span>
                </div>
                <button class="close-alert text-gray-500 hover:text-gray-700 ml-4">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(alert);
        
        // Animate in
        setTimeout(() => {
            alert.style.transform = 'translateX(0)';
        }, 10);
        
        const closeBtn = alert.querySelector('.close-alert');
        closeBtn.addEventListener('click', function() {
            alert.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (document.body.contains(alert)) {
                    alert.remove();
                }
            }, 300);
            if (currentAlertTimeout) {
                clearTimeout(currentAlertTimeout);
            }
        });
        
        currentAlertTimeout = setTimeout(() => {
            if (document.body.contains(alert)) {
                alert.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (document.body.contains(alert)) {
                        alert.remove();
                    }
                }, 300);
            }
        }, 5000);
    }
    
    // Fungsi untuk menampilkan error pada form field
    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errorElement = document.getElementById(`${fieldId}-error`);
        
        if (field) {
            field.classList.add('border-red-500');
        }
        
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }
    }
    
    // Fungsi untuk menghapus semua error
    function clearAllErrors() {
        document.querySelectorAll('.validation-error').forEach(element => {
            element.classList.remove('show');
            element.textContent = '';
        });
        
        document.querySelectorAll('#siswa-form input, #siswa-form select').forEach(input => {
            input.classList.remove('border-red-500');
        });
    }
    
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
        
        // Reset delete data
        if (modalId === 'deleteModal') {
            siswaToDelete = null;
        }
        
        if (modalId === 'importModal') {
            const importForm = document.getElementById('import-form');
            if (importForm) {
                importForm.reset();
                document.getElementById('file-name').textContent = '';
            }
        }
        
        // Clear errors ketika modal ditutup
        clearAllErrors();
    }
    
    // Fungsi untuk menampilkan modal konfirmasi buat akun
    function showCreateAccountModal(nisn, nama) {
        // Generate username
        const username = generateUsernameFromName(nama);
        
        // Buat modal HTML
        const modalHTML = `
            <div id="createAccountModal" class="modal-overlay show">
                <div class="modal-content max-w-md">
                    <!-- Header Modal -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-plus text-green-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">Buat Akun Siswa</h3>
                                    <p class="text-sm text-gray-500">Konfirmasi pembuatan akun pengguna</p>
                                </div>
                            </div>
                            <button type="button" 
                                    class="text-gray-500 hover:text-gray-700 transition duration-300 close-create-account-modal">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Body Modal -->
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Info Siswa -->
                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                                <h4 class="text-sm font-medium text-blue-800 mb-2 flex items-center">
                                    <i class="fas fa-user-circle mr-2"></i>
                                    Data Siswa
                                </h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Nama:</span>
                                        <span class="text-sm font-medium text-gray-800">${nama}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">NISN:</span>
                                        <span class="text-sm font-medium text-gray-800">${nisn}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Detail Akun yang Akan Dibuat -->
                            <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                                <h4 class="text-sm font-medium text-green-800 mb-2 flex items-center">
                                    <i class="fas fa-key mr-2"></i>
                                    Detail Akun yang Akan Dibuat
                                </h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Username:</span>
                                        <span class="text-sm font-mono font-bold text-green-600">${username}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Password:</span>
                                        <span class="text-sm font-mono font-bold text-blue-600">${nisn}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Role:</span>
                                        <span class="text-sm font-medium text-purple-600">Siswa</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pesan Informasi -->
                            <div class="p-3 bg-yellow-50 rounded-md border border-yellow-100">
                                <p class="text-xs text-yellow-800 flex items-start">
                                    <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                                    Siswa dapat login menggunakan username dan password di atas. 
                                    Disarankan untuk mengubah password saat login pertama kali.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Footer Modal -->
                        <div class="flex justify-end space-x-3 mt-8 pt-4 border-t border-gray-200">
                            <button type="button" 
                                    class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-300 close-create-account-modal">
                                Batal
                            </button>
                            <button type="button" id="confirm-create-account" 
                                    class="px-5 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition duration-300 flex items-center">
                                <i class="fas fa-user-plus mr-2"></i>
                                Buat Akun
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Tambahkan modal ke body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Event listeners untuk modal
        const modal = document.getElementById('createAccountModal');
        const confirmBtn = document.getElementById('confirm-create-account');
        const closeBtns = document.querySelectorAll('.close-create-account-modal');
        
        // Fungsi untuk menyembunyikan modal
        function hideCreateAccountModal() {
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.remove();
                }, 300);
                document.body.style.overflow = '';
            }
        }
        
        // Event listener untuk tombol konfirmasi
        if (confirmBtn) {
            confirmBtn.addEventListener('click', async function() {
                // Nonaktifkan tombol dan tampilkan loading
                confirmBtn.disabled = true;
                confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
                
                try {
                    const response = await fetch('{{ route("admin.siswa.create-account") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ nisn: nisn, nama: nama })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Tutup modal
                        hideCreateAccountModal();
                        
                        // Tampilkan alert sukses
                        showAlert(`Akun berhasil dibuat! Username: ${data.username}`, 'success');
                        
                        // Refresh table data
                        await performSearch(currentSearch, currentPage);
                    } else {
                        // Aktifkan kembali tombol
                        confirmBtn.disabled = false;
                        confirmBtn.innerHTML = '<i class="fas fa-user-plus mr-2"></i> Buat Akun';
                        
                        // Tampilkan error
                        showAlert(data.message || 'Gagal membuat akun', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    
                    // Aktifkan kembali tombol
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = '<i class="fas fa-user-plus mr-2"></i> Buat Akun';
                    
                    // Tampilkan error
                    showAlert('Terjadi kesalahan saat membuat akun', 'error');
                }
            });
        }
        
        // Event listener untuk tombol close/batal
        closeBtns.forEach(btn => {
            btn.addEventListener('click', hideCreateAccountModal);
        });
        
        // Close modal ketika klik di luar
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideCreateAccountModal();
            }
        });
        
        // Close modal dengan ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal && modal.classList.contains('show')) {
                hideCreateAccountModal();
            }
        });
        
        // Set overflow hidden untuk body
        document.body.style.overflow = 'hidden';
    }
    // ========== FUNGSI BUAT AKUN ==========
    async function createUserAccount(nisn, nama) {
        // Tampilkan modal konfirmasi custom
        showCreateAccountModal(nisn, nama);
    }

    // Fungsi untuk menampilkan modal konfirmasi buat akun
    function showCreateAccountModal(nisn, nama) {
        // Hapus modal yang mungkin masih ada
        const existingModal = document.getElementById('createAccountModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Generate username
        const username = generateUsernameFromName(nama);
        
        // Ambil CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Buat modal HTML
        const modalHTML = `
            <div id="createAccountModal" class="modal-overlay">
                <div class="modal-content max-w-md">
                    <!-- Header Modal -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-plus text-green-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">Buat Akun Siswa</h3>
                                    <p class="text-sm text-gray-500">Konfirmasi pembuatan akun pengguna</p>
                                </div>
                            </div>
                            <button type="button" 
                                    class="text-gray-500 hover:text-gray-700 transition duration-300 close-create-account-modal">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Body Modal -->
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Info Siswa -->
                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                                <h4 class="text-sm font-medium text-blue-800 mb-2 flex items-center">
                                    <i class="fas fa-user-circle mr-2"></i>
                                    Data Siswa
                                </h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Nama:</span>
                                        <span class="text-sm font-medium text-gray-800">${nama}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">NISN:</span>
                                        <span class="text-sm font-medium text-gray-800">${nisn}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Detail Akun yang Akan Dibuat -->
                            <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                                <h4 class="text-sm font-medium text-green-800 mb-2 flex items-center">
                                    <i class="fas fa-key mr-2"></i>
                                    Detail Akun yang Akan Dibuat
                                </h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Username:</span>
                                        <span class="text-sm font-mono font-bold text-green-600">${username}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Password:</span>
                                        <span class="text-sm font-mono font-bold text-blue-600">${nisn}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Role:</span>
                                        <span class="text-sm font-medium text-purple-600">Siswa</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pesan Informasi -->
                            <div class="p-3 bg-yellow-50 rounded-md border border-yellow-100">
                                <p class="text-xs text-yellow-800 flex items-start">
                                    <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                                    Siswa dapat login menggunakan username dan password di atas. 
                                    Disarankan untuk mengubah password saat login pertama kali.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Footer Modal -->
                        <div class="flex justify-end space-x-3 mt-8 pt-4 border-t border-gray-200">
                            <button type="button" 
                                    class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-300 close-create-account-modal">
                                Batal
                            </button>
                            <button type="button" id="confirm-create-account" 
                                    class="px-5 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition duration-300 flex items-center"
                                    data-nisn="${nisn}"
                                    data-nama="${nama}">
                                <i class="fas fa-user-plus mr-2"></i>
                                Buat Akun
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Tambahkan modal ke body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Tampilkan modal dengan animasi
        setTimeout(() => {
            const modal = document.getElementById('createAccountModal');
            if (modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        }, 10);
        
        // Setup event listeners setelah modal ditambahkan
        setupCreateAccountModalEvents(nisn, nama, csrfToken);
    }

    // Fungsi untuk setup event listeners modal
    function setupCreateAccountModalEvents(nisn, nama, csrfToken) {
        const modal = document.getElementById('createAccountModal');
        const confirmBtn = document.getElementById('confirm-create-account');
        const closeBtns = document.querySelectorAll('.close-create-account-modal');
        
        if (!modal || !confirmBtn) return;
        
        // Fungsi untuk menyembunyikan modal
        function hideCreateAccountModal() {
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    if (modal.parentNode) {
                        modal.remove();
                    }
                }, 300);
                document.body.style.overflow = '';
            }
        }
        
        // Event listener untuk tombol konfirmasi
        confirmBtn.addEventListener('click', async function() {
            // Nonaktifkan tombol dan tampilkan loading
            const originalText = confirmBtn.innerHTML;
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
            
            try {
                console.log('Membuat akun untuk:', { nisn, nama });
                
                const response = await fetch('{{ route("admin.siswa.create-account") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ 
                        nisn: nisn, 
                        nama: nama 
                    })
                });
                
                const data = await response.json();
                console.log('Response:', data);
                
                if (data.success) {
                    // Tutup modal
                    hideCreateAccountModal();
                    
                    // Tampilkan alert sukses
                    showAlert(`Akun berhasil dibuat! Username: ${data.username}`, 'success');
                    
                    // Refresh table data
                    await performSearch(currentSearch, currentPage);
                } else {
                    // Aktifkan kembali tombol
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = originalText;
                    
                    // Tampilkan error
                    showAlert(data.message || 'Gagal membuat akun', 'error');
                    console.error('Error membuat akun:', data);
                }
            } catch (error) {
                console.error('Error:', error);
                
                // Aktifkan kembali tombol
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = originalText;
                
                // Tampilkan error
                showAlert('Terjadi kesalahan saat membuat akun', 'error');
            }
        });
        
        // Event listener untuk tombol close/batal
        closeBtns.forEach(btn => {
            btn.addEventListener('click', hideCreateAccountModal);
        });
        
        // Close modal ketika klik di luar
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideCreateAccountModal();
            }
        });
        
        // Close modal dengan ESC key
        document.addEventListener('keydown', function closeOnEsc(e) {
            if (e.key === 'Escape' && modal && modal.classList.contains('show')) {
                hideCreateAccountModal();
                document.removeEventListener('keydown', closeOnEsc);
            }
        });
    }
    
    // Function untuk render table dari data AJAX
    function renderTable(data) {
        const tableBody = document.getElementById('siswa-table-body');
        const paginationContainer = document.getElementById('pagination-container');
        
        // Render table rows
        if (data.siswa.data.length > 0) {
            let tableHTML = '';
            data.siswa.data.forEach((item, index) => {
                // Warna badge untuk jurusan
                let jurusanClass = '';
                let jurusanText = item.jurusan;
                
                if (item.jurusan == 'RPL') {
                    jurusanClass = 'bg-purple-100 text-purple-800';
                    jurusanText = 'RPL';
                } else if (item.jurusan == 'FKK') {
                    jurusanClass = 'bg-green-100 text-green-800';
                    jurusanText = 'FKK';
                } else if (item.jurusan == 'BDP') {
                    jurusanClass = 'bg-yellow-100 text-yellow-800';
                    jurusanText = 'BDP';
                } else {
                    jurusanClass = 'bg-gray-100 text-gray-800';
                }
                
                tableHTML += `
                <tr class="table-row border-t border-gray-100 hover:bg-gray-50">
                    <td class="py-4 px-4">${(data.siswa.current_page - 1) * data.siswa.per_page + index + 1}</td>
                    <td class="py-4 px-4 font-medium">${item.nisn}</td>
                    <td class="py-4 px-4">
                        <div class="font-medium text-gray-800">${item.nama}</div>
                    </td>
                    <td class="py-4 px-4">
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-md text-xs font-medium">${item.kelas}</span>
                    </td>
                    <td class="py-4 px-4 hide-on-mobile">
                        <span class="px-2 py-1 ${jurusanClass} rounded-md text-xs font-medium">
                            ${jurusanText}
                        </span>
                    </td>
                    <td class="py-4 px-4">
                        ${item.user && item.user.username ? 
                            `<span class="px-2 py-1 bg-green-100 text-green-800 rounded-md text-xs font-medium">${item.user.username}</span>` : 
                            `<span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-medium">Belum ada akun</span>`
                        }
                    </td>
                    <td class="py-4 px-4">
                        <div class="flex items-center space-x-2">
                            ${!item.user || !item.user.username ? 
                                `<button class="create-account-btn p-2 text-green-600 hover:bg-green-50 rounded-lg transition duration-300" 
                                        data-nisn="${item.nisn}" 
                                        data-nama="${item.nama}"
                                        title="Buat Akun">
                                    <i class="fas fa-user-plus"></i>
                                    <span class="sr-only">Buat Akun</span>
                                </button>` : ''
                            }
                            <button class="edit-btn p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition duration-300" 
                                    data-nisn="${item.nisn}" 
                                    data-nama="${item.nama}" 
                                    data-kelas="${item.kelas}" 
                                    data-jurusan="${item.jurusan}"
                                    title="Edit">
                                <i class="fas fa-edit"></i>
                                <span class="sr-only">Edit</span>
                            </button>
                            <button class="delete-btn p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-300" 
                                    data-nisn="${item.nisn}" 
                                    data-nama="${item.nama}"
                                    title="Hapus">
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
                <td colspan="7" class="py-8 text-center text-gray-500">
                    <i class="fas fa-user-slash text-3xl mb-2"></i>
                    <p class="text-lg">Tidak ada data siswa ditemukan</p>
                    ${currentSearch ? '<p class="text-sm mt-1">Coba dengan kata kunci lain</p>' : ''}
                </td>
            </tr>
            `;
        }
        
        // Render pagination
        let paginationHTML = '';
        if (data.siswa.last_page > 1) {
            paginationHTML = `
            <div class="flex flex-col md:flex-row justify-between items-center p-4">
                <div class="text-sm text-gray-600 mb-4 md:mb-0">
                    Menampilkan <span class="font-semibold">${data.siswa.from || 0}-${data.siswa.to || 0}</span> 
                    dari <span class="font-semibold">${data.siswa.total}</span> siswa
                </div>
                
                <div class="flex items-center space-x-2">
                    ${data.siswa.current_page === 1 ? 
                        '<span class="px-3 py-1 rounded border border-gray-300 text-gray-400 cursor-not-allowed"><i class="fas fa-chevron-left"></i></span>' : 
                        `<button onclick="loadPage(${data.siswa.current_page - 1})" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50 transition duration-300 pagination-btn" data-page="${data.siswa.current_page - 1}"><i class="fas fa-chevron-left"></i></button>`
                    }
                    
                    ${Array.from({length: data.siswa.last_page}, (_, i) => i + 1).map(page => 
                        page === data.siswa.current_page ?
                        `<span class="px-3 py-1 rounded bg-gradient-to-r from-green-500 to-green-600 text-white font-medium">${page}</span>` :
                        `<button onclick="loadPage(${page})" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50 transition duration-300 pagination-btn" data-page="${page}">${page}</button>`
                    ).join('')}
                    
                    ${!data.siswa.next_page_url ? 
                        '<span class="px-3 py-1 rounded border border-gray-300 text-gray-400 cursor-not-allowed"><i class="fas fa-chevron-right"></i></span>' : 
                        `<button onclick="loadPage(${data.siswa.current_page + 1})" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50 transition duration-300 pagination-btn" data-page="${data.siswa.current_page + 1}"><i class="fas fa-chevron-right"></i></button>`
                    }
                </div>
            </div>
            `;
        } else {
            paginationHTML = `
            <div class="flex flex-col md:flex-row justify-between items-center p-4">
                <div class="text-sm text-gray-600">
                    Menampilkan <span class="font-semibold">${data.siswa.data.length}</span> dari <span class="font-semibold">${data.siswa.total}</span> siswa
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
            const url = new URL('{{ route("admin.siswa.index") }}', window.location.origin);
            if (searchTerm) {
                url.searchParams.append('search', searchTerm);
            }
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
                
                renderTable(data);
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
                
                const nisn = this.getAttribute('data-nisn');
                const nama = this.getAttribute('data-nama');
                const kelas = this.getAttribute('data-kelas');
                const jurusan = this.getAttribute('data-jurusan');
                
                showSiswaModal(true, {
                    nisn: nisn,
                    nama: nama,
                    kelas: kelas,
                    jurusan: jurusan
                });
            });
        });

        document.querySelectorAll('.create-account-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Tambahkan ini
                
                const nisn = this.getAttribute('data-nisn');
                const nama = this.getAttribute('data-nama');
                
                console.log('Create account clicked:', { nisn, nama });
                createUserAccount(nisn, nama);
            });
        });

        // Delete button handlers
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const nisn = this.getAttribute('data-nisn');
                const nama = this.getAttribute('data-nama');
                
                siswaToDelete = { nisn: nisn, nama: nama };
                
                document.getElementById('delete-message').textContent = 
                    `Apakah Anda yakin ingin menghapus data siswa "${nama}" (NISN: ${nisn})? Data siswa dan akun pengguna terkait akan dihapus. Tindakan ini tidak dapat dibatalkan.`;
                
                showModal('deleteModal');
            });
        });
        
        // Create account button handlers
        document.querySelectorAll('.create-account-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const nisn = this.getAttribute('data-nisn');
                const nama = this.getAttribute('data-nama');
                
                siswaToCreateAccount = { nisn: nisn, nama: nama };
                createUserAccount(nisn, nama);
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
    
    // ========== FUNGSI IMPORT EXCEL ==========
    function showImportModal() {
        const existingAlert = document.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
            if (currentAlertTimeout) {
                clearTimeout(currentAlertTimeout);
            }
        }
        
        // Reset form
        const importForm = document.getElementById('import-form');
        if (importForm) {
            importForm.reset();
            document.getElementById('file-name').textContent = '';
        }
        
        // Clear errors
        document.querySelectorAll('.validation-error').forEach(element => {
            element.classList.remove('show');
            element.textContent = '';
        });
        
        showModal('importModal');
    }
    
    // Fungsi untuk download template
    async function downloadTemplate() {
        try {
            window.location.href = '{{ route("admin.siswa.download-template") }}';
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat download template', 'error');
        }
    }
    
    function validateNISN(nisn) {
        if (!nisn) {
            showFieldError('nisn', 'NISN wajib diisi');
            return false;
        }
        
        if (nisn.length !== 10) {
            showFieldError('nisn', 'NISN harus tepat 10 digit');
            return false;
        }
        
        if (!/^[0-9]{10}$/.test(nisn)) {  // lebih ketat: hanya digit 0-9 tepat 10
            showFieldError('nisn', 'NISN hanya boleh angka 0-9 (boleh diawali 0)');
            return false;
        }
        
        return true;
    }
    
    function validateForm() {
        let isValid = true;
        clearAllErrors();
        
        // Validasi NISN (hanya untuk tambah baru)
        const isEdit = document.getElementById('edit-mode').value === 'true';
        if (!isEdit) {
            const nisn = document.getElementById('nisn').value.trim();
            if (!validateNISN(nisn)) {
                isValid = false;
            }
        }
        
        // Validasi Nama
        const nama = document.getElementById('nama').value.trim();
        if (!nama) {
            showFieldError('nama', 'Nama wajib diisi');
            isValid = false;
        }
        
        // Validasi Kelas
        const kelas = document.getElementById('kelas').value;
        if (!kelas) {
            showFieldError('kelas', 'Kelas wajib dipilih');
            isValid = false;
        }
        
        // Validasi Jurusan
        const jurusan = document.getElementById('jurusan').value;
        if (!jurusan) {
            showFieldError('jurusan', 'Jurusan wajib dipilih');
            isValid = false;
        }
        
        return isValid;
    }

    function showSiswaModal(isEdit = false, data = null) {
        const modal = document.getElementById('siswaModal');
        if (!modal) {
            console.error("Modal #siswaModal tidak ditemukan!");
            return;
        }

        const nisnInput = document.getElementById('nisn');
        const modalTitle = document.getElementById('modal-title');
        const submitBtn = document.getElementById('submit-btn');
        const editModeInput = document.getElementById('edit-mode');
        const currentNisnInput = document.getElementById('current-nisn');

        // Reset dulu kondisi
        nisnInput.removeAttribute('readonly');
        nisnInput.classList.remove('bg-gray-100', 'cursor-not-allowed');

        if (!isEdit) {
            // Mode Tambah Baru
            document.getElementById('siswa-form').reset();
            editModeInput.value = 'false';
            modalTitle.textContent = 'Tambah Siswa Baru';
            submitBtn.textContent = 'Simpan';
            document.getElementById('create-account-section').style.display = 'block';
            
            // NISN bisa diedit
            nisnInput.removeAttribute('readonly');
        } else {
            // Mode Edit
            editModeInput.value = 'true';
            currentNisnInput.value = data.nisn;

            nisnInput.value = data.nisn;
            document.getElementById('nama').value = data.nama;
            document.getElementById('kelas').value = data.kelas;
            document.getElementById('jurusan').value = data.jurusan;

            modalTitle.textContent = 'Edit Data Siswa';
            submitBtn.textContent = 'Update';
            document.getElementById('create-account-section').style.display = 'none';

            // Jadikan NISN readonly
            nisnInput.setAttribute('readonly', 'readonly');
            nisnInput.classList.add('bg-gray-100', 'cursor-not-allowed');
        }

        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        clearAllErrors();
    }

    // Event listener ketika DOM siap
    document.addEventListener('DOMContentLoaded', function() {            
        console.log('Siswa page loaded');
        
        // 1. Tombol tambah siswa
        const tambahBtn = document.getElementById('tambah-siswa-btn');
        if (tambahBtn) {
            tambahBtn.addEventListener('click', function(e) {
                e.preventDefault();
                showSiswaModal(false);
            });
        }
        
        // 2. Tombol download template
        const downloadTemplateBtn = document.getElementById('download-template-btn');
        if (downloadTemplateBtn) {
            downloadTemplateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                downloadTemplate();
            });
        }
        
        // 3. Tombol import excel
        const importBtn = document.getElementById('import-excel-btn');
        if (importBtn) {
            importBtn.addEventListener('click', function(e) {
                e.preventDefault();
                showImportModal();
            });
        }
        
        // 4. Live search dengan debounce
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                const searchTerm = e.target.value.trim();
                
                searchTimeout = setTimeout(function() {
                    performSearch(searchTerm, 1);
                }, 500);
            });
            
            // Handle Enter key untuk search
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    clearTimeout(searchTimeout);
                    const searchTerm = e.target.value.trim();
                    performSearch(searchTerm, 1);
                }
            });
        }
        
        // 5. Toggle pesan akun ketika checkbox dicentang/dibuka
        const createAccountCheckbox = document.getElementById('create_user_account');
        if (createAccountCheckbox) {
            createAccountCheckbox.addEventListener('change', function() {
                updateAccountPreview();
            });
        }
        
        // 6. Update preview username ketika nama berubah
        const namaInput = document.getElementById('nama');
        if (namaInput) {
            namaInput.addEventListener('input', function() {
                const checkbox = document.getElementById('create_user_account');
                if (checkbox && checkbox.checked) {
                    const nama = this.value.trim();
                    if (nama) {
                        const username = generateUsernameFromName(nama);
                        document.getElementById('preview-username').textContent = username;
                    } else {
                        document.getElementById('preview-username').textContent = '-';
                    }
                }
            });
        }
        
        // 7. Update preview password ketika NISN berubah
        const nisnInput = document.getElementById('nisn');
        if (nisnInput) {
            nisnInput.addEventListener('input', function() {
                const checkbox = document.getElementById('create_user_account');
                if (checkbox && checkbox.checked) {
                    const nisn = this.value.trim();
                    document.getElementById('preview-password').textContent = nisn || '-';
                }
            });
        }
        
        // 8. Real-time validation untuk NISN
        if (nisnInput) {
            nisnInput.addEventListener('blur', function() {
                const nisn = this.value.trim();
                const isEdit = document.getElementById('edit-mode').value === 'true';

                if (nisn && !isEdit) {
                    validateNISN(nisn);
                }
            });
        }

        // 9. Submit form siswa (AJAX)
        const siswaForm = document.getElementById('siswa-form');
        if (siswaForm) {
            siswaForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Validasi form
                if (!validateForm()) {
                    return;
                }
                
                const formData = new FormData(this);
                const isEdit = document.getElementById('edit-mode').value === 'true';
                const submitBtn = document.getElementById('submit-btn');
                const originalText = submitBtn.innerHTML;
                
                // Disable button dan show loading state di tombol
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> ' + (isEdit ? 'Memperbarui...' : 'Menyimpan...');
                
                // Determine URL and method
                let url = "{{ route('admin.siswa.store') }}";
                let method = 'POST';
                
                if (isEdit) {
                    const oldNisn = document.getElementById('current-nisn').value;
                    url = "{{ url('admin/siswa') }}/" + oldNisn;
                    method = 'POST';
                    formData.append('_method', 'PUT');
                }
                
                try {
                    // Send AJAX request
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        const successMessage = isEdit ? 'Data siswa berhasil diperbarui!' : 'Data siswa berhasil ditambahkan!';
                        showAlert(successMessage, 'success');
                        hideModal('siswaModal');
                        
                        // Refresh table data tanpa reload page
                        // Set current page ke 1 untuk memastikan data terbaru di atas
                        await performSearch(currentSearch, 1);
                    } else {
                        if (data.errors) {
                            // Tampilkan error untuk setiap field
                            for (const [field, messages] of Object.entries(data.errors)) {
                                showFieldError(field, messages[0]);
                            }
                            showAlert('Terdapat kesalahan dalam pengisian form', 'error');
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
        
        // 10. Submit form import (AJAX)
        const importForm = document.getElementById('import-form');
        if (importForm) {
            importForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const fileInput = document.getElementById('excel-file');
                const file = fileInput.files[0];
                const importBtn = document.getElementById('import-submit-btn');
                const originalText = importBtn.innerHTML;
                
                // Validasi file
                if (!file) {
                    showFieldError('file-error', 'Silahkan pilih file Excel terlebih dahulu');
                    return;
                }
                
                // Validasi ekstensi file
                const validExtensions = ['.xlsx', '.xls', '.csv'];
                const fileName = file.name.toLowerCase();
                const isValidExtension = validExtensions.some(ext => fileName.endsWith(ext));
                
                if (!isValidExtension) {
                    showFieldError('file-error', 'Format file tidak valid. Gunakan file Excel (.xlsx, .xls, .csv)');
                    return;
                }
                
                // Clear error
                document.getElementById('file-error').classList.remove('show');
                
                // Disable button dan show loading
                importBtn.disabled = true;
                importBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengimport...';
                
                try {
                    const formData = new FormData(this);
                    
                    const response = await fetch('{{ route("admin.siswa.import") }}', {
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
                        hideModal('importModal');
                        
                        // Refresh table data
                        await performSearch(currentSearch, 1);
                    } else {
                        showAlert(data.message || 'Terjadi kesalahan saat import data', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('Terjadi kesalahan saat import data', 'error');
                } finally {
                    importBtn.disabled = false;
                    importBtn.innerHTML = originalText;
                }
            });
        }
        
        // 11. File upload preview
        const fileInput = document.getElementById('excel-file');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const fileName = this.files[0] ? this.files[0].name : 'Tidak ada file dipilih';
                document.getElementById('file-name').textContent = `File: ${fileName}`;
                document.getElementById('file-error').classList.remove('show');
            });
        }
        
        // 12. Confirm delete (AJAX)
        const confirmDeleteBtn = document.getElementById('confirm-delete');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                
                if (siswaToDelete) {
                    const deleteBtn = document.getElementById('confirm-delete');
                    const originalText = deleteBtn.innerHTML;
                    
                    deleteBtn.disabled = true;
                    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';
                    
                    try {
                        const response = await fetch("{{ url('admin/siswa') }}/" + siswaToDelete.nisn, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-HTTP-Method-Override': 'DELETE',
                                'Accept': 'application/json'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            showAlert('Data siswa berhasil dihapus!', 'success');
                            hideModal('deleteModal');
                            
                            // Refresh table data tanpa reload page
                            // Jika setelah menghapus data pada halaman kosong, kembali ke halaman sebelumnya
                            const searchTerm = document.getElementById('search-input').value.trim();
                            const tableRows = document.querySelectorAll('#siswa-table-body tr');
                            if (tableRows.length <= 1 && currentPage > 1) {
                                await performSearch(searchTerm, currentPage - 1);
                            } else {
                                await performSearch(searchTerm, currentPage);
                            }
                        } else {
                            showAlert(data.message || 'Terjadi kesalahan saat menghapus data', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showAlert('Terjadi kesalahan saat menghapus data', 'error');
                    } finally {
                        deleteBtn.disabled = false;
                        deleteBtn.innerHTML = originalText;
                        siswaToDelete = null;
                    }
                }
            });
        }
        
        // 13. Remove error highlight when typing
        document.querySelectorAll('#siswa-form input, #siswa-form select').forEach(input => {
            input.addEventListener('input', function() {
                const fieldId = this.id;
                this.classList.remove('border-red-500');
                
                const errorElement = document.getElementById(`${fieldId}-error`);
                if (errorElement) {
                    errorElement.classList.remove('show');
                }
            });
            
            input.addEventListener('change', function() {
                const fieldId = this.id;
                this.classList.remove('border-red-500');
                
                const errorElement = document.getElementById(`${fieldId}-error`);
                if (errorElement) {
                    errorElement.classList.remove('show');
                }
            });
        });
        
        // 14. Handle browser back/forward buttons
        window.addEventListener('popstate', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const searchParam = urlParams.get('search') || '';
            const pageParam = parseInt(urlParams.get('page')) || 1;
            
            document.getElementById('search-input').value = searchParam;
            performSearch(searchParam, pageParam);
        });
        
        // 15. Initial attach event listeners
        attachRowEventListeners();
        
        // 16. Show session messages
        @if(session('success'))
            showAlert('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showAlert('{{ session('error') }}', 'error');
        @endif
        
        // 17. Close modal when clicking outside
        document.addEventListener('click', function(e) {
            // For siswa modal
            const siswaModal = document.getElementById('siswaModal');
            const siswaModalContent = document.querySelector('#siswaModal .modal-content');
            
            if (siswaModal && siswaModal.classList.contains('show')) {
                if (!siswaModalContent.contains(e.target) && !e.target.closest('#tambah-siswa-btn') && 
                    !e.target.closest('.edit-btn') && !e.target.closest('.modal-content')) {
                    hideModal('siswaModal');
                }
            }
            
            // For import modal
            const importModal = document.getElementById('importModal');
            const importModalContent = document.querySelector('#importModal .modal-content');
            
            if (importModal && importModal.classList.contains('show')) {
                if (!importModalContent.contains(e.target) && !e.target.closest('#import-excel-btn') && 
                    !e.target.closest('.modal-content')) {
                    hideModal('importModal');
                }
            }
            
            // For delete modal
            const deleteModal = document.getElementById('deleteModal');
            const deleteModalContent = document.querySelector('#deleteModal .modal-content');
            
            if (deleteModal && deleteModal.classList.contains('show')) {
                if (!deleteModalContent.contains(e.target) && !e.target.closest('.delete-btn') && 
                    !e.target.closest('.modal-content')) {
                    hideModal('deleteModal');
                }
            }
            
            // For create account modal (dynamic)
            const createAccountModal = document.getElementById('createAccountModal');
            if (createAccountModal && createAccountModal.classList.contains('show')) {
                const createAccountModalContent = createAccountModal.querySelector('.modal-content');
                if (!createAccountModalContent.contains(e.target) && !e.target.closest('.create-account-btn')) {
                    createAccountModal.classList.remove('show');
                    setTimeout(() => {
                        if (createAccountModal.parentNode) {
                            createAccountModal.remove();
                        }
                    }, 300);
                    document.body.style.overflow = '';
                }
            }
        });
        
        // 18. Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const siswaModal = document.getElementById('siswaModal');
                const importModal = document.getElementById('importModal');
                const deleteModal = document.getElementById('deleteModal');
                const createAccountModal = document.getElementById('createAccountModal');
                
                if (siswaModal && siswaModal.classList.contains('show')) {
                    hideModal('siswaModal');
                }
                
                if (importModal && importModal.classList.contains('show')) {
                    hideModal('importModal');
                }
                
                if (deleteModal && deleteModal.classList.contains('show')) {
                    hideModal('deleteModal');
                }
                
                if (createAccountModal && createAccountModal.classList.contains('show')) {
                    createAccountModal.classList.remove('show');
                    setTimeout(() => {
                        if (createAccountModal.parentNode) {
                            createAccountModal.remove();
                        }
                    }, 300);
                    document.body.style.overflow = '';
                }
            }
        });
        
        // 19. Initialize with current search value
        if (currentSearch) {
            performSearch(currentSearch, currentPage);
        }
    });
</script>
@endpush