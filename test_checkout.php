<?php
// Test file untuk debugging checkout

// Simulasi request data
$testData = [
    'items' => [
        ['product_id' => 1, 'quantity' => 2],
        ['product_id' => 2, 'quantity' => 1]
    ],
    'payment_method' => 'QRIS'
];

echo "✅ Test Data Checkout:\n";
echo json_encode($testData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

// Cek database connection
try {
    require 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "✅ Laravel app bootstrapped\n";
    
    // Test model access
    $produk = \App\Models\Produk::first();
    echo "✅ Database connected - Found product: " . ($produk ? $produk->nama : 'None') . "\n";
    
    // Test transaction creation
    $invoice = 'TEST-' . now()->format('YmdHis');
    echo "✅ Invoice format: $invoice\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
