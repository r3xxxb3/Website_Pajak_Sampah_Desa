@extends('layouts.admin-master')

@section('title')
Index Data Pegawai
@endsection

@section('scripts')
<script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            "oLanguage":{
                "sSearch": "Cari:",
                "sZeroRecords": "Data tidak ditemukan",
                "sSearchPlaceholder": "Cari pegawai...",
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
</script>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Data Pegawai</h1>
    </div>

  <div class="section-body">
       <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- DataTales Example -->
        <!-- Copy drisini -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List Pegawai</h6>
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
            <a class= "btn btn-success text-white mb-2" href="{{route('pegawai-create')}}"><i class="fas fa-plus"></i> Tambah Pegawai</a>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr class="table-primary">
                        <th class="col-2">Action</th>
                        <!-- <th>No Kartu Keluarga</th> -->
                        <th>Role</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Kelamin</th>
                        <th>No Telp</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($index as $pegawai)
                    <tr>
                        <td align="center">
                            <a href="/admin/pegawai/edit/{{$pegawai->id_pegawai}}" class="btn btn-info btn-sm col"><i class="fas fa-eye"> Lihat</i></a> <br>
                            <a style="margin-right:7px" class="btn btn-danger btn-sm col" href="/admin/pegawai/delete/{{$pegawai->id_pegawai}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-trash"> Hapus</i></a>
                        </td>
                        <td>
                            {{isset($pegawai->role) ? $pegawai->role->role : ''}}
                        </td>
                        <td>
                            {{isset($pegawai->kependudukan) ? $pegawai->kependudukan->nama : ''}}
                        </td>
                        <td>
                        {{isset($pegawai->kependudukan) ? $pegawai->kependudukan->jenis_kelamin : ''}}
                        </td>
                        <td>
                        {{isset($pegawai->kependudukan) ? $pegawai->kependudukan->telepon : ''}}
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
@endsection