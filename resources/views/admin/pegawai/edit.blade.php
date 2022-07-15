@extends('layouts.admin-master')

@section('title')
Edit Data Pegawai
@endsection

@section('scripts')
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Data Pegawai</h1>
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
            
  <div class="section-body">
       <!-- Begin Page Content -->
    <div class = "container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row justify-content-between">
                    <div class="col">
                        <h6 class="m-0 font-weight-bold text-primary">Tambah Pegawai</h6>
                    </div>
                </div>
            </div>
            <form method="POST" enctype="multipart/form-data" action="{{route('pegawai-update', $pegawai->id_pegawai)}}">
            @csrf
            <div class="form-group card-body">    
                <div class="row">
                    <!-- <div class='col mb-2'>
                        <label for="kk" class="font-weight-bold text-dark">No Kartu Keluarga</label>
                        <input type="text" class="form-control @error('kk') is-invalid @enderror" id="kk" name="kk" placeholder="Masukan No Kartu Keluarga">
                            @error('kk')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                    </div> -->
                    <div class="col mb-2">
                        <label for="nik" class="font-weight-bold text-dark">NIK<i class="text-danger text-sm text-bold">*</i></label>
                        <input type="text" class="form-control @error('nik') is-invalid @enderror disabled" id="nik" name="nik" placeholder="Masukan No Induk Kependudukan" value="{{isset($pegawai->kependudukan) ? $pegawai->kependudukan->nik : ''}}">
                            @error('nik')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>    
                            @enderror
                    </div>
                    <div class="col mb-2">
                        <label for="nama" class="font-weight-bold text-dark">Nama Lengkap<i class="text-danger text-sm text-bold">*</i></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror disabled" id="nama" name="nama" placeholder="Masukan Nama Lengkap Pegawai" value="{{isset($pegawai->kependudukan) ? $pegawai->kependudukan->nama : ''}}">
                            @error('nama')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>    
                            @enderror
                    </div>
                    <div class="col mb-2">
                        <label for="role" class="font-weight-bold text-dark">Role</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror disabled" id="role" name="role" placeholder="Masukan Role Pegawai" value="{{$pegawai->role}}">
                            @error('role')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>    
                            @enderror
                    </div>
                </div>
                <div class="row">
                    <div class='col mb-2'>
                        <label for="jenis" class="font-weight-bold text-dark">Jenis Kelamin<i class="text-danger text-sm text-bold">*</i></label>
                        <select class="form-control @error('jenis') is-invalid @enderror disabled" id="jenis" name="jenis">
                            <option value="" selected>Pilih Jenis Kelamin</option>
                                <option value="Pria" {{isset($pegawai->kependudukan) ? ($pegawai->kependudukan->jenis_kelamin == 'Pria' ? 'selected' : '') : ''}}>Pria</option>
                                <option value="Wanita" {{isset($pegawai->kependudukan) ? ($pegawai->kependudukan->jenis_kelamin == 'Wanita' ? 'selected' : '') : ''}}>Wanita</option>
                        </select>
                            @error('jenis')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                    </div>
                    <div class="col mb-2">
                        <label for="tanggal" class="font-weight-bold text-dark">Tanggal Lahir</label>
                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror disabled" id="tanggal" name="tanggal" placeholder="Masukan Tanggal Lahir" value="{{isset($pegawai->kependudukan) ? $pegawai->kependudukan->tanggal_lahir : ''}}">
                            @error('tanggal')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>    
                            @enderror
                    </div>
                    <div class='col mb-2'>
                        <label for="no" class="font-weight-bold text-dark">No Telpon</label>
                        <input type="text" class="form-control @error('no') is-invalid @enderror" id="no" name="no" placeholder="Masukan No Telpon Aktif" value="{{isset($pegawai->kependudukan)? $pegawai->kependudukan->telepon : ''}}">
                            @error('no')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                    </div>
                </div>

                <div class="row mb-3 ">
                    <!-- <div class='col mb-2'>
                        <label for="banjar" class="font-weight-bold text-dark">Banjar</label>
                        <input type="text" class="form-control @error('banjar') is-invalid @enderror" list="banjardata" id="banjar" name="banjar" placeholder="Masukan Banjar (opsional)" value="{{isset($pegawai->banjar) ? $pegawai->banjar->nama_banjar_dinas : ''}}">
                            <datalist id="banjardata">
                                @if($banjar != [])
                                    @foreach($banjar as $b)
                                        <option value="{{$b->nama_banjar_dinas}}">
                                    @endforeach
                                @endif
                            </datalist>
                            @error('banjar')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                    </div> -->
                    <div class="col mb-2">
                        <label for="alamat" class="font-weight-bold text-dark">Alamat</label>
                        <input type="text" class="form-control @error('alamat') is-invalid @enderror disabled" id="alamat" name="alamat" placeholder="Masukan Alamat Tinggal" value="{{isset($pegawai->kependudukan) ? $pegawai->kependudukan->alamat : ''}}">
                            
                            @error('alamat')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>    
                            @enderror
                    </div>
                    
                </div>

                <!-- <div class="row mb-3">
                    <div class='col mb-2'>
                        <label for="file_kk" class="font-weight-bold text-dark">File Kartu Keluarga</label>
                        <input type="file" class="form-control @error('file_kk') is-invalid @enderror" id="file_kk" name="file_kk" placeholder="Upload File Kartu Keluarga">
                            @error('file_kk')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                    </div>
                </div> -->
                <div class="row">
                    <div class="col mr-auto">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Menambah Data?')"><i class="fas fa-save"></i> Simpan</button>
                            <a href="{{route('pegawai-index')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
        </div>
    </div>
</section>

@endsection