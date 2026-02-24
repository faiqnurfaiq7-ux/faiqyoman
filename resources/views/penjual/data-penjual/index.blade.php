@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 p-8">
    <!-- Header -->
    <div class="mb-8 animate-fade-in">
        <div class="flex items-center justify-between mb-6">
            <div class="animate-slide-up">
                <h1 class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                    👥 Data Penjual
                </h1>
                <p class="text-gray-600 mt-2 text-lg">Kelola data penjual dengan gaya yang menarik ✨</p>
            </div>
            <a href="{{ route('penjual.data-penjual.create') }}" class="bg-gradient-to-r from-green-400 to-green-600 hover:from-green-500 hover:to-green-700 text-white px-8 py-4 rounded-2xl font-bold flex items-center gap-3 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 animate-bounce-in">
                ➕ Tambah Penjual Baru
                <span class="text-xl">🚀</span>
            </a>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-slide-up-delay">
            <div class="bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 rounded-3xl shadow-2xl p-6 text-white transform hover:scale-105 transition-all duration-300 hover:shadow-blue-300/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-semibold mb-1">Total Penjual</p>
                        <h3 class="text-4xl font-bold">{{ $totalPenjual }}</h3>
                    </div>
                    <div class="text-6xl opacity-80">👥</div>
                </div>
                <div class="mt-4 flex items-center">
                    <div class="w-full bg-blue-300 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full" style="width: 80%"></div>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-400 via-green-500 to-green-600 rounded-3xl shadow-2xl p-6 text-white transform hover:scale-105 transition-all duration-300 hover:shadow-green-300/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-semibold mb-1">Penjual Aktif</p>
                        <h3 class="text-4xl font-bold">{{ $totalAktif }}</h3>
                    </div>
                    <div class="text-6xl opacity-80">✅</div>
                </div>
                <div class="mt-4 flex items-center">
                    <div class="w-full bg-green-300 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mb-6 bg-gradient-to-r from-green-400 to-green-600 border-l-4 border-green-700 text-white p-4 rounded-2xl shadow-lg animate-slide-down">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- Grid Penjual -->
    @if($penjuals->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in-delay">
            @foreach($penjuals as $index => $penjual)
                <div class="bg-white rounded-3xl shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300 overflow-hidden group animate-slide-up relative">
                    <!-- Header Card -->
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white/20 rounded-full overflow-hidden flex items-center justify-center">
                                    @if($penjual->foto)
                                        <img src="{{ $penjual->foto_url }}" alt="{{ $penjual->nama }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xl font-bold">{{ strtoupper(substr($penjual->nama, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg">{{ $penjual->nama }}</h3>
                                    <p class="text-indigo-100 text-sm">{{ $penjual->kota }}</p>
                                </div>
                            </div>
                            @if($penjual->status === 'aktif')
                                <span class="bg-green-400 text-green-900 px-3 py-1 rounded-full text-xs font-bold animate-pulse">✅ Aktif</span>
                            @else
                                <span class="bg-red-400 text-red-900 px-3 py-1 rounded-full text-xs font-bold">❌ Nonaktif</span>
                            @endif
                        </div>
                    </div>

                    <!-- Body Card -->
                    <div class="p-6">
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center gap-3">
                                <span class="text-gray-400">📧</span>
                                <span class="text-gray-700">{{ $penjual->email }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-gray-400">📱</span>
                                <span class="text-gray-700">{{ $penjual->telepon }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-gray-400">💰</span>
                                <span class="text-blue-600 font-bold">{{ $penjual->komisi_persen }}% Komisi</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('penjual.data-penjual.show', $penjual) }}" class="flex-1 bg-gradient-to-r from-blue-400 to-blue-600 hover:from-blue-500 hover:to-blue-700 text-white py-2 px-4 rounded-xl text-center font-semibold transition-all duration-300 transform hover:scale-105">
                                👁️ Lihat
                            </a>
                            <a href="{{ route('penjual.data-penjual.edit', $penjual) }}" class="flex-1 bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white py-2 px-4 rounded-xl text-center font-semibold transition-all duration-300 transform hover:scale-105">
                                ✏️ Edit
                            </a>
                        </div>
                        <form action="{{ route('penjual.data-penjual.destroy', $penjual) }}" method="POST" class="mt-2" onsubmit="return confirm('Yakin ingin menghapus penjual ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-gradient-to-r from-red-400 to-red-600 hover:from-red-500 hover:to-red-700 text-white py-2 px-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>

                    <!-- Decorative Element -->
                    <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-purple-300 to-transparent rounded-full -mr-10 -mt-10 opacity-20 group-hover:opacity-30 transition-opacity duration-300"></div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 animate-fade-in">
            <div class="text-8xl mb-6 animate-bounce">📭</div>
            <h3 class="text-2xl font-bold text-gray-600 mb-4">Belum ada data penjual</h3>
            <p class="text-gray-500 mb-8">Mulai tambahkan penjual pertama Anda untuk memulai perjalanan sukses! 🌟</p>
            <a href="{{ route('penjual.data-penjual.create') }}" class="bg-gradient-to-r from-green-400 to-green-600 hover:from-green-500 hover:to-green-700 text-white px-8 py-4 rounded-2xl font-bold inline-block shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                ➕ Tambah Penjual Pertama
                <span class="ml-2">🚀</span>
            </a>
        </div>
    @endif

    <!-- Navigation -->
    <div class="mt-8 text-center animate-fade-in">
        <a href="{{ route('penjual.index') }}" class="bg-gradient-to-r from-blue-400 to-blue-600 hover:from-blue-500 hover:to-blue-700 text-white px-8 py-4 rounded-2xl font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
            ← Kembali ke Dashboard Penjual
        </a>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slide-down {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounce-in {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.6s ease-out;
}

.animate-slide-up-delay {
    animation: slide-up 0.6s ease-out 0.2s both;
}

.animate-slide-down {
    animation: slide-down 0.6s ease-out;
}

.animate-fade-in-delay {
    animation: fade-in 0.8s ease-out 0.4s both;
}

.animate-bounce-in {
    animation: bounce-in 0.8s ease-out;
}
</style>
@endsection
