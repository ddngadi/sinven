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
        <h2 class="h5 mb-0">Show Barang</h2>
    </div>
</div>
</br>
    <div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5>Detail :</h5>                
                <ul>
                    <li>ID: {{ $barang->id }}</li>
                    <li>Merk: {{ $barang->merk }}</li>
                    <li>Seri: {{ $barang->seri }}</li>
                    <li>Spesifikasi: {{ $barang->spesifikasi }}</li>
                    <li>Stok: {{ $barang->stok }}</li>
                    <li>Kategori_id: <a href="{{ route('kategori.show', $barang->kategori->id) }}">
                        {{ $barang->kategori->id }} </a>
                    </li>
                    @if($barang->foto)
                <li class="foto-label"><strong>Foto:</strong></li>
                <ul class="foto">
                    <img src="{{ asset('storage/' . $barang->foto) }}" alt="Foto Barang" style="max-width: 200px;">
                </ul>
                @endif

                </ul>

                <a href="{{ route('barang.index') }}" class="btn btn-primary">Back</a>

            </div>
    </div>



</div>
@endsection
