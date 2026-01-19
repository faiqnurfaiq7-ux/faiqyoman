<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Aplikasi Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<div class="p-8 bg-gray-100 min-h-screen">
    <div class="max-w-lg mx-auto bg-white rounded-xl shadow p-6">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">✏️ Edit Pelanggan</h2>

        <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block text-gray-700 mb-1">Nama</label>
        <input type="text" name="nama" 
               class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400" 
               value="{{ $pelanggan->nama }}" required>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 mb-1">Alamat</label>
        <textarea name="alamat" 
                  class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400">{{ $pelanggan->alamat }}</textarea>
    </div>

    <div class="mb-4">
        <label class="block text-gray-700 mb-1">Telepon</label>
        <input type="text" name="telepon" 
               class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-400"
               value="{{ $pelanggan->telepon }}">
    </div>

    <div class="flex justify-between">
        <a href="{{ route('pelanggan.index') }}" class="text-gray-600 hover:underline">← Kembali</a>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            Update
        </button>
    </div>
</form>

    </div>
</div>
</body>
</html>