<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan opsi CASH ke enum payment_method
        DB::statement("ALTER TABLE `transactions` MODIFY `payment_method` ENUM('QRIS','BANK','PAYLETTER','CASH') NOT NULL DEFAULT 'QRIS'");
    }

    public function down(): void
    {
        // Kembalikan ke kondisi semula tanpa CASH
        DB::statement("ALTER TABLE `transactions` MODIFY `payment_method` ENUM('QRIS','BANK','PAYLETTER') NOT NULL DEFAULT 'QRIS'");
    }
};
