@extends('layouts.app')

@section('content')
<div class="p-10 bg-blue-400 min-h-screen">
    <!-- Judul Halaman -->
    <h2 class="text-3xl font-bold mb-8 flex items-center gap-2 text-blue-400">
        🛒 <span>Kasir Penjualan</span>
    </h2>

    <!-- Grid Kasir -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Daftar Produk -->
        <div class="md:col-span-2 bg-white p-6 rounded-2xl shadow-lg">
            <h3 class="text-xl font-semibold mb-4 text-gray-700">🧃 Daftar Produk</h3>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($produks as $produk)
                    <div class="border rounded-2xl shadow hover:shadow-lg p-4 flex flex-col items-center bg-gray-50 transition">
                        <img src="{{ asset('images/'.$produk->gambar) }}" class="w-24 h-24 object-cover rounded-lg mb-3">
                        <h4 class="font-semibold text-gray-800">{{ $produk->nama }}</h4>
                        <p class="text-green-600 font-bold">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                        <button 
                            wire:click="tambahKeKeranjang({{ $produk->id }})"
                            class="mt-3 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-xl transition">
                            + Tambah
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Keranjang Belanja -->
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <h3 class="text-xl font-semibold mb-4 text-gray-700">🧾 Keranjang</h3>

            @if(count($keranjang) > 0)
                <div class="space-y-3 mb-4">
                    @foreach($keranjang as $item)
                        <div class="flex justify-between items-center border-b pb-2">
                            <div>
                                <p class="font-semibold">{{ $item['nama'] }}</p>
                                <p class="text-sm text-gray-500">Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="bg-gray-300 px-2 rounded-lg" wire:click="kurangi({{ $item['id'] }})">-</button>
                                <span>{{ $item['qty'] }}</span>
                                <button class="bg-gray-300 px-2 rounded-lg" wire:click="tambah({{ $item['id'] }})">+</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-between items-center font-semibold text-lg border-t pt-3">
                    <p>Total:</p>
                    <p class="text-green-700">Rp {{ number_format($total, 0, ',', '.') }}</p>
                </div>

                <button class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-2xl font-bold transition">
                    💰 Bayar Sekarang
                </button>
            @else
                <p class="text-center text-gray-500">Keranjang masih kosong 🛍️</p>
            @endif
        </div>
    </div>
</div>
@endsection
