<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangKeluar;
use App\Models\Barang;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PostResource;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $barangKeluars = BarangKeluar::with('barang')->paginate(10);
        return new PostResource(true, 'Data Barang Keluar Berhasil Diambil', $barangKeluars);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tgl_keluar' => 'required|date',
            'qty_keluar' => 'required|integer|min:1',
            'barang_id' => 'required|exists:barang,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barang = Barang::findOrFail($request->barang_id);
        $barangMasukTerakhir = $barang->barangmasuk()->latest('tgl_masuk')->first();

        if ($barangMasukTerakhir && $request->tgl_keluar < $barangMasukTerakhir->tgl_masuk) {
            return new PostResource(false, 'Tanggal barang keluar tidak boleh mendahului tanggal barang masuk terakhir.', null);
        }

        if ($request->qty_keluar > $barang->stok) {
            return new PostResource(false, 'Jumlah keluar melebihi stok yang tersedia.', null);
        }

        $barangKeluar = BarangKeluar::create($request->all());
        $barang->stok -= $request->qty_keluar;
        $barang->save();

        return new PostResource(true, 'Data Barang Keluar Berhasil Disimpan!', $barangKeluar);
    }

    public function show($id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);
        return new PostResource(true, 'Detail Data Barang Keluar', $barangKeluar);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tgl_keluar' => 'required|date',
            'qty_keluar' => 'required|integer|min:1',
            'barang_id' => 'required|exists:barang,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $barangKeluar = BarangKeluar::findOrFail($id);
        $barang = Barang::findOrFail($request->barang_id);
        $barangMasukTerakhir = $barang->barangmasuk()->latest('tgl_masuk')->first();

        if ($barangMasukTerakhir && $request->tgl_keluar < $barangMasukTerakhir->tgl_masuk) {
            return new PostResource(false, 'Tanggal barang keluar tidak boleh mendahului tanggal barang masuk terakhir.', null);
        }

        if ($request->qty_keluar > $barang->stok + $barangKeluar->qty_keluar) {
            return new PostResource(false, 'Jumlah keluar melebihi stok yang tersedia.', null);
        }

        $barangKeluar->update($request->all());
        return new PostResource(true, 'Data Barang Keluar Berhasil Diperbarui!', $barangKeluar);
    }

    public function destroy($id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);
        $barangKeluar->delete();
        return new PostResource(true, 'Data Barang Keluar Berhasil Dihapus!', null);
    }
}