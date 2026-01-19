@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('penjual.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold mb-2">← Kembali ke Dashboard</a>
                <h1 class="text-4xl font-bold text-gray-800">📅 Detail Penjualan</h1>
                <p class="text-gray-600 text-lg">{{ $selectedDate->format('l, d F Y') }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-600">Tanggal: {{ now()->format('d M Y') }}</p>
                <p class="text-gray-600">Waktu: <span id="current-time">{{ now()->format('H:i:s') }}</span></p>
            </div>
        </div>
    </div>

    <!-- Statistik Hari -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
            <p class="text-blue-100 text-sm mb-2">Total Transaksi</p>
            <h3 class="text-4xl font-bold">{{ $totalTransactions }}</h3>
        </div>

        <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-2xl shadow-lg p-6 text-white">
            <p class="text-green-100 text-sm mb-2">Total Pendapatan</p>
            <h3 class="text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>

        <div class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
            <p class="text-purple-100 text-sm mb-2">Total Item</p>
            <h3 class="text-4xl font-bold">{{ $totalItems }}</h3>
        </div>
    </div>

    <!-- Daftar Transaksi -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">🧾 Transaksi Hari {{ $selectedDate->format('d M Y') }}</h3>
        
        @if($transactions->count() > 0)
            <div class="space-y-4">
                @foreach($transactions as $transaction)
                    <div class="border-2 border-gray-200 rounded-xl p-4 hover:border-blue-300 transition">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-3">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">No. Invoice</p>
                                <p class="font-mono font-bold text-blue-600">{{ $transaction->invoice_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Waktu</p>
                                <p class="font-semibold text-gray-800">{{ $transaction->created_at->format('H:i:s') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Total Item</p>
                                <p class="font-bold text-purple-600">{{ $transaction->details->sum('quantity') }} unit</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Total</p>
                                <p class="font-bold text-2xl text-green-600">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Metode</p>
                                @php
                                    $methodsColors = ['QRIS' => 'blue', 'BANK' => 'green', 'PAYLETTER' => 'purple'];
                                    $color = $methodsColors[$transaction->payment_method] ?? 'gray';
                                @endphp
                                <span class="bg-{{ $color }}-100 text-{{ $color }}-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $transaction->payment_method }}
                                </span>
                            </div>
                        </div>

                        <!-- Detail Item -->
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs font-bold text-gray-600 mb-2">📦 Item Pembelian:</p>
                            <div class="space-y-2">
                                @foreach($transaction->details as $detail)
                                    <div class="flex justify-between items-center text-sm">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $detail->produk->nama }}</p>
                                            <p class="text-xs text-gray-500">{{ $detail->quantity }} unit × Rp {{ number_format($detail->price, 0, ',', '.') }}</p>
                                        </div>
                                        <p class="font-bold text-blue-600">Rp {{ number_format($detail->quantity * $detail->price, 0, ',', '.') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-gray-500">
                <p class="text-lg">📭 Belum ada transaksi pada tanggal ini</p>
            </div>
        @endif
    </div>

    <!-- Navigation -->
    <div class="mt-8 flex gap-4">
        <a href="{{ route('penjual.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold">
            ← Kembali ke Dashboard
        </a>
    </div>
</div>

<script>
// Update waktu real-time
setInterval(function() {
    const now = new Date();
    document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID');
}, 1000);
</script>

@endsection
