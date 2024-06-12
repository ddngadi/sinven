<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PostResource;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::paginate(5);
        return new PostResource(true, 'Data Barang Berhasil Diambil', $barangs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merk' => 'nullable|string|max:50',
            'seri' => 'nullable|string|max:50',
            'spesifikasi' => 'nullable|string',
            'stok' => 'integer|min:0',
            'kategori_id' => 'required|exists:kategori,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $stok = $request->input('stok', 1);

        // Create a new barang record
        $barang = Barang::create([
            'merk' => $request->merk,
            'seri' => $request->seri,
            'spesifikasi' => $request->spesifikasi,
            'stok' => $stok,
            'kategori_id' => $request->kategori_id,
        ]);

        return new PostResource(true, 'Data Barang Berhasil Disimpan!', $barang);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        return new PostResource(true, 'Detail Data Barang', $barang);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'merk' => 'nullable|string|max:50',
            'seri' => 'nullable|string|max:50',
            'spesifikasi' => 'nullable|string',
            'stok' => 'integer|min:0',
            'kategori_id' => 'required|exists:kategori,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find the barang record and update it
        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return new PostResource(true, 'Data Barang Berhasil Diperbarui!', $barang);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        if (DB::table('barangmasuk')->where('barang_id', $id)->exists() ||
            DB::table('barangkeluar')->where('barang_id', $id)->exists()) {
            return new PostResource(false, 'Data Gagal Dihapus karena dipakai di tabel barang Masuk!', null);
        }

        $barang->delete();
        return new PostResource(true, 'Data Barang Berhasil Dihapus!', null);
    }
}