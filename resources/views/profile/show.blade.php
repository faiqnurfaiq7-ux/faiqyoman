@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center gap-4 mb-6">
        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold overflow-hidden">
            @if($user->foto)
                <img src="{{ asset('storage/' . $user->foto) }}" alt="Profile" class="w-full h-full object-cover">
            @else
                👤
            @endif
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Profile</h1>
            <p class="text-gray-600">Kelola informasi akun Anda</p>
        </div>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Foto Profile -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profile</label>
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                    @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Profile" class="w-full h-full object-cover">
                    @else
                        <span class="text-2xl">👤</span>
                    @endif
                </div>
                <div>
                    <input type="file" name="foto" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF. Max 2MB</p>
                </div>
            </div>
        </div>

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                Simpan Perubahan
            </button>
        </div>
    </form>

    @if(session('success'))
        <div class="mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Social Accounts Section -->
    <div class="mt-8 border-t pt-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4">Koneksi Akun Sosial</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 border border-gray-200 rounded-lg flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/google.svg') }}" alt="Google" class="w-6 h-6" />
                    <div>
                        <div class="text-sm font-medium">Google</div>
                        <div class="text-xs text-gray-500">{{ $user->socialAccounts->where('provider','google')->isNotEmpty() ? 'Tertaut' : 'Tidak tertaut' }}</div>
                    </div>
                </div>
                <div>
                    @if($user->socialAccounts->where('provider','google')->isNotEmpty())
                        <form method="POST" action="{{ route('profile.unlink','google') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-500 hover:text-red-700" onclick="return confirm('Yakin ingin memutus tautan Google?')">Unlink</button>
                        </form>
                    @else
                        <a href="{{ route('social.redirect','google') }}?action=link" class="text-sm text-blue-600 hover:text-blue-700">Link</a>
                    @endif
                </div>
            </div>

            <div class="p-4 border border-gray-200 rounded-lg flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/facebook.svg') }}" alt="Facebook" class="w-6 h-6" />
                    <div>
                        <div class="text-sm font-medium">Facebook</div>
                        <div class="text-xs text-gray-500">{{ $user->socialAccounts->where('provider','facebook')->isNotEmpty() ? 'Tertaut' : 'Tidak tertaut' }}</div>
                    </div>
                </div>
                <div>
                    @if($user->socialAccounts->where('provider','facebook')->isNotEmpty())
                        <form method="POST" action="{{ route('profile.unlink','facebook') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-500 hover:text-red-700" onclick="return confirm('Yakin ingin memutus tautan Facebook?')">Unlink</button>
                        </form>
                    @else
                        <a href="{{ route('social.redirect','facebook') }}?action=link" class="text-sm text-blue-600 hover:text-blue-700">Link</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
