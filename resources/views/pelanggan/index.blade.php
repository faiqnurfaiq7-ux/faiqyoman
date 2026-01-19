@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-6">
    <div class="max-w-5xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">📋 Daftar Pelanggan</h1>
            <a href="{{ route('pelanggan.create') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-lg transition">
                + Tambah Pelanggan
            </a>
        </div>

        <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-blue-100 text-gray-700 uppercase text-sm">
                <tr>
                    <th class="px-6 py-3 text-left">Nama</th>
                    <th class="px-6 py-3 text-left">Telepon</th>
                    <th class="px-6 py-3 text-left">Alamat</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-base">
                @forelse ($pelanggan as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $item->nama }}</td>
                        <td class="px-6 py-4">{{ $item->telepon }}</td>
                        <td class="px-6 py-4">{{ $item->alamat }}</td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="{{ route('pelanggan.edit', $item->id) }}" 
                               class="inline-block bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-md font-semibold transition">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('pelanggan.destroy', $item->id) }}" 
                                  method="POST" 
                                  class="inline-block"
                                  onsubmit="return confirm('Yakin ingin hapus pelanggan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md font-semibold transition">
                                    🗑 Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-400 py-6 text-lg">
                            Belum ada data pelanggan 🥺
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
