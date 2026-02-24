@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <!-- Dashboard Kasir Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-gray-800">📊 Dashboard Kasir</h1>
            <div class="text-right">
                <p class="text-gray-600 text-sm">Tanggal: {{ now()->format('d M Y') }}</p>
                <p class="text-gray-600 text-sm">Waktu: <span id="current-time">{{ now()->format('H:i:s') }}</span></p>
            </div>
        </div>

        <!-- Statistik Ringkas Hari Ini -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Transaksi Hari Ini</p>
                        <h3 class="text-2xl font-bold text-blue-600">{{ $totalTransactionsToday }}</h3>
                    </div>
                    <div class="text-3xl text-blue-200">📝</div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Pendapatan Hari Ini</p>
                        <h3 class="text-xl font-bold text-green-600">Rp {{ number_format($totalRevenueToday, 0, ',', '.') }}</h3>
                    </div>
                    <div class="text-3xl text-green-200">💰</div>
                </div>
            </div>
        </div>

        <!-- Chart Utama -->
        <div class="bg-white p-4 rounded-lg shadow mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">📈 Transaksi Per Jam Hari Ini</h3>
            <canvas id="chartHourlyTransactions" height="60"></canvas>
        </div>
    </div>

    <!-- Separator -->
    <hr class="my-8 border-gray-300">

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" id="success-message">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" id="error-message">
            ❌ {{ session('error') }}
        </div>
    @endif

    <!-- Menu Penjualan (Kasir Section) -->
    <h2 class="text-2xl font-bold mb-4 flex items-center">
        🛍️ Menu Penjualan
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Bagian kiri: daftar produk -->
        <div class="md:col-span-2">
            <!-- Search Bar -->
            <div class="mb-4">
                <input type="text" id="search-produk" placeholder="🔍 Cari produk..." class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3" id="produk-grid">
                @foreach ($produk as $item)
                <div class="produk-item bg-white shadow rounded-lg p-3 text-center hover:shadow-md transition" data-nama="{{ strtolower($item->nama) }}">
                    <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}" class="w-16 h-16 object-cover rounded mx-auto mb-2">
                    <h3 class="font-semibold text-gray-800 text-sm truncate">{{ $item->nama }}</h3>
                    <p class="text-xs text-gray-500 mb-1">Stok: <span class="font-bold text-{{ $item->stok > 0 ? 'green' : 'red' }}-600">{{ $item->stok }}</span></p>
                    <p class="text-blue-600 font-bold text-sm mb-2">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                    <button onclick="tambahKeranjang({{ $item->id }}, '{{ $item->nama }}', {{ $item->harga }}, 1)"
                        {{ $item->stok == 0 ? 'disabled' : '' }}
                        class="w-full {{ $item->stok == 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-600' }} text-white px-2 py-1 rounded text-xs font-semibold transition">
                        {{ $item->stok == 0 ? '❌ Habis' : '🛒 Beli' }}
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Bagian kanan: keranjang -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-3">🧾 Keranjang</h3>
            <div id="keranjang-list" class="space-y-2 mb-3 max-h-48 overflow-y-auto">
                <p class="text-gray-400 text-center text-sm" id="empty-cart">Belum ada item 😅</p>
            </div>
            <hr class="my-3">
            <div class="bg-blue-100 p-2 rounded mb-3">
                <div class="flex justify-between font-bold text-lg text-blue-900">
                    <span>Total:</span>
                    <span id="total">Rp 0</span>
                </div>
                <div class="flex justify-between text-xs text-blue-700 mt-1">
                    <span>Item:</span>
                    <span id="total-items">0</span>
                </div>
            </div>

            <!-- Input nama pembeli -->
            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Pembeli (opsional):</label>
                <input id="buyer-name" type="text" placeholder="Masukkan nama pembeli" class="w-full px-2 py-1 border rounded text-sm" />
            </div>

            <!-- Pilihan Metode Pembayaran -->
            <div class="mb-3">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Metode Pembayaran:</label>
                <div class="space-y-1">
                    <label class="flex items-center text-sm">
                        <input type="radio" name="payment_method" value="QRIS" checked class="mr-1">
                        <span>💳 QRIS</span>
                    </label>
                    <label class="flex items-center text-sm">
                        <input type="radio" name="payment_method" value="CASH" class="mr-1">
                        <span>💵 Cash</span>
                    </label>
                    <label class="flex items-center text-sm">
                        <input type="radio" name="payment_method" value="BANK" class="mr-1">
                        <span>🏦 Bank</span>
                    </label>
                    <label class="flex items-center text-sm">
                        <input type="radio" name="payment_method" value="PAYLETTER" class="mr-1">
                        <span>📱 PayLetter</span>
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <button id="btn-scan-qris" onclick="openQrisScanner()" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-1 rounded text-sm font-semibold">🔍 Scan QRIS</button>
                <p id="qris-status" class="text-xs text-gray-500 mt-1 text-center">Belum memindai</p>
            </div>

            <button onclick="testCheckout()"
                class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded font-semibold text-sm">
                🧪 Test Checkout
            </button>
            <!-- Checkout Form -->
            <form id="checkout-form" method="POST" action="/kasir" style="display: none;">
                @csrf
                <input type="hidden" name="items" id="form-items">
                <input type="hidden" name="payment_method" id="form-payment-method">
                <input type="hidden" name="buyer_name" id="form-buyer-name">
                <input type="hidden" name="qris_payload" id="form-qris-payload">
            </form>

            <button onclick="testClick()"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded font-semibold text-sm mb-2">
                🧪 Test Klik
            </button>
            <button onclick="doCheckout()"
                class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded font-semibold text-sm mt-2"
                id="btn-checkout">
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
<div id="modal-nota" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
        <!-- Header -->
        <div class="bg-blue-600 text-white p-4 text-center relative">
            <button onclick="cancelTransaction()" class="absolute left-3 top-3 text-white hover:bg-white hover:bg-opacity-20 rounded-full p-1 transition">
                <span class="text-lg">↶</span>
            </button>
            <div class="flex items-center justify-center">
                <span class="text-2xl mr-2">🧾</span>
                <div>
                    <h3 class="text-lg font-bold">NOTA TRANSAKSI</h3>
                    <p class="text-blue-100 text-xs" id="nota-time">-</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-4">
            <!-- Invoice & Payment -->
            <div class="bg-gray-50 rounded-lg p-3 mb-3">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600">Invoice:</span>
                    <span id="nota-invoice" class="font-bold text-gray-800">-</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Pembayaran:</span>
                    <span id="nota-metode" class="font-semibold text-blue-600">-</span>
                </div>
            </div>

            <!-- Items List - Simplified -->
            <div class="mb-3">
                <div id="nota-items" class="space-y-1 text-sm">
                    <!-- Items will be inserted here by JavaScript -->
                </div>
            </div>

            <!-- Total - Prominent -->
            <div class="bg-green-50 border-2 border-green-200 rounded-lg p-3 mb-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-700 font-medium">Total:</span>
                    <span id="nota-total" class="text-xl font-bold text-green-600">-</span>
                </div>
                <div class="text-center text-xs text-gray-500 mt-1">
                    <span id="nota-item-count">0 item</span> • ✅ Lunas
                </div>
            </div>

            <!-- Thank You Message -->
            <div class="text-center text-sm text-gray-600 mb-3">
                <p class="font-medium">Terima Kasih! 🙏</p>
                <p class="text-xs">{{ config('app.name', 'Toko') }}</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button onclick="cancelTransaction()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 rounded-lg font-medium text-sm transition">
                    ↶ Kembali
                </button>
                <button onclick="confirmTransaction()" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-medium text-sm transition">
                    ✅ Konfirmasi
                </button>
                <button onclick="printNota()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium text-sm transition">
                    🖨️ Cetak
                </button>
            </div>
</div>

<script>
    let keranjang = [];

    // ====== SEARCH FUNCTION ======
    document.getElementById('search-produk').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const produkItems = document.querySelectorAll('.produk-item');
        
        produkItems.forEach(item => {
            const nama = item.getAttribute('data-nama');
            if (nama.includes(query)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
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

        // Notifikasi sederhana
        const notif = document.createElement('div');
        notif.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        notif.textContent = `✅ ${nama} ditambahkan ke keranjang`;
        document.body.appendChild(notif);
        setTimeout(() => notif.remove(), 2000);
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

    function testCheckout() {
        alert('Test Checkout: Keranjang = ' + JSON.stringify(keranjang));
        alert('Test Checkout: Payment method = ' + (document.querySelector('input[name="payment_method"]:checked')?.value || 'none'));
        alert('Test Checkout: CSRF = ' + (document.querySelector('meta[name="csrf-token"]')?.content ? 'found' : 'not found'));
    }

    function showNota(data, paymentMethod, buyerName) {
        try {
            const invoice = data.invoice || '—';
            const metode = data.payment_method || paymentMethod;
            const totalText = document.getElementById('total').textContent || '';

            console.log('Showing nota with data:', { invoice, metode, buyerName, totalText });

            // isi detail nota
            document.getElementById('nota-invoice').textContent = invoice;
            document.getElementById('nota-metode').textContent = metode;
            document.getElementById('nota-total').textContent = totalText;

            // hitung total item
            const totalItems = keranjang.reduce((sum, item) => sum + item.jumlah, 0);
            document.getElementById('nota-item-count').textContent = `${totalItems} item`;

            // daftar item dengan styling yang lebih simpel
            const notaItems = document.getElementById('nota-items');
            notaItems.innerHTML = '';
            keranjang.forEach(it => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'flex justify-between py-1 border-b border-gray-100 last:border-b-0';
                itemDiv.innerHTML = `
                    <span class="text-gray-800">${it.nama} x ${it.jumlah}</span>
                    <span class="font-medium text-gray-700">${formatRupiah(it.harga * it.jumlah)}</span>
                `;
                notaItems.appendChild(itemDiv);
            });

            // set timestamp dengan format yang lebih bagus
            document.getElementById('nota-time').textContent = new Date().toLocaleString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            // Animasi muncul nota
            setTimeout(() => {
                document.getElementById('modal-nota').classList.remove('hidden');
            }, 100);

            // Simpan data keranjang untuk cancel
            window.lastTransaction = {
                keranjang: [...keranjang],
                buyerName: buyerName,
                paymentMethod: paymentMethod
            };

        } catch (error) {
            console.error('Error showing nota:', error);
            alert('❌ Terjadi kesalahan saat menampilkan nota: ' + error.message);
        }
    }

    function cancelTransaction() {
        if (confirm('Batalkan transaksi dan kembalikan keranjang?')) {
            // Kembalikan keranjang ke kondisi sebelum transaksi
            if (window.lastTransaction && window.lastTransaction.keranjang) {
                keranjang = [...window.lastTransaction.keranjang];
                renderKeranjang();
            }

            // Tutup modal nota
            document.getElementById('modal-nota').classList.add('hidden');

            // Reset data transaksi terakhir
            window.lastTransaction = null;

            alert('Transaksi dibatalkan. Keranjang dikembalikan.');
        }
    }

    function confirmTransaction() {
        // Tutup modal nota
        document.getElementById('modal-nota').classList.add('hidden');

        // Kosongkan keranjang setelah konfirmasi
        keranjang = [];
        renderKeranjang();
        updateTotal();

        // Reset data transaksi terakhir
        window.lastTransaction = null;

        alert('✅ Transaksi berhasil dikonfirmasi!');
    }

    function printNota() {
        window.print();
    }

    function testClick() {
        alert('Test klik berhasil! JavaScript berfungsi.');
        console.log('Test click function called');
    }

    function doCheckout() {
        alert('Tombol checkout diklik! Memproses...');

        console.log('doCheckout called');

        // Basic check - if cart is empty, alert and return
        if (!keranjang || keranjang.length === 0) {
            alert('Keranjang kosong!');
            return;
        }

        // Check payment method
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!paymentMethod) {
            alert('Pilih metode pembayaran!');
            return;
        }

        console.log('Preparing form data...');

        // Prepare form data
        const items = keranjang.map(item => ({
            product_id: item.id,
            quantity: item.jumlah
        }));

        const buyerName = document.getElementById('buyer-name').value || '';

        // Fill form
        document.getElementById('form-items').value = JSON.stringify(items);
        document.getElementById('form-payment-method').value = paymentMethod.value;
        document.getElementById('form-buyer-name').value = buyerName;

        // Add QRIS payload if available
        if (paymentMethod.value === 'QRIS' && window.scannedQr) {
            document.getElementById('form-qris-payload').value = window.scannedQr;
        }

        console.log('Form data ready, submitting...');

        // Submit form
        document.getElementById('checkout-form').submit();
    }

    function simpleCheckout() {
        doCheckout();
    }

    function checkout() {
        doCheckout();
    }
</script>

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
        #modal-nota {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: white !important;
            box-shadow: none !important;
            margin: 0;
            padding: 0;
        }
        #modal-nota .max-w-sm {
            max-width: none;
            width: 100%;
            height: 100%;
            border-radius: 0;
            box-shadow: none;
        }
        .no-print { display: none !important; }
        /* Hide action buttons when printing */
        #modal-nota button { display: none !important; }
    }

    /* Enhanced nota styles for screen */
    #modal-nota {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    #modal-nota .bg-blue-600 {
        background: #2563eb;
    }

    #modal-nota .rounded-2xl {
        border-radius: 1rem;
    }

    /* Smooth animations */
    #modal-nota button {
        transition: all 0.2s ease-in-out;
    }

    #modal-nota button:hover {
        transform: translateY(-1px);
    }

    /* Simple item styling */
    #nota-items > div {
        transition: all 0.2s ease-in-out;
    }

    #nota-items > div:hover {
        background-color: rgba(0,0,0,0.02);
    }
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

    // Simple initialization
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded - checkout ready');

        // Auto-hide flash messages after 5 seconds
        const successMsg = document.getElementById('success-message');
        const errorMsg = document.getElementById('error-message');

        if (successMsg) {
            setTimeout(() => {
                successMsg.style.display = 'none';
            }, 5000);
            // Clear cart on successful transaction
            keranjang = [];
            renderKeranjang();
            updateTotal();
        }

        if (errorMsg) {
            setTimeout(() => {
                errorMsg.style.display = 'none';
            }, 5000);
        }
    });
</script>
