<!DOCTYPE html>
<html>
<head>
    <title>Daftar Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-2">Daftar Produk</h1>

        @if(!empty($q))
            <p class="text-sm text-gray-600 mb-2">Hasil pencarian untuk "<strong>{{ $q }}</strong>": <strong>{{ $produks->total() ?? $produks->count() }}</strong> produk</p>
        @endif

        <a href="{{ route('produk.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">+ Tambah Produk</a>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border">
        <thead>
    <tr class="bg-gray-200">
        <th class="border p-2">ID</th>
        <th class="border p-2">Nama Produk</th>
        <th class="border p-2">Harga</th>
        <th class="border p-2">Stok</th> <!-- Tambah ini -->
        <th class="border p-2">Aksi</th>
    </tr>
</thead>
<tbody>
    @foreach ($produks as $produk)
        <tr>
            <td class="border p-2">{{ $produk->id }}</td>
            <td class="border p-2">
                @if(isset($q) && $q)
                    {{-- Escape the product name first to prevent XSS, then highlight the escaped text --}}
                    {!! preg_replace('/(' . preg_quote($q, '/') . ')/i', '<span style="background:#ffe6b3; padding:1px 3px; border-radius:3px;">$1</span>', e($produk->nama)) !!}
                @else
                    {{ $produk->nama }}
                @endif
            </td>
            <td class="border p-2">Rp {{ number_format($produk->harga) }}</td>
            <td class="border p-2">{{ $produk->stok }}</td> <!-- Tambah ini -->
            <td class="border p-2 text-center">
                <a href="{{ route('produk.edit', $produk->id) }}" class="bg-yellow-400 text-white px-2 py-1 rounded">Edit</a>
                <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Hapus</button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>
        </table>
        <div class="mt-4">
            {{ $produks->links() }}
        </div>
    </div>
</body>
</html>