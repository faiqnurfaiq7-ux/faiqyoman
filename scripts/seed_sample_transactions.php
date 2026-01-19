<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Cek produk yang tersedia
$produk = App\Models\Produk::pluck('id')->toArray();
echo "Available product IDs: " . json_encode($produk) . "\n";

// Ambil 2 produk pertama
$prod1 = $produk[0] ?? 1;
$prod2 = $produk[1] ?? 2;

$db = app('db');

// Clear existing transactions (with FK constraint handling)
$db->statement('SET FOREIGN_KEY_CHECKS=0');
$db->table('transaction_details')->truncate();
$db->table('transactions')->truncate();
$db->statement('SET FOREIGN_KEY_CHECKS=1');

// Create 10 sample transactions
$data = [
    ['date' => now()->subDays(6), 'amount' => 75000, 'payment' => 'QRIS', 'items' => [[$prod1, 5, 15000]]],
    ['date' => now()->subDays(5), 'amount' => 45000, 'payment' => 'BANK', 'items' => [[$prod1, 3, 15000]]],
    ['date' => now()->subDays(5), 'amount' => 30000, 'payment' => 'PAYLETTER', 'items' => [[$prod2, 2, 15000]]],
    ['date' => now()->subDays(4), 'amount' => 120000, 'payment' => 'QRIS', 'items' => [[$prod1, 4, 15000], [$prod2, 4, 15000]]],
    ['date' => now()->subDays(3), 'amount' => 60000, 'payment' => 'BANK', 'items' => [[$prod1, 4, 15000]]],
    ['date' => now()->subDays(2), 'amount' => 90000, 'payment' => 'PAYLETTER', 'items' => [[$prod2, 6, 15000]]],
    ['date' => now()->subDays(1), 'amount' => 50000, 'payment' => 'QRIS', 'items' => [[$prod1, 3, 15000], [$prod2, 1, 15000]]],
    ['date' => now(), 'amount' => 105000, 'payment' => 'BANK', 'items' => [[$prod1, 7, 15000]]],
    ['date' => now(), 'amount' => 45000, 'payment' => 'PAYLETTER', 'items' => [[$prod2, 3, 15000]]],
    ['date' => now()->subHours(2), 'amount' => 80000, 'payment' => 'QRIS', 'items' => [[$prod1, 4, 15000], [$prod2, 2, 15000]]],
];

$created = [];
foreach ($data as $idx => $d) {
    $trx = App\Models\Transaction::create([
        'invoice_number' => 'INV-' . $d['date']->format('YmdHis') . '-' . str_pad($idx+1, 3, '0', STR_PAD_LEFT),
        'total_amount' => $d['amount'],
        'payment_method' => $d['payment'],
        'created_at' => $d['date'],
        'updated_at' => $d['date'],
    ]);
    
    foreach ($d['items'] as $item) {
        $trx->details()->create([
            'product_id' => $item[0],
            'quantity' => $item[1],
            'price' => $item[2],
            'created_at' => $d['date'],
            'updated_at' => $d['date'],
        ]);
    }
    
    $created[] = ['id' => $trx->id, 'invoice' => $trx->invoice_number, 'amount' => $trx->total_amount, 'payment' => $trx->payment_method];
}

echo "Created " . count($created) . " sample transactions:\n";
echo json_encode($created, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
