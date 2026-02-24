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
        'foto',
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

    /**
     * Add accessor to get full URL for the foto field
     */
    public function getFotoUrlAttribute()
    {
        // if no foto, return a placeholder
        if (!$this->foto) {
            return 'https://via.placeholder.com/150?text=Penjual';
        }

        // if the foto already looks like a full URL, return it
        if (preg_match('/^https?:\/\//i', $this->foto)) {
            return $this->foto;
        }

        // assume the file is stored in storage/app/public and served via /storage
        return asset('storage/' . ltrim($this->foto, '/'));
    }
}
