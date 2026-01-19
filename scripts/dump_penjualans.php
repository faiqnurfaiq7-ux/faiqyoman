<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$items = App\Models\Penjualan::with('pelanggan')->get()->map(function($p){
    return [
        'id'=>$p->id,
        'pelanggan'=> $p->pelanggan?->nama,
        'tanggal'=> (string)($p->tanggal ?? $p->created_at),
        'total'=> (float)$p->total,
    ];
})->toArray();

echo json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
