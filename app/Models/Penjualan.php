<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = ['pelanggan_id', 'total', 'tanggal'];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
