<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Penjualan;


class PenjualanSeeder extends Seeder
{
    public function run()
    {
        Penjualan::create([
            'nama_pelanggan' => 'Rudi',
            'total' => 75000,
            'created_at' => now(),
        ]);
    }
}

