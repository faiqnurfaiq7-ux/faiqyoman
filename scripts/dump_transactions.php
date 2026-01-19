<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$items = App\Models\Transaction::latest()->limit(10)->get()->map(function($t){
    return [
        'id'=>$t->id,
        'invoice'=> $t->invoice_number,
        'total'=> (float)$t->total_amount,
        'created_at'=> (string)$t->created_at,
    ];
})->toArray();

echo json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
