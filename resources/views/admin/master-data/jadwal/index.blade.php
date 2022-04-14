@extends('layouts.admin-master')

@section('title')
Index Jadwal
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
                    <tr>
                        <th >Action</th>
                        <th>Hari</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($index as $jadwal)
                    <tr>
                        <td align="center">
                            <a href="/admin/masterdata/jadwal/edit/{{$jadwal->id_jadwal}}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i></a>
                            <a style="margin-right:7px" class="btn btn-danger btn-sm" href="/admin/masterdata/jadwal/delete/{{$jadwal->id_jadwal}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-trash"></i></a>
                        </td>
                        <td>
                            {{$jadwal->hari}}
                        </td>
                        <td>
                            {{$jadwal->mulai}}
                        </td>
                        <td>
                            {{$jadwal->selesai}}
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