@extends('layouts.user-master')

@section('title')
Data Diri
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Data Diri</h1>
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
            <form method="POST" enctype="multipart/form-data" action="{{route('data-update', $pengguna->id)}}">
            @csrf
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <div class="row">
                        <div class="col">
                            <h3 class="font-weight-bold text-primary"><i class="fas fa-pen"></i>Edit Data Diri</h3>
                        </div>
                    </div>
                </div>
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
                            <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" placeholder="Masukan No Induk Kependudukan" value="{{$pengguna->kependudukan->nik}}">
                                @error('nik')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="nama" class="font-weight-bold text-dark">Nama Lengkap<i class="text-danger text-sm text-bold">*</i></label>
                            <input type="nama" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" placeholder="Masukan Nama Lengkap Pengguna" value="{{$pengguna->kependudukan->nama}}">
                                @error('nama')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class='col mb-2'>
                            <label for="jenis" class="font-weight-bold text-dark">Jenis Kelamin<i class="text-danger text-sm text-bold">*</i></label>
                            <select class="form-control @error('jenis') is-invalid @enderror" id="jenis" name="jenis">
                                <option value="" selected>Pilih Jenis Kelamin</option>
                                    <option value="Pria" {{$pengguna->kependudukan->jenis_kelamin == 'laki-laki' ? 'selected' : ''}}>laki-laki</option>
                                    <option value="Wanita" {{$pengguna->kependudukan->jenis_kelamin == 'perempuan' ? 'selected' : ''}}>perempuan</option>
                            </select>
                                @error('jenis')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="tanggal" class="font-weight-bold text-dark">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" placeholder="Masukan Tanggal Lahir" value="{{$pengguna->kependudukan->tanggal_lahir}}">
                                @error('tanggal')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="alamat" class="font-weight-bold text-dark">Alamat</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror"  id="alamat" name="alamat" placeholder="Masukan Alamat Penggunan" value="{{$pengguna->kependudukan->alamat}}">
                                @error('alamat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class='col mb-2'>
                            <label for="banjar" class="font-weight-bold text-dark">Banjar</label>
                            <input type="text" class="form-control @error('banjar') is-invalid @enderror" id="banjar" name="banjar" placeholder="Masukan Banjar (Tempat Tinggal)" value="{{isset($pengguna->kependudukan->mipil) ? $pengguna->kependudukan->mipil->banjarAdat->nama_banjar_adat : (isset($pengguna->kependudukan->kTamiu) ? $pengguna->kependudukan->kTamiu->banjarAdat->nama_banjar_adat : (isset($pengguna->kependudukan->tamiu) ? $pengguna->kependudukan->tamiu->banjarAdat->nama_banjar_adat : '' )) }}">
                                @error('banjar')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        <div class='col mb-2'>
                            <label for="no" class="font-weight-bold text-dark">No Telpon</label>
                            <input type="text" class="form-control @error('no') is-invalid @enderror" id="no" name="no" placeholder="Masukan No Telpon Aktif" value="{{$pengguna->kependudukan->telepon}}">
                                @error('no')
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
                        <div class="col">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Menambah Data?')"><i class="fas fa-save"></i> Simpan</button>
                            <a href="{{route('user-dashboard')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>

    </div>
</section>


@endsection