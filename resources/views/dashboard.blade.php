@extends('layouts.app')

@section('content')
<div class="space-y-4 dashboard bg-gray-50 min-h-screen p-4">
    <!-- Header with Profile -->
    <div class="bg-white p-4 rounded-lg shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg overflow-hidden">
                    @if(auth()->user()->foto)
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Profile" class="w-full h-full object-cover">
                    @else
                        👤
                    @endif
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-800">Dashboard</h1>
                    <p class="text-sm text-gray-600">Selamat datang, {{ auth()->user()->name }}!</p>
                </div>
            </div>
            <div class="text-right text-gray-600">
                <p class="font-medium text-sm">{{ now()->format('d M Y') }}</p>
                <p class="text-xs" id="current-time">{{ now()->format('H:i:s') }}</p>
            </div>
        </div>
    </div>

    <!-- Simplified KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalTransactionsToday }}</h3>
                </div>
                <div class="text-blue-500 text-2xl">📄</div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalRevenueToday, 0, ',', '.') }}</h3>
                </div>
                <div class="text-green-500 text-2xl">💰</div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Stok</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalStok }}</h3>
                </div>
                <div class="text-orange-500 text-2xl">📦</div>
            </div>
        </div>
    </div>

    </div>
</div>

<script>
// Update waktu real-time
setInterval(function() {
    const now = new Date();
    document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID', { hour12: false });
}, 1000);
</script>
@endsection
