@extends('layouts.user-master')

@section('title')
Data Diri
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Info Data Diri</h1>
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
            <form method="POST" enctype="multipart/form-data" action="{{route('data-update', $pelanggan->id)}}">
            @csrf
            <div class="card shadow">
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
                            <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" placeholder="Masukan No Induk Kependudukan" value="{{$pelanggan->kependudukan->nik}}" disabled>
                                @error('nik')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="nama" class="font-weight-bold text-dark">Nama Lengkap<i class="text-danger text-sm text-bold">*</i></label>
                            <input type="nama" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" placeholder="Masukan Nama Lengkap Pengguna" value="{{$pelanggan->kependudukan->nama}}" disabled>
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
                            <select class="form-control @error('jenis') is-invalid @enderror" id="jenis" name="jenis" disabled>
                                <option value="" selected>Pilih Jenis Kelamin</option>
                                    <option value="Pria" {{$pelanggan->kependudukan->jenis_kelamin == 'laki-laki' ? 'selected' : ''}}>laki-laki</option>
                                    <option value="Wanita" {{$pelanggan->kependudukan->jenis_kelamin == 'perempuan' ? 'selected' : ''}}>perempuan</option>
                            </select>
                                @error('jenis')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="tanggal" class="font-weight-bold text-dark">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" placeholder="Masukan Tanggal Lahir" value="{{$pelanggan->kependudukan->tanggal_lahir}}" disabled>
                                @error('tanggal')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        <div class='col mb-2'>
                            <label for="no" class="font-weight-bold text-dark">No Telpon</label>
                            <input type="text" class="form-control @error('no') is-invalid @enderror" id="no" name="no" placeholder="Masukan No Telpon Aktif" value="{{$pelanggan->kependudukan->telepon}}" disabled>
                                @error('no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class='col mb-2'>
                                <label for="desa" class="font-weight-bold text-dark">Desa Adat</label>
                                <input type="text" class="form-control @error('desa') is-invalid @enderror" list="desadata" id="desa" name="desa" placeholder="Masukan desa adat (Tempat Tinggal)" value="{{isset($pelanggan->kependudukan->mipil) ? $pelanggan->kependudukan->mipil->banjarAdat->desaAdat->desadat_nama : (isset($pelanggan->kependudukan->kTamiu) ? $pelanggan->kependudukan->kTamiu->banjarAdat->desaAdat->desadat_nama : (isset($pelanggan->kependudukan->tamiu) ? $pelanggan->kependudukan->tamiu->banjarAdat->desaAdat->desadat_nama : '' )) }}" disabled>
                                    <datalist id="desadata">
                                        @if($desaAdat != [])
                                            @foreach($desaAdat as $d)
                                                <option value="{{$d->desadat_nama}}">
                                            @endforeach
                                        @endif
                                    </datalist>
                                    @error('desa')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>
                        <div class='col mb-2'>
                            <label for="banjar" class="font-weight-bold text-dark">Banjar Adat</label>
                            <input type="text" class="form-control @error('banjar') is-invalid @enderror" id="banjar" name="banjar" list="banjardata" placeholder="Masukan Banjar (Tempat Tinggal)" value="{{isset($pelanggan->kependudukan->mipil) ? $pelanggan->kependudukan->mipil->banjarAdat->nama_banjar_adat : (isset($pelanggan->kependudukan->kTamiu) ? $pelanggan->kependudukan->kTamiu->banjarAdat->nama_banjar_adat : (isset($pelanggan->kependudukan->tamiu) ? $pelanggan->kependudukan->tamiu->banjarAdat->nama_banjar_adat : '' )) }}" disabled>
                                <datalist id="banjardata">
                                        @if($banjarAdat != [])
                                            @foreach($banjarAdat as $b)
                                                <option value="{{$b->nama_banjar_adat}}">
                                            @endforeach
                                        @endif
                                    </datalist>
                                @error('banjar')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col mb-2">
                            <label for="alamat" class="font-weight-bold text-dark">Alamat</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror"  id="alamat" name="alamat" placeholder="Masukan Alamat Penggunan" value="{{$pelanggan->kependudukan->alamat}}" disabled>
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
                        <div class="col" hidden>
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