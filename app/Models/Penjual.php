<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjual extends Model
{
    protected $table = 'penjuals';

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'bank_name',
        'bank_account',
        'bank_account_name',
        'komisi_persen',
        'status',
    ];

    protected $casts = [
        'komisi_persen' => 'float',
    ];

    /**
     * Relasi dengan transaksi
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'penjual_id');
    }

    /**
     * Hitung total penjualan
     */
    public function getTotalPenjualan()
    {
        return $this->transactions()->sum('total_amount');
    }

    /**
     * Hitung komisi
     */
    public function getKomisi()
    {
        return $this->getTotalPenjualan() * ($this->komisi_persen / 100);
    }
}
