<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Buat sample transactions
$t1 = App\Models\Transaction::create([
    'invoice_number' => 'INV-20251113001',
    'total_amount' => 45000,
]);
$t1->details()->create([
    'product_id' => 3,
    'quantity' => 3,
    'price' => 15000,
]);

$t2 = App\Models\Transaction::create([
    'invoice_number' => 'INV-20251113002',
    'total_amount' => 30000,
]);
$t2->details()->create([
    'product_id' => 2,
    'quantity' => 2,
    'price' => 15000,
]);

echo "Sample transactions created:\n";
echo json_encode([
    ['id' => $t1->id, 'invoice' => $t1->invoice_number, 'total' => $t1->total_amount],
    ['id' => $t2->id, 'invoice' => $t2->invoice_number, 'total' => $t2->total_amount],
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
