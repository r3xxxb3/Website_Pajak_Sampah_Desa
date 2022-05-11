@extends('layouts.admin-master')

@section('title')
Index Data Pengguna
@endsection

@section('scripts')
<script>
    function setPengguna( id ){
            $('#idpeng').val(id);
            // console.log(id);
    };
</script>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Data Pegawai</h1>
    </div>

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
            <form method="POST" enctype="multipart/form-data" action="{{route('pegawai-store-new')}}">
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
                        <label for="nik" class="font-weight-bold text-dark">NIK</label>
                        <input type="text" class="form-control @error('nik') is-invalid @enderror disabled" id="nik" name="nik" placeholder="Masukan No Induk Kependudukan">
                            @error('nik')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>    
                            @enderror
                    </div>
                    <div class="col mb-2">
                        <label for="nama" class="font-weight-bold text-dark">Nama Lengkap</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror disabled" id="nama" name="nama" placeholder="Masukan Nama Lengkap Pegawai">
                            @error('nama')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>    
                            @enderror
                    </div>
                    <div class="col mb-2">
                        <label for="role" class="font-weight-bold text-dark">Role</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror disabled" id="nama" name="nama" placeholder="Masukan Role Pegawai">
                            @error('role')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>    
                            @enderror
                    </div>
                </div>
                <div class="row">
                    <div class='col mb-2'>
                        <label for="jenis" class="font-weight-bold text-dark">Jenis Kelamin</label>
                        <select class="form-control @error('jenis') is-invalid @enderror disabled" id="jenis" name="jenis">
                            <option value="" selected>Pilih Jenis Kelamin</option>
                                <option value="Pria">Pria</option>
                                <option value="Wanita">Wanita</option>
                        </select>
                            @error('jenis')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                    </div>
                    <div class="col mb-2">
                        <label for="tanggal" class="font-weight-bold text-dark">Tanggal Lahir</label>
                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror disabled" id="tanggal" name="tanggal" placeholder="Masukan Tanggal Lahir">
                            @error('tanggal')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>    
                            @enderror
                    </div>
                    <div class="col mb-2">
                        <label for="alamat" class="font-weight-bold text-dark">Alamat</label>
                        <input type="text" class="form-control @error('alamat') is-invalid @enderror disabled" id="nama" name="nama" placeholder="Masukan Alamat Tinggal">
                            
                            @error('alamat')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>    
                            @enderror
                    </div>
                </div>

                <div class="row mb-3 ">
                    <div class='col mb-2'>
                        <label for="banjar" class="font-weight-bold text-dark">Banjar</label>
                        <input type="text" class="form-control @error('banjar') is-invalid @enderror" list="banjardata" id="banjar" name="banjar" placeholder="Masukan Banjar (opsional)">
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
                    </div>
                    <div class='col mb-2'>
                        <label for="no" class="font-weight-bold text-dark">No Telpon</label>
                        <input type="text" class="form-control @error('no') is-invalid @enderror" id="no" name="no" placeholder="Masukan No Telpon Aktif">
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
                    <div class="col mr-auto">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Menambah Data?')"><i class="fas fa-save"></i> Simpan</button>
                            <a href="{{route('pegawai-index')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
            

    <div class="container-fluid">
        <!-- DataTales Example -->
        <!-- Copy drisini -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pilih Pegawai</h6>
        </div>
        <div class="card-body">
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-times"></i> 
                    {{ Session::get('error') }}
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
                @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check"></i> {{Session::get('success')}}
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
            <!-- <a class= "btn btn-success text-white mb-2" href="{{route('pengguna-create')}}"><i class="fas fa-plus"></i> Tambah Data Pengguna</a> -->
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="col-2">Action</th>
                        <!-- <th>No Kartu Keluarga</th> -->
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Kelamin</th>
                        <th>No Telp</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($index as $pengguna)
                    <tr>
                        <td align="center">
                            <a class="btn btn-success btn-sm text-white" data-toggle="modal" data-target="#modal-global-filter" onclick="setPengguna(<?php echo $pengguna->id; ?>)" ><i class="fas fa-plus"></i></a>
                            {{-- <a href="/admin/pengguna/edit/{{$pengguna->id}}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i></a> --}}
                            {{-- <a style="margin-right:7px" class="btn btn-danger btn-sm" href="/admin/pengguna/delete/{{$pengguna->id}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-trash"></i></a> --}}
                        </td>
                        <!-- <td>
                            {{$pengguna->no_kk}}
                        </td> -->
                        <td>
                            {{$pengguna->nik}}
                        </td>
                        <td>
                            {{$pengguna->nama_pengguna}}
                        </td>
                        <td>
                            {{$pengguna->jenis_kelamin}}
                        </td>
                        <td>
                            {{$pengguna->no_telp}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
  </div>
</section>

<div class="modal fade" id="modal-global-filter">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary">Pilih Role Pegawai</h6>
                            </div>
                        </div>
                        <form method="POST" enctype="multipart/form-data" action="{{route('pegawai-store')}}">
                        @csrf   
                        <div class="row mb-2">
                            <div class="col mb-2">
                                <input type="text" class="form-control @error('idpeng') is-invalid @enderror" id="idpeng" name="idpeng" hidden>
                                <input type="text" class="form-control @error('role') is-invalid @enderror disabled" id="nama" name="nama" placeholder="Pilih Role Pegawai">
                                    @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>    
                                    @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Menambah Data?')"><i class="fas fa-save"></i> Simpan</button>
                                <a class="btn btn-danger text-white" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection