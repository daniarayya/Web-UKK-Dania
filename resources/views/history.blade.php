<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUARAKITA</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        secondary: {
                            DEFAULT: '#3b82f6',
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        accent: {
                            DEFAULT: '#f59e0b',
                            dark: '#d97706',
                        },
                        dark: '#1f2937',
                        light: '#f9fafb'
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                        'inter': ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'bounce-slow': 'bounce 2s infinite',
                        'slide-in': 'slideIn 0.5s ease-out',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        slideIn: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                        'gradient-conic': 'conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))',
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom CSS for additional effects */
        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .gradient-text {
            background: linear-gradient(90deg, #22c55e 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .floating-element {
            animation: float 5s ease-in-out infinite;
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .btn-glow:hover {
            box-shadow: 0 5px 20px rgba(34, 197, 94, 0.4);
        }
        
        .btn-secondary-glow:hover {
            box-shadow: 0 5px 20px rgba(59, 130, 246, 0.4);
        }
        
        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #22c55e;
            animation: pulse-slow 2s infinite;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22c55e 0%, #3b82f6 100%);
            border-radius: 10px;
        }
        
        /* Status badges */
        .status-badge {
            padding: 0.4rem 1.2rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .status-menunggu {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 1px solid #fcd34d;
        }
        
        .status-proses {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
        
        .status-selesai {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        
        .status-ditolak {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        
        /* Detail section animation */
        .detail-section {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
        }
        
        .detail-section.show {
            max-height: 2000px;
            opacity: 1;
        }
        
        /* Feedback styling */
        .feedback-bubble {
            background: #f8fafc;
            border-radius: 1rem;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .feedback-bubble:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .admin-feedback {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-color: #bfdbfe;
        }
        
        /* Logo styling */
        .logo-container {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .logo-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        /* Show more button styling */
        .show-more-btn {
            transition: all 0.3s ease;
            font-weight: 500;
            color: #3b82f6;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px 0;
        }
        
        .show-more-btn:hover {
            color: #2563eb;
            transform: translateX(2px);
        }
        
        /* Category and location badges */
        .info-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 500;
            background: #f8fafc;
            color: #4b5563;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .info-badge:hover {
            background: #f3f4f6;
            transform: translateY(-1px);
        }
        
        /* Pagination styling with green color */
        .pagination-item {
            margin: 0 2px;
        }
        
        .pagination-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border-radius: 10px;
            background: white;
            color: #4b5563;
            font-weight: 500;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }
        
        .pagination-link:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }
        
        .pagination-item.active .pagination-link {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            border-color: #22c55e;
            box-shadow: 0 4px 6px rgba(34, 197, 94, 0.2);
        }
        
        .pagination-item.disabled .pagination-link {
            color: #9ca3af;
            background: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }
        
        /* Green pagination arrows */
        .pagination-arrow {
            color: #22c55e;
        }
        
        .pagination-arrow:hover {
            color: #16a34a;
        }
        
        /* Login required badge */
        .login-required-badge {
            font-size: 0.7rem;
            color: #6b7280;
            margin-left: 8px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        
        /* Badge warna berdasarkan role */
        .role-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .role-admin {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
        
        .hidden-detail-message {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #fcd34d;
            border-radius: 1rem;
            padding: 1.5rem;
            margin: 1rem 0;
        }
        
        .warning-message {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1px solid #fde68a;
            border-radius: 1rem;
            padding: 1.5rem;
            margin: 1rem 0;
        }
    </style>
</head>
<body class="font-inter bg-light text-dark">
    @include('auth')
    
    <!-- Header & Navbar -->
    <header class="sticky top-0 z-50">
        <nav class="glass-effect py-4 shadow-lg">
            <div class="container mx-auto px-6">
                <div class="flex justify-between items-center">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('home') }}" class="flex items-center space-x-3 no-underline">
                            <div class="relative">
                                <div class="absolute -inset-1 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full blur opacity-30"></div>
                                <div class="relative bg-white p-1 rounded-full">
                                    <!-- Logo PNG -->
                                    <div class="logo-container">
                                        <img src="{{ asset('logo.png') }}" alt="Logo Sekolah" class="logo-img">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-2xl font-poppins font-bold">
                                    <span class="gradient-text">SUARAKITA</span>
                                </h1>
                                <div class="flex items-center space-x-1">
                                    <div class="pulse-dot"></div>
                                    <p class="text-xs text-gray-600">Dari Kita, Oleh Kita, Untuk SKAMASKDA</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('home') }}"
                        class="{{ request()->routeIs('home') 
                                ? 'text-primary-600 font-semibold border-b-2 border-primary-500' 
                                : 'text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200' }}">
                            <i class="fas fa-home mr-2"></i>Beranda
                        </a>

                        <a href="{{ route('history.index') }}"
                        class="{{ request()->routeIs('history.*') 
                                ? 'text-primary-600 font-semibold border-b-2 border-primary-500' 
                                : 'text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200' }}">
                            <i class="fas fa-comment-alt mr-2"></i>Aspirasi
                        </a>

                        @auth
                            <!-- User Profile Dropdown -->
                            <div class="relative">
                                <button id="userMenuBtn" class="flex items-center space-x-3 px-5 py-3 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-lg">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mb-1">
                                            <i class="fas fa-user text-sm"></i>
                                        </div>
                                        <span class="text-xs font-normal">{{ Str::limit(auth()->user()->username, 12) }}</span>
                                    </div>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-50 hidden">
                                    <div class="py-2">
                                        @php
                                            $role = auth()->user()->role;
                                            $dashboardRoute = match($role) {
                                                'admin' => 'admin.dashboard',
                                                'kepsek' => 'kepsek.dashboard',
                                                default => null
                                            };
                                        @endphp

                                        @if($dashboardRoute)
                                            <a href="{{ route($dashboardRoute) }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition duration-150">
                                                <i class="fas fa-tachometer-alt text-gray-500 mr-3"></i>
                                                <span>Dashboard</span>
                                            </a>
                                        @endif

                                        <form method="POST" action="{{ route('auth.logout') }}" class="@if(in_array(auth()->user()->role, ['admin', 'kepsek'])) border-t border-gray-100 @endif">
                                            @csrf
                                            <button type="submit" class="w-full text-left flex items-center px-4 py-3 text-red-600 hover:bg-red-50 transition duration-150">
                                                <i class="fas fa-sign-out-alt mr-3"></i>
                                                <span>Logout</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <button id="openLoginModal" class="flex items-center space-x-2 px-5 py-3 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-lg">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Login</span>
                            </button>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="py-8 bg-gradient-to-b from-white to-gray-50/50">
        <div class="container mx-auto px-6">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-primary-100 to-secondary-100 text-primary-700 font-medium mb-4">
                    <i class="fas fa-comments mr-2"></i>
                    Platform Aspirasi
                </div>
                
                <h1 class="text-4xl md:text-5xl font-poppins font-bold mb-6">
                    <span class="gradient-text">Riwayat Aspirasi</span>
                </h1>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Lihat semua aspirasi yang telah dikirimkan oleh siswa. Pantau perkembangan dan berikan masukan untuk perbaikan sekolah.
                </p>
            </div>

            @if(session('success'))
                <div id="success-alert"
                    class="mb-6 p-4 rounded-xl bg-green-100 border-l-4 border-green-500 text-green-700 shadow-md flex items-center space-x-3 transition-all duration-500">
                    <i class="fas fa-check-circle text-xl"></i>
                    <span class="font-semibold">
                        {{ session('success') }}
                    </span>
                </div>
            @endif

            <!-- Search Bar -->
            <div class="mb-8">
                <div class="max-w-4xl mx-auto">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input 
                            type="text" 
                            id="searchInput"
                            placeholder="Cari aspirasi berdasarkan nama siswa..." 
                            class="w-full pl-12 pr-12 py-4 rounded-2xl border border-gray-300 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm transition-all duration-300"
                            autocomplete="off"
                            value="{{ request()->search ?? '' }}"
                        >
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <button 
                                id="clearSearch"
                                class="text-gray-400 hover:text-gray-600 {{ request()->search ? '' : 'hidden' }}"
                                title="Hapus pencarian"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mt-3 text-sm text-gray-500 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span>Tekan Enter untuk mencari atau ketik minimal 3 karakter untuk pencarian otomatis</span>
                    </div>
                </div>
            </div>

            <!-- Search Results Indicator -->
            <div id="searchResultsIndicator" class="{{ request()->search ? '' : 'hidden' }} mb-6">
                <div class="flex items-center justify-between bg-gradient-to-r from-primary-50 to-secondary-50 rounded-xl p-4 border border-primary-100">
                    <div class="flex items-center">
                        <i class="fas fa-filter text-primary-500 mr-3"></i>
                        <div>
                            <p class="font-semibold text-primary-700">Hasil Pencarian</p>
                            <p class="text-sm text-gray-600" id="searchQueryText">{{ request()->search ? 'Pencarian: "' . request()->search . '"' : '' }}</p>
                        </div>
                    </div>
                    <button 
                        id="clearSearchResults" 
                        class="flex items-center px-4 py-2 rounded-lg bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 transition duration-200"
                    >
                        <i class="fas fa-times mr-2"></i>
                        Hapus Filter
                    </button>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="hidden flex justify-center items-center py-12">
                <div class="text-center">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary-500 mb-4"></div>
                    <p class="text-gray-600">Memuat hasil pencarian...</p>
                </div>
            </div>

            <!-- Aspirasi Cards Grid -->
            <div id="aspirasiContainer">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12" id="aspirasiGrid">
                    @forelse($aspirasis as $aspirasi)
                    @php
                        // Potong teks untuk preview
                        $aspirasiText = $aspirasi->pengaduan->keterangan;
                        $words = str_word_count($aspirasiText, 1);
                        $shortText = implode(' ', array_slice($words, 0, 6));
                        
                        // Permission untuk feedback
                        $canViewFeedback = false;
                        if (auth()->check()) {
                            $userRole = auth()->user()->role;
                            $aspirasiNisn = $aspirasi->pengaduan->nisn ?? null;
                            $userNisn = auth()->user()->nisn;
                            
                            if (in_array($userRole, ['admin', 'kepsek'])) {
                                $canViewFeedback = true;
                            } elseif ($aspirasiNisn && $userNisn && $aspirasiNisn == $userNisn) {
                                $canViewFeedback = true;
                            }
                        }
                        
                        // Check apakah user adalah pemilik aspirasi
                        $isOwner = false;
                        if (auth()->check()) {
                            $isOwner = $aspirasi->pengaduan->nisn == auth()->user()->nisn;
                        }
                        
                        // Status styling
                        $statusConfig = [
                            'menunggu' => [
                                'class' => 'status-menunggu',
                                'icon' => 'fa-clock',
                                'text' => 'Menunggu'
                            ],
                            'proses' => [
                                'class' => 'status-proses',
                                'icon' => 'fa-cogs',
                                'text' => 'Diproses'
                            ],
                            'selesai' => [
                                'class' => 'status-selesai',
                                'icon' => 'fa-check-circle',
                                'text' => 'Selesai'
                            ],
                            'ditolak' => [
                                'class' => 'status-ditolak',
                                'icon' => 'fa-times-circle',
                                'text' => 'Ditolak'
                            ]
                        ];
                        $status = $aspirasi->status;
                        $statusInfo = $statusConfig[$status] ?? $statusConfig['menunggu'];
                        
                        // Tentukan apakah card harus ditampilkan dengan styling khusus
                        $isDitolak = $status === 'ditolak';
                        $showDitolakCard = $isDitolak && $isOwner;
                    @endphp
                    
                    <!-- Aspirasi Card -->
                    <div class="card-hover bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden animate-slide-in {{ $isDitolak ? 'card-ditolak' : '' }}"
                         data-aspirasi-id="{{ $aspirasi->id_aspirasi }}"
                         data-status="{{ $status }}"
                         data-owner="{{ $isOwner ? 'true' : 'false' }}">
                        <!-- Card Header -->
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-primary-100 to-secondary-100 flex items-center justify-center mr-4">
                                        <i class="fas fa-user text-primary-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-lg text-gray-800">
                                            {{ $aspirasi->pengaduan->siswa->nama ?? 'User' }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-0.5 flex items-center">
                                            <i class="fas fa-calendar-alt mr-2 text-primary-500"></i>
                                            {{ $aspirasi->created_at->format('d M Y • H:i') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <span class="status-badge {{ $statusInfo['class'] }}">
                                    <i class="fas {{ $statusInfo['icon'] }}"></i>
                                    {{ $statusInfo['text'] }}
                                </span>
                            </div>
                            
                            <!-- Preview Text -->
                            <p class="text-gray-600 leading-relaxed">
                                {{ $shortText }}{{ count($words) > 6 ? '...' : '' }}
                            </p>
                            
                            <!-- Show More Button -->
                            <div class="mt-4">
                                @auth
                                    <!-- Jika sudah login, tombol berfungsi normal -->
                                    <button 
                                        class="show-more-btn text-left"
                                        data-aspirasi-id="{{ $aspirasi->id_aspirasi }}">
                                        <span id="btn-text-{{ $aspirasi->id_aspirasi }}">Lihat Detail</span>
                                        <i class="fas fa-chevron-down text-xs ml-1" id="btn-icon-{{ $aspirasi->id_aspirasi }}"></i>
                                    </button>
                                @else
                                    <!-- Jika belum login, arahkan ke modal login -->
                                    <button 
                                        class="show-more-btn text-left open-login-modal">
                                        <span>Lihat Detail</span>
                                        <i class="fas fa-lock text-xs ml-1"></i>
                                        <span class="login-required-badge">(Login diperlukan)</span>
                                    </button>
                                @endauth
                            </div>
                        </div>
                        
                        <!-- Detail Section (Hanya bisa dilihat jika sudah login) -->
                        @auth
                        <div class="detail-section" id="detail-{{ $aspirasi->id_aspirasi }}">
                            <div class="px-6 py-6">
                                <!-- Pesan khusus untuk status ditolak yang bukan milik user -->
                                @if($isDitolak && !$isOwner && auth()->user()->role != 'admin' && auth()->user()->role != 'kepsek')
                                    <div class="warning-message mb-6">
                                        <div class="flex items-start">
                                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mr-3 mt-1"></i>
                                            <div>
                                                <h4 class="font-semibold text-yellow-800 mb-2">Akses Terbatas</h4>
                                                <p class="text-yellow-700 text-sm">
                                                    Anda tidak dapat melihat detail dari aspirasi ini karena statusnya ditolak dan bukan milik Anda.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Lokasi dan Kategori -->
                                    <div class="flex flex-wrap gap-3 mb-6">
                                        <span class="info-badge">
                                            <i class="fas fa-map-marker-alt text-primary-500 mr-2"></i>
                                            {{ $aspirasi->pengaduan->lokasi }}
                                        </span>
                                        <span class="info-badge">
                                            <i class="fas fa-tag text-secondary-500 mr-2"></i>
                                            {{ $aspirasi->pengaduan->kategori->nama_kategori }}
                                        </span>
                                    </div>
                                    
                                    <!-- Keterangan Lengkap -->
                                    <div class="mb-6">
                                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                                            <i class="fas fa-comment-alt text-primary-500 mr-2"></i>
                                            Detail Aspirasi
                                        </h4>
                                        <div class="bg-gray-50 rounded-2xl p-5">
                                            <p class="text-gray-700 leading-relaxed">
                                                {{ $aspirasi->pengaduan->keterangan }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Feedback Section -->
                                    @if($aspirasi->feedbacks->count() > 0)
                                    <div class="mt-8 pt-8 border-t border-gray-200">
                                        <h4 class="font-semibold text-gray-700 mb-4 flex items-center">
                                            <i class="fas fa-reply text-green-500 mr-2"></i>
                                            Feedback ({{ $aspirasi->feedbacks->count() }})
                                        </h4>
                                        
                                        @if($canViewFeedback)
                                            <!-- Bisa lihat feedback -->
                                            <div class="space-y-4">
                                                @foreach($aspirasi->feedbacks as $feedback)
                                                @php
                                                    // Ambil nama user yang memberi feedback
                                                    $namaPengirim = 'Staff Sekolah'; // Default
                                                    $roleClass = 'admin-feedback';
                                                    $roleBadgeClass = 'role-admin';
                                                    $roleBadgeText = 'Admin';
                                                    $iconClass = 'fa-user-shield';
                                                    
                                                    // Jika ada relasi user, ambil nama dari user yang memberi feedback
                                                    if ($feedback->user) {
                                                        $namaPengirim = $feedback->user->nama;
                                                        
                                                        // Tentukan class berdasarkan role
                                                        if ($feedback->user->role === 'kepsek') {
                                                            $roleClass = 'kepsek-feedback';
                                                            $roleBadgeClass = 'role-kepsek';
                                                            $roleBadgeText = 'Kepala Sekolah';
                                                            $iconClass = 'fa-user-tie';
                                                        } elseif ($feedback->user->role === 'admin') {
                                                            $roleBadgeText = 'Admin';
                                                            $iconClass = 'fa-user-shield';
                                                        }
                                                    }
                                                @endphp
                                                <div class="feedback-bubble {{ $roleClass }}">
                                                    <div class="flex items-center mb-3">
                                                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-secondary-400 to-secondary-600 flex items-center justify-center text-white text-sm mr-3">
                                                            <i class="fas {{ $iconClass }}"></i>
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="flex items-center justify-between">
                                                                <div>
                                                                    <div class="flex items-center gap-2 mb-1">
                                                                        <p class="font-semibold text-gray-800">
                                                                            {{ $namaPengirim }}
                                                                        </p>
                                                                        <span class="{{ $roleBadgeClass }} role-badge">
                                                                            <i class="fas {{ $iconClass }} text-xs"></i>
                                                                            {{ $roleBadgeText }}
                                                                        </span>
                                                                    </div>
                                                                    <p class="text-xs text-gray-500 mt-0.5">
                                                                        <i class="fas fa-clock mr-1"></i>
                                                                        {{ $feedback->created_at->format('d M Y H:i') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="text-gray-700">
                                                        {{ $feedback->isi }}
                                                    </p>
                                                </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <!-- Tidak bisa lihat feedback -->
                                            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-2xl p-6 text-center border border-yellow-200">
                                                <div class="flex flex-col items-center">
                                                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-yellow-100 to-yellow-200 flex items-center justify-center mb-4">
                                                        <i class="fas fa-lock text-yellow-600 text-xl"></i>
                                                    </div>
                                                    <h5 class="font-semibold text-yellow-800 text-lg mb-2">
                                                        Akses Terbatas
                                                    </h5>
                                                    <p class="text-yellow-700 text-sm mb-4">
                                                        Hanya admin, kepala sekolah, dan pengirim aspirasi yang dapat melihat jawaban
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    @else
                                        <!-- Belum ada feedback -->
                                        <div class="mt-8 pt-8 border-t border-gray-200">
                                            <div class="flex flex-col items-center text-center py-4">
                                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                                    <i class="fas fa-comment-slash text-gray-400"></i>
                                                </div>
                                                <p class="text-gray-500">
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    Belum ada jawaban dari admin
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @endauth
                    </div>
                    @empty
                    <!-- No Results Message -->
                    <div class="col-span-2 text-center py-16">
                        <div class="max-w-md mx-auto">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-r from-primary-100 to-secondary-100 flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-inbox text-4xl text-primary-500"></i>
                            </div>
                            <h3 class="text-2xl font-semibold text-gray-700 mb-3">
                                @if(request()->search && request()->search != '')
                                    Tidak ditemukan aspirasi untuk "{{ request()->search }}"
                                @else
                                    Belum ada aspirasi
                                @endif
                            </h3>
                            <p class="text-gray-600 mb-6">
                                @if(request()->search && request()->search != '')
                                    Coba gunakan kata kunci lain atau hapus filter pencarian
                                @else
                                    Jadilah yang pertama mengirimkan aspirasi untuk perbaikan sekolah!
                                @endif
                            </p>
                            @if(!(request()->search && request()->search != ''))
                            <a href="{{ route('home') }}#form-pengaduan" 
                               class="inline-flex items-center px-6 py-3 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-lg">
                                <i class="fas fa-plus-circle mr-2"></i>
                                Buat Pengaduan Baru
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination with Green Theme -->
                @if($aspirasis->hasPages())
                <div class="flex flex-col items-center justify-center mt-12 space-y-4" id="paginationContainer">
                    <!-- Pagination Info -->
                    <div class="text-gray-600 text-sm">
                        Halaman <span class="font-semibold text-green-600">{{ $aspirasis->currentPage() }}</span> dari 
                        <span class="font-semibold text-green-600">{{ $aspirasis->lastPage() }}</span>
                    </div>
                    
                    <!-- Pagination Controls -->
                    <nav class="flex items-center space-x-2">
                        <!-- Previous Button -->
                        @if($aspirasis->onFirstPage())
                        <span class="pagination-item disabled">
                            <span class="pagination-link">
                                <i class="fas fa-chevron-left mr-1 text-gray-400"></i>
                            </span>
                        </span>
                        @else
                        <a href="{{ $aspirasis->previousPageUrl() }}" class="pagination-item">
                            <span class="pagination-link pagination-arrow">
                                <i class="fas fa-chevron-left mr-1"></i>
                            </span>
                        </a>
                        @endif
                        
                        <!-- Page Numbers -->
                        @php
                            $current = $aspirasis->currentPage();
                            $last = $aspirasis->lastPage();
                            $start = max($current - 2, 1);
                            $end = min($current + 2, $last);
                            
                            if($current <= 3) {
                                $end = min(5, $last);
                            }
                            if($current >= $last - 2) {
                                $start = max($last - 4, 1);
                            }
                        @endphp
                        
                        @if($start > 1)
                        <a href="{{ $aspirasis->url(1) }}" class="pagination-item">
                            <span class="pagination-link">1</span>
                        </a>
                        @if($start > 2)
                        <span class="pagination-item disabled">
                            <span class="pagination-link">...</span>
                        </span>
                        @endif
                        @endif
                        
                        @for($i = $start; $i <= $end; $i++)
                        @if($i == $current)
                        <span class="pagination-item active">
                            <span class="pagination-link">{{ $i }}</span>
                        </span>
                        @else
                        <a href="{{ $aspirasis->url($i) }}" class="pagination-item">
                            <span class="pagination-link">{{ $i }}</span>
                        </a>
                        @endif
                        @endfor
                        
                        @if($end < $last)
                        @if($end < $last - 1)
                        <span class="pagination-item disabled">
                            <span class="pagination-link">...</span>
                        </span>
                        @endif
                        <a href="{{ $aspirasis->url($last) }}" class="pagination-item">
                            <span class="pagination-link">{{ $last }}</span>
                        </a>
                        @endif
                        
                        <!-- Next Button -->
                        @if($aspirasis->hasMorePages())
                        <a href="{{ $aspirasis->nextPageUrl() }}" class="pagination-item"> <!-- PERBAIKAN DI SINI: $aspirasi-> menjadi $aspirasis-> -->
                            <span class="pagination-link pagination-arrow">
                                <i class="fas fa-chevron-right ml-1"></i>
                            </span>
                        </a>
                        @else
                        <span class="pagination-item disabled">
                            <span class="pagination-link">
                                <i class="fas fa-chevron-right ml-1 text-gray-400"></i>
                            </span>
                        </span>
                        @endif
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </main>

    <footer class="bg-gradient-to-r from-gray-900 to-dark text-white py-5">
        <div class="border-t border-gray-800">
            <div class="text-center">
                <p class="text-white text-xs">
                    © SUARAKITA - Sistem Manajemen Aduan Sekolah. Semua hak dilindungi.
                </p>
            </div>
        </div>
    </footer>

    <script>
        // State untuk melacak card yang terbuka
        let openDetailId = null;
        
        // Fungsi untuk toggle detail
        function toggleDetail(aspirasiId) {
            console.log('Toggling detail for:', aspirasiId);
            
            const detailSection = document.getElementById('detail-' + aspirasiId);
            const buttonText = document.getElementById('btn-text-' + aspirasiId);
            const buttonIcon = document.getElementById('btn-icon-' + aspirasiId);
            const cardElement = document.querySelector(`[data-aspirasi-id="${aspirasiId}"]`);
            
            if (!detailSection) {
                console.error('Detail section not found:', 'detail-' + aspirasiId);
                return;
            }
            
            // Cek status dan kepemilikan
            const status = cardElement.getAttribute('data-status');
            const isOwner = cardElement.getAttribute('data-owner') === 'true';
            
            // Jika status ditolak dan bukan pemilik, jangan buka detail
            if (status === 'ditolak' && !isOwner) {
                alert('Anda tidak dapat melihat detail aspirasi yang ditolak kecuali jika itu milik Anda.');
                return;
            }
            
            // Jika klik card yang sama, tutup
            if (openDetailId === aspirasiId) {
                detailSection.classList.remove('show');
                if (buttonText) buttonText.textContent = 'Lihat Detail';
                if (buttonIcon) buttonIcon.className = 'fas fa-chevron-down text-xs ml-1';
                openDetailId = null;
            } else {
                // Tutup detail yang sebelumnya terbuka
                if (openDetailId) {
                    const prevDetail = document.getElementById('detail-' + openDetailId);
                    const prevButtonText = document.getElementById('btn-text-' + openDetailId);
                    const prevButtonIcon = document.getElementById('btn-icon-' + openDetailId);
                    
                    if (prevDetail) {
                        prevDetail.classList.remove('show');
                        if (prevButtonText) prevButtonText.textContent = 'Lihat Detail';
                        if (prevButtonIcon) prevButtonIcon.className = 'fas fa-chevron-down text-xs ml-1';
                    }
                }
                
                // Buka detail yang baru
                detailSection.classList.add('show');
                if (buttonText) buttonText.textContent = 'Tutup Detail';
                if (buttonIcon) buttonIcon.className = 'fas fa-chevron-up text-xs ml-1';
                openDetailId = aspirasiId;
                
                // Scroll ke detail section jika perlu
                setTimeout(() => {
                    detailSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            }
        }
        
        // Fungsi untuk membuka modal login
        function openLoginForDetails() {
            const loginButton = document.getElementById('openLoginModal');
            if (loginButton) {
                loginButton.click();
            }
        }
        
        // User menu toggle
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userMenu = document.getElementById('userMenu');
        
        if (userMenuBtn && userMenu) {
            userMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userMenu.classList.toggle('hidden');
            });
            
            // Close menu when clicking outside
            document.addEventListener('click', function() {
                userMenu.classList.add('hidden');
            });
            
            // Prevent closing when clicking inside menu
            userMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
        
        // Live Search Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const clearSearchBtn = document.getElementById('clearSearch');
            const clearSearchResultsBtn = document.getElementById('clearSearchResults');
            const searchResultsIndicator = document.getElementById('searchResultsIndicator');
            const searchQueryText = document.getElementById('searchQueryText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const aspirasiGrid = document.getElementById('aspirasiGrid');
            const paginationContainer = document.getElementById('paginationContainer');
            
            let searchTimeout;
            let isLoading = false;
            
            // Fungsi untuk melakukan pencarian
            function performSearch(searchTerm) {
                if (isLoading) return;
                
                isLoading = true;
                loadingSpinner.classList.remove('hidden');
                
                // Update URL tanpa reload halaman
                const url = new URL(window.location);
                if (searchTerm.trim()) {
                    url.searchParams.set('search', searchTerm);
                } else {
                    url.searchParams.delete('search');
                }
                window.history.pushState({}, '', url);
                
                // Update UI untuk menunjukkan pencarian
                if (searchTerm.trim() !== '') {
                    searchResultsIndicator.classList.remove('hidden');
                    searchQueryText.textContent = `Pencarian: "${searchTerm}"`;
                    clearSearchBtn.classList.remove('hidden');
                } else {
                    searchResultsIndicator.classList.add('hidden');
                    clearSearchBtn.classList.add('hidden');
                }
                
                // Kirim request AJAX
                fetch(`${url.pathname}?${url.searchParams.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Parse HTML response
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Update grid dengan hasil pencarian
                    const newGrid = doc.getElementById('aspirasiGrid');
                    if (newGrid) {
                        aspirasiGrid.innerHTML = newGrid.innerHTML;
                    }
                    
                    // Update pagination
                    const newPagination = doc.getElementById('paginationContainer');
                    if (newPagination) {
                        paginationContainer.innerHTML = newPagination.innerHTML;
                    } else {
                        paginationContainer.innerHTML = '';
                    }
                    
                    // Highlight teks pencarian di nama siswa
                    if (searchTerm.trim()) {
                        highlightSearchResults(searchTerm);
                    }
                    
                    // Re-inisialisasi event listeners untuk detail cards
                    reinitializeAllEventListeners();
                })
                .catch(error => {
                    console.error('Error:', error);
                    aspirasiGrid.innerHTML = `
                        <div class="col-span-2 text-center py-16">
                            <div class="max-w-md mx-auto">
                                <div class="w-24 h-24 rounded-full bg-gradient-to-r from-red-100 to-red-200 flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-exclamation-triangle text-4xl text-red-500"></i>
                                </div>
                                <h3 class="text-2xl font-semibold text-gray-700 mb-3">Terjadi Kesalahan</h3>
                                <p class="text-gray-600 mb-6">Gagal memuat hasil pencarian. Silakan coba lagi.</p>
                            </div>
                        </div>
                    `;
                })
                .finally(() => {
                    isLoading = false;
                    loadingSpinner.classList.add('hidden');
                });
            }
            
            // Fungsi untuk highlight hasil pencarian
            function highlightSearchResults(searchTerm) {
                const searchLower = searchTerm.toLowerCase();
                const namaElements = document.querySelectorAll('h3.font-bold.text-lg');
                
                namaElements.forEach(element => {
                    const nama = element.textContent.toLowerCase();
                    if (nama.includes(searchLower)) {
                        const regex = new RegExp(`(${searchTerm})`, 'gi');
                        element.innerHTML = element.textContent.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
                    }
                });
            }
            
            // Event listener untuk input pencarian
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.trim();
                
                // Clear timeout sebelumnya
                clearTimeout(searchTimeout);
                
                // Tampilkan tombol clear jika ada teks
                if (searchTerm !== '') {
                    clearSearchBtn.classList.remove('hidden');
                } else {
                    clearSearchBtn.classList.add('hidden');
                }
                
                // Lakukan pencarian otomatis setelah 500ms jika teks >= 3 karakter
                if (searchTerm.length >= 3 || searchTerm.length === 0) {
                    searchTimeout = setTimeout(() => {
                        performSearch(searchTerm);
                    }, 500);
                }
            });
            
            // Enter untuk immediate search
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimeout);
                    performSearch(this.value.trim());
                }
            });
            
            // Tombol clear search
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                clearSearchBtn.classList.add('hidden');
                performSearch('');
            });
            
            // Tombol clear search results
            if (clearSearchResultsBtn) {
                clearSearchResultsBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    clearSearchBtn.classList.add('hidden');
                    searchResultsIndicator.classList.add('hidden');
                    performSearch('');
                });
            }
            
            // Event delegation untuk tombol "Lihat Detail"
            function initializeEventDelegation() {
                // Event delegation untuk tombol lihat detail (sudah login)
                document.addEventListener('click', function(e) {
                    // Tombol lihat detail untuk user yang sudah login
                    const detailBtn = e.target.closest('.show-more-btn:not(.open-login-modal)');
                    if (detailBtn) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        const aspirasiId = detailBtn.getAttribute('data-aspirasi-id');
                        if (aspirasiId) {
                            toggleDetail(aspirasiId);
                        }
                    }
                    
                    // Tombol untuk user yang belum login
                    const loginBtn = e.target.closest('.show-more-btn.open-login-modal');
                    if (loginBtn) {
                        e.preventDefault();
                        e.stopPropagation();
                        openLoginForDetails();
                    }
                });
                
                // Event delegation untuk pagination links
                document.addEventListener('click', function(e) {
                    const paginationLink = e.target.closest('.pagination-link');
                    if (paginationLink && paginationLink.parentElement.tagName === 'A') {
                        e.preventDefault();
                        const href = paginationLink.parentElement.getAttribute('href');
                        if (href) {
                            window.location.href = href;
                        }
                    }
                });
            }
            
            // Inisialisasi event delegation
            initializeEventDelegation();
            
            // Initialize page
            console.log('Page loaded successfully');
            
            // Hide all detail sections by default
            document.querySelectorAll('.detail-section').forEach(section => {
                section.classList.remove('show');
            });
            
            // Add animation delay to cards
            const cards = document.querySelectorAll('.card-hover');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Highlight pencarian awal jika ada
            @if(request()->search && request()->search != '')
                setTimeout(() => {
                    highlightSearchResults('{{ request()->search }}');
                }, 100);
            @endif
            
            // Filter untuk menyembunyikan aspirasi ditolak yang bukan milik user
            filterDitolakAspirasi();
        });

        // Auto-hide success alert
        document.addEventListener('DOMContentLoaded', function () {
            const alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(() => {
                    alert.classList.add('opacity-0', '-translate-y-4');
                    setTimeout(() => alert.remove(), 500);
                }, 3000); // 3 detik
            }
        });
        
        // Fungsi untuk filter aspirasi ditolak
        function filterDitolakAspirasi() {
            const cards = document.querySelectorAll('.card-hover[data-status="ditolak"]');
            
            cards.forEach(card => {
                const isOwner = card.getAttribute('data-owner') === 'true';
                
                // Jika bukan pemilik, sembunyikan card
                if (!isOwner) {
                    card.style.display = 'none';
                }
            });
        }
        
        // Fungsi untuk inisialisasi ulang semua event listeners
        function reinitializeAllEventListeners() {
            console.log('Reinitializing event listeners...');
            
            // Hide all detail sections by default
            document.querySelectorAll('.detail-section').forEach(section => {
                section.classList.remove('show');
            });
            
            // Reset open detail state
            openDetailId = null;
            
            // Add animation delay to new cards
            const cards = document.querySelectorAll('.card-hover');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
            
            // Filter aspirasi ditolak untuk user non-pemilik
            filterDitolakAspirasi();
            
            console.log('Event listeners reinitialized successfully');
        }
    </script>
</body>
</html>