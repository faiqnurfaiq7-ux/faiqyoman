<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kasir Faiq')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Fallback overrides (loads after other styles) -->
    <link rel="stylesheet" href="{{ asset('css/overrides.css') }}">
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="app-sidebar w-64 text-white p-6 fixed h-screen overflow-y-auto">
            <!-- Logo/Brand -->
            <div class="mb-6 flex flex-col items-center">
                <h1 class="text-xl font-bold flex items-center gap-2">
                    <span class="text-2xl">🛍️</span>
                    <span class="menu-label">Kasir Faiq</span>
                </h1>
                <p class="text-slate-400 text-xs mt-1 menu-label">Sistem POS</p>
            </div>

            <!-- Navigation Menu -->
            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-slate-700 transition {{ request()->routeIs('dashboard') ? 'bg-blue-600' : '' }}">
                    <span class="text-xl">📊</span>
                    <span class="font-medium menu-label">Dashboard</span>
                </a>
                <a href="{{ route('kasir.index') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-slate-700 transition {{ request()->routeIs('kasir.*') ? 'bg-blue-600' : '' }}">
                    <span class="text-xl">🛒</span>
                    <span class="font-medium menu-label">Kasir</span>
                </a>
                <a href="{{ route('penjual.index') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-slate-700 transition {{ request()->routeIs('penjual.*') ? 'bg-blue-600' : '' }}">
                    <span class="text-xl">📈</span>
                    <span class="font-medium menu-label">Penjualan</span>
                </a>
                <a href="{{ route('produk.index') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-slate-700 transition {{ request()->routeIs('produk.*') ? 'bg-blue-600' : '' }}">
                    <span class="text-xl">📦</span>
                    <span class="font-medium menu-label">Produk</span>
                </a>
                <a href="{{ route('pelanggan.index') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-slate-700 transition {{ request()->routeIs('pelanggan.*') ? 'bg-blue-600' : '' }}">
                    <span class="text-xl">👥</span>
                    <span class="font-medium menu-label">Pelanggan</span>
                </a>
            </nav>

            <!-- Divider -->
            <div class="border-t border-slate-700 my-8"></div>

            <!-- Secondary Menu -->
            <div class="mb-8">
                <p class="text-slate-400 text-xs font-semibold uppercase px-4 mb-3">Manajemen</p>
                <nav class="space-y-2">
                    <a href="{{ route('penjual.data-penjual.index') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-slate-700 transition">
                        <span class="text-xl">👨‍💼</span>
                        <span class="font-medium menu-label">Data Penjual</span>
                    </a>
                    <a href="{{ route('penjual.report') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-slate-700 transition">
                        <span class="text-xl">📋</span>
                        <span class="font-medium menu-label">Laporan</span>
                    </a>
                </nav>
            </div>

            <!-- Footer -->
            <div class="mt-auto pt-8 border-t border-slate-700">
                <div class="bg-slate-700 rounded-lg p-3 text-center">
                    <p class="text-slate-300 text-xs mb-2 menu-label">👤 Admin</p>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-red-400 hover:text-red-300 text-xs font-medium transition menu-label">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
    <main class="flex-1 main-compact">
            <!-- Topbar -->
            <div class="topbar px-6">
                <div style="width:24px"></div>
                <div style="display:flex; align-items:center; gap:12px; width:100%;">
                    <form action="{{ route('produk.index') }}" method="GET" class="search-input" role="search" style="flex:1; display:flex; align-items:center; gap:8px;">
                        <input type="search" name="q" value="{{ request()->query('q') }}" placeholder="Cari produk, merek, atau kategori..." style="width:100%; padding:.5rem .6rem; border-radius:8px; border:1px solid #e6edf3; background:#fff">
                        <button class="search-btn" style="background:#ff6a00; color:#fff; border-radius:8px; padding:.45rem .6rem; border:none">Cari</button>
                    </form>

                    <div style="display:flex; gap:10px; align-items:center; margin-left:12px;">
                        <button class="icon-btn" title="Keranjang">🛒</button>
                        <button class="icon-btn" title="Notifikasi">🔔</button>

                        <!-- Profile dropdown -->
                        <div style="position:relative">
                            <button id="profile-btn" class="profile-btn" onclick="toggleProfileMenu()">Hi, Admin ▾</button>
                            <div id="profile-menu" style="display:none; position:absolute; right:0; margin-top:8px; background:#fff; border:1px solid #e6edf3; border-radius:8px; box-shadow:0 8px 20px rgba(0,0,0,0.06); min-width:160px;">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700">Profil</a>
                                <button class="block w-full text-left px-4 py-2 text-sm text-red-600" onclick="showLogoutModal()">Logout</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>

<script>
// Simple debounce
function debounce(fn, wait) {
    let t;
    return function(...args){ clearTimeout(t); t = setTimeout(()=>fn.apply(this,args), wait); }
}

document.addEventListener('DOMContentLoaded', function(){
    const form = document.querySelector('form.search-input');
    if(!form) return;
    const input = form.querySelector('input[name="q"]');

    // create suggestions container
    const wrapper = document.createElement('div');
    wrapper.className = 'search-suggestions';
    wrapper.style.display = 'none';
    form.style.position = 'relative';
    form.appendChild(wrapper);

    async function fetchSuggest(q){
        if(!q) { wrapper.innerHTML=''; wrapper.style.display='none'; return; }
        try{
            const res = await fetch(`{{ route('produk.suggest') }}?q=${encodeURIComponent(q)}`);
            if(!res.ok) throw new Error('Network');
            const items = await res.json();
            render(items);
        }catch(e){ console.error(e); }
    }

    function render(items){
        if(!items || items.length===0){ wrapper.innerHTML=''; wrapper.style.display='none'; return; }
        wrapper.innerHTML = '';
        items.forEach(it=>{
            const el = document.createElement('div');
            el.className='item';
            el.innerHTML = `
                <img src="${it.foto || '/images/placeholder.png'}" alt="${it.nama}">
                <div class="meta">
                    <div class="name">${escapeHtml(it.nama)}</div>
                    <div class="price">Rp ${numberWithCommas(it.harga)}</div>
                </div>`;
            el.addEventListener('click', ()=>{
                input.value = it.nama;
                form.submit();
            });
            wrapper.appendChild(el);
        });
        wrapper.style.display='block';
    }

    function escapeHtml(s){ return String(s).replace(/[&<>"']/g, function(c){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'})[c]; }); }
    function numberWithCommas(x){ return x==null? '' : x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }

    input.addEventListener('input', debounce(function(e){ fetchSuggest(e.target.value); }, 250));

    document.addEventListener('click', function(ev){ if(!form.contains(ev.target)){ wrapper.style.display='none'; } });
});
</script>

</body>
</html>

<!-- Logout confirmation modal -->
<div id="logout-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:200; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:18px; border-radius:8px; width:320px; margin:auto;">
        <h3 style="font-weight:700; margin-bottom:8px">Konfirmasi Logout</h3>
        <p style="color:#444; margin-bottom:12px">Apakah Anda yakin ingin logout?</p>
        <div style="display:flex; gap:8px; justify-content:flex-end;">
            <button onclick="hideLogoutModal();" style="padding:8px 12px; border-radius:6px; border:1px solid #e5e7eb; background:#fff">Batal</button>
            <button onclick="document.getElementById('logout-form').submit();" style="padding:8px 12px; border-radius:6px; background:#ef4444; color:#fff; border:none">Ya, Logout</button>
        </div>
    </div>
</div>

<script>
function showLogoutModal(){ document.getElementById('logout-modal').style.display='flex'; }
function hideLogoutModal(){ document.getElementById('logout-modal').style.display='none'; }
</script>

<script>
function toggleProfileMenu(){
    const el = document.getElementById('profile-menu');
    if(!el) return;
    el.style.display = (el.style.display === 'block') ? 'none' : 'block';
}
document.addEventListener('click', function(e){
    const menu = document.getElementById('profile-menu');
    const btn = document.getElementById('profile-btn');
    if(!menu || !btn) return;
    if(!btn.contains(e.target) && !menu.contains(e.target)){
        menu.style.display = 'none';
    }
});
</script>
