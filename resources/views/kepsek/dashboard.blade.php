@extends('layouts.kepsek')
@section('title','SUARAKITA')
@section('page-title','Dashboard Kepala Sekolah')
@section('content')

@section('header-title')
<div>
    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Dashboard Kepala Sekolah</h2>
    <p class="text-gray-600 text-sm md:text-base">Ringkasan sistem aspirasi siswa</p>
</div>
@endsection

<!-- Chart -->
<div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 mb-8">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
        <div class="mb-4 lg:mb-0">
            <h3 class="font-bold text-gray-800 text-lg">Statistik Aspirasi</h3>
            <p class="text-sm text-gray-600">Distribusi status aspirasi siswa</p>
        </div>
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                <span class="text-sm text-gray-600">Menunggu</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                <span class="text-sm text-gray-600">Diproses</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                <span class="text-sm text-gray-600">Selesai</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                <span class="text-sm text-gray-600">Ditolak</span>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="h-72">
            <canvas id="aspirasiChart"></canvas>
        </div>
        
        <div class="space-y-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Ringkasan Aspirasi</span>
                    @php
                        $totalAspirasi = $aspirasiMenunggu + $aspirasiProses + $aspirasiSelesai + $aspirasiDitolak;
                    @endphp
                    <span class="text-xs text-gray-500">Total: {{ $totalAspirasi }}</span>
                </div>
                
                <!-- Progress Bar Menunggu -->
                <div class="mb-4">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-600">Menunggu</span>
                        <span class="font-medium">{{ $aspirasiMenunggu }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" 
                             style="width: {{ ($totalAspirasi > 0 ? ($aspirasiMenunggu / $totalAspirasi) * 100 : 0) }}%"></div>
                    </div>
                </div>
                
                <!-- Progress Bar Diproses -->
                <div class="mb-4">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-600">Diproses</span>
                        <span class="font-medium">{{ $aspirasiProses }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" 
                             style="width: {{ ($totalAspirasi > 0 ? ($aspirasiProses / $totalAspirasi) * 100 : 0) }}%"></div>
                    </div>
                </div>
                
                <!-- Progress Bar Selesai -->
                <div class="mb-4">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-600">Selesai</span>
                        <span class="font-medium">{{ $aspirasiSelesai }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" 
                             style="width: {{ ($totalAspirasi > 0 ? ($aspirasiSelesai / $totalAspirasi) * 100 : 0) }}%"></div>
                    </div>
                </div>
                
                <!-- Progress Bar Ditolak -->
                <div class="mb-4">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-600">Ditolak</span>
                        <span class="font-medium">{{ $aspirasiDitolak }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" 
                             style="width: {{ ($totalAspirasi > 0 ? ($aspirasiDitolak / $totalAspirasi) * 100 : 0) }}%"></div>
                    </div>
                </div>
                
                <!-- Persentase -->
                <div class="mt-6 p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Persentase Penyelesaian</span>
                        <span class="text-lg font-bold text-blue-600">
                            @php
                                $persentase = $totalAspirasi > 0 ? round((($aspirasiSelesai + $aspirasiDitolak) / $totalAspirasi) * 100) : 0;
                            @endphp
                            {{ $persentase }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('aspirasiChart');
    
    // Data dari controller
    const data = {
        menunggu: {{ $aspirasiMenunggu }},
        proses: {{ $aspirasiProses }},
        selesai: {{ $aspirasiSelesai }},
        ditolak: {{ $aspirasiDitolak }}
    };
    
    // Total perhitungan
    const total = data.menunggu + data.proses + data.selesai + data.ditolak;
    
    // Inisialisasi chart
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Menunggu', 'Diproses', 'Selesai', 'Ditolak'],
            datasets: [{
                data: [data.menunggu, data.proses, data.selesai, data.ditolak],
                backgroundColor: [
                    '#f59e0b', // Yellow for Menunggu
                    '#3b82f6', // Blue for Diproses
                    '#10b981', // Green for Selesai
                    '#ef4444'  // Red for Ditolak
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 15,
                hoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.raw || 0;
                            let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    },
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    padding: 12
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true,
                duration: 1500,
                easing: 'easeOutQuart'
            }
        }
    });

});
</script>
@endpush