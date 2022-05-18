@extends('layouts.user-master')

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
            <a class= "btn btn-success text-white mb-2" data-toggle="" data-target=""><i class="fas fa-cart-plus"></i> Bayar Tagihan Retribusi</a>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="col-2">Action</th>
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
                            <a href="#" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i></a>
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
            </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
  </div>
</section>


@endsection

