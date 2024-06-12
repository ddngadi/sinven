<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PostResource;

class BarangMasukController extends Controller
{
    public function index()
    {
        $barangMasuks = BarangMasuk::with('barang')->paginate(10);
        return new PostResource(true, 'Data Barang Masuk Berhasil Diambil', $barangMasuks);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tgl_masuk' => 'required|date',
            'qty_masuk' => 'required|integer|min:1',
            'barang_id' => 'required|exists:barang,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barangMasuk = BarangMasuk::create($request->all());

        return new PostResource(true, 'Data Barang Masuk Berhasil Disimpan!', $barangMasuk);
    }

    public function show($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        return new PostResource(true, 'Detail Data Barang Masuk', $barangMasuk);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tgl_masuk' => 'required|date',
            'qty_masuk' => 'required|integer|min:1',
            'barang_id' => 'required|exists:barang,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barangMasuk = BarangMasuk::findOrFail($id);
        $barangMasuk->update($request->all());

        return new PostResource(true, 'Data Barang Masuk Berhasil Diperbarui!', $barangMasuk);
    }

    public function destroy($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);

        $barangKeluarCount = BarangKeluar::where('barang_id', $barangMasuk->barang_id)
            ->where('tgl_keluar', '>=', $barangMasuk->tgl_masuk)
            ->count();

        if ($barangKeluarCount > 0) {
            return new PostResource(false, 'Data Barang Masuk tidak dapat dihapus karena ada Barang Keluar yang terkait.', null);
        }

        $barangMasuk->delete();
        return new PostResource(true, 'Data Barang Masuk Berhasil Dihapus!', null);
    }
}