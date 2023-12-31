@extends('layouts.admin-master')

@section('title')
Create Jadwal
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Data Jadwal Pengangkutan</h1>
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
            <form method="POST" enctype="multipart/form-data" action="{{route('masterdata-jadwal-store')}}">
            @csrf
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <div class="row">
                        <div class="col">
                            <h3 class="font-weight-bold text-primary"><i class="fas fa-plus"></i>Tambah Data Jadwal Pengangkutan</h3>
                        </div>
                    </div>
                </div>
                <div class="form-group card-body">    
                    <div class="row">
                        <div class="col mb-4">
                            <label for="alamat" class="font-weight-bold text-dark">Hari<i class="text-danger text-sm text-bold">*</i></label>
                            <select class="form-control @error('hari') is-invalid @enderror" id="hari" name="hari">
                                <option value="" selected>Pilih Hari</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                    <option value="Minggu">Minggu</option>
                            </select>
                                @error('hari')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        <div class='col mb-2'>
                            <label for="mulai" class="font-weight-bold text-dark">Jadwal Mulai<i class="text-danger text-sm text-bold">*</i></label>
                            <input type="time" class="form-control @error('mulai') is-invalid @enderror" id="mulai" name="mulai" placeholder="Masukan waktu mulai (Standar waktu 24 Jam)">
                                @error('mulai')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <label for="jenis" class="font-weight-bold text-dark">Jenis Sampah<i class="text-danger text-sm text-bold">*</i></label>
                            <select class="form-control @error('jenis') is-invalid @enderror" id="jenis" name="jenis">
                                <option value="umum" selected>Umum</option>
                                    @foreach($jenis as $jen)
                                        <option value="{{$jen->id}}" >{{$jen->jenis_sampah}}</option>
                                    @endforeach
                            </select>
                                @error('hari')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="selesai" class="font-weight-bold text-dark">Jadwal Selesai</label>
                            <input type="time" class="form-control @error('selesai') is-invalid @enderror" id="selesai" name="selesai" placeholder="Masukan Waktu Selesai (Standar Waktu 24 jam)">
                                @error('selesai')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Menambah Data?')"><i class="fas fa-save"></i> Simpan</button>
                            <a href="{{route('masterdata-jadwal-index')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</section>

@endsection