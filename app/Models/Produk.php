<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $fillable = ['nama', 'harga', 'stok', 'foto'];

    // Add accessor to get full URL for the foto field
    public function getFotoUrlAttribute()
    {
        // if no foto, return a placeholder
        if (!$this->foto) {
            return 'https://via.placeholder.com/80?text=Produk';
        }

        // if the foto already looks like a full URL, return it
        if (preg_match('/^https?:\/\//i', $this->foto)) {
            return $this->foto;
        }

        // assume the file is stored in storage/app/public and served via /storage
        return asset('storage/' . ltrim($this->foto, '/'));
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'produk_id');
    }
}
