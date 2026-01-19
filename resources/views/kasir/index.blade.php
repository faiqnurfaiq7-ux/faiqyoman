@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-8">
    <!-- Dashboard Kasir Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-4xl font-bold text-gray-800">📊 Dashboard Kasir</h1>
            <div class="text-right">
                <p class="text-gray-600">Tanggal: {{ now()->format('d M Y') }}</p>
                <p class="text-gray-600">Waktu: <span id="current-time">{{ now()->format('H:i:s') }}</span></p>
            </div>
        </div>

        <!-- Statistik Ringkas Hari Ini -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Transaksi Hari Ini</p>
                        <h3 class="text-3xl font-bold text-blue-600">{{ $totalTransactionsToday }}</h3>
                    </div>
                    <div class="text-5xl text-blue-200">📝</div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Pendapatan Hari Ini</p>
                        <h3 class="text-2xl font-bold text-green-600">Rp {{ number_format($totalRevenueToday, 0, ',', '.') }}</h3>
                    </div>
                    <div class="text-5xl text-green-200">💰</div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Rata-rata Transaksi</p>
                        <h3 class="text-2xl font-bold text-yellow-600">Rp {{ number_format($avgTransactionToday, 0, ',', '.') }}</h3>
                    </div>
                    <div class="text-5xl text-yellow-200">📊</div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Item Terjual</p>
                        <h3 class="text-3xl font-bold text-purple-600">{{ $totalItemsSoldToday }}</h3>
                    </div>
                    <div class="text-5xl text-purple-200">📦</div>
                </div>
            </div>
        </div>

        <!-- Dashboard Row: Chart + Info -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Chart: Transaksi Per Jam -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow">
                <h3 class="text-lg font-bold text-gray-800 mb-4">📈 Transaksi Per Jam Hari Ini</h3>
                <canvas id="chartHourlyTransactions" height="80"></canvas>
            </div>

            <!-- Info: Top Produk & Metode Pembayaran -->
            <div class="space-y-4">
                <!-- Top Produk Hari Ini -->
                <div class="bg-white p-6 rounded-2xl shadow">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">🏆 Top Produk Hari Ini</h3>
                    <div class="space-y-2">
                        @forelse($topProductsToday as $product)
                        <div class="flex justify-between items-center text-sm border-b pb-2">
                            <span class="font-semibold text-gray-700">{{ $product->nama }}</span>
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">{{ $product->qty }} unit</span>
                        </div>
                        @empty
                        <p class="text-gray-400 text-center text-sm">Belum ada transaksi</p>
                        @endforelse
                    </div>
                </div>

                <!-- Metode Pembayaran Hari Ini -->
                <div class="bg-white p-6 rounded-2xl shadow">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">💳 Metode Pembayaran</h3>
                    <div class="space-y-2">
                        @php
                            $methods = ['QRIS' => ['icon' => '💳', 'label' => 'QRIS'], 'BANK' => ['icon' => '🏦', 'label' => 'Bank'], 'PAYLETTER' => ['icon' => '📱', 'label' => 'PayLetter'], 'CASH' => ['icon' => '💵', 'label' => 'Cash']];
                        @endphp
                        @foreach($methods as $key => $method)
                            @php $data = $paymentBreakdown[$key] ?? null; @endphp
                            <div class="flex justify-between items-center text-sm border-b pb-2">
                                <span class="font-semibold text-gray-700">{{ $method['icon'] }} {{ $method['label'] }}</span>
                                <div class="text-right">
                                    <p class="font-bold">{{ $data ? $data['count'] : 0 }}</p>
                                    <p class="text-xs text-gray-500">Rp {{ number_format($data ? $data['total'] : 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Cepat & Akses -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">⚡ Menu Cepat</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <a href="{{ route('kasir.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-2xl shadow transition text-center">
                    <div class="text-3xl mb-2">🛒</div>
                    <span class="font-semibold">Kasir</span>
                </a>
                <a href="{{ route('penjual.index') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white p-4 rounded-2xl shadow transition text-center">
                    <div class="text-3xl mb-2">📈</div>
                    <span class="font-semibold">Penjual</span>
                </a>
                <a href="{{ route('laporan.index') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-2xl shadow transition text-center">
                    <div class="text-3xl mb-2">📊</div>
                    <span class="font-semibold">Laporan</span>
                </a>
                <a href="{{ route('produk.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-2xl shadow transition text-center">
                    <div class="text-3xl mb-2">📦</div>
                    <span class="font-semibold">Produk</span>
                </a>
                <a href="{{ route('pelanggan.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-2xl shadow transition text-center">
                    <div class="text-3xl mb-2">👥</div>
                    <span class="font-semibold">Pelanggan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Separator -->
    <hr class="my-8 border-gray-300">

    <!-- Menu Penjualan (Kasir Section) -->
    <h2 class="text-3xl font-bold mb-6 flex items-center">
        🛍️ Menu Penjualan
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Bagian kiri: daftar produk -->
        <div class="md:col-span-2">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($produk as $item)
                <div class="bg-white shadow-md rounded-2xl p-4 text-center hover:shadow-lg transition transform hover:scale-105">
                    <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}" class="w-20 h-20 object-cover rounded mx-auto mb-3">
                    <h3 class="font-semibold text-gray-800 truncate">{{ $item->nama }}</h3>
                    <p class="text-sm text-gray-500 mb-2">Stok: <span class="font-bold text-{{ $item->stok > 0 ? 'green' : 'red' }}-600">{{ $item->stok }}</span></p>
                    <p class="text-blue-600 font-bold mb-3">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                    <button onclick="bukaModalPembelian({{ $item->id }}, '{{ $item->nama }}', {{ $item->harga }}, {{ $item->stok }})"
                        {{ $item->stok == 0 ? 'disabled' : '' }}
                        class="w-full {{ $item->stok == 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-600' }} text-white px-3 py-2 rounded-xl text-sm font-semibold transition">
                        {{ $item->stok == 0 ? '❌ Habis' : '🛒 Beli' }}
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Bagian kanan: keranjang -->
        <div class="bg-white p-6 rounded-2xl shadow-md">
            <h3 class="text-xl font-bold mb-4">🧾 Keranjang</h3>
            <div id="keranjang-list" class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                <p class="text-gray-400 text-center" id="empty-cart">Belum ada item 😅</p>
            </div>
            <hr class="my-4">
            <div class="bg-blue-100 p-3 rounded-lg mb-4">
                <div class="flex justify-between font-bold text-xl text-blue-900">
                    <span>Total:</span>
                    <span id="total">Rp 0</span>
                </div>
                <div class="flex justify-between text-sm text-blue-700 mt-1">
                    <span>Item:</span>
                    <span id="total-items">0</span>
                </div>
            </div>

            <!-- Input nama pembeli -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pembeli (opsional):</label>
                <input id="buyer-name" type="text" placeholder="Masukkan nama pembeli" class="w-full px-3 py-2 border rounded-lg" />
            </div>

            <!-- Pilihan Metode Pembayaran -->
            <div class="mt-6 mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Metode Pembayaran:</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="payment_method" value="QRIS" checked class="mr-2">
                        <span class="text-sm">💳 QRIS</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="payment_method" value="CASH" class="mr-2">
                        <span class="text-sm">💵 Cash</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="payment_method" value="BANK" class="mr-2">
                        <span class="text-sm">🏦 Bank Transfer</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="payment_method" value="PAYLETTER" class="mr-2">
                        <span class="text-sm">📱 PayLetter</span>
                    </label>
                </div>
            </div>

            <div class="mt-3">
                <button id="btn-scan-qris" onclick="openQrisScanner()" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold">🔍 Scan QRIS</button>
                <p id="qris-status" class="text-sm text-gray-500 mt-2 text-center">Belum memindai</p>
            </div>

            <button onclick="checkout()"
                class="w-full mt-6 bg-green-500 hover:bg-green-600 text-white py-3 rounded-xl font-semibold">
                💰 Selesaikan Transaksi
            </button>
        </div>
    </div>
</div>

<!-- ===== QRIS fallback (upload / paste) - hidden by default ===== -->
<div id="qris-fallback" class="hidden fixed bottom-6 right-6 bg-white p-4 rounded-lg shadow-lg w-80 z-50">
    <h4 class="font-semibold mb-2">Fallback Scanner</h4>
    <p class="text-sm text-gray-600 mb-2">Jika pemindaian otomatis gagal, Anda dapat unggah gambar QR atau paste payload di sini.</p>
    <input type="file" accept="image/*" onchange="qrisFallbackUseFile(this)" class="mb-2 w-full" />
    <textarea id="qris-fallback-text" placeholder="Paste payload QR / kode di sini" class="w-full p-2 border rounded mb-2" rows="3"></textarea>
    <div class="flex gap-2">
        <button onclick="qrisFallbackUseText()" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-1 rounded">Gunakan Payload</button>
        <button onclick="document.getElementById('qris-fallback').classList.add('hidden')" class="bg-gray-200 px-3 rounded">Tutup</button>
    </div>
</div>

<!-- ===== MODAL NOTA / RECEIPT ===== -->
<div id="modal-nota" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-96 p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-lg font-bold">🧾 Nota Transaksi</h3>
                <p class="text-sm text-gray-500">{{ config('app.name', 'Toko') }} · <span id="nota-time">-</span></p>
            </div>
            <button onclick="document.getElementById('modal-nota').classList.add('hidden')" class="text-sm text-gray-500">Tutup</button>
        </div>

        <!-- Nota header -->
        <div class="mb-3 flex items-center gap-4">
            @if (file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="logo" class="w-14 h-14 rounded" />
            @else
                <img src="{{ asset('images/logo-compact.png') }}" alt="logo" class="w-14 h-14 rounded" />
            @endif
            <div>
                <p class="text-sm text-gray-500">Invoice:</p>
                <p id="nota-invoice" class="font-semibold text-gray-800">-</p>
                <div class="text-sm text-gray-600 mt-1">
                    <span id="nota-buyer">-</span> · <span id="nota-metode">-</span>
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    <div>{{ config('app.name', 'Toko') }}</div>
                    <div>{{ env('STORE_ADDRESS', 'Jl. Contoh No.1, Kota') }}</div>
                    <div>{{ env('STORE_PHONE', '0812-3456-7890') }}</div>
                    @if(env('STORE_NPWP'))
                        <div>NPWP: {{ env('STORE_NPWP') }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mb-3">
            <ul id="nota-items" class="list-none text-sm text-gray-700 mb-2">
                <!-- JS will insert li elements like: <li class="flex justify-between"><span>Nama x qty</span><span>Rp ...</span></li> -->
            </ul>
            <div class="flex justify-between items-center mt-2">
                <div class="text-sm font-semibold">Total</div>
                <div id="nota-total" class="text-sm font-semibold text-green-600">-</div>
            </div>
        </div>

        <div class="mb-3">
            <p class="text-sm text-gray-600">QRIS payload:</p>
            <p id="nota-qris" class="text-xs text-gray-700 break-all">-</p>
        </div>

        <!-- Nota footer: return policy / terms -->
        <div class="mt-4 pt-3 border-t text-xs text-gray-600">
            <p class="font-semibold text-sm text-gray-700">Kebijakan Pengembalian (singkat):</p>
            <p id="nota-return-short">Barang dapat dikembalikan dalam 14 hari jika cacat produksi atau salah kirim. Tunjukkan nota asli.</p>
            <button onclick="toggleReturnPolicy()" class="mt-2 text-xs text-blue-600 underline">Lihat selengkapnya</button>
            <div id="nota-return-full" class="hidden mt-2 text-xs text-gray-600">{{ env('STORE_RETURN_POLICY', 'Pengembalian barang diterima dalam jangka waktu 14 hari sejak tanggal pembelian dengan ketentuan: (1) pembeli menunjukkan nota asli; (2) barang dalam kondisi belum dipakai dan kemasan asli utuh; (3) pengembalian disebabkan cacat produksi atau kesalahan pengiriman. Untuk pengembalian yang bukan disebabkan oleh kesalahan toko, biaya pengiriman pengembalian menjadi tanggung jawab pembeli. Produk yang telah dibuka segel atau dipasang (mis. software, beberapa elektronik) tidak dapat dikembalikan.') }}</div>
        </div>

        <div class="flex gap-2">
            <button onclick="window.print()" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded">Cetak</button>
            <button onclick="document.getElementById('modal-nota').classList.add('hidden')" class="bg-gray-200 px-3 rounded">Tutup</button>
        </div>
    </div>
</div>

<script>
    let keranjang = [];

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    // ====== MODAL PEMBELIAN ======
    function bukaModalPembelian(id, nama, harga, stok) {
        document.getElementById('modal-produk-id').value = id;
        document.getElementById('modal-produk-nama').textContent = nama;
        document.getElementById('modal-produk-harga').textContent = formatRupiah(harga);
        document.getElementById('modal-produk-stok').value = stok;
        document.getElementById('modal-jumlah').value = 1;
        document.getElementById('modal-jumlah').max = stok;
        document.getElementById('modal-subtotal').textContent = formatRupiah(harga);
        document.getElementById('modal-pembelian').classList.remove('hidden');
    }

    function tutupModalPembelian() {
        document.getElementById('modal-pembelian').classList.add('hidden');
    }

    function updateSubtotal() {
        const hargaText = document.getElementById('modal-produk-harga').textContent;
        const harga = parseInt(hargaText.replace(/[^0-9]/g, ''));
        const jumlah = parseInt(document.getElementById('modal-jumlah').value) || 1;
        const subtotal = harga * jumlah;
        document.getElementById('modal-subtotal').textContent = formatRupiah(subtotal);
    }

    function kurangiJumlah() {
        const input = document.getElementById('modal-jumlah');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
            updateSubtotal();
        }
    }

    function tambahJumlah() {
        const input = document.getElementById('modal-jumlah');
        const stok = parseInt(document.getElementById('modal-produk-stok').value);
        if (parseInt(input.value) < stok) {
            input.value = parseInt(input.value) + 1;
            updateSubtotal();
        }
    }

    function konfirmasiPembelian() {
        const id = parseInt(document.getElementById('modal-produk-id').value);
        const nama = document.getElementById('modal-produk-nama').textContent;
        const hargaText = document.getElementById('modal-produk-harga').textContent;
        const harga = parseInt(hargaText.replace(/[^0-9]/g, ''));
        const jumlah = parseInt(document.getElementById('modal-jumlah').value);

        tambahKeranjang(id, nama, harga, jumlah);
        tutupModalPembelian();
    }

    // ====== QRIS SCANNER (dynamic load html5-qrcode) ======
    let _html5QrcodeScanner = null;
    let _html5QrcodeContainerId = 'qrcode-reader';

    function _loadHtml5QrcodeLib(callback) {
        if (window.Html5Qrcode) {
            return callback();
        }
        const s = document.createElement('script');
        s.src = 'https://unpkg.com/html5-qrcode@2.3.7/minified/html5-qrcode.min.js';
        s.onload = () => callback();
        s.onerror = () => {
            // Tampilkan fallback agar user tetap bisa memasukkan payload QR
            console.warn('Gagal memuat html5-qrcode dari CDN, menampilkan fallback input.');
            const fallback = document.getElementById('qris-fallback');
            if (fallback) fallback.classList.remove('hidden');
            const statusEl = document.getElementById('qris-status');
            if (statusEl) statusEl.textContent = 'Library scanner tidak tersedia, gunakan upload/paste payload.';
        };
        document.head.appendChild(s);
    }

    function openQrisScanner() {
        const statusEl = document.getElementById('qris-status');
        statusEl.textContent = 'Mempersiapkan kamera...';

        _loadHtml5QrcodeLib(async () => {
            try {
                // buat modal dan start scanner
                document.getElementById('modal-qris').classList.remove('hidden');

                // jika sudah ada instance, clear dulu
                if (_html5QrcodeScanner) {
                    try { await _html5QrcodeScanner.stop(); } catch(e){}
                    _html5QrcodeScanner.clear().catch(()=>{});
                    _html5QrcodeScanner = null;
                }

                const html5Qr = new Html5Qrcode(_html5QrcodeContainerId);
                _html5QrcodeScanner = html5Qr;

                const cameras = await Html5Qrcode.getCameras();
                const cameraId = (cameras && cameras.length) ? cameras[0].id : null;
                if (!cameraId) {
                    alert('Kamera tidak ditemukan pada perangkat ini.');
                    stopQrisScanner();
                    return;
                }

                await html5Qr.start(
                    { deviceId: { exact: cameraId } },
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    qrCodeMessage => {
                        // hasil scan
                        window.scannedQr = qrCodeMessage;
                        document.getElementById('qris-status').textContent = 'Terscan: ' + qrCodeMessage;
                        // hentikan scanner otomatis
                        stopQrisScanner();
                    },
                    errorMessage => {
                        // optional: tampilkan progress kecil
                        // console.log('Scan error', errorMessage);
                    }
                );

                statusEl.textContent = 'Menunggu pemindaian...';
            } catch (err) {
                console.error('openQrisScanner error', err);
                alert('Tidak dapat memulai scanner: ' + err.message);
                stopQrisScanner();
            }
        });

            // Fallback: gunakan file upload image atau paste payload QR manual
            function qrisFallbackUseFile(input) {
                const file = input.files && input.files[0];
                if (!file) return alert('Pilih file gambar QR terlebih dahulu.');

                const form = new FormData();
                form.append('image', file);

                const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

                fetch('{{ route('kasir.decode_qrcode') }}', {
                    method: 'POST',
                    headers: csrf ? { 'X-CSRF-TOKEN': csrf } : {},
                    body: form,
                })
                .then(res => res.json())
                .then(json => {
                    if (json.error) {
                        alert('❌ ' + json.error);
                        return;
                    }
                    window.scannedQr = json.text;
                    document.getElementById('qris-status').textContent = 'Terscan (server): ' + json.text;
                    document.getElementById('qris-fallback').classList.add('hidden');
                })
                .catch(err => {
                    console.error('Upload decode error', err);
                    alert('Gagal mengunggah gambar untuk didecode. Cek console untuk detail.');
                });
            }

            function qrisFallbackUseText() {
                const txt = document.getElementById('qris-fallback-text').value.trim();
                if (!txt) return alert('Masukkan payload QR atau kode yang tertera.');
                window.scannedQr = txt;
                document.getElementById('qris-status').textContent = 'Terscan (manual): ' + txt;
                document.getElementById('qris-fallback').classList.add('hidden');
                stopQrisScanner();
            }
    }

    async function stopQrisScanner() {
        try {
            if (_html5QrcodeScanner) {
                await _html5QrcodeScanner.stop();
                await _html5QrcodeScanner.clear();
                _html5QrcodeScanner = null;
            }
        } catch (e) {
            console.warn('stopQrisScanner:', e);
        }
        const modal = document.getElementById('modal-qris');
        if (modal) modal.classList.add('hidden');
    }

    // ====== KERANJANG ======
    function tambahKeranjang(id, nama, harga, jumlah = 1) {
        document.getElementById('empty-cart').style.display = 'none';
        const item = keranjang.find(i => i.id === id);
        if (item) {
            item.jumlah += jumlah;
        } else {
            keranjang.push({ id, nama, harga, jumlah });
        }
        renderKeranjang();
    }

    function renderKeranjang() {
        const container = document.getElementById('keranjang-list');
        container.innerHTML = '';
        let total = 0;
        let totalItems = 0;

        if (keranjang.length === 0) {
            container.innerHTML = '<p class="text-gray-400 text-center" id="empty-cart">Belum ada item 😅</p>';
        } else {
            keranjang.forEach((item, index) => {
                const subtotal = item.harga * item.jumlah;
                total += subtotal;
                totalItems += item.jumlah;

                container.innerHTML += `
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-semibold text-gray-800">${item.nama}</p>
                                <small class="text-gray-600">${formatRupiah(item.harga)} x ${item.jumlah}</small>
                            </div>
                            <p class="font-bold text-blue-600">${formatRupiah(subtotal)}</p>
                        </div>
                        <div class="flex gap-2 items-center justify-between">
                            <div class="flex gap-2">
                                <button class="bg-yellow-400 hover:bg-yellow-500 text-white px-2 py-1 rounded text-xs font-semibold"
                                    onclick="kurangiKuantitas(${index})">−</button>
                                <span class="px-2 py-1 bg-white border rounded text-center min-w-[2rem]">${item.jumlah}</span>
                                <button class="bg-yellow-400 hover:bg-yellow-500 text-white px-2 py-1 rounded text-xs font-semibold"
                                    onclick="tambahKuantitas(${index})">+</button>
                            </div>
                            <button class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs font-semibold"
                                onclick="hapusItem(${index})">🗑️ Hapus</button>
                        </div>
                    </div>
                `;
            });
        }

        document.getElementById('total').textContent = formatRupiah(total);
        document.getElementById('total-items').textContent = totalItems;
    }

    function tambahKuantitas(index) {
        keranjang[index].jumlah += 1;
        renderKeranjang();
    }

    function kurangiKuantitas(index) {
        if (keranjang[index].jumlah > 1) {
            keranjang[index].jumlah -= 1;
        }
        renderKeranjang();
    }

    function hapusItem(index) {
        keranjang.splice(index, 1);
        if (keranjang.length === 0) {
            document.getElementById('empty-cart').style.display = 'block';
        }
        renderKeranjang();
    }

    function checkout() {
        if (keranjang.length === 0) {
            alert('Keranjang masih kosong!');
            return;
        }

        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
            alert('❌ CSRF Token tidak ditemukan. Silakan refresh halaman.');
            return;
        }

        // Konversi keranjang ke format items untuk API
        const items = keranjang.map(item => ({
            product_id: item.id,
            quantity: item.jumlah
        }));

        console.log('Mengirim checkout:', {
            items: items,
            payment_method: paymentMethod,
            csrf: csrfToken
        });

        // Kirim ke server
        const buyerName = document.getElementById('buyer-name')?.value || null;
        const payload = { items: items, payment_method: paymentMethod, buyer_name: buyerName };
        if (paymentMethod === 'QRIS' && window.scannedQr) {
            payload.qris_payload = window.scannedQr;
        }

        fetch('/kasir', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                items: payload.items,
                payment_method: payload.payment_method,
                qris_payload: payload.qris_payload ?? null,
                buyer_name: payload.buyer_name ?? null
            })
        })
        .then(res => {
            console.log('Response status:', res.status);
            return res.json().catch(() => ({error: 'Invalid response from server'}));
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.error) {
                    alert('❌ Error: ' + data.error);
            } else if (data.message || data.invoice) {
                    // Tampilkan nota/receipt dalam modal
                    const invoice = data.invoice || '—';
                    const metode = data.payment_method || paymentMethod;
                    const totalText = document.getElementById('total').textContent || '';

                    // isi detail nota
                    document.getElementById('nota-invoice').textContent = invoice;
                    document.getElementById('nota-metode').textContent = metode;
                    document.getElementById('nota-buyer').textContent = buyerName || '-';
                    document.getElementById('nota-total').textContent = totalText;
                    // daftar item
                    const notaItems = document.getElementById('nota-items');
                    notaItems.innerHTML = '';
                    keranjang.forEach(it => {
                        const li = document.createElement('li');
                        li.className = 'flex justify-between py-1';
                        li.innerHTML = `<span>${it.nama} x ${it.jumlah}</span><span>${formatRupiah(it.harga * it.jumlah)}</span>`;
                        notaItems.appendChild(li);
                    });

                    // qris payload
                    document.getElementById('nota-qris').textContent = window.scannedQr ?? '-';
                    // set timestamp
                    document.getElementById('nota-time').textContent = new Date().toLocaleString('id-ID');

                    document.getElementById('modal-nota').classList.remove('hidden');

                    // reset cart but keep modal open so cashier dapat mencetak nota
                    keranjang = [];
                    renderKeranjang();
                    document.getElementById('empty-cart').style.display = 'block';
            } else {
                alert('❌ Response tidak sesuai: ' + JSON.stringify(data));
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            alert('❌ Terjadi kesalahan saat mengirim transaksi:\n' + err.message);
        });
    }
</script>

<!-- ===== MODAL PEMBELIAN PRODUK ===== -->
<div id="modal-pembelian" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-96 p-8">
        <h2 class="text-2xl font-bold mb-6">🛒 Pilih Jumlah Produk</h2>
        
        <input type="hidden" id="modal-produk-id">
        <input type="hidden" id="modal-produk-stok">
        
        <div class="mb-4">
            <p class="text-gray-600 text-sm">Produk:</p>
            <p id="modal-produk-nama" class="text-xl font-bold text-gray-800 mb-2"></p>
        </div>

        <div class="mb-4 p-3 bg-blue-50 rounded-lg">
            <p class="text-gray-600 text-sm">Harga Satuan:</p>
            <p id="modal-produk-harga" class="text-lg font-bold text-blue-600"></p>
        </div>

        <div class="mb-6">
            <p class="text-gray-600 text-sm mb-2">Jumlah:</p>
            <div class="flex items-center gap-4">
                <button onclick="kurangiJumlah()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-lg font-bold">−</button>
                <input type="number" id="modal-jumlah" value="1" min="1" max="10" class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center text-lg font-bold" onchange="updateSubtotal()" oninput="updateSubtotal()">
                <button onclick="tambahJumlah()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-lg font-bold">+</button>
            </div>
        </div>

        <div class="mb-6 p-4 bg-yellow-50 rounded-lg border-2 border-yellow-300">
            <p class="text-gray-600 text-sm">Subtotal:</p>
            <p id="modal-subtotal" class="text-2xl font-bold text-yellow-600"></p>
        </div>

        <div class="flex gap-3">
            <button onclick="tutupModalPembelian()" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white py-2 rounded-lg font-semibold">Batal</button>
            <button onclick="konfirmasiPembelian()" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg font-semibold">✅ Tambah ke Keranjang</button>
        </div>
    </div>
</div>

<!-- ===== MODAL QRIS SCANNER ===== -->
<div id="modal-qris" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-11/12 md:w-3/4 lg:w-1/2 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">🔍 Pindai QRIS</h3>
            <button onclick="stopQrisScanner()" class="text-sm text-red-500 font-semibold">Tutup</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div id="qrcode-reader" style="width:100%;"></div>
            </div>
            <div>
                <p class="text-sm text-gray-600">Petunjuk:</p>
                <ul class="list-disc list-inside text-sm text-gray-700 mb-4">
                    <li>Arahkan kamera ke QRIS pada layar pelanggan.</li>
                    <li>Pastikan pencahayaan cukup dan QR terlihat jelas.</li>
                </ul>
                <p class="font-semibold">Status:</p>
                <p id="qris-modal-status" class="text-sm text-gray-700">Menunggu pemindaian...</p>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- Load Chart.js dari CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<script>
// Update waktu real-time
setInterval(function() {
    const now = new Date();
    document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID');
}, 1000);

// Chart: Transaksi Per Jam
const ctxHourly = document.getElementById('chartHourlyTransactions').getContext('2d');
const chartHourly = new Chart(ctxHourly, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00', $chartHours)) !!},
        datasets: [
            {
                label: 'Jumlah Transaksi',
                data: {!! json_encode($chartHourlyCounts) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: '#3b82f6',
                borderWidth: 1,
                yAxisID: 'y',
            },
            {
                label: 'Total Pendapatan (Rp)',
                data: {!! json_encode($chartHourlyTotals) !!},
                backgroundColor: 'rgba(34, 197, 94, 0.7)',
                borderColor: '#22c55e',
                borderWidth: 1,
                yAxisID: 'y1',
                type: 'line',
                tension: 0.3,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Jumlah Transaksi',
                    color: '#3b82f6',
                },
                ticks: {
                    stepSize: 1,
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Pendapatan (Rp)',
                    color: '#22c55e',
                },
                grid: {
                    drawOnChartArea: false,
                },
                ticks: {
                    callback: function(v) {
                        return 'Rp ' + (v / 1000).toFixed(0) + 'K';
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top',
            }
        }
    }
});
</script>
<!-- Print styling for nota -->
<style>
    @media print {
        body * { visibility: hidden; }
        #modal-nota, #modal-nota * { visibility: visible; }
        #modal-nota { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
    }
    /* Nota styles for screen */
    #modal-nota .w-96 { width: 380px; }
    #modal-nota table td, #modal-nota table th { padding: 6px 0; }
</style>
<script>
    function toggleReturnPolicy() {
        const full = document.getElementById('nota-return-full');
        const short = document.getElementById('nota-return-short');
        if (!full || !short) return;
        if (full.classList.contains('hidden')) {
            full.classList.remove('hidden');
            short.classList.add('hidden');
        } else {
            full.classList.add('hidden');
            short.classList.remove('hidden');
        }
    }
</script>
