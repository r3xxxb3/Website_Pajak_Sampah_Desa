@extends('layouts.user-master')

@section('title')
List Request Pengangkutan
@endsection

@section('scripts')

<script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            "oLanguage":{
                "sSearch": "Cari:",
                "sZeroRecords": "Data tidak ditemukan",
                "sSearchPlaceholder": "Cari request...",
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

    function lihatLokasi(lokasi) {
        // console.log(lokasi);
        if(lokasi.file != null){
            $('#prop').attr('src', "{{asset('assets/img/request_p/')}}"+"/"+lokasi.file);
        }else{
            $('#prop').attr('src', "{{asset('assets/img/properti/blank.png')}}");
        }
    }
</script>

@endsection

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Manajemen Request Pengangkutan</h1>
    </div>

  <div class="section-body">
       <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- DataTales Example -->
        <!-- Copy drisini -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List Request Pengangkutan</h6>
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
                    <form action="" method="post">
                        @csrf
                        <a class= "btn btn-success text-white mb-2" href="{{route('request-create')}}" ><i class="fas fa-plus"></i> Tambah Request Pengangkutan</a>
                        <table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="table-primary">
                                    <th class="col-2 text-center">Action</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Alamat</th>
                                    <th>Nominal </th>
                                    <th>Tanggal Request </th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody >
                                @foreach ($index as $i)
                                <tr>
                                    <td align="center">
                                        <a href="/user/request/edit/{{$i->id}}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                        @if($i->status == "Batal")
                                            <a style="margin-right:7px" class="btn btn-danger btn-sm" href="/user/request/delete/{{$i->id}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-trash"></i></a>
                                        @elseif($i->status != "Selesai" && $i->status != "Terkonfirmasi")
                                            <a style="margin-right:7px" class="btn btn-danger btn-sm" href="/user/request/cancel/{{$i->id}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-times"></i></a>
                                        @endif
                                    </td>
                                    <td>
                                        {{isset($i->pelanggan) ? $i->pelanggan->kependudukan->nama : ''}}
                                    </td>
                                    <td>
                                        {{isset($i) ? $i->alamat : ''}}
                                    </td>
                                    <td>
                                        {{isset($i->nominal) ? $i->nominal : 'Belum Ditetapkan !'}}
                                    </td>
                                    <td>
                                        {{isset($i) ? $i->created_at : ''}}
                                    </td>
                                    <td>
                                        <a class= "btn btn-success text-white mb-2" data-toggle="modal" data-target="#modal-single" onClick="lihatLokasi({{$i}})"><i class="fas fa-eye"></i> Lihat Lokasi</a>
                                    </td>
                                    <td>
                                        @if($i->status == "Pending")
                                            <span class="badge badge-warning">{{$i->status}}</span>
                                        @elseif($i->status == "Terkonfirmasi")
                                            <span class="badge badge-info">{{$i->status}}</span>
                                        @elseif($i->status == "Selesai")
                                            <span class="badge badge-success">{{$i->status}}</span>
                                        @else
                                            <span class="badge badge-danger">{{$i->status}}</span>
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