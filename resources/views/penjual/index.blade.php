@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 to-slate-800 p-6">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-white">📊 Penjualan</h1>
            <p class="text-gray-400 text-sm mt-1">{{ now()->format('l, d F Y') }}</p>
        </div>
        <div class="text-right">
            <p class="text-gray-300 font-mono text-lg"><span id="current-time">{{ now()->format('H:i:s') }}</span></p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1 mb-8 border-b border-gray-700">
        <a href="#" class="text-white bg-blue-600 px-4 py-2 rounded-t-lg font-medium text-sm">📊 Dashboard</a>
        <a href="{{ route('penjual.data-penjual.index') }}" class="text-gray-400 hover:text-white px-4 py-2 rounded-t-lg font-medium text-sm">👥 Data Penjual</a>
        <a href="{{ route('penjual.report') }}" class="text-gray-400 hover:text-white px-4 py-2 rounded-t-lg font-medium text-sm">📈 Laporan</a>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Left: Stats + Chart -->
        <div class="lg:col-span-2 space-y-6">
            <!-- 3 Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-lg p-6 text-white shadow-lg">
                    <p class="text-emerald-200 text-xs font-semibold">PENDAPATAN</p>
                    <h2 class="text-3xl font-bold mt-2">Rp {{ number_format($totalRevenueToday, 0, ',', '.') }}</h2>
                    <p class="text-xs text-emerald-200 mt-2">Hari ini</p>
                </div>
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg p-6 text-white shadow-lg">
                    <p class="text-blue-200 text-xs font-semibold">TRANSAKSI</p>
                    <h2 class="text-3xl font-bold mt-2">{{ $totalTransactionsToday }}</h2>
                    <p class="text-xs text-blue-200 mt-2">Transaksi sukses</p>
                </div>
                <div class="bg-gradient-to-br from-violet-600 to-violet-700 rounded-lg p-6 text-white shadow-lg">
                    <p class="text-violet-200 text-xs font-semibold">RATA-RATA</p>
                    <h2 class="text-3xl font-bold mt-2">Rp {{ number_format($avgTransactionToday, 0, ',', '.') }}</h2>
                    <p class="text-xs text-violet-200 mt-2">Per transaksi</p>
                </div>
            </div>

            <!-- Chart -->
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <h3 class="text-white font-bold mb-4">📈 Penjualan 7 Hari</h3>
                <canvas id="chartWeeklySales" height="80"></canvas>
            </div>

            <!-- Top Products & Transactions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Top Products -->
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <h3 class="text-white font-bold mb-4">🏆 Top Produk</h3>
                    <div class="space-y-3">
                        @forelse($topProductsToday as $product)
                            <div class="flex items-center justify-between pb-3 border-b border-gray-700 last:border-0">
                                <div>
                                    <p class="text-white text-sm font-medium truncate">{{ $product->nama }}</p>
                                    <p class="text-gray-400 text-xs">{{ $product->qty }} terjual</p>
                                </div>
                                <p class="text-emerald-400 font-bold">Rp {{ number_format($product->revenue, 0, ',', '.') }}</p>
                            </div>
                        @empty
                            <p class="text-gray-400 text-center py-4">Belum ada penjualan</p>
                        @endforelse
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <h3 class="text-white font-bold mb-4">💳 Metode Pembayaran</h3>
                    <div class="space-y-3">
                        @php
                            $methods = [
                                'QRIS' => ['icon' => '💳', 'color' => 'blue'],
                                'BANK' => ['icon' => '🏦', 'color' => 'green'],
                                'PAYLETTER' => ['icon' => '📱', 'color' => 'purple']
                            ];
                        @endphp
                        @foreach($methods as $key => $method)
                            @php $data = $paymentBreakdown[$key] ?? null; @endphp
                            <div class="flex items-center justify-between pb-3 border-b border-gray-700 last:border-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-xl">{{ $method['icon'] }}</span>
                                    <div>
                                        <p class="text-white text-sm font-medium">{{ $key }}</p>
                                        <p class="text-gray-400 text-xs">{{ $data ? $data['count'] : 0 }} transaksi</p>
                                    </div>
                                </div>
                                <p class="text-white font-bold">Rp {{ number_format($data ? $data['total'] : 0, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Summary & Actions -->
        <div class="space-y-6">
            <!-- Monthly Stats -->
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <h3 class="text-white font-bold mb-4">📅 Bulan Ini</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-400 text-xs mb-1">Total Pendapatan</p>
                        <p class="text-white text-2xl font-bold">Rp {{ number_format($totalRevenueThisMonth, 0, ',', '.') }}</p>
                    </div>
                    <div class="border-t border-gray-700 pt-4">
                        <p class="text-gray-400 text-xs mb-1">Transaksi</p>
                        <p class="text-white text-2xl font-bold">{{ $totalTransactionsThisMonth }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="space-y-2">
                <a href="{{ route('kasir.index') }}" class="block bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-4 text-center font-semibold transition">
                    🛒 Kasir
                </a>
                <a href="{{ route('penjual.data-penjual.create') }}" class="block bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg p-4 text-center font-semibold transition">
                    ➕ Tambah Penjual
                </a>
                <a href="{{ route('produk.index') }}" class="block bg-orange-600 hover:bg-orange-700 text-white rounded-lg p-4 text-center font-semibold transition">
                    📦 Produk
                </a>
            </div>

            <!-- Stock Warning -->
            @if($lowStockProducts->count() > 0)
            <div class="bg-red-900/30 border border-red-700 rounded-lg p-4">
                <h4 class="text-red-300 font-bold mb-3">⚠️ Stok Rendah</h4>
                <div class="space-y-2">
                    @foreach($lowStockProducts->take(3) as $product)
                        <div class="flex justify-between items-center">
                            <p class="text-red-200 text-sm truncate">{{ $product->nama }}</p>
                            <span class="text-red-400 font-bold">{{ $product->stok }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
        <h3 class="text-white font-bold mb-4">📋 Transaksi Terbaru</h3>
        @if($transactionsToday->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-gray-400 border-b border-gray-700">
                        <tr>
                            <th class="text-left py-2">Invoice</th>
                            <th class="text-left py-2">Produk</th>
                            <th class="text-center py-2">Qty</th>
                            <th class="text-right py-2">Total</th>
                            <th class="text-center py-2">Metode</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300">
                        @foreach($transactionsToday->take(5) as $trans)
                            <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                                <td class="py-3 font-mono text-blue-400">#{{ $trans->id }}</td>
                                <td class="py-3 text-xs">{{ $trans->details->count() }} produk</td>
                                <td class="py-3 text-center">{{ $trans->details->sum('quantity') }}</td>
                                <td class="py-3 text-right font-bold text-emerald-400">Rp {{ number_format($trans->total_amount, 0, ',', '.') }}</td>
                                <td class="py-3 text-center"><span class="text-xs bg-gray-700 px-2 py-1 rounded">{{ $trans->payment_method }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-400 text-center py-6">Belum ada transaksi hari ini</p>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
// Real-time clock
setInterval(function() {
    const now = new Date();
    document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID', { hour12: false });
}, 1000);

// Chart
const ctxWeekly = document.getElementById('chartWeeklySales').getContext('2d');
new Chart(ctxWeekly, {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartDays) !!},
        datasets: [
            {
                label: 'Jumlah Transaksi',
                data: {!! json_encode($chartCounts) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderRadius: 6,
                yAxisID: 'y',
            },
            {
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($chartTotals) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 2,
                type: 'line',
                tension: 0.4,
                yAxisID: 'y1',
                pointRadius: 4,
                pointBackgroundColor: '#10b981',
                fill: true,
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
                ticks: { color: '#9ca3af' },
                grid: { color: '#374151' }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                ticks: { color: '#9ca3af' },
                grid: { display: false }
            },
            x: {
                ticks: { color: '#9ca3af' },
                grid: { display: false }
            }
        },
        plugins: {
            legend: { display: true, labels: { color: '#9ca3af' } }
        }
    }
});
</script>
@endsection
