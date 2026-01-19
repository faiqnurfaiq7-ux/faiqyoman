@extends('layouts.app')

@section('content')
<div class="p-8 max-w-lg mx-auto">
    <h2 class="text-2xl font-semibold mb-4">Profil Saya</h2>

    @if(session('success'))
        <div class="p-3 bg-green-50 text-green-700 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-3 bg-red-50 text-red-700 rounded mb-4">{{ session('error') }}</div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow">
        <p class="text-sm text-gray-600">Nama: <strong>{{ $user->name }}</strong></p>
        <p class="text-sm text-gray-600">Email: <strong>{{ $user->email }}</strong></p>

        <hr class="my-4">

        <h3 class="text-lg font-medium mb-2">Koneksi Akun Sosial</h3>
        <div class="grid grid-cols-2 gap-3">
            <div class="p-3 border rounded flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/google.svg') }}" alt="Google" class="w-6 h-6" />
                    <div>
                        <div class="text-sm font-medium">Google</div>
                        <div class="text-xs text-gray-500">{{ $user->socialAccounts->where('provider','google')->isNotEmpty() ? 'Tertaut' : 'Tidak tertaut' }}</div>
                    </div>
                </div>
                <div>
                    @if($user->socialAccounts->where('provider','google')->isNotEmpty())
                        <form method="POST" action="{{ route('profile.unlink','google') }}" class="unlink-form">
                            @csrf
                            <button type="submit" class="text-sm text-red-500 unlink-btn" data-provider="google">Unlink</button>
                        </form>
                    @else
                        <a href="{{ route('social.redirect','google') }}?action=link" class="text-sm text-blue-600">Link</a>
                    @endif
                </div>
            </div>

            <div class="p-3 border rounded flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/facebook.svg') }}" alt="Facebook" class="w-6 h-6" />
                    <div>
                        <div class="text-sm font-medium">Facebook</div>
                        <div class="text-xs text-gray-500">{{ $user->socialAccounts->where('provider','facebook')->isNotEmpty() ? 'Tertaut' : 'Tidak tertaut' }}</div>
                    </div>
                </div>
                <div>
                    @if($user->socialAccounts->where('provider','facebook')->isNotEmpty())
                        <form method="POST" action="{{ route('profile.unlink','facebook') }}" class="unlink-form">
                            @csrf
                            <button type="submit" class="text-sm text-red-500 unlink-btn" data-provider="facebook">Unlink</button>
                        </form>
                    @else
                        <a href="{{ route('social.redirect','facebook') }}?action=link" class="text-sm text-blue-600">Link</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.unlink-form').forEach(function(form){
        form.addEventListener('submit', function(e){
            e.preventDefault();
            const provider = form.querySelector('.unlink-btn')?.dataset?.provider || 'this provider';
            if (!confirm('Yakin ingin memutus tautan ' + provider + '?')) return;
            form.submit();
        });
    });
});
</script>
@endpush
