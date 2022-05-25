@extends('layouts.admin-master')

@section('title')
Index Retribusi
@endsection

@section('scripts')

@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Retribusi</h1>
    </div>

  <div class="section-body">
       <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- DataTales Example -->
        <!-- Copy drisini -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List Retribusi</h6>
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
            <form action="/admin/retribusi/verif-many" method="post">
                @csrf
                <a class= "btn btn-warning text-white mb-2"  ><i class="fas fa-history"></i> Lihat Histori Retribusi</a>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="col-2 text-center">Action</th>
                            <th>Nama Pelanggan</th>
                            <th>Nama Properti</th>
                            <th>Jenis Properti</th>
                            <th>Nominal </th>
                            <th>Tanggal Retribusi </th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($index as $retri)
                        <tr>
                            <td align="center">
                                <!-- <input type="checkbox" name="id[]" id="id" value="{{$retri->id}}"> -->
                                <a href="#" data-toggle="modal" data-target="#modal-{{$retri->id}}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                            </td>
                            <td>
                                {{isset($retri->pengguna) ? $retri->pengguna->nama_pengguna : ''}}
                            </td>
                            <td>
                                {{isset($retri->properti) ? $retri->properti->nama_properti : ''}}
                            </td>
                            <td>
                                {{isset($retri->properti->jasa)? $retri->properti->jasa->jenis_jasa : ''}}
                            </td>
                            <td>
                                {{$retri->nominal}}
                            </td>
                            <td>
                                {{$retri->created_at->format('d M Y')}}
                            </td>
                            <td>
                                {{$retri->status}}
                            </td>
    
                        </tr>
                    @endforeach
                    </tbody>
                    </table>
            </form>
            </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
  </div>
</section>



@foreach($index as $retri)
<div class="modal fade" id="modal-{{$retri->id}}">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary">Detail Retribusi</h6>
                            </div>
                        </div>
                        <form method="POST" enctype="multipart/form-data" action="{{route('admin-retribusi-update', $retri->id)}}">
                        @csrf   
                            <div class="row">
                                <div class="col mb-2">
                                    <!-- Properti -->
                                    <label for="file" class="font-weight-bold text-dark">Properti</label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" placeholder="Masukan File Bukti Bayar" value="{{isset($retri->properti) ? $retri->properti->file : old('file')}}" disabled>
                                        @error('file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>

                                <div class='col mb-2'>
                                    <label for="nama" class="font-weight-bold text-dark">Nama Properti</label>
                                    <input type="text" class="form-control @error('jenis') is-invalid @enderror" id="nama" name="nama" placeholder="" value="{{isset($retri->properti) ? $retri->properti->nama_properti : old('nama')}}" disabled>
                                        @error('nama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                </div>
                                <div class="col">
                                    <label for="alamat" class="font-weight-bold text-dark">Alamat Properti</label>
                                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Masukan Alamat Properti" value="{{isset($retri->properti) ? $retri->properti->alamat : old('alamat')}}" disabled>
                                        @error('alamat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <!-- nominal -->
                                    <label for="nominal" class="font-weight-bold text-dark">Nominal</label>
                                    <input type="text" class="form-control @error('nominal') is-invalid @enderror" id="nominal" name="nominal" placeholder="Masukan Nominal Retribusi" value="{{isset($retri->nominal) ? $retri->nominal : old('keterangan')}}" >
                                        @error('nominal')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Mengubah Data?')"><i class="fas fa-check"></i> Lunas</button>
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

