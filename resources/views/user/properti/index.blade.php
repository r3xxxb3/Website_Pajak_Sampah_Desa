@extends('layouts.user-master')

@section('title')
Manajemen Properti User
@endsection

@section('style')
<style>
    div.dataTables_wrapper div.dataTables_filter label {
      width: 100%; 
    }

    div.dataTables_wrapper div.dataTables_filter input {
      width: 100%; 
    }

    
    div.dataTables_wrapper div.dataTables_length select {
      width: 20%; 
    }

    div.dataTables_wrapper div.dataTables_length label {
      width: 100%; 
    }
    
</style>
@endsection

@section('scripts')
<script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            "oLanguage":{
                "sSearch": "",
                "sZeroRecords": "Data tidak ditemukan",
                "sSearchPlaceholder": "Cari properti...",
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
    });
</script>

<script>
function lihatProperti(properti) {
    // console.log(properti);
    if(properti.file != null){
        $('#prop').attr('src', "{{asset('assets/img/properti/')}}"+"/"+properti.file);
    }else{
        $('#prop').attr('src', "{{asset('assets/img/properti/blank.png')}}");
    }
}
</script>

@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Properti</h1>
    </div>

  <div class="section-body">
       <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- DataTales Example -->
        <!-- Copy drisini -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List Properti</h6>
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
                <a class= "btn btn-success text-white mb-2" href="{{route('properti-create')}}"><i class="fas fa-plus"></i> Tambah Properti</a>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="table-primary">
                            <th>Nama Properti</th>
                            <th>Jenis Properti</th>
                            <th>Desa Adat</th>
                            <th>Alamat </th>
                            <th>Foto </th>
                            <th>Status</th>
                            <th class="col-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($index as $properti)
                        <tr>
                            <td style="vertical-align: middle; text-align: center;">
                                {{$properti->nama_properti}}
                            </td>
                            <td style="vertical-align: middle; text-align: center;">
                                {{isset($properti->jasa)? $properti->jasa->jenis_jasa : ''}}
                            </td>
                            <td style="vertical-align: middle; text-align: center;">
                                {{isset($properti->desaAdat) ? $properti->desaAdat->desadat_nama : 'Desa Adat tidak ditemukan !'}}
                            </td>
                            <td style="vertical-align: middle; text-align: center;">
                                {{$properti->alamat}}
                            </td>
                            <td style="vertical-align: middle; text-align: center;">
                                <a class= "btn btn-success text-white mb-2" data-toggle="modal" data-target="#modal-single" onClick="lihatProperti({{$properti}})"><i class="fas fa-eye"></i> Lihat Properti</a>
                                <!-- {{$properti->file}} -->
                            </td>
                            <td style="vertical-align: middle; text-align: center;">
                                @if($properti->status == 'terverifikasi')
                                <span class="badge badge-success">{{$properti->status}}</span>
                                @elseif($properti->status == 'pending')
                                <span class="badge badge-warning">{{$properti->status}}</span>
                                @else
                                <span class="badge badge-danger">{{$properti->status}}</span>
                                @endif
                            </td>
                            <td align="center">
                                @if($properti->status == "terverifikasi")
                                    <a href="/user/properti/edit/{{$properti->id}}" class="btn btn-warning btn-sm col mb-1"><i class="fas fa-pencil-alt"></i> Ubah</a><br>
                                    <a href="/user/properti/cancel/{{$properti->id}}" class="btn btn-danger btn-sm  col" ><i class="fas fa-ban"></i> Batal</a><br>
                                @elseif($properti->status == "pending")
                                    <a href="/user/properti/edit/{{$properti->id}}" class="btn btn-warning mb-1 btn-sm col"><i class="fas fa-pencil-alt"></i> Ubah</a><br>
                                    <a style="margin-right:7px" class="btn btn-danger btn-sm col" href="/user/properti/delete/{{$properti->id}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-trash"></i> Hapus</a>
                                @elseif($properti->status == "batal")
                                    <a style="margin-right:7px" class="btn btn-danger btn-sm col" href="/user/properti/delete/{{$properti->id}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-trash"></i> Hapus</a>
                                @endif
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

<div class="modal fade" id="modal-single">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <img src="{{asset('assets/img/properti/blank.png')}}"  height="300px" style="object-fit:cover" class="mb-3 rounded mx-auto d-block" id="prop">
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection