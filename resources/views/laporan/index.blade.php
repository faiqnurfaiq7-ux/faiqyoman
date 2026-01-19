@extends('layouts.app')

@section('content')
<div class="p-10 bg-gray-100 min-h-screen">
    <!-- Judul -->
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            📊 <span>Laporan Penjualan</span>
        </h2>
        <a href="{{ route('laporan.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-2xl shadow-md transition">
            🔄 Refresh
        </a>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
            <p class="text-gray-500">Total Transaksi</p>
            <h3 class="text-2xl font-bold text-blue-600">{{ $totalTransactions }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
            <p class="text-gray-500">Total Pendapatan</p>
            <h3 class="text-2xl font-bold text-green-600">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </h3>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
            <p class="text-gray-500">Rata-rata Transaksi</p>
            <h3 class="text-2xl font-bold text-yellow-600">
                Rp {{ number_format($average, 0, ',', '.') }}
            </h3>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Chart: Penjualan Per Hari -->
        <div class="bg-white p-6 rounded-2xl shadow">
            <h3 class="text-lg font-bold text-gray-800 mb-4">📈 Penjualan Per Hari (7 Hari Terakhir)</h3>
            <canvas id="chartSalesByDay" height="80"></canvas>
        </div>

        <!-- Chart: Penjualan Per Produk -->
        <div class="bg-white p-6 rounded-2xl shadow">
            <h3 class="text-lg font-bold text-gray-800 mb-4">🍰 Penjualan Per Produk (Top 10)</h3>
            <canvas id="chartSalesByProduct" height="80"></canvas>
        </div>
    </div>

    <!-- Chart: Metode Pembayaran -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow">
            <h3 class="text-lg font-bold text-gray-800 mb-4">💳 Transaksi Per Metode Pembayaran</h3>
            <canvas id="chartPaymentMethods" height="80"></canvas>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow">
            <h3 class="text-lg font-bold text-gray-800 mb-4">💰 Pendapatan Per Metode Pembayaran</h3>
            <div class="space-y-3">
                @foreach($paymentMethods as $pm)
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-700">
                        @if($pm->payment_method === 'QRIS')
                            💳 QRIS
                        @elseif($pm->payment_method === 'BANK')
                            🏦 Bank Transfer
                        @else
                            📱 PayLetter
                        @endif
                    </span>
                    <div class="text-right">
                        <p class="font-bold text-lg text-blue-600">Rp {{ number_format($pm->total, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500">{{ $pm->count }} transaksi</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Tabel Data Penjualan -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Invoice</th>
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Total</th>
                    <th class="p-3 text-left">Metode Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="p-3">{{ $loop->iteration }}</td>
                    <td class="p-3">{{ $trx->invoice_number ?? '-' }}</td>
                    <td class="p-3">{{ $trx->created_at->format('d M Y') }}</td>
                    <td class="p-3 font-semibold text-green-700">
                        Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                    </td>
                    <td class="p-3 text-sm">
                        @if($trx->payment_method === 'QRIS')
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">💳 QRIS</span>
                        @elseif($trx->payment_method === 'BANK')
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded">🏦 Bank</span>
                        @else
                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded">📱 PayLetter</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-500">Belum ada data penjualannnnn 😅</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Load Chart.js dari CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<script>
// Chart 1: Penjualan Per Hari (Garis + Bar)
const ctxDay = document.getElementById('chartSalesByDay').getContext('2d');
const chartSalesByDay = new Chart(ctxDay, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartDays) !!},
        datasets: [
            {
                label: 'Penjualan (Rp)',
                data: {!! json_encode($chartSalesByDay) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                yAxisID: 'y',
            },
            {
                label: 'Jumlah Transaksi',
                data: {!! json_encode($chartCountByDay) !!},
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Penjualan (Rp)',
                    color: '#3b82f6',
                },
                ticks: {
                    callback: function(v) {
                        return 'Rp ' + (v / 1000).toFixed(0) + 'K';
                    }
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Jumlah Transaksi',
                    color: '#f59e0b',
                },
                grid: {
                    drawOnChartArea: false,
                },
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top',
            }
        }
    }
});

// Chart 2: Penjualan Per Produk (Pie/Donat)
const ctxProduct = document.getElementById('chartSalesByProduct').getContext('2d');
const chartSalesByProduct = new Chart(ctxProduct, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($chartProducts) !!},
        datasets: [{
            label: 'Jumlah Terjual',
            data: {!! json_encode($chartQtyByProduct) !!},
            backgroundColor: [
                '#ff6b6b',
                '#4ecdc4',
                '#45b7d1',
                '#f9ca24',
                '#6c5ce7',
                '#a29bfe',
                '#fd79a8',
                '#fdcb6e',
                '#6c7a89',
                '#fab1a0'
            ],
            borderColor: '#fff',
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed.y + ' unit(s)';
                    }
                }
            }
        }
    }
});

// Chart 3: Metode Pembayaran (Bar)
const ctxPayment = document.getElementById('chartPaymentMethods').getContext('2d');
const chartPaymentMethods = new Chart(ctxPayment, {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartPaymentMethods) !!},
        datasets: [{
            label: 'Jumlah Transaksi',
            data: {!! json_encode($chartPaymentCounts) !!},
            backgroundColor: [
                '#3b82f6',
                '#10b981',
                '#a855f7'
            ],
            borderColor: [
                '#1e40af',
                '#047857',
                '#6d28d9'
            ],
            borderWidth: 1,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        indexAxis: 'y',
        plugins: {
            legend: {
                display: true,
                position: 'top',
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                }
            }
        }
    }
});

@endsection
