@extends('layouts.user-master')

@section('title')
Penilaian Layanan
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

    table.dataTable tr td {
        vertical-align: middle;
        text-align: center;
    }

    @media (min-width: 768px) {
                .modal-xl {
                    width: 90%;
                max-width:1200px;
                }
            }
</style>
@endsection

@section('scripts')
<script>
    var table = $('#dataTable').DataTable({
        "oLanguage":{
            "sSearch": "Cari:",
            "sZeroRecords": "Data tidak ditemukan",
            "sSearchPlaceholder": "Cari item",
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
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]]
    });

    function update(nilai, id){
        console.log(nilai.kepuasan[0].rate);
        $('.urate').removeClass('text-warning');
        for(i = 1 ; i <= nilai.kepuasan[0].rate; i++) {
            $('#'+i+'.urate').addClass('text-warning');
            console.log($('#'+i+'.urate').attr('id'));
        }
        $('#ucomment').val(nilai.kepuasan[0].comment);
        $("#urate").val(nilai.kepuasan[0].rate);
        $("#uid").val(id);
    }

    $(document).ready( function(){
        $(".nilai").on('click', function(e){
            $(".srate").removeClass('text-warning');
            $("#id").val($(this).attr('id'));
        });

        $(".srate").on('click', function(e){
            $(".srate").removeClass('text-warning');
            console.log($(this).attr('id'));
            var rate = $(this).attr('id');
            for(var i = 0; i <= rate; i++) {
                $("#"+i).addClass('text-warning');
            }
            $("#rate").val(rate);
        })

        $(".urate").on('click', function(e){
            $(".urate").removeClass('text-warning');
            console.log($(this).attr('id'));
            var rate = $(this).attr('id');
            for(var i = 0; i <= rate; i++) {
                $("#"+i+".urate").addClass('text-warning');
            }
            $("#urate").val(rate);
        })
    });
</script>
@endsection

@section('content')
    
<section class="section">
    <div class="section-header">
        <h1>Penilaian Layanan</h1>
    </div>

    <div class="section-body">
        <div class="container-fluid">
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-times"></i> 
                {{ Session::get('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <h5 class="font-weight-bold text-primary"><i class="fas fa-list"></i> List Penilaian</h5>
                </div>
                <div class="form-group card-body">
                    <div id="myDIV" style="display: block">
                        <div class="row">
                            <div class="col">
                                <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nama Item</th>
                                            <th>Jenis </th>
                                            <th>Nominal </th>
                                            <th>Tanggal Buat</th>
                                            <th>Status</th>
                                            <th class="col-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $m)
                                        <tr>
                                            <td>{{isset($m->properti) ? $m->properti->nama_properti : "Pengangkutan (".$m->alamat.")"}}</td>
                                            <td>{{isset($m->properti) ? $m->properti->jasa->jenis_jasa : "Request Pengangkutan Sampah"}}</td>
                                            <td>{{"Rp ".number_format($m->nominal ?? 0, 2, ',','.')}}</td>
                                            <td>{{$m->created_at->format('d M Y')}}</td>
                                            <td>
                                                @if($m->status == "pending")
                                                    <span class="badge badge-warning">{{$m->status}}</span>
                                                @elseif($m->status == "lunas")
                                                    <span class="badge badge-success">{{$m->status}}</span>
                                                @elseif($m->status == "Selesai")
                                                <span class="badge badge-success">lunas</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($m->properti))
                                                    @if(!$m->kepuasan->isEmpty())
                                                        <a class="btn btn-warning btn-sm text-white edit" id="{{$m->id.'-'}}retribusi" data-toggle="modal" data-target="#modal-ubah" onClick="update({{$m}},{{$m->id}}+'-retribusi')"><i class="fa fa-pencil-alt"></i> Ubah Penilaian</a>
                                                    @else
                                                        <a class="btn btn-info btn-sm text-white nilai" id="{{$m->id.'-'}}retribusi" data-toggle="modal" data-target="#modal-rate"><i class="fas fa-star"></i> Beri Penilaian</a>
                                                    @endif
                                                @else
                                                    @if(!$m->kepuasan->isEmpty())
                                                    <a class="btn btn-warning btn-sm text-white edit" id="{{$m->id.'-'}}pengangkutan" data-toggle="modal" data-target="#modal-ubah" onClick="update({{$m}},{{$m->id}}+'-pengangkutan')"><i class="fa fa-pencil-alt"></i> Ubah Penilaian</a>
                                                    @else
                                                        <a class="btn btn-info btn-sm text-white nilai" id="{{$m->id.'-'}}pengangkutan" data-toggle="modal" data-target="#modal-rate"><i class="fas fa-star"></i> Beri Penilaian</a>
                                                    @endif
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
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-rate">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                            <h5 class="text-primary">Beri Penilaian</h5>
                        </div>
                        <hr>
                        <div class="card-body">
                            <form action="{{route('custom-penilaian-store')}}" enctype="multipart/form-data" method="post">
                                @csrf
                                <div id="myDIV" style="display: block">
                                    <div class="row justify-content-between mb-3">
                                        <input type="text" name="id" id="id" hidden>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col" style="vertical-align: middle; text-align: center;">
                                        <span class="fa fa-star fa-5x ml-2 srate" id="1"></span>
                                        <span class="fa fa-star fa-5x ml-5 srate" id="2"></span>
                                        <span class="fa fa-star fa-5x ml-5 srate" id="3"></span>
                                        <span class="fa fa-star fa-5x ml-5 srate" id="4"></span>
                                        <span class="fa fa-star fa-5x ml-5 srate" id="5"></span>
                                        <input type="text" name="rate" id="rate" hidden>
                                    </div>
                                </div>
                                <div class="row" style="height:150px;">
                                    <div class="col" >
                                        <label for="comment" class="font-weight-bold text-primary">Komentar</label>
                                        <textarea class="form-control" name="comment" id="comment" style="height:100%; background-color:light;" placeholder="Masukan komentar anda !">
        
                                        </textarea>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col" style="vertical-align: middle; text-align: right;">
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Penilaian Sudah Benar?')"><i class="fas fa-save"></i> Simpan</button>
                                        <a class="btn btn-danger text-white" data-toggle="modal" data-target="#modal-rate"><i class="fas fa-times"></i> Batal</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-ubah">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                            <h5 class="text-primary">Ubah Penilaian</h5>
                        </div>
                        <hr>
                        <div class="card-body">
                            <form action="{{route('custom-penilaian-update')}}" enctype="multipart/form-data" method="post">
                                @csrf
                                <div id="myDIV" style="display: block">
                                    <div class="row justify-content-between mb-3">
                                        <input type="text" name="uid" id="uid" hidden>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col" style="vertical-align: middle; text-align: center;">
                                        <span class="fa fa-star fa-5x ml-2 urate" id="1"></span>
                                        <span class="fa fa-star fa-5x ml-5 urate" id="2"></span>
                                        <span class="fa fa-star fa-5x ml-5 urate" id="3"></span>
                                        <span class="fa fa-star fa-5x ml-5 urate" id="4"></span>
                                        <span class="fa fa-star fa-5x ml-5 urate" id="5"></span>
                                        <input type="text" name="urate" id="urate" hidden>
                                    </div>
                                </div>
                                <div class="row" style="height:150px;">
                                    <div class="col" >
                                        <label for="comment" class="font-weight-bold text-primary">Komentar</label>
                                        <textarea class="form-control" name="ucomment" id="ucomment" style="height:100%; background-color:light;" placeholder="Masukan komentar anda !">
        
                                        </textarea>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col" style="vertical-align: middle; text-align: right;">
                                        <button type="submit" class="btn btn-warning" onclick="return confirm('Apakah Penilaian Sudah Benar?')"><i class="fas fa-save"></i> Ubah</button>
                                        <a class="btn btn-danger text-white" data-toggle="modal" data-target="#modal-ubah"><i class="fas fa-times"></i> Batal</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>

@endsection