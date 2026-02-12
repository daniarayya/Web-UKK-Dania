<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SUARAKITA')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }
        
        /* Custom Scrollbar Styling - Green Blue Gradient */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(90deg, #22c55e 0%, #3b82f6 100%);
            border-radius: 5px;
            border: 2px solid #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(90deg, #16a34a 0%, #2563eb 100%);
        }
        
        /* Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: linear-gradient(90deg, #22c55e 0%, #3b82f6 100%) #f1f5f9;
        }
        
        /* Custom Scrollbar for specific containers */
        .w-64.bg-white.shadow-lg.border-r.border-gray-200.flex.flex-col,
        .flex-1.overflow-y-auto,
        .modal-content {
            scrollbar-width: thin;
            scrollbar-color: #22c55e #f1f5f9;
        }
        
        .w-64.bg-white.shadow-lg.border-r.border-gray-200.flex.flex-col::-webkit-scrollbar,
        .flex-1.overflow-y-auto::-webkit-scrollbar,
        .modal-content::-webkit-scrollbar {
            width: 8px;
        }
        
        .w-64.bg-white.shadow-lg.border-r.border-gray-200.flex.flex-col::-webkit-scrollbar-track,
        .flex-1.overflow-y-auto::-webkit-scrollbar-track,
        .modal-content::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        .w-64.bg-white.shadow-lg.border-r.border-gray-200.flex.flex-col::-webkit-scrollbar-thumb,
        .flex-1.overflow-y-auto::-webkit-scrollbar-thumb,
        .modal-content::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22c55e 0%, #3b82f6 100%);
            border-radius: 4px;
        }
        
        .w-64.bg-white.shadow-lg.border-r.border-gray-200.flex.flex-col::-webkit-scrollbar-thumb:hover,
        .flex-1.overflow-y-auto::-webkit-scrollbar-thumb:hover,
        .modal-content::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #16a34a 0%, #2563eb 100%);
        }
        
        .gradient-text {
            background: linear-gradient(90deg, #22c55e 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .sidebar-active {
            background: linear-gradient(90deg, rgba(34,197,94,.1), rgba(59,130,246,.05));
            border-left: 4px solid #22c55e;
            color: #22c55e;
            font-weight: 600;
        }
        
        .sidebar-dropdown {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            margin-left: 2rem;
        }
        
        .sidebar-dropdown.show {
            max-height: 200px;
        }
        
        .rotate-180 {
            transform: rotate(180deg);
            transition: transform 0.3s ease;
        }
        
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }
        
        .modal-overlay.show {
            display: flex !important;
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .modal-content {
            background-color: white;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10001;
            max-width: 400px;
            animation: slideIn 0.3s ease-out;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            overflow: hidden;
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
        
        .form-error {
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        
        .form-error.show {
            display: block;
        }
        
        .input-error {
            border-color: #ef4444 !important;
        }
        
        .input-success {
            border-color: #10b981 !important;
        }
        
        .show {
            display: block !important;
        }
        
        .hidden {
            display: none !important;
        }
        
        .flex.h-screen {
            overflow: hidden;
        }
        
        .flex-1.flex.flex-col.overflow-hidden {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
        }
        
        .flex-1.overflow-y-auto {
            flex: 1;
            overflow-y: auto !important;
        }
        
        .w-64.bg-white.shadow-lg.border-r.border-gray-200.flex.flex-col {
            overflow-y: auto;
            max-height: 100vh;
        }
        
        #profile-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 0.5rem;
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            width: 14rem;
            z-index: 50;
        }
        
        #profile-dropdown.show {
            display: block !important;
        }
        
        #profile-dropdown .dropdown-content {
            display: flex;
            flex-direction: column;
            padding: 0.25rem;
        }
        
        #profile-dropdown button,
        #profile-dropdown a {
            display: flex;
            align-items: center;
            width: 100%;
            text-align: left;
            padding: 0.75rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 0.875rem;
            text-decoration: none;
        }
        
        #profile-dropdown button:hover,
        #profile-dropdown a:hover {
            background-color: #f9fafb;
        }
        
        #profile-dropdown button.text-red-600:hover,
        #profile-dropdown a.text-red-600:hover {
            background-color: #fef2f2;
        }
        
        .btn-primary, .btn-secondary {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
            min-width: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #22c55e 0%, #3b82f6 100%);
            color: white;
            border: none;
        }
        
        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(34, 197, 94, 0.25);
        }
        
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-secondary {
            background: white;
            border: 2px solid #e5e7eb;
            color: #374151;
        }
        
        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }
        
        .password-input-container {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6b7280;
            padding: 5px;
        }
        
        .password-toggle:hover {
            color: #374151;
        }
        
        @media (max-width: 640px) {
            .btn-primary, .btn-secondary {
                width: 100%;
                padding: 0.875rem 1rem;
            }
        }
        
        .section-title {
            display: flex;
            align-items: center;
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .section-title i {
            margin-right: 0.5rem;
        }
    </style>
    
    @stack('styles')
</head>

<body class="font-inter bg-gray-50">

<div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg border-r border-gray-200 flex flex-col">
        <div class="p-6 border-b border-gray-200">
            <a href="{{ route('admin.dashboard')}}" class="flex items-center space-x-3">
                <img src="{{ asset('logo.png') }}" alt="Logo Sekolah" class="w-12 h-12 object-contain">
                <div>
                    <h1 class="text-2xl font-bold gradient-text">SUARAKITA</h1>
                    <!-- <p class="text-xs text-gray-600"> </p> -->
                </div>
            </a>
        </div>

        <nav class="flex-1 p-4">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg 
                       {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : 'hover:bg-gray-100' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.siswa.index') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg 
                       {{ request()->routeIs('admin.siswa.*') ? 'sidebar-active' : 'hover:bg-gray-100' }}">
                        <i class="fas fa-users"></i>
                        <span>Siswa</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.kategori.index') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg 
                       {{ request()->routeIs('admin.kategori.*') ? 'sidebar-active' : 'hover:bg-gray-100' }}">
                        <i class="fas fa-tags"></i>
                        <span>Kategori</span>
                    </a>
                </li>

                <li>
                    <div class="relative">
                        <button id="aspirasi-dropdown-btn" class="flex items-center justify-between w-full p-3 rounded-lg hover:bg-gray-100 transition duration-300">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-comment-dots"></i>
                                <span class="font-medium">Aspirasi</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                        </button>
                        
                        <div id="aspirasi-dropdown" class="sidebar-dropdown">
                            <ul class="space-y-1 mt-1">
                                <li>
                                    <a href="{{ route('admin.aspirasi.masuk.index') }}"
                                    class="flex items-center space-x-3 p-2 rounded-lg 
                                    {{ request()->routeIs('admin.aspirasi.masuk.index') ? 'sidebar-active' : 'hover:bg-gray-100' }}">
                                        <i class="fas fa-inbox text-xs"></i>
                                        <span class="text-sm">Aspirasi Masuk</span>
                                        <span id="unread-count" class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full hidden">0</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.aspirasi.index') }}"
                                       class="flex items-center space-x-3 p-2 rounded-lg 
                                       {{ request()->routeIs('admin.aspirasi.index') ? 'sidebar-active' : 'hover:bg-gray-100' }}">
                                        <i class="fas fa-list text-xs"></i>
                                        <span class="text-sm">List Aspirasi</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.feedback.index') }}"
                                       class="flex items-center space-x-3 p-2 rounded-lg 
                                       {{ request()->routeIs('admin.feedback.index') ? 'sidebar-active' : 'hover:bg-gray-100' }}">
                                        <i class="fas fa-comments text-xs"></i>
                                        <span class="text-sm">Umpan Balik</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.history.index') }}"
                                       class="flex items-center space-x-3 p-2 rounded-lg 
                                       {{ request()->routeIs('admin.history.*') ? 'sidebar-active' : 'hover:bg-gray-100' }}">
                                        <i class="fas fa-history text-xs"></i>
                                        <span class="text-sm">History</span>
                                    </a>
                                </li>
                               <li>
                                    <a href="{{ route('admin.arsip.index') }}"
                                    class="flex items-center space-x-3 p-2 rounded-lg 
                                    {{ request()->routeIs('admin.arsip.*') ? 'sidebar-active' : 'hover:bg-gray-100' }}">
                                        <i class="fas fa-archive text-xs"></i>
                                        <span class="text-sm">Arsip Ditolak</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>

                <li>
                    <a href="{{ route('admin.laporan') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg 
                       {{ request()->routeIs('admin.laporan') ? 'sidebar-active' : 'hover:bg-gray-100' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.pengguna.index') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg 
                       {{ request()->routeIs('admin.pengguna.*') ? 'sidebar-active' : 'hover:bg-gray-100' }}">
                        <i class="fas fa-user-circle"></i>
                        <span>Pengguna</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 px-6 py-3 flex justify-between items-center">
            <div>
                <!-- Breadcrumb -->
                <nav class="flex items-center text-sm text-gray-500 mb-1 space-x-2">
                    <a href="{{ route('home') }}" class="hover:text-green-600 font-medium flex items-center space-x-1">
                        <i class="fas fa-home text-xs"></i>
                        <span>Home</span>
                    </a>

                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>

                    <span class="text-gray-700 font-semibold">@yield('page-title','Dashboard Admin')</span>
                </nav>

                <h2 class="text-xl font-bold text-gray-800">@yield('page-title','Dashboard Admin')</h2>
                <p class="text-sm text-gray-600">@yield('page-description', 'Statistik dan ringkasan sistem aspirasi siswa')</p>
            </div>

            <!-- Profile -->
            <div class="relative">
                <button id="profile-dropdown-btn" 
                        class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <div class="text-left">
                        <span class="block font-medium text-gray-900" id="profile-name">{{ auth()->user()->nama ?? 'Admin' }}</span>
                        <span class="block text-xs text-gray-500" id="profile-username">
                            {{ auth()->user()->username ?? 'admin' }} • 
                            <span id="profile-role">{{ auth()->user()->role ? ucfirst(auth()->user()->role) : 'Admin' }}</span>
                        </span>
                    </div>
                    <i class="fas fa-chevron-down text-sm text-gray-500 ml-2"></i>
                </button>

                <div id="profile-dropdown" class="hidden">
                    <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-green-50 to-blue-50">
                        <p class="text-sm font-semibold text-gray-900" id="dropdown-name">{{ auth()->user()->nama ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-600" id="dropdown-username">{{ auth()->user()->username ?? 'admin' }}</p>
                        <p class="text-xs font-medium text-blue-600 mt-1" id="dropdown-role">
                            {{ auth()->user()->role ? ucfirst(auth()->user()->role) : 'Admin' }}
                        </p>
                    </div>
                    
                    <div class="dropdown-content">
                        <button onclick="openProfileModal()" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition duration-300 text-left text-gray-700">
                            <i class="fas fa-user-cog text-gray-600 w-5"></i>
                            <span class="text-sm">Pengaturan Profil</span>
                        </button>
                        
                        <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-red-50 transition duration-300 text-left text-red-600 border-t border-gray-100">
                                <i class="fas fa-sign-out-alt text-red-500 w-5"></i>
                                <span class="text-sm">Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

<!-- MODALS SECTION -->
@yield('modals')

<!-- Modal untuk Pengaturan Profil -->
<div id="profileModal" class="modal-overlay">
    <div class="modal-content">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-green-100 to-blue-100 flex items-center justify-center">
                        <i class="fas fa-user-cog text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Pengaturan Profil</h3>
                        <p class="text-sm text-gray-600">Kelola informasi akun Anda</p>
                    </div>
                </div>
                <button type="button" onclick="closeProfileModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="profileForm">
                @csrf
                
                <div class="space-y-6">
                    <!-- Informasi Pribadi -->
                    <div>
                        <h4 class="section-title text-gray-800">
                            <i class="fas fa-user-circle text-blue-500"></i>
                            Informasi Pribadi
                        </h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                                <input type="text" name="nama" id="namaInput" value="{{ auth()->user()->nama ?? '' }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <div id="nama-error" class="form-error text-red-600"></div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                                <input type="text" name="username" id="usernameInput" value="{{ auth()->user()->username ?? '' }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <div id="username-error" class="form-error text-red-600"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Keamanan Akun -->
                    <div>
                        <h4 class="section-title text-gray-800">
                            <i class="fas fa-shield-alt text-green-500"></i>
                            Keamanan Akun
                        </h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                <div class="password-input-container">
                                    <input type="password" name="password" id="passwordInput"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors pr-10">
                                    <button type="button" onclick="togglePassword('passwordInput')" 
                                            class="password-toggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="password-error" class="form-error text-red-600"></div>
                                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                                <div class="password-input-container">
                                    <input type="password" name="password_confirmation" id="passwordConfirmationInput"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors pr-10">
                                    <button type="button" onclick="togglePassword('passwordConfirmationInput')" 
                                            class="password-toggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="general-error" class="form-error text-red-600 mt-4"></div>
                
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeProfileModal()" 
                            class="btn-secondary">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" id="submitProfileBtn"
                            class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Notification -->
<div id="successNotification" class="notification hidden">
    <div class="bg-gradient-to-r from-green-500 to-blue-500 text-white p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle"></i>
                <span id="notificationMessage">Profil berhasil diperbarui!</span>
            </div>
            <button type="button" onclick="hideNotification()" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

<script>
    // Profile dropdown toggle
    const profileBtn = document.getElementById('profile-dropdown-btn');
    const profileMenu = document.getElementById('profile-dropdown');
    
    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            
            if (profileMenu.classList.contains('hidden')) {
                profileMenu.classList.remove('hidden');
                profileMenu.classList.add('show');
            } else {
                profileMenu.classList.add('hidden');
                profileMenu.classList.remove('show');
            }
        });
        
        document.addEventListener('click', (e) => {
            if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.add('hidden');
                profileMenu.classList.remove('show');
            }
        });
        
        profileMenu.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }
    
    // Fungsi untuk toggle password visibility (show/hide)
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const button = input.nextElementSibling;
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
    // Aspirasi dropdown
    document.addEventListener('DOMContentLoaded', function() {
        const aspirasiDropdownBtn = document.getElementById('aspirasi-dropdown-btn');
        const aspirasiDropdown = document.getElementById('aspirasi-dropdown');

        if (aspirasiDropdownBtn && aspirasiDropdown) {
            aspirasiDropdownBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const isExpanded = aspirasiDropdown.classList.contains('show');
                aspirasiDropdown.classList.toggle('show');
                
                const icon = this.querySelector('.fa-chevron-down');
                icon.classList.toggle('rotate-180');
                this.setAttribute('aria-expanded', !isExpanded);
            });
            
            document.addEventListener('click', function(e) {
                if (!aspirasiDropdownBtn.contains(e.target) && 
                    !aspirasiDropdown.contains(e.target)) {
                    
                    aspirasiDropdown.classList.remove('show');
                    const icon = aspirasiDropdownBtn.querySelector('.fa-chevron-down');
                    icon.classList.remove('rotate-180');
                    aspirasiDropdownBtn.setAttribute('aria-expanded', 'false');
                }
            });
        }
        
        // Auto-expand aspirasi dropdown
        if (window.location.pathname.includes('/admin/aspirasi') || 
            window.location.pathname.includes('/admin/feedback') || 
            window.location.pathname.includes('/admin/history') ||
            window.location.pathname.includes('/admin/arsip-ditolak')) {
            if (aspirasiDropdown && aspirasiDropdownBtn) {
                aspirasiDropdown.classList.add('show');
                const icon = aspirasiDropdownBtn.querySelector('.fa-chevron-down');
                icon.classList.add('rotate-180');
                aspirasiDropdownBtn.setAttribute('aria-expanded', 'true');
            }
        }
        
        // Tutup modal dengan ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.modal-overlay.show');
                if (openModal) {
                    openModal.classList.remove('show');
                }
                if (profileMenu && profileMenu.classList.contains('show')) {
                    profileMenu.classList.add('hidden');
                    profileMenu.classList.remove('show');
                }
                hideNotification();
            }
        });
        
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                e.target.classList.remove('show');
                hideNotification();
            }
        });
    });

    function openProfileModal() {
        const modal = document.getElementById('profileModal');
        if (modal) {
            clearProfileFormErrors();
            modal.classList.add('show');
            
            if (profileMenu) {
                profileMenu.classList.add('hidden');
                profileMenu.classList.remove('show');
            }
            
            setTimeout(() => {
                const firstInput = modal.querySelector('#namaInput');
                if (firstInput) firstInput.focus();
            }, 300);
        }
    }

    function closeProfileModal() {
        const modal = document.getElementById('profileModal');
        if (modal) {
            modal.classList.remove('show');
            clearProfileFormErrors();
        }
    }

    function clearProfileFormErrors() {
        const errorElements = document.querySelectorAll('.form-error');
        errorElements.forEach(el => {
            el.textContent = '';
            el.classList.remove('show');
        });
        
        const inputElements = document.querySelectorAll('#profileForm input');
        inputElements.forEach(input => {
            input.classList.remove('input-error', 'input-success');
        });
    }

    function showProfileError(field, message) {
        const errorElement = document.getElementById(`${field}-error`);
        const inputElement = document.getElementById(`${field}Input`);
        
        if (errorElement && inputElement) {
            errorElement.textContent = message;
            errorElement.classList.add('show');
            inputElement.classList.add('input-error');
        }
    }

    function showSuccessNotification(message = 'Profil berhasil diperbarui!') {
        const notification = document.getElementById('successNotification');
        const messageElement = document.getElementById('notificationMessage');
        
        if (notification && messageElement) {
            messageElement.textContent = message;
            notification.classList.remove('hidden');
            
            setTimeout(() => {
                hideNotification();
            }, 3000);
        }
    }

    function hideNotification() {
        const notification = document.getElementById('successNotification');
        if (notification) notification.classList.add('hidden');
    }

    function updateProfileInfo(userData) {
        // Update nama
        const nameElements = document.querySelectorAll('#profile-name, #dropdown-name');
        nameElements.forEach(el => {
            if (el) el.textContent = userData.nama;
        });
        
        // Update username
        const usernameElements = document.querySelectorAll('#profile-username, #dropdown-username');
        usernameElements.forEach(el => {
            if (el) {
                if (el.id === 'profile-username') {
                    el.innerHTML = `${userData.username} • <span id="profile-role">${userData.role}</span>`;
                } else {
                    el.textContent = userData.username;
                }
            }
        });
        
        // Update role
        const roleElements = document.querySelectorAll('#profile-role, #dropdown-role');
        roleElements.forEach(el => {
            if (el) el.textContent = userData.role.charAt(0).toUpperCase() + userData.role.slice(1);
        });
        
        // Update form inputs
        const namaInput = document.getElementById('namaInput');
        const usernameInput = document.getElementById('usernameInput');
        
        if (namaInput) namaInput.value = userData.nama;
        if (usernameInput) usernameInput.value = userData.username;
    }

   const profileForm = document.getElementById('profileForm');

    if (profileForm) {
        profileForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            clearProfileFormErrors();

            const submitBtn = document.getElementById('submitProfileBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';

            try {
                const formData = new FormData(this);
                formData.append('_method', 'PUT');

                const response = await fetch("{{ route('admin.profile.update') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    updateProfileInfo(result.user);
                    showSuccessNotification(result.message);

                    setTimeout(() => {
                        closeProfileModal();
                    }, 1000);

                    // Reset password fields
                    document.getElementById('passwordInput').value = '';
                    document.getElementById('passwordConfirmationInput').value = '';
                } else {
                    if (result.errors) {
                        Object.keys(result.errors).forEach(field => {
                            showProfileError(field, result.errors[field][0]);
                        });
                    }
                }

            } catch (error) {
                const generalError = document.getElementById('general-error');
                generalError.textContent = 'Terjadi kesalahan jaringan.';
                generalError.classList.add('show');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }
</script>

@stack('scripts')

</body>
</html>