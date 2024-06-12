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
    <div class="container-fluid">
        <h2 class="h5 mb-0">Edit Barang</h2>
    </div>
</div>
</br>

    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="merk">Merk</label>
                <input type="text" name="merk" class="form-control text-gray-100" value="{{ $barang->merk }}">
            </div>

            <div class="form-group">
                <label for="seri">Seri</label>
                <input type="text" name="seri" class="form-control text-gray-100" value="{{ $barang->seri }}">
            </div>

            <div class="form-group">
                <label for="spesifikasi">Spesifikasi</label>
                <textarea name="spesifikasi" class="form-control text-gray-100">{{ $barang->spesifikasi }}</textarea>
            </div>


            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select name="kategori_id" class="form-control text-gray-100">
                    @foreach($kategori as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $barang->kategori_id ? 'selected' : '' }}>
                            {{ $category->deskripsi }} - {{ $category->kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="foto">Foto:</label>
                <div class="custom-file">
                    <input type="file" name="foto" class="custom-file-input text-gray-100" id="foto">
                    <label class="custom-file-label" for="foto"></label>
                </div>
            </div>

</br>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
