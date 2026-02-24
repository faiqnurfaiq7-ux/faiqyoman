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

        return view('dashboard', compact(
            'totalTransactionsToday',
            'totalRevenueToday',
            'avgTransactionToday',
            'totalItemsSoldToday',
            'totalStok',
            'totalProduk',
            'totalPelanggan'
        ));
    }
}
