<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BarangController extends Controller
{
    use ValidatesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $barangs = Barang::paginate(5);
        // return view('barang.index', compact('barangs'));
        
        $searchBarang = $request->input('search');

    // Check if search query is provided
    if ($searchBarang) {
        // Filter barang based on the search query
        $barangs = Barang::with('kategori')
            ->select('id', 'merk', 'seri', 'spesifikasi', 'stok', 'foto', 'kategori_id') // Include kategori_id in the select statement
            ->where('merk', 'like', '%' . $searchBarang . '%')
            ->orWhere('seri', 'like', '%' . $searchBarang . '%')
            ->orWhere('stok', 'like', '%' . $searchBarang . '%')
            ->orWhere('spesifikasi', 'like', '%' . $searchBarang . '%')
            ->orWhere('kategori_id', 'like', '%' . $searchBarang . '%')
            ->paginate(10);
    } else {
        // If no search query, retrieve all barang
        $barangs = Barang::with('kategori')->paginate(5);
    }

        // Return the view with the paginated data
        return view('barang.index', compact('barangs'));
        }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('barang.create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'merk' => 'nullable|string|max:50',
            'seri' => 'nullable|string|max:50',
            'spesifikasi' => 'nullable|string',
            'stok' => 'integer|min:0',
            'foto' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'kategori_id' => 'required|exists:kategori,id',
        ]);
        $stok = $request->input('stok', 1);

        $foto = $request->file('foto');
        $fotoPath = $foto->store('barang_photos', 'public');

        // Create a new barang record
        // Barang::create($request->all());
        Barang::create([
            'merk' => $request->merk,
            'seri' => $request->seri,
            'spesifikasi' => $request->spesifikasi,
            'stok' => $stok,
            'foto' => $fotoPath,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->route('barang.index')->with(['success' => 'Data Barang Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategori = Kategori::all();
        return view('barang.edit', compact('barang', 'kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'merk' => 'nullable|string|max:50',
            'seri' => 'nullable|string|max:50',
            'spesifikasi' => 'nullable|string',
            'stok' => 'integer|min:0',
            'foto' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'kategori_id' => 'required|exists:kategori,id',
        ]);

        // Find the barang record and update it
        $barang = Barang::findOrFail($id);
        //$barang->update($request->all());
        $barang->update([
            'merk' => $request->merk,
            'seri' => $request->seri,
            'spesifikasi' => $request->spesifikasi,
            'kategori_id' => $request->kategori_id,
        ]);

        // Jika ada foto baru yang diunggah, simpan ke penyimpanan dan perbarui kolom foto
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoPath = $foto->store('barang_photos', 'public');
            $barang->update(['foto' => $fotoPath]);
        }

        return redirect()->route('barang.index')->with(['success' => 'Data Barang Berhasil Diperbarui!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {


        $barang = Barang::findOrFail($id);
        if (DB::table('barangmasuk')->where('barang_id', $id)->exists() ||
            DB::table('barangkeluar')->where('barang_id', $id)->exists()) {
        return redirect()->route('barang.index')->with(['Gagal' => 'Data Gagal Dihapus!']);
        }
        $barang->delete();

        return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Dihapus!']);

    }
}