@extends('layouts.layouts')

@section('sidebar')
    <li class="sidebar-item"><a class="sidebar-link" href="{{ route('dashboard.index') }}"> 
        <svg class="svg-icon svg-icon-sm svg-icon-heavy">
            <use xlink:href="#real-estate-1"> </use>
        </svg><span>Home </span></a></li>
    <li class="sidebar-item"><a class="sidebar-link" href="{{ route('barang.index') }}"> 
        <svg class="svg-icon svg-icon-sm svg-icon-heavy">
            <use xlink:href="#survey-1"> </use>
        </svg><span>Barang </span></a></li>
    <li class="sidebar-item active"><a class="sidebar-link" href="#exampledropdownDropdown" data-bs-toggle="collapse"> 
        <svg class="svg-icon svg-icon-sm svg-icon-heavy">
            <use xlink:href="#browser-window-1"> </use>
        </svg><span>Management Barang </span></a>
    <ul class="collapse list-unstyled " id="exampledropdownDropdown">
        <li><a class="sidebar-link" href="{{ route('barangmasuk.index') }}">Barang Masuk</a></li>
        <li><a class="sidebar-link" href="{{ route('barangkeluar.index') }}">Barang Keluar</a></li>
    </ul>

    </ul><span class="text-uppercase text-gray-600 text-xs mx-3 px-2 heading mb-2">Administrator</span>
        <ul class="list-unstyled">
            <li class="sidebar-item"><a class="sidebar-link" href="{{ route('kategori.index') }}"> 
                <svg class="svg-icon svg-icon-sm svg-icon-heavy">
                    <use xlink:href="#imac-screen-1"> </use>
                </svg><span>Kategori </span></a></li>
@endsection

@section('content')
<div class="bg-dash-dark-2 py-4">
    <div class="container-fluid">
        <h2 class="h5 mb-0">Barang Masuk</h2>
    </div>
</div>
</br>

    <div class="container-fluid">
        <a href="{{ route('barangmasuk.create') }}" class="btn btn-success mb-3">Create Barang Masuk</a>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <table class="table">
            <thead class="lh-1 mb-0 text-dash-color-2">
                <tr>
                    <th>ID</th>
                    <th>Tanggal Masuk</th>
                    <th>Qty Masuk</th>
                    <th>Nama Barang</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-100 lh-1 mb-0">
                @forelse ($barangmasuks as $barangmasuk)
                    <tr>
                        <td>{{ $barangmasuk->id }}</td>
                        <td>{{ $barangmasuk->tgl_masuk }}</td>
                        <td>{{ $barangmasuk->qty_masuk }}</td>
                        <td>{{ $barangmasuk->barang->merk }} - {{ $barangmasuk->barang->seri }}</td>
                        <td>
                            <a href="{{ route('barangmasuk.show', $barangmasuk->id) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Detail</a>
                            <a href="{{ route('barangmasuk.edit', $barangmasuk->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i> Edit</a>
                            <form action="{{ route('barangmasuk.destroy', $barangmasuk->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data?')"><i class="fa fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Tidak ada data barang masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $barangmasuks->links() }}
    </div>
@endsection