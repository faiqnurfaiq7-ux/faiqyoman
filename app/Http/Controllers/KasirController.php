<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaction;
use App\Models\Pelanggan;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        $produk = Produk::all();

        // Data untuk dashboard kasir
        // Statistik hari ini
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        $todayTransactions = Transaction::whereBetween('created_at', [$todayStart, $todayEnd])->get();
        $totalTransactionsToday = $todayTransactions->count();
        $totalRevenueToday = (float) $todayTransactions->sum('total_amount');
        $avgTransactionToday = $totalTransactionsToday ? ($totalRevenueToday / $totalTransactionsToday) : 0;

        // Total produk terjual hari ini
        $totalItemsSoldToday = DB::table('transaction_details')
            ->whereIn('transaction_id', $todayTransactions->pluck('id'))
            ->sum('quantity');

        // Top produk hari ini
        $topProductsToday = DB::table('transaction_details')
            ->join('produk', 'transaction_details.product_id', '=', 'produk.id')
            ->whereIn('transaction_id', $todayTransactions->pluck('id'))
            ->selectRaw('produk.nama, SUM(transaction_details.quantity) as qty, COUNT(*) as times')
            ->groupBy('transaction_details.product_id', 'produk.nama')
            ->orderBy('qty', 'desc')
            ->limit(5)
            ->get();

        // Breakdown metode pembayaran hari ini
        $paymentBreakdown = $todayTransactions->groupBy('payment_method')->map(fn($group) => [
            'count' => $group->count(),
            'total' => $group->sum('total_amount'),
        ])->toArray();

        // Chart: Transaksi per jam hari ini
        $hourlyTransactions = Transaction::selectRaw('HOUR(created_at) as jam, COUNT(*) as count, SUM(total_amount) as total')
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->groupBy('jam')
            ->orderBy('jam')
            ->get();

        $chartHours = range(0, 23);
        $chartHourlyCounts = [];
        $chartHourlyTotals = [];
        foreach ($chartHours as $hour) {
            $data = $hourlyTransactions->firstWhere('jam', $hour);
            $chartHourlyCounts[] = $data ? (int)$data->count : 0;
            $chartHourlyTotals[] = $data ? (float)$data->total : 0;
        }

        return view('kasir.index', compact(
            'produk',
            'totalTransactionsToday',
            'totalRevenueToday',
            'avgTransactionToday',
            'totalItemsSoldToday',
            'topProductsToday',
            'paymentBreakdown',
            'chartHours',
            'chartHourlyCounts',
            'chartHourlyTotals'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'exists:produk,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:QRIS,BANK,PAYLETTER,CASH',
            'buyer_name' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $invoice = 'INV-' . now()->format('YmdHis');
            $total = 0;
            $items = [];

            foreach ($request->items as $item) {
                $produk = Produk::findOrFail($item['product_id']);
                
                // Pastikan kolom di database namanya 'stok' dan 'nama'
                if ($produk->stok < $item['quantity']) {
                    throw new \Exception("Stok {$produk->nama} tidak cukup.");
                }
            
                $subtotal = $produk->harga * $item['quantity'];
                $total += $subtotal;
            
                $items[] = [
                    'product_id' => $produk->id,
                    'quantity' => $item['quantity'],
                    'price' => $produk->harga,
                ];
            
                // Kurangi stok di database
                $produk->decrement('stok', $item['quantity']);
            }

            // Jika ada buyer_name, cari atau buat pelanggan
            $pelangganId = null;
            $buyerName = $request->input('buyer_name');
            if ($buyerName) {
                $pelanggan = Pelanggan::firstOrCreate(['nama' => $buyerName]);
                $pelangganId = $pelanggan->id;
            }

            $transaction = Transaction::create([
                'invoice_number' => $invoice,
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                'qris_payload' => $request->input('qris_payload'),
                'pelanggan_id' => $pelangganId,
            ]);

            foreach ($items as $item) {
                $transaction->details()->create($item);
            }

            DB::commit();
            return response()->json(['message' => 'Transaksi berhasil!', 'invoice' => $invoice, 'payment_method' => $request->payment_method]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // ✅ Tambahkan ini di bawah
    public function laporan()
    {
        $transaksi = Transaction::with('details.produk')->latest()->get();

        $totalPenjualan = $transaksi->sum('total_amount');
        $totalTransaksi = $transaksi->count();
        $totalProduk = TransactionDetail::sum('quantity');
        $keuntungan = $totalPenjualan * 0.3;

        $weeklySales = Transaction::selectRaw('DAYNAME(created_at) as hari, SUM(total_amount) as total')
            ->groupBy('hari')
            ->orderByRaw("FIELD(hari, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->pluck('total', 'hari');

        return view('kasir.laporan', compact(
            'transaksi', 'totalPenjualan', 'totalTransaksi', 'totalProduk', 'keuntungan', 'weeklySales'
        ));
    }

    /**
     * Decode uploaded QR image (fallback when client scanner unavailable)
     */
    public function decodeQrcode(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        try {
            $file = $request->file('image');
            $path = $file->getRealPath();

            // Use Zxing QrReader from khanamiryan/qrcode-detector-decoder
            $qrcode = new \Zxing\QrReader($path);
            $text = $qrcode->text();

            if (!$text) {
                return response()->json(['error' => 'Tidak dapat mendeteksi QR pada gambar.'], 422);
            }

            return response()->json(['text' => $text]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memproses gambar: ' . $e->getMessage()], 500);
        }
    }
}
