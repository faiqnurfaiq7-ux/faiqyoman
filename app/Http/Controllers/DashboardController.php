<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Produk;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik hari ini
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        // Transaksi hari ini
        $todayTransactions = Transaction::whereBetween('created_at', [$todayStart, $todayEnd])->get();
        $totalTransactionsToday = $todayTransactions->count();
        $totalRevenueToday = (float) $todayTransactions->sum('total_amount');
        $avgTransactionToday = $totalTransactionsToday ? ($totalRevenueToday / $totalTransactionsToday) : 0;

        // Produk terjual hari ini
        $totalItemsSoldToday = DB::table('transaction_details')
            ->whereIn('transaction_id', $todayTransactions->pluck('id'))
            ->sum('quantity');

        // Total stok produk
        $totalStok = Produk::sum('stok');
        $totalProduk = Produk::count();

        // Total pelanggan
        $totalPelanggan = Pelanggan::count();

        // Transaksi 7 hari terakhir
        $last7DaysTransactions = Transaction::where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        $chartDays = $last7DaysTransactions->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->toArray();
        $chartCounts = $last7DaysTransactions->pluck('count')->map(fn($v) => (int)$v)->toArray();
        $chartTotals = $last7DaysTransactions->pluck('total')->map(fn($v) => (float)$v)->toArray();

        // Top produk 7 hari terakhir
        $topProducts = DB::table('transaction_details')
            ->join('produk', 'transaction_details.product_id', '=', 'produk.id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.created_at', '>=', now()->subDays(7))
            ->selectRaw('produk.id, produk.nama, produk.foto, SUM(transaction_details.quantity) as qty')
            ->groupBy('transaction_details.product_id', 'produk.id', 'produk.nama', 'produk.foto')
            ->orderBy('qty', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalTransactionsToday',
            'totalRevenueToday',
            'avgTransactionToday',
            'totalItemsSoldToday',
            'totalStok',
            'totalProduk',
            'totalPelanggan',
            'chartDays',
            'chartCounts',
            'chartTotals',
            'topProducts'
        ));
    }
}
