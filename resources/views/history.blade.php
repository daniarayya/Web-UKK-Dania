<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUARAKITA - Aspirasi</title>
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
                    },
                    screens: {
                        'xs': '475px',
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
            padding: 0.3rem 1rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }
        
        @media (min-width: 640px) {
            .status-badge {
                padding: 0.4rem 1.2rem;
                font-size: 0.8rem;
                gap: 6px;
            }
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
            padding: 0.8rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        @media (min-width: 640px) {
            .feedback-bubble {
                padding: 1rem;
            }
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
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        @media (min-width: 768px) {
            .logo-container {
                width: 48px;
                height: 48px;
            }
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
            padding: 6px 0;
            font-size: 0.85rem;
        }
        
        @media (min-width: 640px) {
            .show-more-btn {
                padding: 8px 0;
                font-size: 0.9rem;
            }
        }
        
        .show-more-btn:hover {
            color: #2563eb;
            transform: translateX(2px);
        }
        
        /* Category and location badges */
        .info-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.3rem 0.8rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 500;
            background: #f8fafc;
            color: #4b5563;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        @media (min-width: 640px) {
            .info-badge {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }
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
            min-width: 32px;
            height: 32px;
            padding: 0 8px;
            border-radius: 8px;
            background: white;
            color: #4b5563;
            font-weight: 500;
            font-size: 0.8rem;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }
        
        @media (min-width: 640px) {
            .pagination-link {
                min-width: 40px;
                height: 40px;
                padding: 0 12px;
                border-radius: 10px;
                font-size: 0.9rem;
            }
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
            font-size: 0.6rem;
            color: #6b7280;
            margin-left: 4px;
            display: inline-flex;
            align-items: center;
            gap: 2px;
        }
        
        @media (min-width: 640px) {
            .login-required-badge {
                font-size: 0.7rem;
                margin-left: 8px;
                gap: 3px;
            }
        }
        
        /* Badge warna berdasarkan role */
        .role-badge {
            padding: 0.15rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.6rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 2px;
            white-space: nowrap;
        }
        
        @media (min-width: 640px) {
            .role-badge {
                padding: 0.25rem 0.75rem;
                font-size: 0.75rem;
                gap: 4px;
            }
        }
        
        .role-admin {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
        
        .role-kepsek {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 1px solid #fcd34d;
        }
        
        .hidden-detail-message {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #fcd34d;
            border-radius: 1rem;
            padding: 1rem;
            margin: 0.5rem 0;
        }
        
        @media (min-width: 640px) {
            .hidden-detail-message {
                padding: 1.5rem;
                margin: 1rem 0;
            }
        }
        
        .warning-message {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1px solid #fde68a;
            border-radius: 1rem;
            padding: 1rem;
            margin: 0.5rem 0;
        }
        
        @media (min-width: 640px) {
            .warning-message {
                padding: 1.5rem;
                margin: 1rem 0;
            }
        }
        
        /* Responsive navbar adjustments */
        @media (max-width: 768px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .text-4xl {
                font-size: 2rem;
            }
            
            .text-5xl {
                font-size: 2.5rem;
            }
            
            .pulse-dot {
                width: 6px;
                height: 6px;
            }
        }
        
        /* User profile in navbar */
        .user-profile-btn {
            padding: 0.4rem 0.8rem;
        }
        
        @media (min-width: 640px) {
            .user-profile-btn {
                padding: 0.5rem 1rem;
            }
        }
        
        @media (min-width: 768px) {
            .user-profile-btn {
                padding: 0.75rem 1.25rem;
            }
        }
        
        /* Feedback avatar */
        .feedback-avatar {
            width: 32px;
            height: 32px;
        }
        
        @media (min-width: 640px) {
            .feedback-avatar {
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>
<body class="font-inter bg-light text-dark">
    @include('auth')
    
    <!-- Header & Navbar - Responsif -->
    <header class="sticky top-0 z-50">
        <nav class="glass-effect py-3 md:py-4 shadow-lg">
            <div class="container mx-auto px-4 sm:px-6">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <!-- Logo -->
                    <div class="flex items-center space-x-2 sm:space-x-3 mb-2 sm:mb-0">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2 sm:space-x-3 no-underline">
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
                                <h1 class="text-lg sm:text-xl md:text-2xl font-poppins font-bold">
                                    <span class="gradient-text">SUARAKITA</span>
                                </h1>
                                <div class="flex items-center space-x-1">
                                    <div class="pulse-dot"></div>
                                    <p class="text-[8px] sm:text-xs text-gray-600 truncate max-w-[120px] sm:max-w-none">
                                        Dari Kita, Untuk SKAMASKDA
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-4 md:gap-8">
                        <a href="{{ route('home') }}"
                        class="{{ request()->routeIs('home') 
                                ? 'text-primary-600 font-semibold border-b-2 border-primary-500' 
                                : 'text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200' }} text-xs sm:text-sm md:text-base">
                            <i class="fas fa-home mr-1 sm:mr-2"></i><span class="hidden xs:inline">Beranda</span>
                        </a>

                        <a href="{{ route('history.index') }}"
                        class="{{ request()->routeIs('history.*') 
                                ? 'text-primary-600 font-semibold border-b-2 border-primary-500' 
                                : 'text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200' }} text-xs sm:text-sm md:text-base">
                            <i class="fas fa-comment-alt mr-1 sm:mr-2"></i><span class="hidden xs:inline">Aspirasi</span>
                        </a>

                        @auth
                            <!-- User Profile Dropdown -->
                            <div class="relative">
                                <button id="userMenuBtn" class="user-profile-btn flex items-center space-x-1 sm:space-x-2 md:space-x-3 px-2 sm:px-3 md:px-5 py-1.5 sm:py-2 md:py-3 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-lg text-xs sm:text-sm">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-5 h-5 sm:w-6 sm:h-6 md:w-8 md:h-8 rounded-full bg-white/20 flex items-center justify-center mb-0.5 sm:mb-1">
                                            <i class="fas fa-user text-[0.5rem] sm:text-xs md:text-sm"></i>
                                        </div>
                                        <span class="text-[0.45rem] sm:text-[0.65rem] md:text-xs font-normal">{{ Str::limit(auth()->user()->username, 8) }}</span>
                                    </div>
                                    <i class="fas fa-chevron-down text-[0.5rem] sm:text-xs"></i>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div id="userMenu" class="absolute right-0 mt-2 w-36 sm:w-40 md:w-48 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-50 hidden">
                                    <div class="py-1 md:py-2">
                                        @php
                                            $role = auth()->user()->role;
                                            $dashboardRoute = match($role) {
                                                'admin' => 'admin.dashboard',
                                                'kepsek' => 'kepsek.dashboard',
                                                default => null
                                            };
                                        @endphp

                                        @if($dashboardRoute)
                                            <a href="{{ route($dashboardRoute) }}" class="flex items-center px-3 md:px-4 py-2 md:py-3 text-gray-700 hover:bg-gray-50 transition duration-150 text-xs md:text-sm">
                                                <i class="fas fa-tachometer-alt text-gray-500 mr-2 md:mr-3 text-xs md:text-sm"></i>
                                                <span>Dashboard</span>
                                            </a>
                                        @endif

                                        <form method="POST" action="{{ route('auth.logout') }}" class="@if(in_array(auth()->user()->role, ['admin', 'kepsek'])) border-t border-gray-100 @endif">
                                            @csrf
                                            <button type="submit" class="w-full text-left flex items-center px-3 md:px-4 py-2 md:py-3 text-red-600 hover:bg-red-50 transition duration-150 text-xs md:text-sm">
                                                <i class="fas fa-sign-out-alt mr-2 md:mr-3 text-xs md:text-sm"></i>
                                                <span>Logout</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <button id="openLoginModal" class="flex items-center space-x-1 sm:space-x-2 px-2 sm:px-3 md:px-5 py-1.5 sm:py-2 md:py-3 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-lg text-xs sm:text-sm md:text-base">
                                <i class="fas fa-sign-in-alt text-xs sm:text-sm"></i>
                                <span class="hidden xs:inline">Login</span>
                            </button>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="py-6 sm:py-8 bg-gradient-to-b from-white to-gray-50/50">
        <div class="container mx-auto px-4 sm:px-6">
            <!-- Header Section -->
            <div class="text-center mb-6 sm:mb-8 md:mb-12">
                <div class="inline-flex items-center px-3 py-1 sm:px-4 sm:py-2 rounded-full bg-gradient-to-r from-primary-100 to-secondary-100 text-primary-700 font-medium mb-2 sm:mb-3 md:mb-4 text-xs sm:text-sm">
                    <i class="fas fa-comments mr-1 sm:mr-2"></i>
                    Platform Aspirasi
                </div>
                
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-poppins font-bold mb-3 sm:mb-4 md:mb-6">
                    <span class="gradient-text">Riwayat Aspirasi</span>
                </h1>
                <p class="text-sm sm:text-base md:text-lg text-gray-600 max-w-3xl mx-auto px-2">
                    Lihat semua aspirasi yang telah dikirimkan oleh siswa. Pantau perkembangan dan berikan masukan untuk perbaikan sekolah.
                </p>
            </div>

            @if(session('success'))
                <div id="success-alert"
                    class="mb-4 sm:mb-6 p-3 sm:p-4 rounded-xl bg-green-100 border-l-4 border-green-500 text-green-700 shadow-md flex items-center space-x-2 sm:space-x-3 transition-all duration-500 text-sm sm:text-base">
                    <i class="fas fa-check-circle text-lg sm:text-xl"></i>
                    <span class="font-semibold">
                        {{ session('success') }}
                    </span>
                </div>
            @endif

            <!-- Search Bar -->
            <div class="mb-6 sm:mb-8">
                <div class="max-w-4xl mx-auto">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-sm sm:text-base"></i>
                        </div>
                        <input 
                            type="text" 
                            id="searchInput"
                            placeholder="Cari aspirasi berdasarkan nama siswa..." 
                            class="w-full pl-9 sm:pl-12 pr-10 sm:pr-12 py-2.5 sm:py-4 rounded-xl sm:rounded-2xl border border-gray-300 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent shadow-sm transition-all duration-300 text-sm sm:text-base"
                            autocomplete="off"
                            value="{{ request()->search ?? '' }}"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 sm:pr-4 flex items-center">
                            <button 
                                id="clearSearch"
                                class="text-gray-400 hover:text-gray-600 {{ request()->search ? '' : 'hidden' }}"
                                title="Hapus pencarian"
                            >
                                <i class="fas fa-times text-xs sm:text-sm"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mt-2 sm:mt-3 text-xs sm:text-sm text-gray-500 flex items-center">
                        <i class="fas fa-info-circle mr-1 sm:mr-2"></i>
                        <span>Tekan Enter untuk mencari atau ketik minimal 3 karakter</span>
                    </div>
                </div>
            </div>

            <!-- Search Results Indicator -->
            <div id="searchResultsIndicator" class="{{ request()->search ? '' : 'hidden' }} mb-4 sm:mb-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-gradient-to-r from-primary-50 to-secondary-50 rounded-xl p-3 sm:p-4 border border-primary-100 gap-3 sm:gap-0">
                    <div class="flex items-center">
                        <i class="fas fa-filter text-primary-500 mr-2 sm:mr-3 text-sm sm:text-base"></i>
                        <div>
                            <p class="font-semibold text-primary-700 text-sm sm:text-base">Hasil Pencarian</p>
                            <p class="text-xs sm:text-sm text-gray-600" id="searchQueryText">{{ request()->search ? 'Pencarian: "' . request()->search . '"' : '' }}</p>
                        </div>
                    </div>
                    <button 
                        id="clearSearchResults" 
                        class="flex items-center px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 transition duration-200 text-xs sm:text-sm"
                    >
                        <i class="fas fa-times mr-1 sm:mr-2"></i>
                        Hapus Filter
                    </button>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="hidden flex justify-center items-center py-8 sm:py-12">
                <div class="text-center">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 sm:h-12 sm:w-12 border-t-2 border-b-2 border-primary-500 mb-3 sm:mb-4"></div>
                    <p class="text-xs sm:text-sm text-gray-600">Memuat hasil pencarian...</p>
                </div>
            </div>

            <!-- Aspirasi Cards Grid -->
            <div id="aspirasiContainer">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-8 sm:mb-12" id="aspirasiGrid">
                    @forelse($aspirasis as $aspirasi)
                    @php
                        // Potong teks untuk preview
                        $aspirasiText = $aspirasi->pengaduan->keterangan;
                        $words = str_word_count($aspirasiText, 1);
                        $shortText = implode(' ', array_slice($words, 0, 5));
                        
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
                    <div class="card-hover bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-gray-100 overflow-hidden animate-slide-in {{ $isDitolak ? 'card-ditolak' : '' }}"
                         data-aspirasi-id="{{ $aspirasi->id_aspirasi }}"
                         data-status="{{ $status }}"
                         data-owner="{{ $isOwner ? 'true' : 'false' }}">
                        <!-- Card Header -->
                        <div class="p-4 sm:p-5 md:p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                            <div class="flex flex-col xs:flex-row justify-between items-start gap-3 mb-3 sm:mb-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-full bg-gradient-to-r from-primary-100 to-secondary-100 flex items-center justify-center mr-2 sm:mr-3 md:mr-4">
                                        <i class="fas fa-user text-primary-600 text-xs sm:text-sm md:text-base"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-sm sm:text-base md:text-lg text-gray-800">
                                            {{ $aspirasi->pengaduan->siswa->nama ?? 'User' }}
                                        </h3>
                                        <p class="text-[10px] sm:text-xs md:text-sm text-gray-500 mt-0.5 flex items-center">
                                            <i class="fas fa-calendar-alt mr-1 sm:mr-2 text-primary-500 text-[8px] sm:text-xs"></i>
                                            {{ $aspirasi->created_at->format('d M Y • H:i') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <span class="status-badge {{ $statusInfo['class'] }}">
                                    <i class="fas {{ $statusInfo['icon'] }} text-[8px] sm:text-xs"></i>
                                    {{ $statusInfo['text'] }}
                                </span>
                            </div>
                            
                            <!-- Preview Text -->
                            <p class="text-xs sm:text-sm md:text-base text-gray-600 leading-relaxed">
                                {{ $shortText }}{{ count($words) > 5 ? '...' : '' }}
                            </p>
                            
                            <!-- Show More Button -->
                            <div class="mt-2 sm:mt-3 md:mt-4">
                                @auth
                                    <!-- Jika sudah login, tombol berfungsi normal -->
                                    <button 
                                        class="show-more-btn"
                                        data-aspirasi-id="{{ $aspirasi->id_aspirasi }}">
                                        <span id="btn-text-{{ $aspirasi->id_aspirasi }}">Lihat Detail</span>
                                        <i class="fas fa-chevron-down text-[8px] sm:text-xs ml-1" id="btn-icon-{{ $aspirasi->id_aspirasi }}"></i>
                                    </button>
                                @else
                                    <!-- Jika belum login, arahkan ke modal login -->
                                    <button 
                                        class="show-more-btn open-login-modal">
                                        <span>Lihat Detail</span>
                                        <i class="fas fa-lock text-[8px] sm:text-xs ml-1"></i>
                                        <span class="login-required-badge">(Login)</span>
                                    </button>
                                @endauth
                            </div>
                        </div>
                        
                        <!-- Detail Section (Hanya bisa dilihat jika sudah login) -->
                        @auth
                        <div class="detail-section" id="detail-{{ $aspirasi->id_aspirasi }}">
                            <div class="px-4 sm:px-5 md:px-6 py-4 sm:py-5 md:py-6">
                                <!-- Pesan khusus untuk status ditolak yang bukan milik user -->
                                @if($isDitolak && !$isOwner && auth()->user()->role != 'admin' && auth()->user()->role != 'kepsek')
                                    <div class="warning-message">
                                        <div class="flex items-start">
                                            <i class="fas fa-exclamation-triangle text-yellow-600 text-sm sm:text-xl mr-2 sm:mr-3 mt-0.5"></i>
                                            <div>
                                                <h4 class="font-semibold text-yellow-800 text-xs sm:text-sm md:text-base mb-1 sm:mb-2">Akses Terbatas</h4>
                                                <p class="text-yellow-700 text-[10px] sm:text-xs md:text-sm">
                                                    Anda tidak dapat melihat detail aspirasi ini karena statusnya ditolak.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Lokasi dan Kategori -->
                                    <div class="flex flex-wrap gap-2 sm:gap-3 mb-4 sm:mb-5 md:mb-6">
                                        <span class="info-badge">
                                            <i class="fas fa-map-marker-alt text-primary-500 mr-1 sm:mr-2 text-[8px] sm:text-xs"></i>
                                            {{ $aspirasi->pengaduan->lokasi }}
                                        </span>
                                        <span class="info-badge">
                                            <i class="fas fa-tag text-secondary-500 mr-1 sm:mr-2 text-[8px] sm:text-xs"></i>
                                            {{ $aspirasi->pengaduan->kategori->nama_kategori }}
                                        </span>
                                    </div>
                                    
                                    <!-- Keterangan Lengkap -->
                                    <div class="mb-4 sm:mb-5 md:mb-6">
                                        <h4 class="font-semibold text-gray-700 text-xs sm:text-sm md:text-base mb-2 sm:mb-3 flex items-center">
                                            <i class="fas fa-comment-alt text-primary-500 mr-1 sm:mr-2 text-xs sm:text-sm"></i>
                                            Detail Aspirasi
                                        </h4>
                                        <div class="bg-gray-50 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-5">
                                            <p class="text-gray-700 text-xs sm:text-sm md:text-base leading-relaxed">
                                                {{ $aspirasi->pengaduan->keterangan }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Feedback Section -->
                                    @if($aspirasi->feedbacks->count() > 0)
                                    <div class="mt-6 sm:mt-7 md:mt-8 pt-5 sm:pt-6 md:pt-8 border-t border-gray-200">
                                        <h4 class="font-semibold text-gray-700 text-xs sm:text-sm md:text-base mb-3 sm:mb-4 flex items-center">
                                            <i class="fas fa-reply text-green-500 mr-1 sm:mr-2"></i>
                                            Feedback ({{ $aspirasi->feedbacks->count() }})
                                        </h4>
                                        
                                        @if($canViewFeedback)
                                            <!-- Bisa lihat feedback -->
                                            <div class="space-y-3 sm:space-y-4">
                                                @foreach($aspirasi->feedbacks as $feedback)
                                                @php
                                                    // Ambil nama user yang memberi feedback
                                                    $namaPengirim = 'Staff Sekolah';
                                                    $roleClass = 'admin-feedback';
                                                    $roleBadgeClass = 'role-admin';
                                                    $roleBadgeText = 'Admin';
                                                    $iconClass = 'fa-user-shield';
                                                    
                                                    if ($feedback->user) {
                                                        $namaPengirim = $feedback->user->nama;
                                                        
                                                        if ($feedback->user->role === 'kepsek') {
                                                            $roleClass = 'admin-feedback';
                                                            $roleBadgeClass = 'role-kepsek';
                                                            $roleBadgeText = 'Kepsek';
                                                            $iconClass = 'fa-user-tie';
                                                        }
                                                    }
                                                @endphp
                                                <div class="feedback-bubble {{ $roleClass }}">
                                                    <div class="flex items-start sm:items-center mb-2 sm:mb-3">
                                                        <div class="feedback-avatar w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-r from-secondary-400 to-secondary-600 flex items-center justify-center text-white mr-2 sm:mr-3">
                                                            <i class="fas {{ $iconClass }} text-[0.5rem] sm:text-xs"></i>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex flex-col xs:flex-row xs:items-center justify-between gap-1">
                                                                <div class="flex items-center gap-1 sm:gap-2 flex-wrap">
                                                                    <p class="font-semibold text-gray-800 text-xs sm:text-sm truncate max-w-[100px] sm:max-w-none">
                                                                        {{ $namaPengirim }}
                                                                    </p>
                                                                    <span class="{{ $roleBadgeClass }} role-badge">
                                                                        <i class="fas {{ $iconClass }} text-[0.4rem] sm:text-xs"></i>
                                                                        {{ $roleBadgeText }}
                                                                    </span>
                                                                </div>
                                                                <p class="text-[8px] sm:text-xs text-gray-500">
                                                                    <i class="fas fa-clock mr-1"></i>
                                                                    {{ $feedback->created_at->format('d M H:i') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="text-gray-700 text-xs sm:text-sm">
                                                        {{ $feedback->isi }}
                                                    </p>
                                                </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <!-- Tidak bisa lihat feedback -->
                                            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl sm:rounded-2xl p-4 sm:p-5 md:p-6 text-center border border-yellow-200">
                                                <div class="flex flex-col items-center">
                                                    <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-full bg-gradient-to-r from-yellow-100 to-yellow-200 flex items-center justify-center mb-2 sm:mb-3 md:mb-4">
                                                        <i class="fas fa-lock text-yellow-600 text-sm sm:text-base md:text-xl"></i>
                                                    </div>
                                                    <h5 class="font-semibold text-yellow-800 text-xs sm:text-sm md:text-base mb-1 sm:mb-2">
                                                        Akses Terbatas
                                                    </h5>
                                                    <p class="text-yellow-700 text-[10px] sm:text-xs md:text-sm">
                                                        Hanya admin dan pengirim yang dapat melihat jawaban
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    @else
                                        <!-- Belum ada feedback -->
                                        <div class="mt-6 sm:mt-7 md:mt-8 pt-5 sm:pt-6 md:pt-8 border-t border-gray-200">
                                            <div class="flex flex-col items-center text-center py-3 sm:py-4">
                                                <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-full bg-gray-100 flex items-center justify-center mb-2 sm:mb-3">
                                                    <i class="fas fa-comment-slash text-gray-400 text-xs sm:text-sm md:text-base"></i>
                                                </div>
                                                <p class="text-gray-500 text-xs sm:text-sm">
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
                    <div class="col-span-1 lg:col-span-2 text-center py-10 sm:py-12 md:py-16">
                        <div class="max-w-md mx-auto px-4">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full bg-gradient-to-r from-primary-100 to-secondary-100 flex items-center justify-center mx-auto mb-4 sm:mb-5 md:mb-6">
                                <i class="fas fa-inbox text-2xl sm:text-3xl md:text-4xl text-primary-500"></i>
                            </div>
                            <h3 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-700 mb-2 sm:mb-3">
                                @if(request()->search && request()->search != '')
                                    Tidak ditemukan aspirasi untuk "{{ request()->search }}"
                                @else
                                    Belum ada aspirasi
                                @endif
                            </h3>
                            <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-5 md:mb-6">
                                @if(request()->search && request()->search != '')
                                    Coba gunakan kata kunci lain atau hapus filter pencarian
                                @else
                                    Jadilah yang pertama mengirimkan aspirasi!
                                @endif
                            </p>
                            @if(!(request()->search && request()->search != ''))
                            <a href="{{ route('home') }}#form-pengaduan" 
                               class="inline-flex items-center px-4 sm:px-5 md:px-6 py-2 sm:py-2.5 md:py-3 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-lg text-xs sm:text-sm md:text-base">
                                <i class="fas fa-plus-circle mr-1 sm:mr-2"></i>
                                Buat Pengaduan Baru
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination with Green Theme -->
                @if($aspirasis->hasPages())
                <div class="flex flex-col items-center justify-center mt-8 sm:mt-10 md:mt-12 space-y-3 sm:space-y-4" id="paginationContainer">
                    <!-- Pagination Info -->
                    <div class="text-gray-600 text-xs sm:text-sm">
                        Halaman <span class="font-semibold text-green-600">{{ $aspirasis->currentPage() }}</span> dari 
                        <span class="font-semibold text-green-600">{{ $aspirasis->lastPage() }}</span>
                    </div>
                    
                    <!-- Pagination Controls -->
                    <nav class="flex items-center space-x-1 sm:space-x-2">
                        <!-- Previous Button -->
                        @if($aspirasis->onFirstPage())
                        <span class="pagination-item disabled">
                            <span class="pagination-link">
                                <i class="fas fa-chevron-left text-gray-400 text-xs sm:text-sm"></i>
                            </span>
                        </span>
                        @else
                        <a href="{{ $aspirasis->previousPageUrl() }}" class="pagination-item">
                            <span class="pagination-link pagination-arrow">
                                <i class="fas fa-chevron-left text-xs sm:text-sm"></i>
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
                            <span class="pagination-link text-xs sm:text-sm">1</span>
                        </a>
                        @if($start > 2)
                        <span class="pagination-item disabled">
                            <span class="pagination-link text-xs sm:text-sm">...</span>
                        </span>
                        @endif
                        @endif
                        
                        @for($i = $start; $i <= $end; $i++)
                        @if($i == $current)
                        <span class="pagination-item active">
                            <span class="pagination-link text-xs sm:text-sm">{{ $i }}</span>
                        </span>
                        @else
                        <a href="{{ $aspirasis->url($i) }}" class="pagination-item">
                            <span class="pagination-link text-xs sm:text-sm">{{ $i }}</span>
                        </a>
                        @endif
                        @endfor
                        
                        @if($end < $last)
                        @if($end < $last - 1)
                        <span class="pagination-item disabled">
                            <span class="pagination-link text-xs sm:text-sm">...</span>
                        </span>
                        @endif
                        <a href="{{ $aspirasis->url($last) }}" class="pagination-item">
                            <span class="pagination-link text-xs sm:text-sm">{{ $last }}</span>
                        </a>
                        @endif
                        
                        <!-- Next Button -->
                        @if($aspirasis->hasMorePages())
                        <a href="{{ $aspirasis->nextPageUrl() }}" class="pagination-item">
                            <span class="pagination-link pagination-arrow">
                                <i class="fas fa-chevron-right text-xs sm:text-sm"></i>
                            </span>
                        </a>
                        @else
                        <span class="pagination-item disabled">
                            <span class="pagination-link">
                                <i class="fas fa-chevron-right text-gray-400 text-xs sm:text-sm"></i>
                            </span>
                        </span>
                        @endif
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </main>

    <footer class="bg-gradient-to-r from-gray-900 to-dark text-white py-3 sm:py-4 md:py-5">
        <div class="border-t border-gray-800 pt-2 sm:pt-3">
            <div class="text-center">
                <p class="text-white text-[8px] sm:text-[10px] md:text-xs">
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
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userMenu = document.getElementById('userMenu');
            
            if (userMenuBtn && userMenu) {
                userMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenu.classList.toggle('hidden');
                });
                
                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userMenuBtn.contains(e.target) && !userMenu.contains(e.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }
            
            // Live Search Functionality
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
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const newGrid = doc.getElementById('aspirasiGrid');
                    if (newGrid) {
                        aspirasiGrid.innerHTML = newGrid.innerHTML;
                    }
                    
                    const newPagination = doc.getElementById('paginationContainer');
                    if (newPagination) {
                        paginationContainer.innerHTML = newPagination.innerHTML;
                    } else {
                        paginationContainer.innerHTML = '';
                    }
                    
                    if (searchTerm.trim()) {
                        highlightSearchResults(searchTerm);
                    }
                    
                    reinitializeAllEventListeners();
                })
                .catch(error => {
                    console.error('Error:', error);
                })
                .finally(() => {
                    isLoading = false;
                    loadingSpinner.classList.add('hidden');
                });
            }
            
            // Fungsi untuk highlight hasil pencarian
            function highlightSearchResults(searchTerm) {
                const searchLower = searchTerm.toLowerCase();
                const namaElements = document.querySelectorAll('h3.font-bold');
                
                namaElements.forEach(element => {
                    const nama = element.textContent.toLowerCase();
                    if (nama.includes(searchLower)) {
                        const regex = new RegExp(`(${searchTerm})`, 'gi');
                        element.innerHTML = element.textContent.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
                    }
                });
            }
            
            // Event listener untuk input pencarian
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.trim();
                    
                    clearTimeout(searchTimeout);
                    
                    if (searchTerm !== '') {
                        clearSearchBtn.classList.remove('hidden');
                    } else {
                        clearSearchBtn.classList.add('hidden');
                    }
                    
                    if (searchTerm.length >= 3 || searchTerm.length === 0) {
                        searchTimeout = setTimeout(() => {
                            performSearch(searchTerm);
                        }, 500);
                    }
                });
                
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        clearTimeout(searchTimeout);
                        performSearch(this.value.trim());
                    }
                });
            }
            
            // Tombol clear search
            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    clearSearchBtn.classList.add('hidden');
                    performSearch('');
                });
            }
            
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
                document.addEventListener('click', function(e) {
                    const detailBtn = e.target.closest('.show-more-btn:not(.open-login-modal)');
                    if (detailBtn) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        const aspirasiId = detailBtn.getAttribute('data-aspirasi-id');
                        if (aspirasiId) {
                            toggleDetail(aspirasiId);
                        }
                    }
                    
                    const loginBtn = e.target.closest('.show-more-btn.open-login-modal');
                    if (loginBtn) {
                        e.preventDefault();
                        e.stopPropagation();
                        openLoginForDetails();
                    }
                });
                
                document.addEventListener('click', function(e) {
                    const paginationLink = e.target.closest('.pagination-item a');
                    if (paginationLink) {
                        e.preventDefault();
                        const href = paginationLink.getAttribute('href');
                        if (href) {
                            window.location.href = href;
                        }
                    }
                });
            }
            
            // Inisialisasi event delegation
            initializeEventDelegation();
            
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
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Auto-hide success alert
            const alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(() => {
                    alert.classList.add('opacity-0', '-translate-y-4');
                    setTimeout(() => alert.remove(), 500);
                }, 3000);
            }
        });

        // Fungsi untuk inisialisasi ulang semua event listeners
        function reinitializeAllEventListeners() {
            console.log('Reinitializing event listeners...');
            
            document.querySelectorAll('.detail-section').forEach(section => {
                section.classList.remove('show');
            });
            
            openDetailId = null;
            
            const cards = document.querySelectorAll('.card-hover');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
            
            filterDitolakAspirasi();
            
            console.log('Event listeners reinitialized successfully');
        }
        
        // Fungsi untuk filter aspirasi ditolak
        function filterDitolakAspirasi() {
            const cards = document.querySelectorAll('.card-hover[data-status="ditolak"]');
            
            cards.forEach(card => {
                const isOwner = card.getAttribute('data-owner') === 'true';
                
                if (!isOwner) {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>