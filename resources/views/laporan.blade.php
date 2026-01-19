<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Aplikasi Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-6xl mx-auto bg-white rounded-xl shadow p-6">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">📊 Laporan Penjualan</h1>
                <p class="text-gray-500 mt-1">Rekap data penjualan dan pelanggan</p>
            </div>
            <a href="{{ route('dashboard') }}" 
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg shadow transition-all duration-200">
               ⬅ Kembali
            </a>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tombol Cetak --}}
        <div class="mb-6 flex justify-end">
            <button onclick="window.print()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition-all duration-200">
                🖨 Cetak Laporan
            </button>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 rounded-lg">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">#</th>
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-left">Nama Pelanggan</th>
                        <th class="py-3 px-4 text-left">Total (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @forelse ($penjualans as $index => $p)
    <tr class="hover:bg-gray-50 transition">
        <td class="py-3 px-4">{{ $index + 1 }}</td>
        <td class="p-3">{{ $p->created_at->format('d M Y') }}</td>
        <td class="p-3">{{ $p->pelanggan->nama ?? '-' }}</td>
        <td class="py-3 px-4 font-semibold text-gray-800">
            Rp {{ number_format($p->total, 0, ',', '.') }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center py-4 text-gray-500">
            Belum ada data penjualan 😕😕😕
        </td>
    </tr>
@endforelse


                </tbody>
                @if ($penjualans->count())
                <tfoot class="bg-gray-100 font-bold">
                    <tr>
                        <td colspan="3" class="py-3 px-4 text-right">Total Semua</td>
                        <td class="py-3 px-4 text-gray-800">
                            Rp {{ number_format($penjualans->sum('total'), 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

</body>
</html>
