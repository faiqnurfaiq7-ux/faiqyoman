<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$items = App\Models\Produk::select('id','nama','foto')->get()->toArray();
echo json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
