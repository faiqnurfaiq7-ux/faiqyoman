@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-4xl font-bold text-gray-800">👥 Data Penjual</h1>
                <p class="text-gray-600 mt-2">Kelola data penjual dan informasi kontak</p>
            </div>
            <a href="{{ route('penjual.data-penjual.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-semibold flex items-center gap-2">
                ➕ Tambah Penjual Baru
            </a>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl shadow-lg p-4 text-white">
                <p class="text-blue-100 text-sm">Total Penjual</p>
                <h3 class="text-3xl font-bold">{{ $totalPenjual }}</h3>
            </div>
            <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-xl shadow-lg p-4 text-white">
                <p class="text-green-100 text-sm">Penjual Aktif</p>
                <h3 class="text-3xl font-bold">{{ $totalAktif }}</h3>
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabel Penjual -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        @if($penjuals->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b-2">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold text-gray-800">No</th>
                            <th class="px-6 py-4 text-left font-bold text-gray-800">Nama</th>
                            <th class="px-6 py-4 text-left font-bold text-gray-800">Email</th>
                            <th class="px-6 py-4 text-left font-bold text-gray-800">Telepon</th>
                            <th class="px-6 py-4 text-left font-bold text-gray-800">Kota</th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800">Komisi</th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800">Status</th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjuals as $index => $penjual)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-800">{{ $penjual->nama }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $penjual->email }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $penjual->telepon }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $penjual->kota }}</td>
                                <td class="px-6 py-4 text-center font-bold text-blue-600">{{ $penjual->komisi_persen }}%</td>
                                <td class="px-6 py-4 text-center">
                                    @if($penjual->status === 'aktif')
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">✅ Aktif</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">❌ Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <a href="{{ route('penjual.data-penjual.show', $penjual) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs font-semibold">
                                            👁️ Lihat
                                        </a>
                                        <a href="{{ route('penjual.data-penjual.edit', $penjual) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs font-semibold">
                                            ✏️ Edit
                                        </a>
                                        <form action="{{ route('penjual.data-penjual.destroy', $penjual) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg mb-4">📭 Belum ada data penjual</p>
                <a href="{{ route('penjual.data-penjual.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-semibold inline-block">
                    ➕ Tambah Penjual Pertama
                </a>
            </div>
        @endif
    </div>

    <!-- Navigation -->
    <div class="mt-8">
        <a href="{{ route('penjual.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold">
            ← Kembali ke Dashboard Penjual
        </a>
    </div>
</div>

@endsection
