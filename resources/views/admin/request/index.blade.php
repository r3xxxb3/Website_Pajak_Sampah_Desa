@extends('layouts.admin-master')

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

    function lihatLokasi(lokasi){
        // console.log(lokasi);
        if(lokasi.file != null){
            $('#prop').attr('src', "{{asset('assets/img/request_p/')}}"+"/"+lokasi.file);
        }else{
            $('#prop').attr('src', "{{asset('assets/img/properti/blank.png')}}");
        }
    }

    function setRequest(id){
            $('#idReq').val(id);
            console.log(id);
    }

    $(document).ready( function () {
        $(".status").on('click', function(){
            var status = $(this).attr('name');
            console.log(status);
            if(status == "Selesai"){
                console.log(status);
                $(".statuses").show();
                $(".pending").removeClass('active');
                $("ul.checkout-bar").removeClass('pen');
                $("ul.checkout-bar li.active").removeClass('pending');
                $(".terkonfirmasi").removeClass('active');
                $("ul.checkout-bar").removeClass('konfirm');
                $("ul.checkout-bar li.active").removeClass('konfirm');
                $(".selesai").addClass('active');
                // $("ul.checkout-bar li.active").toggleClass('selesai');
                $(".desc-stat").html('Request Pengangkutan Sampah telah selesai');
            } else if (status == "Terkonfirmasi") {
                console.log(status);
                $(".statuses").show();
                $(".pending").removeClass('active');
                $("ul.checkout-bar").removeClass('pen');
                $("ul.checkout-bar li.active").removeClass('pending');
                $(".selesai").removeClass('active');
                $("ul.checkout-bar").removeClass('selesai');
                $("ul.checkout-bar li.active").removeClass('selesai');
                $(".terkonfirmasi").addClass('active');
                $("ul.checkout-bar").addClass('konfirm');
                $(".desc-stat").html('Request Pengangkutan Sampah sedang dikerjakan');
            } else if (status == "Pending") {
                $(".statuses").show();
                $(".selesai").removeClass('active');
                $("ul.checkout-bar").removeClass('selesai');
                $(".terkonfirmasi").removeClass('active');
                $("ul.checkout-bar").removeClass('konfirm');
                $(".pending").addClass('active');
                $("ul.checkout-bar").addClass('pen');
                $(".desc-stat").html('Request Pengangkutan Sampah belum diperiksa');
            } else if (status == "Batal") {
                $(".statuses").css("display", "none");
                $(".desc-stat").html('Request Pengangkutan Sampah telah dibatalkan');
            }
        });
    });
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
                        <a class= "btn btn-success text-white mb-2"  href="{{Route('admin-request-create')}}"><i class="fas fa-plus"></i> Tambah Request Pengangkutan</a>
                        <table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="table-primary">
                                    <th>Nama Pelanggan</th>
                                    <th>Alamat</th>
                                    <th>Nominal </th>
                                    <th>Tanggal Request </th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th class="col-2 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody >
                            @foreach ($index as $i)
                                <tr>
                                    <td style="vertical-align: middle; text-align: center;">
                                        {{isset($i->pelanggan) ? $i->pelanggan->kependudukan->nama : ''}}
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        {{isset($i) ? $i->alamat : ''}}
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;" >
                                        {{isset($i->nominal) ? 'Rp'.number_format($i->nominal ?? 0, 2, ',', '.') : 'Belum Ditetapkan !'}}
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;" >
                                        {{isset($i) ? $i->created_at->format('d M Y') : ''}}
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;" >
                                        <a class= "btn btn-success text-white mb-2" data-toggle="modal" data-target="#modal-single" onClick="lihatLokasi({{$i}})"><i class="fas fa-eye"></i> Lihat Lokasi</a>
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        @if($i->status == "Pending")
                                        <span class="badge badge-warning status" name="{{$i->status}}" data-toggle="modal" data-target="#modal-status">{{$i->status}}</span>
                                        @elseif($i->status == "Terkonfirmasi")
                                        <span class="badge badge-info status" name="{{$i->status}}" data-toggle="modal" data-target="#modal-status">{{$i->status}}</span>
                                        @elseif($i->status == "Selesai")
                                        <span class="badge badge-success status" name="{{$i->status}}" data-toggle="modal" data-target="#modal-status">{{$i->status}}</span>
                                        @else
                                        <span class="badge badge-danger status" name="{{$i->status}}" data-toggle="modal" data-target="#modal-status">{{$i->status}}</span>
                                        @endif
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        @if($i->status == "Pending")
                                            <a class="btn btn-info btn-sm text-white col mb-1" onclick="swal({title: 'Konfirmasi Request Pengangkutan ?', icon: 'warning', buttons:{cancel: {text: 'Tidak',value: null,visible: true,closeModal: true,},confirm: {text: 'Ya',value: true,visible: true,closeModal: true}}}).then(function(value){if(value){window.location = window.location = '{{Route('admin-request-confirm', $i->id)}}' }})" ><i class="fas fa-check"></i> Konfirmasi</a><br>
                                            <a href="/admin/request/edit/{{$i->id}}" class="btn btn-warning btn-sm col mb-1"><i class="fas fa-pencil-alt"></i> Ubah</a> <br>
                                            <a style="margin-right:7px" class="btn btn-danger btn-sm col " href="/admin/request/cancel/{{$i->id}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-times"></i> Batal</a><br>
                                        @elseif($i->status == "Terkonfirmasi")
                                            <a class="btn btn-success btn-sm text-white col" onclick="setRequest({{$i->id}})" data-toggle="modal" data-target="#modal-confirm"  ><i class="fas fa-check-double"></i> Verifikasi</a><br>
                                        @elseif($i->status == "Selesai")
                                            <a href="/admin/request/edit/{{$i->id}}" class="btn btn-info btn-sm col"><i class="fas fa-eye"></i> Lihat</a> <br>
                                        @else
                                            <a style="margin-right:7px" class="btn btn-danger btn-sm col" href="/admin/request/delete/{{$i->id}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-trash"></i> Hapus</a>
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

<div class="modal fade" id="modal-confirm">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary">Verifikasi Request Pengangkutan</h6>
                            </div>
                        </div>
                            <form id="konfirmasi" action="{{Route('admin-request-verif')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col mt-4 mb-2">
                                        <input type="text" class="form-control @error('idReq') is-invalid @enderror" id="idReq" name="idReq" hidden>
                                        <label for="nominal" class="font-weight-bold text-dark">Nominal Pengangkutan</label>
                                        <input type="number" class="form-control @error('nominal') is-invalid @enderror disabled" id="nominal" name="nominal" placeholder="Tentukan Nominal !">
                                        @error('nominal')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                    </div>
                                </div>
                            </form>
                            <div class="row mt-3">
                                <div class="col">
                                    <button class="btn btn-success" onclick="swal({title: 'Selesaikan Pengangkutan ?', icon: 'warning', buttons:{cancel: {text: 'Tidak',value: null,visible: true,closeModal: true,},confirm: {text: 'Ya',value: true,visible: true,closeModal: true}}}).then(function(value){if(value){$('#konfirmasi').submit()}})"><i class="fas fa-save"></i> Selesai</button>
                                    <a data-dismiss="modal" class="btn btn-danger text-white"><i class="fas fa-times"></i> Close</a>
                                </div>
                            </div>
                    </div>
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-status">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6>Status</h6>
            </div>
            <div class="modal-body">
                <div id="myDIV2" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col statuses">
                                <!-- <input type="range" min="0" max="4" value="1" step="1">
                                <ul>
                                    <li></li>
                                </ul> -->
                                <div class="checkout-wrap pull-right">
                                    <ul class="checkout-bar">
                                        <li class="pending"><a  data-toggle="tab" >Pending</a>
                                        </li>
                                        <li class="terkonfirmasi"><a  data-toggle="tab" >Terkonfirmasi</a>
                                        </li>
                                        <li class="selesai"><a  data-toggle="tab" >Selesai</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5 bg-light">
                            <div class="col mt-2 text-bold" align="center">
                                <h5 class="desc-stat">Request Pengangkutan Sampah sedang diperiksa Admin</h5>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>

@endsection

@section('style')
<style>
    
    .status input[type="range"] {
      width: 100%;
    }
    
    .checkout-wrap {
        font-family:'PT Sans Caption', sans-serif;
        margin: 30px auto 100px;
        z-index: 0;
    }
    ul.checkout-bar li {
        color: #ccc;
        font-size: 16px;
        font-weight: 600;
        position: relative;
        display: inline-block;
        margin: 50px auto;
        padding: 0;
        text-align: center;
        width: 32.5%;
    }

    ul.checkout-bar li:before {
        -webkit-box-shadow: inset 2px 2px 2px 0px rgba(0, 0, 0, 0.2);
        box-shadow: inset 2px 2px 2px 0px rgba(0, 0, 0, 0.2);
        background: #ddd;
        border: 2px solid #FFF;
        border-radius: 50%;
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        text-align: center;
        text-shadow: 1px 1px rgba(0, 0, 0, 0.2);
        height: 34px;
        left: 40%;
        line-height: 34px;
        position: absolute;
        top: -60px;
        width: 34px;
        z-index: 99999;
    }

    ul.checkout-bar li.active {
        /* color: #A6447A; */
        font-weight: bold;
    }

    ul.checkout-bar li.active:before {
        background: #A6447A;
    }

    ul.checkout-bar li.active.selesai:before {
        background: #27c499;
    }

    ul.checkout-bar li.active.terkonfirmasi:before {
        background: #3abaf4;
    }

    ul.checkout-bar li.active.pending:before {
        background: #ffa426;
    }

    ul.checkout-bar li.visited {
        color: #27c499;
        z-index: 99999;
        background: none;
    }

    ul.checkout-bar li.visited:before {
        background: #27c499;
        z-index: 99999;
    }

    ul.checkout-bar li:nth-child(1):before {
        content:"1";
    }

    ul.checkout-bar li:nth-child(2):before {
        content:"2";
    }

    ul.checkout-bar li:nth-child(3):before {
        content:"3";
    }

    ul.checkout-bar li:nth-child(4):before {
        content:"4";
    }

    ul.checkout-bar li:nth-child(5):before {
        content:"5";
    }

    ul.checkout-bar li:nth-child(6):before {
        content:"6";
    }

    ul.checkout-bar a {
        color: #ccc;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
    }

    ul.checkout-bar li.active.selesai a {
        color: #27c499;
    }

    ul.checkout-bar li.active.terkonfirmasi a {
        color: #3abaf4;
    }

    ul.checkout-bar li.active.pending a {
        color: #ffa426;
    }

    ul.checkout-bar li.visited a {
        color: #27c499;
    }

    /* .checkout-bar li.active:after {
        -webkit-animation: myanimation 3s 0;
        background-size: 35px 35px;
        background-color: #A6447A;
        background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, 0.2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 75%, transparent 75%, transparent);
        background-image: -moz-linear-gradient(-45deg, rgba(255, 255, 255, 0.2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 75%, transparent 75%, transparent);
        -webkit-box-shadow: inset 2px 2px 2px 0px rgba(0, 0, 0, 0.2);
        box-shadow: inset 2px 2px 2px 0px rgba(0, 0, 0, 0.2);
        content:"";
        height: 15px;
        width: 100%;
        left: 50%;
        position: absolute;
        top: -50px;
        z-index: 0;
    } */

    

    ul.checkout-bar {
        /* -webkit-box-shadow: inset 2px 2px 2px 0px rgba(0, 0, 0, 0.2); */
        /* box-shadow: inset 2px 2px 2px 0px rgba(0, 0, 0, 0.2); */
        background-size: 35px 35px;
        /* background-color: #EcEcEc; */
        /* background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, 0.4) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.4) 50%, rgba(255, 255, 255, 0.4) 75%, transparent 75%, transparent);
        background-image: -moz-linear-gradient(-45deg, rgba(255, 255, 255, 0.4) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.4) 50%, rgba(255, 255, 255, 0.4) 75%, transparent 75%, transparent); */
        border-radius: 15px;
        height: 15px;
        margin: 0 65px 0;
        /* padding: 0; */
        padding-left: 5px;
        position: absolute;
        width: 80%;
    }

    ul.checkout-bar:before {
        background-size: 35px 35px;
        background-color: #27c499;
        background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, 0.2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 75%, transparent 75%, transparent);
        background-image: -moz-linear-gradient(-45deg, rgba(255, 255, 255, 0.2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 75%, transparent 75%, transparent);
        -webkit-box-shadow: inset 2px 2px 2px 0px rgba(0, 0, 0, 0.2);
        box-shadow: inset 2px 2px 2px 0px rgba(0, 0, 0, 0.2);
        border-radius: 15px;
        content:" ";
        height: 15px;
        left: 0;
        position: absolute;
        width: 100%;
    }

    ul.checkout-bar.konfirm:before {
        background-size: 35px 35px;
        background-color: #3abaf4;
        background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, 0.2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 75%, transparent 75%, transparent);
        background-image: -moz-linear-gradient(-45deg, rgba(255, 255, 255, 0.2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 75%, transparent 75%, transparent);
        -webkit-box-shadow: inset 2px 2px 2px 0px rgba(0, 0, 0, 0.2);
        box-shadow: inset 2px 2px 2px 0px rgba(0, 0, 0, 0.2);
        border-radius: 15px;
        content:" ";
        height: 15px;
        left: 0;
        position: absolute;
        width: 50%;
    }

    ul.checkout-bar.pen:before {
        background-size: 35px 35px;
        background-color: #ffa426;
        background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, 0.2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 75%, transparent 75%, transparent);
        background-image: -moz-linear-gradient(-45deg, rgba(255, 255, 255, 0.2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 75%, transparent 75%, transparent);
        -webkit-box-shadow: inset 2px 2px 2px 0px rgba(0, 0, 0, 0.2);
        box-shadow: inset 2px 2px 2px 0px rgba(0, 0, 0, 0.2);
        border-radius: 15px;
        content:" ";
        height: 15px;
        left: 0;
        position: absolute;
        width: 0%;
    }
    
    ul.checkout-bar li.visited:after {
        background-size: 35px 35px;
        background-color: #27c499;
        /* background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, 0.2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 75%, transparent 75%, transparent);
        background-image: -moz-linear-gradient(-45deg, rgba(255, 255, 255, 0.2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 75%, transparent 75%, transparent); */
        -webkit-box-shadow: inset 2px 2px 2px 0px rgba(0, 0, 0, 0.2);
        box-shadow: inset 2px 2px 2px 0px rgba(0, 0, 0, 0.2);
        content:"";
        height: 15px;
        left: 50%;
        position: absolute;
        top: -50px;
        width: 100%;
        z-index: 99;
    }

    .selesai {
        left: 17.5%;
    }

    .pending {
        left: -15%;
    }

</style>
@endsection