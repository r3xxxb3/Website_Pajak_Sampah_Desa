@extends('layouts.admin-master')

@section('title')
Edit Data Pelanggan
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Data Pelanggan</h1>
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
            <form method="POST" enctype="multipart/form-data" action="{{route('pengguna-update', $pengguna->id)}}">
            @csrf
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <div class="row">
                        <div class="col">
                            <h3 class="font-weight-bold text-primary"><i class="fas fa-pen"></i>Edit Data Pelanggan</h3>
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
                            <label for="nik" class="font-weight-bold text-dark">NIK</label>
                            <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" placeholder="Masukan No Induk Kependudukan" value="{{$pengguna->nik}}">
                                @error('nik')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="nama" class="font-weight-bold text-dark">Nama Lengkap</label>
                            <input type="nama" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" placeholder="Masukan Nama Lengkap Pengguna" value="{{$pengguna->nama_pengguna}}">
                                @error('nama')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class='col mb-2'>
                            <label for="jenis" class="font-weight-bold text-dark">Jenis Kelamin</label>
                            <select class="form-control @error('jenis') is-invalid @enderror" id="jenis" name="jenis">
                                <option value="" selected>Pilih Jenis Kelamin</option>
                                    <option value="Pria" {{$pengguna->jenis_kelamin == 'Pria' ? 'selected' : ''}}>Pria</option>
                                    <option value="Wanita" {{$pengguna->jenis_kelamin == 'Wanita' ? 'selected' : ''}}>Wanita</option>
                            </select>
                                @error('jenis')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="tanggal" class="font-weight-bold text-dark">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" placeholder="Masukan Tanggal Lahir" value="{{$pengguna->tgl_lahir}}">
                                @error('tanggal')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="alamat" class="font-weight-bold text-dark">Alamat</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror"  id="alamat" name="alamat" placeholder="Masukan Alamat Penggunan" value="{{$pengguna->alamat}}">
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
                            <input type="text" class="form-control @error('banjar') is-invalid @enderror" id="banjar" name="banjar" placeholder="Masukan Banjar (Tempat Tinggal)" value="{{isset($pengguna->banjar) ? $pengguna->banjar->nama_banjar_dinas : '' }}">
                                @error('banjar')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        <div class='col mb-2'>
                            <label for="no" class="font-weight-bold text-dark">No Telpon</label>
                            <input type="text" class="form-control @error('no') is-invalid @enderror" id="no" name="no" placeholder="Masukan No Telpon Aktif" value="{{$pengguna->no_telp}}">
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
                            <a href="{{route('pengguna-index')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>

        <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List Properti</h6>
                </div>
                <div class="card-body">
                @if (Session::has('error-1'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-times"></i> 
                        {{ Session::get('error-1') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    @if (isset($errors) && $errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        {{$error}}
                        @endforeach
                    </div>
                    @endif
                    @if (Session::has('success-1'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check"></i> {{Session::get('success-1')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    @endif
                    @if (!empty($success))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check"></i> {{$success}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    @endif
                <div class="table-responsive">
                <a class= "btn btn-success text-white mb-2" data-toggle="modal" data-target="#modal-add"><i class="fas fa-plus"></i> Tambah Properti</a>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="col-2">Action</th>
                            <th>Nama Properti</th>
                            <th>Jenis Properti</th>
                            <th>Alamat </th>
                            <th>Foto </th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($index as $properti)
                        <tr>
                            <td align="center">
                                <a  data-toggle="modal" data-target="#modal-{{$properti->id}}" class="btn btn-info btn-sm text-white"><i class="fas fa-pencil-alt"></i></a>
                                <a style="margin-right:7px" class="btn btn-danger btn-sm" href="{{Route('admin-properti-delete', $properti->id)}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-trash"></i></a>
                            </td>
                            <td>
                                {{$properti->nama_properti}}
                            </td>
                            <td>
                                {{isset($properti->jasa) ? $properti->jasa->jenis_jasa : ''}}
                            </td>
                            <td>
                                {{$properti->alamat}}
                            </td>
                            <td class="col-5">
                                <!-- {{$properti->file}} -->
                            </td>
                            <td>
                                {{$properti->status}}
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-add">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary">Tambah Properti Pelanggan</h6>
                            </div>
                        </div>
                        <form method="POST" enctype="multipart/form-data" action="{{route('admin-properti-store')}}">
                        @csrf   
                            <div class="row">
                                <div class='col mb-2'>
                                    <label for="nama" class="font-weight-bold text-dark">Nama Properti</label>
                                    <input type="text" class="form-control @error('jenis') is-invalid @enderror" id="nama" name="nama" placeholder="Masukan Nama Properti (cth: rumah tinggal,.. etc)" value="{{isset($properti->nama_properti) ? $properti->nama_properti : old('nama')}}">
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
                                                <option value="{{$j->id}}" {{old('jenis') == $j->id ? 'selected' : ''}}>{{$j->jenis_jasa}}</option>
                                            @endforeach
                                    </select>
                                        @error('deskripsi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                                <div class="col mb-2" id="additional" hidden>
                                    <label for="kamar" class="font-weight-bold text-dark">Jumlah Kamar</label>
                                    <input type="number" class="form-control @error('deskripsi') is-invalid @enderror" id="kamar" name="kamar" placeholder="Masukan Jumlah Kamar terisi (Kos)" value="{{old('kamar')}}">
                                        @error('deskripsi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="banjar" class="font-weight-bold text-dark">Banjar</label>
                                    <input type="text" class="form-control @error('banjar') is-invalid @enderror" id="banjar" name="banjar" placeholder="Masukan Banjar Properti (opsional)" value="{{old('banjar')}}">
                                        @error('banjar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                                <div class="col">
                                    <label for="alamat" class="font-weight-bold text-dark">Alamat Properti</label>
                                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Masukan Alamat Properti" value="{{old('alamat')}}">
                                        @error('alamat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                                <div class="col">
                                    <label for="file" class="font-weight-bold text-dark">Upload Gambar Properti</label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" placeholder="Masukan file gambar properti" value="{{old('file')}}">
                                        @error('file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Mengubah Data?')"><i class="fas fa-save"></i> Simpan</button>
                                    <a data-dismiss="modal" class="btn btn-danger text-white"><i class="fas fa-times"></i> Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</div>

@foreach($index as $properti)
<div class="modal fade" id="modal-{{$properti->id}}">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary">Edit Properti Pelanggan</h6>
                            </div>
                        </div>
                        <form method="POST" enctype="multipart/form-data" action="{{route('admin-properti-update', $properti->id)}}">
                        @csrf   
                            <div class="row">
                                <div class='col mb-2'>
                                    <label for="nama" class="font-weight-bold text-dark">Nama Properti</label>
                                    <input type="text" class="form-control @error('jenis') is-invalid @enderror" id="nama" name="nama" placeholder="Masukan Nama Properti (cth: rumah tinggal,.. etc)" value="{{isset($properti->nama_properti) ? $properti->nama_properti : old('nama')}}" disabled>
                                        @error('nama')
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
                                    <input type="text" class="form-control @error('banjar') is-invalid @enderror" id="banjar" name="banjar" placeholder="Masukan Banjar Properti (opsional)" value="{{isset($properti->id_banjar) ? $properti->banjar->first()->nama_banjar_dinas : old('banjar')}}" disabled>
                                        @error('banjar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                                <div class="col">
                                    <label for="alamat" class="font-weight-bold text-dark">Alamat Properti</label>
                                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Masukan Alamat Properti" value="{{isset($properti->alamat) ? $properti->alamat : old('alamat')}}" disabled>
                                        @error('alamat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                                <div class="col">
                                    <label for="file" class="font-weight-bold text-dark">Upload Gambar Properti</label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" placeholder="Masukan file gambar properti" value="{{isset($properti->file) ? $properti->file : old('file')}}" disabled>
                                        @error('file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Mengubah Data?')"><i class="fas fa-save"></i> Verifikasi</button>
                                    <a href="{{Route('admin-properti-cancel', $properti->id)}}" class="btn btn-warning" onclick="return confirm('Apakah Anda Yakin Ingin Membatalkan Properti ?')"><i class="fa fa-exclamation-circle"></i> Batalkan Properti</a>
                                    <a data-dismiss="modal" class="btn btn-danger text-white"><i class="fas fa-times"></i> Close</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</div>
@endforeach

@endsection