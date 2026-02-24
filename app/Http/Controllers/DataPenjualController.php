<?php

namespace App\Http\Controllers;

use App\Models\Penjual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataPenjualController extends Controller
{
    /**
     * Tampilkan daftar semua penjual
     */
    public function index()
    {
        $penjuals = Penjual::orderBy('nama')->get();
        
        $totalPenjual = $penjuals->count();
        $totalAktif = $penjuals->where('status', 'aktif')->count();
        
        return view('penjual.data-penjual.index', compact('penjuals', 'totalPenjual', 'totalAktif'));
    }

    /**
     * Form tambah penjual baru
     */
    public function create()
    {
        return view('penjual.data-penjual.create');
    }

    /**
     * Simpan penjual baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:penjuals,email',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'bank_name' => 'required|string|max:100',
            'bank_account' => 'required|string|max:50',
            'bank_account_name' => 'required|string|max:255',
            'komisi_persen' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('penjual', 'public');
            $validated['foto'] = $fotoPath;
        }

        Penjual::create($validated);

        return redirect()->route('penjual.data-penjual.index')
            ->with('success', '✅ Data penjual berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail penjual
     */
    public function show(Penjual $penjual)
    {
        $totalPenjualan = $penjual->getTotalPenjualan();
        $totalKomisi = $penjual->getKomisi();
        $jumlahTransaksi = $penjual->transactions()->count();

        return view('penjual.data-penjual.show', compact(
            'penjual',
            'totalPenjualan',
            'totalKomisi',
            'jumlahTransaksi'
        ));
    }

    /**
     * Form edit penjual
     */
    public function edit(Penjual $penjual)
    {
        return view('penjual.data-penjual.edit', compact('penjual'));
    }

    /**
     * Update penjual
     */
    public function update(Request $request, Penjual $penjual)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:penjuals,email,' . $penjual->id,
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'bank_name' => 'required|string|max:100',
            'bank_account' => 'required|string|max:50',
            'bank_account_name' => 'required|string|max:255',
            'komisi_persen' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old foto if exists
            if ($penjual->foto && \Storage::disk('public')->exists($penjual->foto)) {
                \Storage::disk('public')->delete($penjual->foto);
            }

            $fotoPath = $request->file('foto')->store('penjual', 'public');
            $validated['foto'] = $fotoPath;
        }

        $penjual->update($validated);

        return redirect()->route('penjual.data-penjual.show', $penjual)
            ->with('success', '✅ Data penjual berhasil diperbarui!');
    }

    /**
     * Hapus penjual
     */
    public function destroy(Penjual $penjual)
    {
        $nama = $penjual->nama;
        $penjual->delete();

        return redirect()->route('penjual.data-penjual.index')
            ->with('success', "✅ Data penjual '$nama' berhasil dihapus!");
    }
}
