@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">📈 Laporan Penjualan</h1>
        <p class="text-gray-600">Analisis lengkap penjualan dan performa produk</p>
    </div>

    <!-- Statistik Keseluruhan -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
            <p class="text-blue-100 text-sm mb-2">Total Transaksi</p>
            <h3 class="text-4xl font-bold">{{ $totalTransactions }}</h3>
            <p class="text-xs text-blue-100 mt-2">Semua waktu</p>
        </div>

        <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-2xl shadow-lg p-6 text-white">
            <p class="text-green-100 text-sm mb-2">Total Pendapatan</p>
            <h3 class="text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <p class="text-xs text-green-100 mt-2">Semua transaksi</p>
        </div>

        <div class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
            <p class="text-purple-100 text-sm mb-2">Total Item Terjual</p>
            <h3 class="text-4xl font-bold">{{ $totalItems }}</h3>
            <p class="text-xs text-purple-100 mt-2">Unit</p>
        </div>

        <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl shadow-lg p-6 text-white">
            <p class="text-yellow-100 text-sm mb-2">Rata-rata per Transaksi</p>
            <h3 class="text-3xl font-bold">Rp {{ number_format($totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0, 0, ',', '.') }}</h3>
            <p class="text-xs text-yellow-100 mt-2">Nilai transaksi</p>
        </div>
    </div>

    <!-- Charts & Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Chart: Penjualan 30 Hari -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">📊 Penjualan 30 Hari Terakhir</h3>
            <canvas id="chartMonthlySales" height="80"></canvas>
        </div>

        <!-- Metode Pembayaran -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">💳 Metode Pembayaran</h3>
            <canvas id="chartPaymentMethods" height="100"></canvas>
        </div>
    </div>

    <!-- Top 10 Produk -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">🏆 Top 10 Produk Terlaris</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b-2">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Nama Produk</th>
                        <th class="px-4 py-3 text-center">Harga Satuan</th>
                        <th class="px-4 py-3 text-center">Total Terjual</th>
                        <th class="px-4 py-3 text-right">Total Revenue</th>
                        <th class="px-4 py-3 text-center">% Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $index => $product)
                        @php
                            $percentage = $totalRevenue > 0 ? ($product->revenue / $totalRevenue * 100) : 0;
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 font-bold text-gray-800">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-800">{{ $product->nama }}</td>
                            <td class="px-4 py-3 text-center">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center font-bold text-blue-600">{{ $product->qty }} unit</td>
                            <td class="px-4 py-3 text-right font-bold text-green-600">Rp {{ number_format($product->revenue, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-xs font-bold text-gray-600 mt-1 block">{{ number_format($percentage, 1) }}%</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Metode Pembayaran Detail -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">💳 Detail Metode Pembayaran</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $methodsInfo = [
                    'QRIS' => ['icon' => '💳', 'color' => 'blue', 'label' => 'QRIS'],
                    'BANK' => ['icon' => '🏦', 'color' => 'green', 'label' => 'Bank Transfer'],
                    'PAYLETTER' => ['icon' => '📱', 'color' => 'purple', 'label' => 'PayLetter']
                ];
            @endphp

            @foreach($paymentMethods as $payment)
                @php
                    $info = $methodsInfo[$payment->payment_method] ?? ['icon' => '🔹', 'color' => 'gray'];
                @endphp
                <div class="bg-gradient-to-br from-{{ $info['color'] }}-100 to-{{ $info['color'] }}-50 rounded-2xl p-6 border-l-4 border-{{ $info['color'] }}-500">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-4xl">{{ $info['icon'] }}</span>
                        <h4 class="text-xl font-bold text-gray-800">{{ $payment->payment_method }}</h4>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-gray-700">Jumlah Transaksi:</span>
                            <span class="font-bold text-2xl text-{{ $info['color'] }}-600">{{ $payment->count }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Total Pendapatan:</span>
                            <span class="font-bold text-2xl text-{{ $info['color'] }}-600">Rp {{ number_format($payment->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t">
                            <span class="text-gray-700 text-sm">Persentase:</span>
                            <span class="font-bold text-{{ $info['color'] }}-600">{{ number_format($totalRevenue > 0 ? ($payment->total / $totalRevenue * 100) : 0, 1) }}%</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Navigation -->
    <div class="mt-8 flex gap-4">
        <a href="{{ route('penjual.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold">
            ← Kembali ke Dashboard
        </a>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<script>
// Chart: Penjualan 30 Hari
const ctxMonthly = document.getElementById('chartMonthlySales').getContext('2d');
const monthlySalesData = {!! json_encode($monthlySales) !!};

const monthlyLabels = monthlySalesData.map(d => {
    const date = new Date(d.tanggal);
    return date.toLocaleDateString('id-ID', { month: 'short', day: 'numeric' });
});
const monthlyCounts = monthlySalesData.map(d => parseInt(d.count));
const monthlyTotals = monthlySalesData.map(d => parseFloat(d.total));

new Chart(ctxMonthly, {
    type: 'bar',
    data: {
        labels: monthlyLabels,
        datasets: [
            {
                label: 'Jumlah Transaksi',
                data: monthlyCounts,
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: '#3b82f6',
                borderWidth: 2,
                yAxisID: 'y',
                borderRadius: 6,
            },
            {
                label: 'Pendapatan (Rp)',
                data: monthlyTotals,
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                borderColor: '#22c55e',
                borderWidth: 2,
                type: 'line',
                tension: 0.4,
                yAxisID: 'y1',
                pointRadius: 4,
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
                    text: 'Jumlah Transaksi',
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Pendapatan',
                },
                grid: {
                    drawOnChartArea: false,
                }
            }
        }
    }
});

// Chart: Metode Pembayaran
const ctxPayment = document.getElementById('chartPaymentMethods').getContext('2d');
const paymentData = {!! json_encode($paymentMethods) !!};

const paymentLabels = paymentData.map(p => p.payment_method);
const paymentCounts = paymentData.map(p => parseInt(p.count));
const paymentColors = ['#3b82f6', '#22c55e', '#a855f7'];

new Chart(ctxPayment, {
    type: 'doughnut',
    data: {
        labels: paymentLabels,
        datasets: [{
            data: paymentCounts,
            backgroundColor: paymentColors,
            borderColor: '#fff',
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});
</script>

@endsection
