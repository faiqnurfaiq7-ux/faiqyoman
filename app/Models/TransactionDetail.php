<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $table = 'transaction_details'; // optional, tapi bagus untuk eksplisit

    protected $fillable = ['transaction_id', 'product_id', 'quantity', 'price'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'product_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
