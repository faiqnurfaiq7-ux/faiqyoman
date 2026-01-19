<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah presisi kolom `harga` agar mendukung nilai lebih besar.
        // Gunakan ALTER TABLE langsung untuk menghindari dependensi doctrine/dbal.
        DB::statement("ALTER TABLE `produk` MODIFY `harga` DECIMAL(15,2) NOT NULL");
    }

    public function down(): void
    {
        // Kembalikan ke DECIMAL(10,2) jika rollback diperlukan
        DB::statement("ALTER TABLE `produk` MODIFY `harga` DECIMAL(10,2) NOT NULL");
    }
};
