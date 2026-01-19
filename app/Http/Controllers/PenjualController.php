<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class PenjualController extends Controller
{
    /**
     * Halaman utama penjual dengan statistik penjualan
     */
    public function index()
    {
        // Statistik hari ini
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        $todayTransactions = Transaction::whereBetween('created_at', [$todayStart, $todayEnd])->get();
        $totalTransactionsToday = $todayTransactions->count();
        $totalRevenueToday = (float) $todayTransactions->sum('total_amount');
        $avgTransactionToday = $totalTransactionsToday ? ($totalRevenueToday / $totalTransactionsToday) : 0;
        $totalItemsSoldToday = DB::table('transaction_details')
            ->whereIn('transaction_id', $todayTransactions->pluck('id'))
            ->sum('quantity');

        // Top produk hari ini (Top 5)
        $topProductsToday = DB::table('transaction_details')
            ->join('produk', 'transaction_details.product_id', '=', 'produk.id')
            ->whereIn('transaction_id', $todayTransactions->pluck('id'))
            ->selectRaw('produk.id, produk.nama, SUM(transaction_details.quantity) as qty, SUM(transaction_details.quantity * transaction_details.price) as revenue')
            ->groupBy('transaction_details.product_id', 'produk.id', 'produk.nama')
            ->orderBy('qty', 'desc')
            ->limit(5)
            ->get();

        // Penjualan per metode pembayaran hari ini
        $paymentBreakdown = $todayTransactions->groupBy('payment_method')->map(fn($group) => [
            'count' => $group->count(),
            'total' => $group->sum('total_amount'),
        ])->toArray();

        // Chart: Penjualan 7 hari terakhir
        $last7DaysTransactions = Transaction::where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        $chartDays = $last7DaysTransactions->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->toArray();
        $chartCounts = $last7DaysTransactions->pluck('count')->map(fn($v) => (int)$v)->toArray();
        $chartTotals = $last7DaysTransactions->pluck('total')->map(fn($v) => (float)$v)->toArray();

        // Detail transaksi hari ini (Terbaru 10)
        $transactionsToday = Transaction::whereBetween('created_at', [$todayStart, $todayEnd])
            ->with('details.produk')
            ->latest()
            ->limit(10)
            ->get();

        // Statistik bulan ini
        $thisMonthStart = now()->startOfMonth();
        $thisMonthEnd = now()->endOfMonth();
        $thisMonthTransactions = Transaction::whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])->get();
        $totalRevenueThisMonth = (float) $thisMonthTransactions->sum('total_amount');
        $totalTransactionsThisMonth = $thisMonthTransactions->count();

        // Produk dengan stok rendah (< 5)
        $lowStockProducts = Produk::where('stok', '<', 5)
            ->orderBy('stok', 'asc')
            ->limit(5)
            ->get();

        return view('penjual.index', compact(
            'totalTransactionsToday',
            'totalRevenueToday',
            'avgTransactionToday',
            'totalItemsSoldToday',
            'topProductsToday',
            'paymentBreakdown',
            'chartDays',
            'chartCounts',
            'chartTotals',
            'transactionsToday',
            'totalRevenueThisMonth',
            'totalTransactionsThisMonth',
            'lowStockProducts'
        ));
    }

    /**
     * Detail penjualan per hari
     */
    public function dailyDetail($date)
    {
        $selectedDate = \Carbon\Carbon::parse($date);
        $dateStart = $selectedDate->startOfDay();
        $dateEnd = $selectedDate->endOfDay();

        $transactions = Transaction::whereBetween('created_at', [$dateStart, $dateEnd])
            ->with('details.produk')
            ->latest()
            ->get();

        $totalRevenue = (float) $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        $totalItems = DB::table('transaction_details')
            ->whereIn('transaction_id', $transactions->pluck('id'))
            ->sum('quantity');

        return view('penjual.daily-detail', compact(
            'selectedDate',
            'transactions',
            'totalRevenue',
            'totalTransactions',
            'totalItems'
        ));
    }

    /**
     * Laporan penjualan
     */
    public function report()
    {
        // Statistik total
        $allTransactions = Transaction::get();
        $totalRevenue = (float) $allTransactions->sum('total_amount');
        $totalTransactions = $allTransactions->count();
        $totalItems = DB::table('transaction_details')->sum('quantity');

        // Top 10 produk
        $topProducts = DB::table('transaction_details')
            ->join('produk', 'transaction_details.product_id', '=', 'produk.id')
            ->selectRaw('produk.nama, SUM(transaction_details.quantity) as qty, SUM(transaction_details.quantity * transaction_details.price) as revenue, produk.harga')
            ->groupBy('transaction_details.product_id', 'produk.nama', 'produk.harga')
            ->orderBy('qty', 'desc')
            ->limit(10)
            ->get();

        // Penjualan per metode pembayaran
        $paymentMethods = Transaction::selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->orderBy('count', 'desc')
            ->get();

        // Penjualan per hari (30 hari terakhir)
        $monthlySales = Transaction::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('penjual.report', compact(
            'totalRevenue',
            'totalTransactions',
            'totalItems',
            'topProducts',
            'paymentMethods',
            'monthlySales'
        ));
    }
}
