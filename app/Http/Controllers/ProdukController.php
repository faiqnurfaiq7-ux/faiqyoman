<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        // Support search via query param `q`
        $q = request()->query('q');
        $produks = Produk::when($q, function ($query, $q) {
            $query->where('nama', 'like', "%{$q}%")
                  ->orWhere('id', $q)
                  ->orWhere('harga', 'like', "%{$q}%");
        })->orderBy('nama')->paginate(15)->withQueryString();

        return view('produk.index', compact('produks', 'q'));
    }

    // AJAX suggestions for search autocomplete
    public function suggest(Request $request)
    {
        $q = $request->query('q');
        if (!$q) {
            return response()->json([]);
        }

        $items = Produk::where('nama', 'like', "%{$q}%")
            ->orderBy('nama')
            ->limit(8)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nama' => $p->nama,
                    'harga' => $p->harga,
                    'foto' => $p->foto_url ?? ($p->foto ?? null),
                ];
            });

        return response()->json($items);
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('produk', 'public');
        }

        Produk::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }
    
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
            'stok'  => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $produk = Produk::findOrFail($id);
        $data = [
            'nama' => $request->nama,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ];

        if ($request->hasFile('foto')) {
            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');

    }
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }
}