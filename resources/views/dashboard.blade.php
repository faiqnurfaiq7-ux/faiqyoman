@extends('layouts.app')

@section('content')
<div class="space-y-8 dashboard">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-800">📊 Dashboard</h1>
            <p class="text-gray-600 mt-1">Ringkasan penjualan hari ini</p>
        </div>
        <div class="text-right text-gray-600">
            <p class="font-medium">{{ now()->format('d M Y') }}</p>
            <p class="text-sm" id="current-time">{{ now()->format('H:i:s') }}</p>
        </div>
    </div>

    <!-- Simplified KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
            <div class="flex items-center justify-between mt-2">
                <h3 class="text-2xl font-bold text-gray-800">{{ $totalTransactionsToday }}</h3>
                <div class="kpi-icon text-gray-300" aria-hidden>
                    <!-- document icon -->
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 7h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7 11h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7 15h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="3" y="5" width="12" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm">
            <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
            <div class="flex items-center justify-between mt-2">
                <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalRevenueToday, 0, ',', '.') }}</h3>
                    <div class="kpi-icon text-gray-300" aria-hidden>
                        <!-- wallet icon -->
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="2" y="6" width="20" height="12" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M16 10h2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm">
            <p class="text-sm text-gray-500">Total Stok</p>
            <div class="flex items-center justify-between mt-2">
                <h3 class="text-2xl font-bold text-gray-800">{{ $totalStok }}</h3>
                    <div class="kpi-icon text-gray-300" aria-hidden>
                        <!-- factory icon -->
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 21h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M3 16l5-4v4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 12l5-4v8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7 12v-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
            </div>
        </div>
    </div>

    <!-- Charts & Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Weekly Chart -->
        <div class="lg:col-span-2 bg-white p-4 rounded-lg shadow-sm">
            <h3 class="text-md font-semibold text-gray-800 mb-2">Transaksi 7 Hari</h3>
            <canvas id="chartWeekly" height="80"></canvas>
        </div>

        <!-- Compact Top Products -->
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <h3 class="text-md font-semibold text-gray-800 mb-3">Top Produk</h3>
            <ul class="space-y-2">
                @forelse($topProducts as $product)
                <li class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @php
                            $foto = null;
                            if(isset($product->foto) && $product->foto){
                                // foto stored path (e.g. "produk/filename.jpg")
                                $foto = asset('storage/' . ltrim($product->foto, '/'));
                            } else {
                                $foto = asset('images/placeholder.svg');
                            }
                        @endphp
                        <img src="{{ $foto }}" class="w-10 h-10 object-cover rounded" alt=""> 
                        <div class="text-sm text-gray-700">{{ $product->nama }}</div>
                    </div>
                    <span class="text-xs text-gray-500">{{ $product->qty }}</span>
                </li>
                @empty
                <p class="text-gray-400 text-center text-sm">Belum ada data</p>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
// Update waktu real-time
setInterval(function() {
    const now = new Date();
    document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID', { hour12: false });
}, 1000);

// Chart: Transaksi 7 Hari
const ctxWeekly = document.getElementById('chartWeekly').getContext('2d');
new Chart(ctxWeekly, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartDays) !!},
        datasets: [
            {
                label: 'Jumlah Transaksi',
                data: {!! json_encode($chartCounts) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                yAxisID: 'y',
                pointRadius: 5,
                pointBackgroundColor: '#3b82f6',
            },
            {
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($chartTotals) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                yAxisID: 'y1',
                pointRadius: 5,
                pointBackgroundColor: '#10b981',
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        interaction: { mode: 'index', intersect: false },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                ticks: { color: '#3b82f6' },
                grid: { color: 'rgba(59, 130, 246, 0.1)' }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                grid: { display: false },
                ticks: { color: '#10b981' }
            }
        },
        plugins: {
            legend: { display: true, position: 'top' }
        }
    }
});
</script>
@endsection
