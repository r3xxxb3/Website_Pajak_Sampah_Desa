@extends('layouts.admin-master')

@section('title')
Create Kota
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Data Kabupaten / Kota</h1>
    </div>

    <div class="section-body">
        <div class="container-fluid">
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-times"></i> 
                {{ Session::get('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check"></i> {{Session::get('success')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <form method="POST" enctype="multipart/form-data" action="{{route('masterdata-kota-store')}}">
            @csrf
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <div class="row">
                        <div class="col">
                            <h3 class="font-weight-bold text-primary"><i class="fas fa-plus"></i>Tambah Data Kabupaten/Kota</h3>
                        </div>
                    </div>
                </div>
                <div class="form-group card-body">    
                    <div class="row mb-2">
                        <div class='col mb-2'>
                            <label for="desadat_nama" class="font-weight-bold text-dark">Nama Kabupaten/Kota</label>
                            <input type="text" class="form-control" id="kabkot" name="kabkot" placeholder="Masukan Nama Kabupaten/Kota">
                            <small style="color: red">
                                @error('kabkot')
                                    {{$message}}
                                @enderror
                            </small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Menambah Data?')"><i class="fas fa-save"></i> Simpan</button>
                            <a href="{{route('masterdata-kota-index')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</section>

@endsection