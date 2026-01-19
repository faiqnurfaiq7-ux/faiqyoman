<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = ['invoice_number', 'total_amount', 'pelanggan_id', 'payment_method', 'qris_payload'];

    // Relasi ke detail transaksi (satu transaksi punya banyak detail)
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }

    // Relasi ke pelanggan (satu transaksi milik satu pelanggan)
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
}
