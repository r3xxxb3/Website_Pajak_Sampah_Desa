@extends('layouts.admin-master')

@section('title')
Index Retribusi
@endsection

@section('scripts')
<script>
    $(document).ready( function () {
        $(document).ready( function () {
        $('#dataTable').DataTable({
            "oLanguage":{
                "sSearch": "Cari:",
                "sZeroRecords": "Data tidak ditemukan",
                "sSearchPlaceholder": "Cari retribusi...",
                "infoEmpty": "Menampilkan 0 data",
                "infoFiltered": "(dari _MAX_ data)",
                "sLengthMenu": "Tampilkan _MENU_ data",
            },
            "language":{
                "paginate": {
                        "previous": 'Sebelumnya',
                        "next": 'Berikutnya'
                    },
                "info": "Menampilkan _START_ s/d _END_ dari _MAX_ data",
            },
        });
    } );
    } );
</script>
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
                    <table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="table-primary">
                                <th class="col-2 text-center">Action</th>
                                <th>Nama Pelanggan</th>
                                <th>Nama Properti</th>
                                <th>Jenis Properti</th>
                                <th>Nominal </th>
                                <th>Tanggal Retribusi </th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody >
                        @foreach ($index as $retri)
                            <tr>
                                <td align="center">
                                    <!-- <input type="checkbox" name="id[]" id="id" value="{{$retri->id}}"> -->
                                    <a href="#" data-toggle="modal" data-target="#modal-{{$retri->id}}" class="btn btn-info btn-sm col"><i class="fas fa-eye"> Lihat</i></a>
                                </td>
                                <td>
                                    {{isset($retri->pelanggan->kependudukan) ? $retri->pelanggan->kependudukan->nama : ''}}
                                </td>
                                <td>
                                    {{isset($retri->properti) ? $retri->properti->nama_properti : ''}}
                                </td>
                                <td>
                                    {{isset($retri->properti->jasa)? $retri->properti->jasa->jenis_jasa : ''}}
                                </td>
                                <td>
                                    {{"Rp. ".number_format($retri->nominal ?? 0,0,',','.')}}
                                </td>
                                <td>
                                    {{$retri->created_at->format('d M Y')}}
                                </td>
                                <td >
                                @if($retri->status == "pending")
                                    <span class="badge badge-warning">{{$retri->status}}</span>
                                @elseif($retri->status == "lunas")
                                    <span class="badge badge-success">{{$retri->status}}</span>
                                @endif
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
                                <h6 class="m-0 font-weight-bold text-primary">Detail Retribusi {{$retri->created_at->format('M Y')}}
                                    <span class="badge badge-warning">{{$retri->status}}</span>
                                </h6>
                            </div>
                        </div>
                        <form method="POST" enctype="multipart/form-data" action="{{route('admin-retribusi-update', $retri->id)}}">
                        @csrf
                            <div class="row">
                                <div class="col mb-2 d-flex justify-content-center">
                                    <!-- Properti -->
                                    @if(!isset($retri->properti->file))
                                        <img src="{{asset('assets/img/properti/blank.png')}}"  height="300px" style="object-fit:cover" class="mb-3" id="prop">
                                    @else
                                        <img src="{{asset('assets/img/properti/'.$retri->properti->file)}}"  height="300px" style="object-fit:cover" class="mb-3" id="prop">
                                    @endif
                                    @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>    
                                    @enderror
                                </div>

                            </div>   
                            <div class="row">
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

