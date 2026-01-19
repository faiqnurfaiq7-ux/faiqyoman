<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\DataPenjualController;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\ProfileController;



// 🔹 ROUTE LOGIN & REGISTER
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Social login routes (Google / Facebook)
Route::get('auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

// Profile (link/unlink social accounts)
Route::middleware('auth')->group(function(){
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/unlink/{provider}', [ProfileController::class, 'unlink'])->name('profile.unlink');
});

// 🔹 ROUTE YANG PERLU LOGIN
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir', [KasirController::class, 'store'])->name('kasir.store');
        // Endpoint untuk decode QR image (fallback upload)
        Route::post('kasir/decode-qrcode', [\App\Http\Controllers\KasirController::class, 'decodeQrcode'])->name('kasir.decode_qrcode');
    Route::get('/kasir/laporan', [KasirController::class, 'laporan'])->name('kasir.laporan');

    Route::resource('produk', ProdukController::class);
    // Suggestion endpoint for search autocomplete (returns JSON)
    Route::get('/produk/suggest', [ProdukController::class, 'suggest'])->name('produk.suggest');
    Route::resource('pelanggan', PelangganController::class);

    // Penjual Routes
    Route::get('/penjual', [PenjualController::class, 'index'])->name('penjual.index');
    Route::get('/penjual/laporan', [PenjualController::class, 'report'])->name('penjual.report');
    Route::get('/penjual/daily/{date}', [PenjualController::class, 'dailyDetail'])->name('penjual.daily');

    // Data Penjual Routes (CRUD) - Flat routes
    Route::get('/data-penjual', [DataPenjualController::class, 'index'])->name('penjual.data-penjual.index');
    Route::get('/data-penjual/create', [DataPenjualController::class, 'create'])->name('penjual.data-penjual.create');
    Route::post('/data-penjual', [DataPenjualController::class, 'store'])->name('penjual.data-penjual.store');
    Route::get('/data-penjual/{penjual}', [DataPenjualController::class, 'show'])->name('penjual.data-penjual.show');
    Route::get('/data-penjual/{penjual}/edit', [DataPenjualController::class, 'edit'])->name('penjual.data-penjual.edit');
    Route::put('/data-penjual/{penjual}', [DataPenjualController::class, 'update'])->name('penjual.data-penjual.update');
    Route::delete('/data-penjual/{penjual}', [DataPenjualController::class, 'destroy'])->name('penjual.data-penjual.destroy');

Route::get('/laporan', function () {
    // Ambil data dari tabel transactions (kasir)
    $transactions = Transaction::latest()->get();

    // Hitung statistik ringkas dari transactions
    $totalTransactions = $transactions->count();
    $totalRevenue = (float) $transactions->sum('total_amount');
    $average = $totalTransactions ? ($totalRevenue / $totalTransactions) : 0;

    // Data untuk chart per hari (7 hari terakhir)
    $salesByDay = Transaction::selectRaw('DATE(created_at) as tanggal, SUM(total_amount) as total, COUNT(*) as jumlah')
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'asc')
        ->get();

    // Format data untuk chart.js (per hari)
    $chartDays = $salesByDay->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->toArray();
    $chartSalesByDay = $salesByDay->pluck('total')->map(fn($v) => (float)$v)->toArray();
    $chartCountByDay = $salesByDay->pluck('jumlah')->map(fn($v) => (int)$v)->toArray();

    // Data untuk chart per produk (top 10 produk paling banyak terjual)
    $salesByProduct = DB::table('transaction_details')
        ->join('produk', 'transaction_details.product_id', '=', 'produk.id')
        ->selectRaw('produk.nama, SUM(transaction_details.quantity) as total_qty, SUM(transaction_details.quantity * transaction_details.price) as total_revenue')
        ->groupBy('transaction_details.product_id', 'produk.nama')
        ->orderBy('total_qty', 'desc')
        ->limit(10)
        ->get();

    // Format data untuk chart.js (per produk)
    $chartProducts = $salesByProduct->pluck('nama')->toArray();
    $chartQtyByProduct = $salesByProduct->pluck('total_qty')->map(fn($v) => (int)$v)->toArray();
    $chartRevenueByProduct = $salesByProduct->pluck('total_revenue')->map(fn($v) => (float)$v)->toArray();

    // Data untuk chart per metode pembayaran
    $paymentMethods = Transaction::selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
        ->groupBy('payment_method')
        ->orderBy('count', 'desc')
        ->get();

    // Format data untuk chart.js (per metode pembayaran)
    $chartPaymentMethods = $paymentMethods->pluck('payment_method')->toArray();
    $chartPaymentCounts = $paymentMethods->pluck('count')->map(fn($v) => (int)$v)->toArray();
    $chartPaymentTotals = $paymentMethods->pluck('total')->map(fn($v) => (float)$v)->toArray();

    // Jika tidak ada transaksi dari kasir, gunakan penjualans (fallback)
    $penjualans = $transactions->isEmpty() ? Penjualan::with('pelanggan')->get() : collect();

    return view('laporan.index', compact(
        'transactions', 'penjualans', 'totalTransactions', 'totalRevenue', 'average',
        'chartDays', 'chartSalesByDay', 'chartCountByDay',
        'chartProducts', 'chartQtyByProduct', 'chartRevenueByProduct',
        'chartPaymentMethods', 'chartPaymentCounts', 'chartPaymentTotals', 'paymentMethods'
    ));
})->name('laporan.index');



});
