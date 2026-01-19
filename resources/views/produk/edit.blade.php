<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Aplikasi Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Edit Produk</h2>

        <form action="{{ route('produk.update', $produk->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label class="block mb-2">Nama Produk:</label>
            <input type="text" name="nama" value="{{ $produk->nama }}" class="w-full border p-2 mb-4" required>

            <label class="block mb-2">Harga:</label>
            <input type="number" name="harga" value="{{ $produk->harga }}" class="w-full border p-2 mb-4" required>

            <label class="block mb-2">Stok:</label>
            <input type="number" name="stok" value="{{ $produk->stok }}" class="w-full border p-2 mb-4" required>

            <div class="flex justify-between items-center pt-4">
                <a href="{{ route('produk.index') }}"
                    class="text-sm text-gray-600 hover:text-gray-800 underline transition">
                    ← Kembali ke daftar produk
                </a>
                
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>
    </body>
</html>