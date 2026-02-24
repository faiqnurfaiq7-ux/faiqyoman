@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="space-y-6 p-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6 animate-fade-in">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">🛍️ Daftar Produk</h1>
                @if(!empty($q))
                    <p class="text-sm text-gray-600">Hasil pencarian untuk "<strong class="text-blue-600">{{ $q }}</strong>": <strong class="text-green-600">{{ $produks->total() }}</strong> produk ditemukan</p>
                @else
                    <p class="text-sm text-gray-600">Menampilkan <strong class="text-green-600">{{ $produks->count() }}</strong> dari <strong class="text-blue-600">{{ $produks->total() }}</strong> produk</p>
                @endif
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('produk.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-medium shadow-md hover:shadow-lg transition-all duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Produk
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg animate-slide-up">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Products Grid -->
    @if($produks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($produks as $produk)
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl card-hover transition-all duration-300 animate-fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <!-- Product Image -->
                    <div class="relative overflow-hidden rounded-t-xl">
                        <img src="{{ $produk->foto_url }}" alt="{{ $produk->nama }}"
                             class="w-full h-48 object-cover transition-transform duration-300 hover:scale-105"
                             onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
                        <div class="absolute top-3 right-3">
                            <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                                ID: {{ $produk->id }}
                            </span>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">
                            @if(isset($q) && $q)
                                {!! preg_replace('/(' . preg_quote($q, '/') . ')/i', '<span class="bg-yellow-200 px-1 rounded">$1</span>', e($produk->nama)) !!}
                            @else
                                {{ $produk->nama }}
                            @endif
                        </h3>

                        <div class="flex items-center justify-between mb-3">
                            <span class="text-2xl font-bold text-green-600">
                                Rp {{ number_format($produk->harga, 0, ',', '.') }}
                            </span>
                            <span class="text-sm text-gray-500">
                                Stok: <strong class="{{ $produk->stok > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $produk->stok }}</strong>
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('produk.edit', $produk->id) }}"
                               class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2 px-4 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" class="flex-1"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-center">
                {{ $produks->appends(request()->query())->links() }}
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <div class="max-w-md mx-auto">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-5v2m0 0v2m0-2h2m-2 0h-2"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Tidak ada produk ditemukan</h3>
                <p class="text-gray-600 mb-6">
                    @if($q)
                        Tidak ada produk yang cocok dengan pencarian "<strong>{{ $q }}</strong>".
                    @else
                        Belum ada produk yang ditambahkan ke sistem.
                    @endif
                </p>
                <a href="{{ route('produk.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium inline-block transition-colors duration-200">
                    Tambah Produk Pertama
                </a>
            </div>
        </div>
    @endif
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideUp {
        from { transform: translateY(10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    .animate-slide-up {
        animation: slideUp 0.3s ease-out;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection