@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">➕ Tambah Penjual Baru</h1>
        <p class="text-gray-600">Isi form di bawah untuk menambah data penjual baru</p>
    </div>

    <!-- Form -->
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg p-8">
        <form action="{{ route('penjual.data-penjual.store') }}" method="POST">
            @csrf

            <!-- Informasi Pribadi -->
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-blue-500">📋 Informasi Pribadi</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nama') border-red-500 @enderror">
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telepon -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                        <input type="tel" name="telepon" value="{{ old('telepon') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('telepon') border-red-500 @enderror">
                        @error('telepon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Komisi -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Komisi (%) <span class="text-red-500">*</span></label>
                        <input type="number" name="komisi_persen" value="{{ old('komisi_persen', 5) }}" min="0" max="100" step="0.1" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('komisi_persen') border-red-500 @enderror">
                        @error('komisi_persen')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Alamat -->
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-green-500">📍 Alamat</h3>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Alamat Lengkap -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="alamat" rows="3" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Kota -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kota <span class="text-red-500">*</span></label>
                            <input type="text" name="kota" value="{{ old('kota') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('kota') border-red-500 @enderror">
                            @error('kota')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Provinsi -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                            <input type="text" name="provinsi" value="{{ old('provinsi') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('provinsi') border-red-500 @enderror">
                            @error('provinsi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kode Pos -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kode Pos <span class="text-red-500">*</span></label>
                            <input type="text" name="kode_pos" value="{{ old('kode_pos') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('kode_pos') border-red-500 @enderror">
                            @error('kode_pos')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Bank -->
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-purple-500">🏦 Informasi Bank</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Bank -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Bank <span class="text-red-500">*</span></label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="BCA, Mandiri, BNI, dll" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('bank_name') border-red-500 @enderror">
                        @error('bank_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Rekening -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Rekening <span class="text-red-500">*</span></label>
                        <input type="text" name="bank_account" value="{{ old('bank_account') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('bank_account') border-red-500 @enderror">
                        @error('bank_account')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Atas Nama Rekening -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Atas Nama Rekening <span class="text-red-500">*</span></label>
                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('bank_account_name') border-red-500 @enderror">
                        @error('bank_account_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-orange-500">🔄 Status</h3>

                <div class="flex gap-6">
                    <label class="flex items-center">
                        <input type="radio" name="status" value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'checked' : '' }} required class="mr-2">
                        <span class="text-gray-800 font-semibold">✅ Aktif</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="status" value="nonaktif" {{ old('status') === 'nonaktif' ? 'checked' : '' }} required class="mr-2">
                        <span class="text-gray-800 font-semibold">❌ Nonaktif</span>
                    </label>
                </div>
            </div>

            <!-- Tombol -->
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-bold transition">
                    ✅ Simpan Penjual
                </button>
                <a href="{{ route('penjual.data-penjual.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold transition text-center">
                    ❌ Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
