<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan; // <- ini cukup buat manggil model, jangan didefinisikan lagi

class LaporanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with('pelanggan')->get();
        return view('laporan.index', compact('penjualans'));
    }
}
