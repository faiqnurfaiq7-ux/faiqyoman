<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FAIQ YOMAN SHOP </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('https://www.deviantart.com/alberth-kill2590/art/Metallica-Hardwired-to-self-destruct-Wallpaper-667365555');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-opacity-60 backdrop-blur-md">

    <div class="min-h-screen flex items-center justify-center bg-gray-50 p-6">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-6 text-center bg-gradient-to-r from-orange-400 via-orange-300 to-orange-500 text-white">
                    <h1 class="text-3xl font-extrabold tracking-wide">M NUR FAIQ</h1>
                    <p class="mt-1 text-sm opacity-90">Selamat datang, masuk untuk melanjutkan</p>
                </div>

                <div class="p-8">
                    <h3 class="text-lg text-gray-700 font-semibold text-center mb-6">LOGIN ACCOUNT</h3>

                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm text-gray-600 mb-2" for="email">Email</label>
                            <div class="flex items-center bg-gray-50 rounded-lg border border-gray-100 px-3 py-2 focus-within:ring-2 focus-within:ring-orange-300">
                                <svg class="w-5 h-5 text-orange-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 0a4 4 0 10-8 0 4 4 0 008 0z"></path></svg>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="flex-1 bg-transparent outline-none text-sm" placeholder="you@example.com" />
                            </div>
                            @error('email') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm text-gray-600 mb-2" for="password">Password</label>
                            <div class="flex items-center bg-gray-50 rounded-lg border border-gray-100 px-3 py-2 focus-within:ring-2 focus-within:ring-orange-300">
                                <svg class="w-5 h-5 text-orange-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3V6a3 3 0 10-6 0v2c0 1.657 1.343 3 3 3z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11h14v8H5z"></path></svg>
                                <input id="password" type="password" name="password" required class="flex-1 bg-transparent outline-none text-sm" placeholder="Masukkan password" />
                            </div>
                            @error('password') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6 flex items-center justify-between text-sm">
                            <label class="inline-flex items-center text-gray-600"><input type="checkbox" name="remember" class="form-checkbox h-4 w-4 text-orange-400"> <span class="ml-2">Ingat saya</span></label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-orange-500 font-medium hover:underline">Lupa password?</a>
                            @else
                                <span class="text-gray-400">&nbsp;</span>
                            @endif
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="w-full py-3 rounded-lg bg-gradient-to-r from-orange-400 to-orange-500 text-white font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition">Masuk</button>
                        </div>

                        <div class="text-center text-sm text-gray-600">
                            Belum punya akun?
                            @if (Route::has('register.form'))
                                <a href="{{ route('register.form') }}" class="text-orange-500 font-semibold hover:underline">Daftar Sekarang</a>
                            @else
                                <span class="text-gray-400">Daftar (tidak tersedia)</span>
                            @endif
                        </div>
                    </form>

                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-100"></div>
                            </div>
                            <div class="relative flex justify-center text-xs">
                                <span class="bg-white px-3 text-gray-400">atau masuk dengan</span>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <a href="{{ route('social.redirect', 'google') }}" class="social-btn flex items-center justify-center py-2 rounded-lg border border-gray-200 hover:bg-gray-50" aria-label="Login dengan Google">
                                <img src="{{ asset('images/google.svg') }}" alt="Google" class="w-5 h-5 mr-2"/>
                                <span class="text-sm">Google</span>
                            </a>
                            <a href="{{ route('social.redirect', 'facebook') }}" class="social-btn flex items-center justify-center py-2 rounded-lg border border-gray-200 hover:bg-gray-50" aria-label="Login dengan Facebook">
                                <img src="{{ asset('images/facebook.svg') }}" alt="Facebook" class="w-5 h-5 mr-2"/>
                                <span class="text-sm">Facebook</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    </div>

</body>
</html>