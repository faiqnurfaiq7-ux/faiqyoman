<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk | Kasir App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-100 p-8 font-sans">
    <div class="max-w-3xl mx-auto bg-white shadow-2xl rounded-3xl p-8 relative overflow-hidden">
        <!-- Hiasan background -->
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-200 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-purple-200 rounded-full blur-3xl opacity-50"></div>

        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-indigo-700 flex items-center gap-2">
                🛍️ Tambah Produk
            </h1>
            <a href="{{ route('produk.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow transition transform hover:-translate-y-1">
                ⬅️ Kembali
            </a>
        </div>

        <!-- Notifikasi Error -->
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 relative z-10">
            @csrf

            <div>
                <label class="block mb-1 font-medium text-gray-700">🆔 ID Produk</label>
                <input type="text" name="id" class="border border-gray-300 focus:ring-2 focus:ring-indigo-400 px-3 py-2 w-full rounded-lg" required value="{{ old('id') }}">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">📦 Nama Produk</label>
                <input type="text" name="nama" class="border border-gray-300 focus:ring-2 focus:ring-indigo-400 px-3 py-2 w-full rounded-lg" required value="{{ old('nama') }}">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">💰 Harga</label>
                <input type="number" name="harga" class="border border-gray-300 focus:ring-2 focus:ring-indigo-400 px-3 py-2 w-full rounded-lg" required value="{{ old('harga') }}">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">📊 Stok</label>
                <input type="number" name="stok" class="border border-gray-300 focus:ring-2 focus:ring-indigo-400 px-3 py-2 w-full rounded-lg" required value="{{ old('stok', 0) }}">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">🖼️ Foto Produk</label>
                <input type="file" name="foto" class="border border-gray-300 focus:ring-2 focus:ring-indigo-400 px-3 py-2 w-full rounded-lg bg-gray-50">
            </div>

            <button type="submit" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-lg font-semibold shadow-md transition transform hover:scale-105">
                💾 Simpan Produk
            </button>
        </form>
    </div>
</body>
</html>
