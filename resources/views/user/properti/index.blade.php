@extends('layouts.user-master')

@section('title')
Manajemen Properti User
@endsection

@section('scripts')

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
                            <a href="/user/properti/cancel/{{$properti->id}}" class="btn btn-warning btn-sm" ><i class="fas fa-ban"></i></a>
                            <a href="/user/properti/edit/{{$properti->id}}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i></a>
                            <a style="margin-right:7px" class="btn btn-danger btn-sm" href="/user/properti/delete/{{$properti->id}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-trash"></i></a>
                        </td>
                        <td>
                            {{$properti->nama_properti}}
                        </td>
                        <td>
                            {{isset($properti->jasa)? $properti->jasa->jenis_jasa : ''}}
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
    <!-- /.container-fluid -->
  </div>
</section>
@endsection