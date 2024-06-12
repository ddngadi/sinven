<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;
use App\Models\Barang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;

class KategoriController extends Controller
{
    use ValidatesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //mengakses method dari model Kategori - OK
        // ----------------------------------------------------------------
        //$rsetKategori = Kategori::getKategoriAll();
        //return view('kategori.index', compact('rsetKategori'));
        $searchKategori = $request->input('search');

    // Check if search query is provided
        if ($searchKategori) {
            // Filter categories based on the search query
            
            $rsetKategori = DB::table('vKategori')
                            ->select('id', 'deskripsi','kategori', DB::raw('ketKategori(kategori) as ketkategori'))
                            ->where('deskripsi', 'like', '%' . $searchKategori . '%')
                            ->orWhere('kategori', 'like', '%' . $searchKategori . '%')
                            ->orWhere('id', 'like', '%' . $searchKategori . '%')
                            ->paginate(10);
        } else {
            // If no search query, retrieve all categories
            $rsetKategori = Kategori::getKategoriAll();
        }

    return view('kategori.index', compact('rsetKategori', 'searchKategori'));
        // ----------------------------------------------------------------
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $aKategori = array('blank'=>'Pilih Kategori',
                            'M'=>'Barang Modal',
                            'A'=>'Alat',
                            'BHP'=>'Bahan Habis Pakai',
                            'BTHP'=>'Bahan Tidak Habis Pakai'
                            );
        return view('kategori.create',compact('aKategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'deskripsi' => 'required|unique:kategori,deskripsi',
            'kategori' => 'required|in:M,A,BHP,BTHP',
        ]);

        try {
            DB::beginTransaction(); // <= Memulai transaksi
            // Insert data into 'kategori' table
            DB::table('kategori')->insert([
                'deskripsi' => $request->deskripsi,
                'kategori' => $request->kategori,
                // Add other columns if needed
            ]);
    
            DB::commit(); // <= Menyimpan perubahan ke database
        } catch (\Exception $e) {
            report($e);
            DB::rollBack(); // <= Mengembalikan transaksi dalam kasus terjadi kesalahan
            return redirect()->route('kategori.index')->with(['error' => 'Terjadi kesalahan. Data tidak berhasil disimpan.']);
        }

        //create post
        // Kategori::create([
        //     'deskripsi'  => $request->deskripsi,
        //     'kategori'   => $request->kategori,
        // ]);

        //redirect to index
        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        if (DB::table('barang')->where('kategori_id', $id)->exists()) {
            $rsetKategori = Kategori::find($id); // Jika ada barang yang terkait, ambil objek kategori dengan find().
        } else {
            $rsetKategori = Kategori::showKategoriById($id); // Jika tidak ada barang yang terkait, gunakan showKategoriById().
        }

        $rsetKategori = Kategori::select('id', 'deskripsi', 'kategori',
            DB::raw('(CASE
                WHEN kategori = "M" THEN "Modal"
                WHEN kategori = "A" THEN "Alat"
                WHEN kategori = "BHP" THEN "Bahan Habis Pakai"
                ELSE "Bahan Tidak Habis Pakai"
                END) AS ketKategori'))
            ->where('id', '=', $id)
            ->first();
        

        //return $rsetKategori;
        return view('kategori.show', compact('rsetKategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $aKategori = array('blank'=>'Pilih Kategori',
        'M'=>'Barang Modal',
        'A'=>'Alat',
        'BHP'=>'Bahan Habis Pakai',
        'BTHP'=>'Bahan Tidak Habis Pakai'
    );

        $rsetKategori = Kategori::find($id);
        //return $rsetBarang;
        return view('kategori.edit', compact('rsetKategori','aKategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'deskripsi' => 'required|unique:kategori,deskripsi,'.$id, 
            'kategori'    => 'required | in:M,A,BHP,BTHP',
        ]);

        $rsetKategori = Kategori::find($id);

        $rsetKategori->update([
            'deskripsi'  => $request->deskripsi,
            'kategori'   => $request->kategori
            ]);

            //redirect to index
        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {


        if (DB::table('barang')->where('kategori_id', $id)->exists()){
            return redirect()->route('kategori.index')->with(['Gagal' => 'Data Gagal Dihapus! Data sudah dipakai ditabel barang']);
        } else {   
            $rsetKategori = Kategori::find($id);
            $rsetKategori->delete();
            return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Dihapus!']);
        }
    }


}