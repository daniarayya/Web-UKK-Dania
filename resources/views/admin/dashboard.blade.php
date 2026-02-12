@extends('layouts.admin')

@section('title', 'SUARAKITA')
@section('page-title', 'Dashboard Admin')
@section('page-description', 'Statistik dan ringkasan sistem aspirasi siswa')

@php
    // Helper functions untuk status
    function getStatusColor($item) {
        // Cek jika ini dari history
        if (isset($item->type) && $item->type === 'history') {
            if ($item->pengaduan && $item->pengaduan->aspirasi) {
                return getAspirasiStatusColor($item->pengaduan->aspirasi->status);
            }
            return 'bg-yellow-100';
        }

        // Jika dari pengaduan langsung
        if (!$item->aspirasi) {
            return 'bg-yellow-100';
        }

        return getAspirasiStatusColor($item->aspirasi->status);
    }

    function getAspirasiStatusColor($status) {
        switch($status) {
            case 'baru':
            case 'menunggu':
                return 'bg-yellow-100';
            case 'proses':
                return 'bg-blue-100';
            case 'selesai':
                return 'bg-green-100';
            case 'ditolak':
                return 'bg-red-100';
            default:
                return 'bg-gray-100';
        }
    }

    function getStatusIcon($item) {
        // Cek jika ini dari history
        if (isset($item->type) && $item->type === 'history') {
            if ($item->pengaduan && $item->pengaduan->aspirasi) {
                return getAspirasiStatusIcon($item->pengaduan->aspirasi->status);
            }
            return 'fa-clock text-yellow-600';
        }

        // Jika dari pengaduan langsung
        if (!$item->aspirasi) {
            return 'fa-clock text-yellow-600';
        }

        return getAspirasiStatusIcon($item->aspirasi->status);
    }

    function getAspirasiStatusIcon($status) {
        switch($status) {
            case 'baru':
            case 'menunggu':
                return 'fa-clock text-yellow-600';
            case 'proses':
                return 'fa-cog text-blue-600';
            case 'selesai':
                return 'fa-check-circle text-green-600';
            case 'ditolak':
                return 'fa-times-circle text-red-600';
            default:
                return 'fa-question text-gray-600';
        }
    }

    function getStatusBadgeClass($item) {
        // Cek jika ini dari history
        if (isset($item->type) && $item->type === 'history') {
            if ($item->pengaduan && $item->pengaduan->aspirasi) {
                return getAspirasiBadgeClass($item->pengaduan->aspirasi->status);
            }
            return 'status-menunggu';
        }

        // Jika dari pengaduan langsung
        if (!$item->aspirasi) {
            return 'status-menunggu';
        }

        return getAspirasiBadgeClass($item->aspirasi->status);
    }

    function getAspirasiBadgeClass($status) {
        switch($status) {
            case 'baru':
            case 'menunggu':
                return 'status-menunggu';
            case 'proses':
                return 'status-proses';
            case 'selesai':
                return 'status-selesai';
            case 'ditolak':
                return 'status-ditolak';
            default:
                return '';
        }
    }

    function getStatusText($item) {
        // Cek jika ini dari history
        if (isset($item->type) && $item->type === 'history') {
            if ($item->pengaduan && $item->pengaduan->aspirasi) {
                return ucfirst($item->pengaduan->aspirasi->status);
            }
            return 'Baru';
        }

        // Jika dari pengaduan langsung
        if (!$item->aspirasi) {
            return 'Baru';
        }

        return ucfirst($item->aspirasi->status);
    }

    function getAktivitasDescription($item) {
        if (isset($item->type) && $item->type === 'history') {
            return $item->keterangan;
        }
        return 'Mengajukan aspirasi baru';
    }

    function getSiswaName($item) {
        if (isset($item->type) && $item->type === 'history') {
            return $item->pengaduan->siswa->nama ?? 'Siswa';
        }
        return $item->siswa->nama ?? 'Siswa';
    }
@endphp

@section('content')
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">

        <!-- Aspirasi Baru -->
        <a href="{{ route('admin.aspirasi.masuk.index') }}" class="block">
            <div class="card-hover bg-white rounded-lg shadow-md p-3 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs md:text-sm">Aspirasi Baru</p>
                        <h3 class="text-xl md:text-2xl font-bold text-yellow-600 mt-1">{{ number_format($aspirasiBaru) }}</h3>
                        <p class="text-yellow-600 text-xs mt-1">
                            <i class="fas fa-hourglass-start mr-1 text-xs"></i>Belum diverifikasi
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-hourglass-start text-yellow-600 text-sm"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Aspirasi Menunggu -->
        <a href="{{ route('admin.aspirasi.index') }}" class="block">
            <div class="card-hover bg-white rounded-lg shadow-md p-3 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs md:text-sm">Menunggu</p>
                        <h3 class="text-xl md:text-2xl font-bold text-orange-600 mt-1">{{ number_format($aspirasiMenunggu) }}</h3>
                        <p class="text-orange-600 text-xs mt-1">
                            <i class="fas fa-clock mr-1 text-xs"></i>Menunggu tindakan
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600 text-sm"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Aspirasi Proses -->
        <a href="{{ route('admin.feedback.index') }}" class="block">
            <div class="card-hover bg-white rounded-lg shadow-md p-3 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs md:text-sm">Diproses</p>
                        <h3 class="text-xl md:text-2xl font-bold text-blue-600 mt-1">{{ number_format($aspirasiProses) }}</h3>
                        <p class="text-blue-600 text-xs mt-1">
                            <i class="fas fa-cog mr-1 text-xs"></i>Sedang diproses
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-cog text-blue-600 text-sm"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Aspirasi Selesai -->
        <a href="{{ route('admin.history.index') }}" class="block">
            <div class="card-hover bg-white rounded-lg shadow-md p-3 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-xs md:text-sm">Selesai</p>
                        <h3 class="text-xl md:text-2xl font-bold text-green-600 mt-1">{{ number_format($aspirasiSelesai) }}</h3>
                        <p class="text-green-600 text-xs mt-1">
                            <i class="fas fa-check-circle mr-1 text-xs"></i>Telah diselesaikan
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-sm"></i>
                    </div>
                </div>
            </div>
        </a>

    </div>

    <!-- Charts and Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 md:mb-8">

        <!-- Chart Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-4 md:mb-6">
                    <h3 class="font-bold text-gray-800 text-lg md:text-xl">Statistik Aspirasi 7 Hari Terakhir</h3>
                    <span class="text-sm text-gray-500">{{ now()->subDays(6)->format('d M') }} - {{ now()->format('d M Y') }}</span>
                </div>

                <!-- Chart Canvas -->
                <div class="h-64 md:h-72">
                    <canvas id="aspirasiChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-4 md:mb-6">
                <h3 class="font-bold text-gray-800 text-lg md:text-xl">Aktivitas Terbaru</h3>
                <a href="{{ route('admin.history.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Lihat semua
                </a>
            </div>

            <div class="space-y-4">
                @forelse($aktivitasTerbaru as $aktivitas)
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ getStatusColor($aktivitas) }}">
                        <i class="fas {{ getStatusIcon($aktivitas) }} text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800">
                            <span class="font-medium">{{ getSiswaName($aktivitas) }}</span>
                            <span class="text-gray-600">{{ getAktivitasDescription($aktivitas) }}</span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $aktivitas->created_at->diffForHumans() }}
                            •
                            <span class="{{ getStatusBadgeClass($aktivitas) }}">
                                {{ getStatusText($aktivitas) }}
                            </span>
                        </p>

                        @if(isset($aktivitas->type) && $aktivitas->type === 'history' && $aktivitas->pengaduan)
                        <p class="text-xs text-gray-600 mt-1 truncate" title="{{ $aktivitas->pengaduan->isi_laporan }}">
                            {{ Str::limit($aktivitas->pengaduan->isi_laporan, 50) }}
                        </p>
                        @elseif(isset($aktivitas->isi_laporan))
                        <p class="text-xs text-gray-600 mt-1 truncate" title="{{ $aktivitas->isi_laporan }}">
                            {{ Str::limit($aktivitas->isi_laporan, 50) }}
                        </p>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-inbox text-2xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500 text-sm">Belum ada aktivitas</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .status-menunggu {
        background-color: #fef3c7;
        color: #92400e;
        border: 1px solid #fbbf24;
        padding: 2px 8px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-proses {
        background-color: #dbeafe;
        color: #1e40af;
        border: 1px solid #60a5fa;
        padding: 2px 8px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-selesai {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #34d399;
        padding: 2px 8px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-ditolak {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #f87171;
        padding: 2px 8px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Inisialisasi Chart untuk Dashboard
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('aspirasiChart');
        if (!ctx) return;

        const ctx2d = ctx.getContext('2d');

        // Data dari PHP
        const chartLabels = @json($chartLabels);
        const chartData = @json($chartData);

        // Warna gradient
        const gradient = ctx2d.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(34, 197, 94, 0.2)');
        gradient.addColorStop(1, 'rgba(34, 197, 94, 0)');

        // Buat chart
        const aspirasiChart = new Chart(ctx2d, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Jumlah Aspirasi',
                    data: chartData,
                    backgroundColor: gradient,
                    borderColor: '#22c55e',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: '#4b5563',
                            font: {
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleFont: {
                            family: "'Inter', sans-serif"
                        },
                        bodyFont: {
                            family: "'Inter', sans-serif"
                        },
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return `Aspirasi: ${context.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                family: "'Inter', sans-serif"
                            },
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                family: "'Inter', sans-serif"
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
