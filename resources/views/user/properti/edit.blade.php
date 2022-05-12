@extends('layouts.user-master')

@section('title')
Edit Properti
@endsection

@section('scripts')
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Properti</h1>
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
            <form method="POST" enctype="multipart/form-data" action="{{route('properti-update', $properti->id)}}">
            @csrf
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <div class="row">
                        <div class="col">
                            <h3 class="font-weight-bold text-primary"><i class="fas fa-plus"></i>Edit Properti</h3>
                        </div>
                    </div>
                </div>
                <div class="form-group card-body">    
                    <div class="row">
                        <div class='col mb-2'>
                            <label for="nama" class="font-weight-bold text-dark">Nama Properti</label>
                            <input type="text" class="form-control @error('jenis') is-invalid @enderror" id="nama" name="nama" placeholder="Masukan Nama Properti (cth: rumah tinggal,.. etc)" value="{{isset($properti->nama_properti) ? $properti->nama_properti : old('nama')}}" >
                                @error('jenis')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="JENIS" class="font-weight-bold text-dark">Jenis Properti</label>
                            <select class="form-control @error('jenis') is-invalid @enderror" id="jenis" name="jenis">
                                <option value="" selected>Pilih Jenis Properti</option>
                                    @foreach($jenis as $j)
                                        <option value="{{$j->id}}" {{old('jenis') == $j->id || $properti->id_jenis == $j->id  ? 'selected' : ''}}>{{$j->jenis_jasa}}</option>
                                    @endforeach
                            </select>
                                @error('deskripsi')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        @if($properti->jasa->jenis_jasa == "Kos-kosan")
                        <div class="col mb-2" id="additional" >
                            <label for="kamar" class="font-weight-bold text-dark">Jumlah Kamar</label>
                            <input type="number" class="form-control @error('kamar') is-invalid @enderror" id="kamar" name="kamar" placeholder="Masukan Jumlah Kamar terisi (Kos)" value="{{isset($properti->jumlah_kamar) ? $properti->jumlah_kamar : old('kamar')}}" disabled>
                                @error('kamar')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="banjar" class="font-weight-bold text-dark">Banjar</label>
                            <input type="text" class="form-control @error('banjar') is-invalid @enderror" id="banjar" name="banjar" placeholder="Masukan Banjar Properti (opsional)" value="{{isset($properti->id_banjar) ? $properti->banjar->first()->nama_banjar_dinas : old('banjar')}}" >
                                @error('banjar')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        <div class="col">
                            <label for="alamat" class="font-weight-bold text-dark">Alamat Properti</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Masukan Alamat Properti" value="{{isset($properti->alamat) ? $properti->alamat : old('alamat')}}" >
                                @error('alamat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        <div class="col">
                            <label for="file" class="font-weight-bold text-dark">Upload Gambar Properti</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" placeholder="Masukan file gambar properti" value="{{isset($properti->file) ? $properti->file : old('file')}}" >
                                @error('file')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Menambah Data?')"><i class="fas fa-save"></i> Simpan</button>
                            <a href="{{route('properti-index')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</section>
@endsection