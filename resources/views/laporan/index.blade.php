@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <!-- Judul -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">📊 Laporan Penjualan</h2>
        <a href="{{ route('laporan.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">🔄 Refresh</a>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-500 text-sm">Total Transaksi</p>
            <h3 class="text-xl font-bold text-blue-600">{{ $totalTransactions }}</h3>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-500 text-sm">Total Pendapatan</p>
            <h3 class="text-xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Chart Utama -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">📈 Penjualan 7 Hari Terakhir</h3>
        <canvas id="chartSalesByDay" height="60"></canvas>
    </div>

    <!-- Tabel Data Penjualan -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">No</th>
                    <th class="p-2 text-left">Invoice</th>
                    <th class="p-2 text-left">Tanggal</th>
                    <th class="p-2 text-left">Total</th>
                    <th class="p-2 text-left">Metode</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-2">{{ $loop->iteration }}</td>
                    <td class="p-2">{{ $trx->invoice_number ?? '-' }}</td>
                    <td class="p-2">{{ $trx->created_at->format('d M Y') }}</td>
                    <td class="p-2 font-semibold text-green-700">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                    <td class="p-2">{{ $trx->payment_method }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Belum ada data penjualan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Load Chart.js dari CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<script>
// Chart Utama: Penjualan Per Hari
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
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            }
        },
        scales: {
            y: {
                ticks: {
                    callback: function(v) {
                        return 'Rp ' + (v / 1000).toFixed(0) + 'K';
                    }
                }
            }
        }
    }
});
</script>

@endsection
