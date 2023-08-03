@extends('layouts.admin-master')

@section('title')
Index Jadwal
@endsection

@section('scripts')
<script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            "oLanguage":{
                "sSearch": "Cari:",
                "sZeroRecords": "Data tidak ditemukan",
                "sSearchPlaceholder": "Cari jadwal...",
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
    
        var pegawai = $('#dataTablePegawai').DataTable({
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
        
        $('table').on('click', '.detail', function(e){
           var elmntId = $(this).attr('id');
           var jId = elmntId.split('-')[1];
           
           $.ajax({
               
           });
        });
    });
</script>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Data Jadwal Pengangkutan</h1>
    </div>

  <div class="section-body">
       <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- DataTales Example -->
        <!-- Copy drisini -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List Jadwal Pengangkutan</h6>
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
            <a class= "btn btn-success text-white mb-2" href="{{route('masterdata-jadwal-create')}}"><i class="fas fa-plus"></i> Tambah Jadwal Pengangkutan</a>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr class="table-primary">
                        <th>Hari</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Jenis Sampah</th>
                        <th>List Pegawai</th>
                        <th >Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($index as $jadwal)
                    <tr>
                        <td style="vertical-align: middle;">
                            {{$jadwal->hari}}
                        </td>
                        <td style="vertical-align: middle;">
                            {{$jadwal->mulai}}
                        </td>
                        <td style="vertical-align: middle;">
                            {{$jadwal->selesai}}
                        </td>
                        <td style="vertical-align: middle;">
                        @if($jadwal->jenis != null)
                            {{$jadwal->jenis->jenis_sampah}}
                        @else
                            Semua Jenis Sampah
                        @endif
                        </td>
                        <td style="vertical-align: middle;">
                            <a class="btn btn-info btn-sm col mb-1 text-white detail" data-toggle="modal" data-target="#modal-pegawai" id="Jadwal-{{$jadwal->id_jadwal}}" ><i class="fas fa-eye"></i> Lihat Jadwal Pegawai</a><br>
                        </td>
                        <td align="center" class="col-2">
                            <a href="/admin/masterdata/jadwal/edit/{{$jadwal->id_jadwal}}" class="btn btn-info btn-sm col mb-1"><i class="fas fa-pencil-alt"></i> Ubah</a><br>
                            <a style="margin-right:7px" class="btn btn-danger btn-sm col" href="/admin/masterdata/jadwal/delete/{{$jadwal->id_jadwal}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-trash"></i> Hapus</a>
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

<div class="modal fade" id="modal-pegawai">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <table class="table table-bordered" id="dataTablePegawai" width="100%" cellspacing="0">
                <thead>
                    <tr class="table-primary">
                        <th>Pegawai</th>
                        <th>Telepon</th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection