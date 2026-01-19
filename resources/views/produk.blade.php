<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Produk</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

  <div class="max-w-5xl mx-auto mt-10 p-6 bg-white shadow rounded-2xl">
    <h1 class="text-2xl font-bold mb-4 text-center">Daftar Produk</h1>

    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-200">
        <thead class="bg-gray-200">
          <tr>
            <th class="border px-4 py-2 text-left">ID</th>
            <th class="border px-4 py-2 text-left">Nama Barang</th>
            <th class="border px-4 py-2 text-left">Harga</th>
            <th class="border px-4 py-2 text-left">Stok</th>
          </tr>
        </thead>
        <tbody>
          @foreach($produk as $item)
          <tr class="hover:bg-gray-100">
            <td class="border px-4 py-2">{{ $item->id }}</td>
            <td class="border px-4 py-2">{{ $item->nama }}</td>
            <td class="border px-4 py-2">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
            <td class="border px-4 py-2">{{ $item->stok }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="text-center mt-6">
      <a href="/dashboard" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Kembali ke Dashboard</a>
    </div>
  </div>

</body>
</html>
