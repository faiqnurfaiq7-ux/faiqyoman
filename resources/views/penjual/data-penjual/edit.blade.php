@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">✏️ Edit Data Penjual</h1>
        <p class="text-gray-600">Perbarui informasi penjual: {{ $penjual->nama }}</p>
    </div>

    <!-- Alert Error -->
    @if ($errors->any())
        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-2xl shadow-lg">
            <h3 class="font-bold mb-2">❌ Ada kesalahan dalam pengisian form:</h3>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg p-8">
        <form action="{{ route('penjual.data-penjual.update', $penjual) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Informasi Pribadi -->
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-blue-500">📋 Informasi Pribadi</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $penjual->nama) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nama') border-red-500 @enderror">
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $penjual->email) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telepon -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                        <input type="tel" name="telepon" value="{{ old('telepon', $penjual->telepon) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('telepon') border-red-500 @enderror">
                        @error('telepon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto Profil -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Foto Profil</label>
                        @if($penjual->foto)
                            <div class="mb-3">
                                <img src="{{ $penjual->foto_url }}" alt="Foto {{ $penjual->nama }}" class="w-20 h-20 rounded-lg object-cover border">
                            </div>
                        @endif
                        <input type="file" name="foto" accept="image/jpeg,image/png,image/gif,image/webp"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('foto') border-red-500 @enderror">
                        <p class="text-gray-500 text-xs mt-1">Format: JPG, PNG, GIF. Maksimal 2MB. Biarkan kosong jika tidak ingin mengubah foto.</p>
                        @error('foto')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Komisi -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Komisi (%) <span class="text-red-500">*</span></label>
                        <input type="number" name="komisi_persen" value="{{ old('komisi_persen', $penjual->komisi_persen) }}" min="0" max="100" step="0.1" required
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
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('alamat') border-red-500 @enderror">{{ old('alamat', $penjual->alamat) }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Kota -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kota <span class="text-red-500">*</span></label>
                            <input type="text" name="kota" value="{{ old('kota', $penjual->kota) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('kota') border-red-500 @enderror">
                            @error('kota')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Provinsi -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                            <input type="text" name="provinsi" value="{{ old('provinsi', $penjual->provinsi) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('provinsi') border-red-500 @enderror">
                            @error('provinsi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kode Pos -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kode Pos <span class="text-red-500">*</span></label>
                            <input type="text" name="kode_pos" value="{{ old('kode_pos', $penjual->kode_pos) }}" required
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
                        <input type="text" name="bank_name" value="{{ old('bank_name', $penjual->bank_name) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('bank_name') border-red-500 @enderror">
                        @error('bank_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Rekening -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Rekening <span class="text-red-500">*</span></label>
                        <input type="text" name="bank_account" value="{{ old('bank_account', $penjual->bank_account) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('bank_account') border-red-500 @enderror">
                        @error('bank_account')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Atas Nama Rekening -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Atas Nama Rekening <span class="text-red-500">*</span></label>
                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $penjual->bank_account_name) }}" required
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
                        <input type="radio" name="status" value="aktif" {{ old('status', $penjual->status) === 'aktif' ? 'checked' : '' }} required class="mr-2">
                        <span class="text-gray-800 font-semibold">✅ Aktif</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="status" value="nonaktif" {{ old('status', $penjual->status) === 'nonaktif' ? 'checked' : '' }} required class="mr-2">
                        <span class="text-gray-800 font-semibold">❌ Nonaktif</span>
                    </label>
                </div>
            </div>

            <!-- Tombol -->
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-bold transition">
                    ✅ Simpan Perubahan
                </button>
                <a href="{{ route('penjual.data-penjual.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold transition text-center">
                    ❌ Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
