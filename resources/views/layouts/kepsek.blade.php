<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SUARAKITA')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        
        /* ===== STYLING UNTUK MODAL (DARI VIEW ANAK) ===== */
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
            z-index: 10001;
        }

        .lightbox-overlay.show {
            display: flex;
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
        
        /* ===== STYLING UNTUK FOTO BADGE ===== */
        .foto-badge {
            display: inline-block;
            padding: 4px 10px;
            background-color: #3b82f6;
            color: white;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 500;
            margin-top: 4px;
            border: 1px solid #1e40af;
        }
        
        /* ===== STYLING UNTUK STATUS BADGE ===== */
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            text-align: center;
            min-width: 100px;
            border: none;
        }
        
        /* ===== STYLING UNTUK ACTION BUTTONS ===== */
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
        
        .btn-view {
            background-color: #3b82f6;
            color: white;
        }
        
        .btn-view:hover {
            background-color: #2563eb;
        }
    </style>
</head>

<body class="font-inter bg-gray-50 min-h-screen">

<div class="flex h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg border-r border-gray-200 flex flex-col">
        <div class="p-6 border-b border-gray-200">
            <a href="{{ route('kepsek.dashboard')}}" class="flex items-center space-x-3">
                <img src="{{ asset('logo.png') }}" alt="Logo Sekolah" class="w-12 h-12 object-contain">
                <div>
                    <h1 class="text-2xl font-bold gradient-text">SUARAKITA</h1>
                </div>
            </a>
        </div>

        <nav class="flex-1 p-4">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('kepsek.dashboard') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg 
                       {{ request()->routeIs('kepsek.dashboard') ? 'sidebar-active' : 'hover:bg-gray-100' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('kepsek.laporan.index') }}"
                       class="flex items-center space-x-3 p-3 rounded-lg 
                       {{ request()->routeIs('kepsek.laporan.index') ? 'sidebar-active' : 'hover:bg-gray-100' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header dengan Breadcrumb -->
        <header class="bg-white border-b border-gray-200 px-6 py-3 flex justify-between items-center">
            <div>
                <!-- Breadcrumb -->
                <nav class="flex items-center text-sm text-gray-500 mb-1 space-x-2">
                    <a href="{{ route('home') }}" class="hover:text-green-600 font-medium flex items-center space-x-1">
                        <i class="fas fa-home text-xs"></i>
                        <span>Home</span>
                    </a>

                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                        <span class="text-gray-700 font-semibold">@yield('page-title','Dashboard Kepala Sekolah')</span>
                </nav>

                <h2 class="text-xl font-bold text-gray-800">@yield('page-title','Dashboard Kepala Sekolah')</h2>
                <p class="text-sm text-gray-600">@yield('page-description', 'Ringkasan laporan sistem')</p>
            </div>

            <!-- Profile -->
            <div class="relative">
                <button id="profile-dropdown-btn" 
                        class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <div class="text-left">
                        <span class="block font-medium text-gray-900" id="profile-name">{{ auth()->user()->nama ?? 'Kepala Sekolah' }}</span>
                        <span class="block text-xs text-gray-500" id="profile-username">
                            {{ auth()->user()->username ?? 'kepsek' }} • 
                            <span id="profile-role">Kepala Sekolah</span>
                        </span>
                    </div>
                    <i class="fas fa-chevron-down text-sm text-gray-500 ml-2"></i>
                </button>

                <div id="profile-dropdown" class="hidden">
                    <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-green-50 to-blue-50">
                        <p class="text-sm font-semibold text-gray-900" id="dropdown-name">{{ auth()->user()->nama ?? 'Kepala Sekolah' }}</p>
                        <p class="text-xs text-gray-600" id="dropdown-username">{{ auth()->user()->username ?? 'kepsek' }}</p>
                        <p class="text-xs font-medium text-blue-600 mt-1" id="dropdown-role">
                            Kepala Sekolah
                        </p>
                    </div>
                    
                    <div class="dropdown-content">
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
    
    document.addEventListener('DOMContentLoaded', function() {
        // Highlight current page in sidebar
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('aside nav a');
        
        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('sidebar-active');
            }
        });
        
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
                
                // Close lightbox
                const lightboxes = document.querySelectorAll('.lightbox-overlay.show');
                lightboxes.forEach(lightbox => lightbox.classList.remove('show'));
            }
        });
        
        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                e.target.classList.remove('show');
                hideNotification();
            }
            
            // Close lightbox
            if (e.target.classList.contains('lightbox-overlay')) {
                e.target.classList.remove('show');
            }
        });
        
        // Global modal close handlers
        const closeModalButtons = document.querySelectorAll('[id^="close-"]');
        closeModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('.modal-overlay') || document.getElementById('detail-modal');
                if (modal) modal.classList.remove('show');
            });
        });
        
        // Lightbox close handlers
        const lightboxCloseButtons = document.querySelectorAll('.lightbox-close');
        lightboxCloseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const lightbox = this.closest('.lightbox-overlay');
                if (lightbox) lightbox.classList.remove('show');
            });
        });
    });

    // Global function untuk membuka lightbox
    window.openLightbox = function(imageSrc) {
        const lightbox = document.createElement('div');
        lightbox.className = 'lightbox-overlay';
        lightbox.innerHTML = `
            <div class="lightbox-content">
                <button class="lightbox-close">
                    <i class="fas fa-times"></i>
                </button>
                <img src="${imageSrc}" alt="Preview" class="lightbox-image">
            </div>
        `;
        
        document.body.appendChild(lightbox);
        
        setTimeout(() => {
            lightbox.classList.add('show');
        }, 10);
        
        // Close handlers
        lightbox.querySelector('.lightbox-close').addEventListener('click', () => {
            lightbox.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(lightbox);
            }, 300);
        });
        
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                lightbox.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(lightbox);
                }, 300);
            }
        });
    };
</script>

@stack('scripts')

</body>
</html>