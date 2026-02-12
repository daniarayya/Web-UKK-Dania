<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SUARAKITA</title>
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Alpine.js -->
        <script src="//unpkg.com/alpinejs" defer></script>
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
            
            .form-input-focus:focus {
                box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
            }
            
            .hero-bg {
                background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
            }
            
            .section-bg {
                background: radial-gradient(circle at top right, rgba(34, 197, 94, 0.05) 0%, transparent 50%),
                            radial-gradient(circle at bottom left, rgba(59, 130, 246, 0.05) 0%, transparent 50%);
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
            /* Dropdown Animation */
            .rotate-180 {
                transform: rotate(180deg);
            }

            /* Custom scrollbar for dropdown */
            .dropdown-scroll {
                max-height: 300px;
                overflow-y: auto;
            }

            .dropdown-scroll::-webkit-scrollbar {
                width: 6px;
            }

            .dropdown-scroll::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }

            .dropdown-scroll::-webkit-scrollbar-thumb {
                background: #c1c1c1;
                border-radius: 10px;
            }

            /* Hover effects for dropdown items */
            .dropdown-item:hover {
                background: linear-gradient(90deg, #f0fdf4 0%, #eff6ff 100%);
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
                            <a href="{{ route('home') }}" class="flex items-center space-x-3 no-underline hover:opacity-90 transition-opacity duration-200">
                                <div class="relative">
                                    <div class="absolute -inset-1 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full blur opacity-30"></div>
                                    <div class="relative bg-white p-1 rounded-full">
                                        <img src="{{ asset('logo.png') }}" alt="Logo Sekolah" class="w-12 h-12 object-contain">
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
                                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                    <button @click="open = !open" class="flex items-center space-x-3 px-5 py-3 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-lg">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mb-1">
                                                <i class="fas fa-user text-sm"></i>
                                            </div>
                                            <span class="text-xs font-normal">{{ Str::limit(auth()->user()->username, 12) }}</span>
                                        </div>
                                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                                    </button>
                                    
                                    <!-- Dropdown Menu -->
                                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-50">
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

        <!-- Hero Section -->
        <section class="hero-bg py-16 md:py-24 overflow-hidden">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Hero Text -->
                    <div class="animate-slide-in">
                        <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-primary-100 to-secondary-100 text-primary-700 font-medium mb-6">
                            <i class="fas fa-bullhorn mr-2"></i>
                            Platform Pengaduan Resmi Sekolah
                        </div>
                        
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-poppins font-bold leading-tight mb-6">
                            <span class="block">Laporkan Kerusakan</span>
                            <span class="block">Fasilitas Sekolah</span>
                            <span class="block gradient-text">Dengan Mudah!</span>
                        </h1>
                        
                        <p class="text-lg text-gray-600 mb-8 max-w-xl">
                            Sistem pengaduan terintegrasi yang memudahkan siswa dan guru melaporkan kerusakan sarana sekolah secara online. Proses cepat, transparan, dan terpantau real-time.
                        </p>
                        
                        <div class="flex flex-wrap gap-4">
                            <a href="#form-pengaduan" class="inline-flex items-center px-6 py-4 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-lg btn-glow">
                                <i class="fas fa-plus-circle mr-3"></i>
                                Buat Pengaduan Baru
                            </a>
                            <a href="#features" class="inline-flex items-center px-6 py-4 rounded-xl bg-gradient-to-r from-secondary-500 to-secondary-600 text-white font-semibold hover:from-secondary-600 hover:to-secondary-700 transition-all duration-300 shadow-lg btn-secondary-glow">
                                <i class="fas fa-info-circle mr-3"></i>
                                Pelajari Lebih Lanjut
                            </a>
                        </div>
                    </div>
                    
                    <!-- Hero Visual - VERSI DIUBAH: FULL FOTO -->
                    <div class="relative">
                        <!-- Background Effects -->
                        <div class="absolute -top-10 -right-10 w-64 h-64 bg-gradient-to-r from-primary-300 to-secondary-300 rounded-full blur-3xl opacity-20"></div>
                        <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-gradient-to-r from-secondary-200 to-primary-200 rounded-full blur-3xl opacity-20"></div>
                        
                        <!-- Main Visual Card - DIUBAH: HANYA FOTO -->
                        <div class="relative rounded-3xl shadow-2xl overflow-hidden border-4 border-white">
                            <!-- Foto Utama - Full Size -->
                            <div class="w-full h-[400px] md:h-[450px] rounded-2xl overflow-hidden">
                                <img src="{{ asset('smk.jpeg') }}" 
                                     alt="SMK Maskumambang 2 Dukun" 
                                     class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                            </div>
                            
                            <!-- Floating Elements (tetap ada untuk efek) -->
                            <div class="absolute top-6 left-6 w-16 h-16 bg-white rounded-2xl shadow-lg flex items-center justify-center animate-bounce-slow">
                                <i class="fas fa-wrench text-primary-600 text-xl"></i>
                            </div>
                            <div class="absolute bottom-6 right-6 w-14 h-14 bg-white rounded-2xl shadow-lg flex items-center justify-center animate-bounce-slow" style="animation-delay: 0.5s;">
                                <i class="fas fa-check text-green-500 text-lg"></i>
                            </div>
                            
                            <!-- School Badge -->
                            <div class="absolute top-6 right-6">
                                <div class="bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-4 py-2 rounded-xl font-bold shadow-lg">
                                    SMK MASKUMAMBANG 2 DUKUN GRESIK
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Visual Elements -->
                        <div class="absolute -z-10 top-10 right-10">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-r from-primary-400 to-secondary-400 opacity-10"></div>
                        </div>
                        <div class="absolute -z-10 bottom-20 left-5">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-r from-secondary-400 to-primary-400 opacity-10"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics Section -->
        <section class="py-12 bg-gradient-to-b from-white to-gray-50">
            <div class="container mx-auto px-6">
                <div class="text-center mb-10">
                    <h2 class="text-3xl md:text-4xl font-poppins font-bold mb-3">Statistik Pengaduan</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Lihat bagaimana sistem kami membantu perbaikan fasilitas sekolah</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Pengaduan Diterima -->
                    <div class="bg-white p-5 rounded-2xl shadow-lg border border-gray-100 text-center card-hover">
                        <div class="w-14 h-14 mx-auto mb-4 rounded-xl bg-gradient-to-r from-primary-100 to-primary-200 flex items-center justify-center">
                            <i class="fas fa-inbox text-primary-600 text-2xl"></i>
                        </div>
                        <div class="text-4xl font-bold text-primary-600 mb-1">{{ $stats['total_pengaduan'] ?? 0 }}</div>
                        <p class="text-gray-800 font-semibold">Pengaduan Diterima</p>
                        <p class="text-gray-500 text-xs mt-1">Total pengaduan yang masuk ke sistem</p>
                    </div>
                    
                    <!-- Pengaduan Terbalas -->
                    <div class="bg-white p-5 rounded-2xl shadow-lg border border-gray-100 text-center card-hover">
                        <div class="w-14 h-14 mx-auto mb-4 rounded-xl bg-gradient-to-r from-secondary-100 to-secondary-200 flex items-center justify-center">
                            <i class="fas fa-reply text-secondary-600 text-2xl"></i>
                        </div>
                        <div class="text-4xl font-bold text-secondary-600 mb-1">{{ $stats['pengaduan_terbalas'] ?? 0 }}</div>
                        <p class="text-gray-800 font-semibold">Pengaduan Terbalas</p>
                        <p class="text-gray-500 text-xs mt-1">Pengaduan yang sudah mendapat respons</p>
                    </div>
                    
                    <!-- Pengaduan Selesai -->
                    <div class="bg-white p-5 rounded-2xl shadow-lg border border-gray-100 text-center card-hover">
                        <div class="w-14 h-14 mx-auto mb-4 rounded-xl bg-gradient-to-r from-green-100 to-green-200 flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                        <div class="text-4xl font-bold text-green-600 mb-1">{{ $stats['pengaduan_selesai'] ?? 0 }}</div>
                        <p class="text-gray-800 font-semibold">Pengaduan Selesai</p>
                        <p class="text-gray-500 text-xs mt-1">Pengaduan yang sudah selesai ditangani</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-16 bg-gradient-to-r from-primary-50 to-secondary-50">
            <div class="container mx-auto px-6">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-poppins font-bold mb-3">Keunggulan Layanan Pengaduan</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Platform pengaduan yang dirancang khusus untuk memudahkan pelaporan dan mempercepat perbaikan sarana sekolah.</p>
                </div>
                
                <!-- Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="card-hover bg-white p-8 rounded-3xl shadow-xl border border-gray-100">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center mb-6">
                            <i class="fas fa-bolt text-2xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Pengaduan Real-time</h3>
                        <p class="text-gray-600 mb-6">Laporan langsung masuk ke sistem dan dapat dipantau progresnya secara real-time oleh tim pemeliharaan.</p>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="card-hover bg-white p-8 rounded-3xl shadow-xl border border-gray-100">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-r from-secondary-500 to-secondary-600 flex items-center justify-center mb-6">
                            <i class="fas fa-chart-line text-2xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Pelacakan Lengkap</h3>
                        <p class="text-gray-600 mb-6">Pantau status pengaduan dari awal hingga selesai. Notifikasi otomatis saat ada perkembangan.</p>
                    </div>
                    
                    <!-- Feature 3 -->
                    <div class="card-hover bg-white p-8 rounded-3xl shadow-xl border border-gray-100">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-r from-accent to-accent-dark flex items-center justify-center mb-6">
                            <i class="fas fa-shield-alt text-2xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Data Terlindungi</h3>
                        <p class="text-gray-600 mb-6">Sistem keamanan tingkat tinggi melindungi identitas dan data pribadi Anda dengan enkripsi end-to-end.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Form Pengaduan -->
        <section id="form-pengaduan" class="py-16 section-bg">
            <div class="container mx-auto px-6">
                <div class="max-w-4xl mx-auto">
                    <!-- Section Header -->
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-poppins font-bold mb-4">Laporkan Kerusakan Sekarang</h2>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Isi form di bawah ini dengan lengkap dan akurat untuk memastikan pengaduan Anda segera ditindaklanjuti.</p>
                    </div>
                    
                    <!-- Status Message -->
                    <div id="statusMessage" class="hidden mb-8 p-6 rounded-2xl border-l-4 shadow-md"></div>
                    
                    <!-- Form Container -->
                    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
                        <div class="bg-gradient-to-r from-primary-500 to-secondary-500 p-6">
                            <h3 class="text-2xl font-bold text-white flex items-center">
                                <i class="fas fa-file-alt mr-3"></i>
                                Form Pengaduan Sarana Sekolah
                            </h3>
                        </div>
                        
                        <form id="complaintForm" 
                            action="{{ route('pengaduan.store') }}" 
                            method="POST" 
                            enctype="multipart/form-data"
                            class="p-8">
                            @csrf               
                            <!-- Lokasi dan Kategori -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                
                                <!-- Lokasi -->
                                <div>
                                    <label for="lokasi" class="block text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                        <span class="w-8 h-8 rounded-full bg-accent/20 text-accent flex items-center justify-center mr-3">
                                            <i class="fas fa-map-marker-alt text-sm"></i>
                                        </span>
                                        Lokasi Sarana 
                                    </label>

                                    <input type="text" id="lokasi" name="lokasi"
                                        class="w-full p-4 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition duration-300 form-input-focus"
                                        required>
                                </div>

                                <!-- Kategori -->
                                <div>
                                    <label for="kategori" class="block text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                        <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center mr-3">
                                            <i class="fas fa-tags text-sm"></i>
                                        </span>
                                        Kategori 
                                    </label>
                                    <select id="kategori" name="id_kategori"
                                        class="w-full p-4 rounded-xl border-2 border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition duration-300 form-input-focus appearance-none"
                                        required>
                                        <option value="" disabled selected>Pilih kategori</option>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id_kategori }}">
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Detail Aduan -->
                            <div class="mb-8">
                                <label for="aduan" class="block text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <span class="w-8 h-8 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center mr-3">
                                        <i class="fas fa-comment-dots text-sm"></i>
                                    </span>
                                    Detail Pengaduan 
                                </label>
                                <textarea id="aduan" name="keterangan"
                                    class="w-full p-4 rounded-2xl border-2 border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition duration-300 form-input-focus min-h-[180px]"
                                    required></textarea>
                                <div class="text-right text-xs text-gray-500 mt-1">
                                    <span id="charCount">0</span>/1000 karakter
                                </div>
                            </div>

                            <!-- Upload Foto - Versi Sederhana -->
                            <div class="mb-8">
                                <label class="block text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center mr-3">
                                        <i class="fas fa-camera text-sm"></i>
                                    </span>
                                    Foto Pendukung (Opsional)
                                </label>
                                <div class="flex items-center space-x-4">
                                    <div>
                                        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg" class="hidden">
                                        <label for="foto" class="inline-flex items-center px-5 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium hover:from-blue-600 hover:to-blue-700 transition-all duration-300 cursor-pointer shadow-md">
                                            <i class="fas fa-upload mr-2"></i>
                                            Pilih Foto
                                        </label>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <p>Format: JPG, JPEG, PNG</p>
                                        <p class="text-xs text-gray-500">Maksimal 2MB</p>
                                    </div>
                                </div>
                                <div id="selectedFile" class="text-sm text-green-600 mt-2 hidden">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    <span>File terpilih: </span>
                                    <span id="fileNameText"></span>
                                </div>
                            </div>

                            <!-- Pesan peringatan login -->
                            <div id="loginWarning" 
                                class="hidden mb-6 p-4 rounded-xl bg-red-100 text-red-700 text-center font-semibold">
                                ⚠️ Silakan login terlebih dahulu untuk mengirim pengaduan!
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                @php
                                    $isStudent = auth()->check() && auth()->user()->role === 'siswa';
                                @endphp

                                @if(auth()->check() && $isStudent)
                                    <!-- Hanya siswa yang bisa submit -->
                                    <button type="submit"
                                        class="inline-flex items-center px-8 py-4 rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold text-lg hover:from-primary-600 hover:to-primary-700 transition-all duration-300 shadow-xl btn-glow transform hover:scale-105">
                                        <i class="fas fa-paper-plane mr-3"></i>
                                        Kirim Pengaduan
                                    </button>
                                @elseif(auth()->check())
                                    <!-- Login tapi bukan siswa -->
                                    <button type="button"
                                        class="inline-flex items-center px-8 py-4 rounded-xl bg-gray-400 text-white font-bold text-lg cursor-not-allowed shadow-xl"
                                        id="nonStudentBtn">
                                        <i class="fas fa-user-graduate mr-3"></i>
                                        Kirim Pengaduan
                                    </button>
                                    <p class="text-sm text-gray-600 mt-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Hanya siswa yang dapat mengirim pengaduan
                                    </p>
                                @else
                                    <!-- Belum login -->
                                    <button type="button" id="btnLoginAlert"
                                        class="inline-flex items-center px-8 py-4 rounded-xl bg-gray-400 text-white font-bold text-lg cursor-not-allowed shadow-xl">
                                        <i class="fas fa-lock mr-3"></i>
                                        Kirim Pengaduan
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

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
            // Karakter counter untuk textarea
            document.getElementById('aduan').addEventListener('input', function() {
                const charCount = this.value.length;
                document.getElementById('charCount').textContent = charCount;
                
                // Jika melebihi 500 karakter, potong teks
                if (charCount > 1000) {
                    this.value = this.value.substring(0, 1000);
                    document.getElementById('charCount').textContent = 1000;
                    document.getElementById('charCount').classList.add('text-red-1000');
                } else {
                    document.getElementById('charCount').classList.remove('text-red-1000');
                }
            });
            
            // Animasi saat scroll ke section
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
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

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const dropdowns = document.querySelectorAll('[x-data]');
                dropdowns.forEach(dropdown => {
                    if (!dropdown.contains(event.target)) {
                        dropdown.__x.$data.open = false;
                    }
                });
            });

            // Jika tombol kirim ditekan saat belum login
            const btnLoginAlert = document.getElementById('btnLoginAlert');
            if(btnLoginAlert){
                btnLoginAlert.addEventListener('click', function(){
                    const warning = document.getElementById('loginWarning');
                    warning.classList.remove('hidden');
                    warning.scrollIntoView({behavior: 'smooth'});
                });
            }

            const nonStudentBtn = document.getElementById('nonStudentBtn');
            if(nonStudentBtn){
                nonStudentBtn.addEventListener('click', function(){
                    alert('Maaf, hanya siswa yang dapat mengirim pengaduan. Admin dan Kepala Sekolah dapat mengelola pengaduan melalui dashboard.');
                });
            }

            // Tampilkan nama file yang dipilih
            const fotoInput = document.getElementById('foto');
            const selectedFileDiv = document.getElementById('selectedFile');
            const fileNameText = document.getElementById('fileNameText');

            if (fotoInput) {
                fotoInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        
                        // Validasi ukuran file
                        if (file.size > 2 * 1024 * 1024) {
                            alert('Ukuran file maksimal 2MB!');
                            this.value = '';
                            selectedFileDiv.classList.add('hidden');
                            return;
                        }
                        
                        // Validasi tipe file
                        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                        if (!validTypes.includes(file.type)) {
                            alert('Format file harus JPG, JPEG, atau PNG!');
                            this.value = '';
                            selectedFileDiv.classList.add('hidden');
                            return;
                        }
                        
                        // Tampilkan nama file
                        fileNameText.textContent = file.name;
                        selectedFileDiv.classList.remove('hidden');
                    }
                });
            }

            // Validasi form sebelum submit
            const complaintForm = document.getElementById('complaintForm');
            if (complaintForm) {
                complaintForm.addEventListener('submit', function(e) {
                    // Validasi file jika ada
                    if (fotoInput && fotoInput.files.length > 0) {
                        const file = fotoInput.files[0];
                        
                        // Validasi ukuran file
                        if (file.size > 2 * 1024 * 1024) {
                            e.preventDefault();
                            alert('Ukuran file maksimal 2MB!');
                            return;
                        }
                        
                        // Validasi tipe file
                        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                        if (!validTypes.includes(file.type)) {
                            e.preventDefault();
                            alert('Format file harus JPG, JPEG, atau PNG!');
                            return;
                        }
                    }
                    
                    // Validasi karakter teks
                    const charCount = document.getElementById('charCount').textContent;
                    if (parseInt(charCount) > 1000) {
                        e.preventDefault();
                        alert('Detail pengaduan maksimal 1000 karakter!');
                        return;
                    }
                });
            }
        </script>

        @if(session('success'))
            <script>
                window.onload = function(){
                    document.getElementById('form-pengaduan').scrollIntoView({behavior: 'smooth'});
                }
            </script>
        @endif
    </body>
</html>