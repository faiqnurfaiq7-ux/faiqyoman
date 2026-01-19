<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Aplikasi Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
<div class="p-8">
    <h1 class="text-2xl font-bold mb-4">Tambah Pelanggan</h1>

    <form action="{{ route('pelanggan.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama</label>
            <input type="text" name="nama" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Alamat</label>
            <input type="text" name="alamat" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Telepon</label>
            <input type="text" name="telepon" class="w-full border rounded p-2" required>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
        <a href="{{ route('pelanggan.index') }}" class="ml-2 text-gray-600 hover:underline">Batal</a>
    </form>
</div>
</body>
</html>