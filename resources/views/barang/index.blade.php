@extends('layouts.layouts')

@section('sidebar')
    <li class="sidebar-item"><a class="sidebar-link" href="{{ route('dashboard.index') }}"> 
        <svg class="svg-icon svg-icon-sm svg-icon-heavy">
            <use xlink:href="#real-estate-1"> </use>
        </svg><span>Home </span></a></li>
    <li class="sidebar-item active"><a class="sidebar-link" href="{{ route('barang.index') }}"> 
        <svg class="svg-icon svg-icon-sm svg-icon-heavy">
            <use xlink:href="#survey-1"> </use>
        </svg><span>Barang </span></a></li>
    <li class="sidebar-item"><a class="sidebar-link" href="#exampledropdownDropdown" data-bs-toggle="collapse"> 
        <svg class="svg-icon svg-icon-sm svg-icon-heavy">
            <use xlink:href="#browser-window-1"> </use>
        </svg><span>Management Barang </span></a>
    <ul class="collapse list-unstyled " id="exampledropdownDropdown">
        <li><a class="sidebar-link" href="{{ route('barangmasuk.index') }}">Barang Masuk</a></li>
        <li><a class="sidebar-link" href="{{ route('barangkeluar.index') }}">Barang Keluar</a></li>
    </ul>
    </li>

    </ul><span class="text-uppercase text-gray-600 text-xs mx-3 px-2 heading mb-2">Administrator</span>
        <ul class="list-unstyled">
            <li class="sidebar-item"><a class="sidebar-link" href="{{ route('kategori.index') }}"> 
                <svg class="svg-icon svg-icon-sm svg-icon-heavy">
                    <use xlink:href="#imac-screen-1"> </use>
                </svg><span>Kategori </span></a></li>
@endsection

@section('content')
<div class="bg-dash-dark-2 py-4">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0">Barang</h2>
        <form action="{{ route('barang.index') }}" method="GET" class="d-inline-block text-end">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search for..." value="{{ isset($searchBarang) ? $searchBarang : '' }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</br>

    <div class="container-fluid">
        <a href="{{ route('barang.create') }}" class="btn btn-success mb-3">Create Barang</a>
        @if (session('Gagal'))
            <div class="alert alert-danger">
                {{ session('Gagal') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="lh-1 mb-0 text-dash-color-2">
                <tr>
                    <th>ID</th>
                    <th>Merk</th>
                    <th>Seri</th>
                    <th width=30%>Spesifikasi</th>
                    <th>Stok</th>
                    <th>Kategori_id</th>
                    <th>Foto</th>
                    <th width=30%>Action</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-100 lh-1 mb-0">
                @foreach($barangs as $barang)
                    <tr>
                        <td>{{ $barang->id }}</td>
                        <td>{{ $barang->merk }}</td>
                        <td>{{ $barang->seri }}</td>
                        <td>{{ $barang->spesifikasi }}</td>
                        <td>{{ $barang->stok }}</td>
                        <td>
                            <a href="{{ route('kategori.show', $barang->kategori->id) }}">
                                {{ $barang->kategori->id }} 
                            </a>
                        </td>
                        <td>
                            @if($barang->foto)
                                <img src="{{ asset('storage/' . $barang->foto) }}" alt="Foto Barang" style="max-width: 100px; max-height: 100px;">
                            @else
                                No Image
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('barang.show', $barang->id) }}" class="btn btn-info "><i class="fa fa-eye"></i> Show</a>
                            <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-warning"><i class="fa fa-pencil"></i> Edit</a>
                            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?')"><i class="fa fa-trash"></i> Delete</button>  
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    <div class="d-flex justify-content">
    {{ $barangs->links('pagination::bootstrap-4') }}
    </div>

    </div>
@endsection
