@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('penjual.data-penjual.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold mb-2">← Kembali</a>
                <h1 class="text-4xl font-bold text-gray-800">👤 {{ $penjual->nama }}</h1>
                <p class="text-gray-600 mt-2">Detail informasi penjual</p>
            </div>
            <div class="text-right flex gap-2">
                @if($penjual->status === 'aktif')
                    <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-bold">✅ Aktif</span>
                @else
                    <span class="bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-bold">❌ Nonaktif</span>
                @endif
                <a href="{{ route('penjual.data-penjual.edit', $penjual) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-semibold">✏️ Edit</a>
            </div>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <p class="text-blue-100 text-sm">Total Penjualan</p>
                <h3 class="text-3xl font-bold">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <p class="text-green-100 text-sm">Total Komisi</p>
                <h3 class="text-3xl font-bold">Rp {{ number_format($totalKomisi, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <p class="text-purple-100 text-sm">Jumlah Transaksi</p>
                <h3 class="text-3xl font-bold">{{ $jumlahTransaksi }}</h3>
            </div>
        </div>
    </div>

    <!-- Informasi Detail -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Informasi Pribadi -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-blue-500">📋 Informasi Pribadi</h3>

            <!-- Foto Profil -->
            <div class="mb-6 text-center">
                <div class="inline-block">
                    <img src="{{ $penjual->foto_url }}" alt="Foto {{ $penjual->nama }}"
                         class="w-32 h-32 rounded-full object-cover border-4 border-blue-100 shadow-lg mx-auto">
                    <p class="text-gray-500 text-sm mt-2">Foto Profil</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <p class="text-gray-600 text-sm">Nama Lengkap</p>
                    <p class="text-lg font-bold text-gray-800">{{ $penjual->nama }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Email</p>
                    <p class="text-lg font-mono text-blue-600">{{ $penjual->email }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Nomor Telepon</p>
                    <p class="text-lg font-bold text-gray-800">{{ $penjual->telepon }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Komisi</p>
                    <p class="text-lg font-bold text-green-600">{{ $penjual->komisi_persen }}%</p>
                </div>
            </div>
        </div>

        <!-- Alamat -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-green-500">📍 Alamat</h3>

            <div class="space-y-4">
                <div>
                    <p class="text-gray-600 text-sm">Alamat Lengkap</p>
                    <p class="text-lg font-bold text-gray-800">{{ $penjual->alamat }}</p>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Kota</p>
                        <p class="text-lg font-bold text-gray-800">{{ $penjual->kota }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Provinsi</p>
                        <p class="text-lg font-bold text-gray-800">{{ $penjual->provinsi }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Kode Pos</p>
                        <p class="text-lg font-bold text-gray-800">{{ $penjual->kode_pos }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Bank -->
        <div class="bg-white rounded-2xl shadow-lg p-6 lg:col-span-2">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-purple-500">🏦 Informasi Bank</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-gray-600 text-sm">Nama Bank</p>
                    <p class="text-lg font-bold text-gray-800">{{ $penjual->bank_name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Nomor Rekening</p>
                    <p class="text-lg font-mono font-bold text-blue-600">{{ $penjual->bank_account }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Atas Nama</p>
                    <p class="text-lg font-bold text-gray-800">{{ $penjual->bank_account_name }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex gap-4">
        <a href="{{ route('penjual.data-penjual.edit', $penjual) }}" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-bold text-center">
            ✏️ Edit Data
        </a>
        <form action="{{ route('penjual.data-penjual.destroy', $penjual) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-bold">
                🗑️ Hapus
            </button>
        </form>
        <a href="{{ route('penjual.data-penjual.index') }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-bold text-center">
            ← Kembali ke Daftar
        </a>
    </div>
</div>

@endsection
