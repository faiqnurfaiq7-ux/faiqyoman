<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggans'; // <-- tambahkan baris ini

    protected $fillable = [
        'nama',
        'telepon',
        'alamat',
        
        // kolom lain jika ada
    ];

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class);
    }
}
