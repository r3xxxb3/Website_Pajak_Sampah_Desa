@extends('layouts.admin-master')

@section('title')
Pembayaran
@endsection

@section('scripts')
<script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            "oLanguage":{
                "sSearch": "Cari:",
                "sZeroRecords": "Data tidak ditemukan",
                "sSearchPlaceholder": "Cari pembayaran...",
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

<script>
    function lihatPembayaran(pembayaran) {
        // console.log(properti);
        if(pembayaran.bukti_bayar != null){
            $('#bayar').attr('src', "{{asset('assets/img/bukti_bayar/')}}"+"/"+pembayaran.bukti_bayar);
        }else{
            $('#bayar').attr('hidden', true);
            $('#ket').attr('hidden', false);
        }
    }
</script>

<script>
    var table = $('#dataTableModal').DataTable({
            "oLanguage":{
                "sSearch": "Cari:",
                "sZeroRecords": "Data tidak ditemukan",
                "sSearchPlaceholder": "Cari detail",
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
    $(document).ready( function(){
        $('.detail').on('click', function(e){
            e.preventDefault();
            var id = event.target.id;
            console.log(id);
    
            var idNum = id.split("-");
            console.log(idNum[1]);
            
            var pembayaran = idNum[1];$.ajax({
                    method : 'POST',
                    url : '{{route("admin-pembayaran-search")}}',
                    data : {
                    "_token" : "{{ csrf_token() }}",
                    pembayaran : pembayaran,
                    },
                    beforeSend : function() {
                                
                    },
                    success : (res) => {
                        console.log(res);
                        table.clear();
                        jQuery.each(res, function(i, val){
                            var jenis = val.model_type.split('\\');
                            console.log(jenis);
                            if(val.model.status == "lunas"){
                                table.row.add([
                                    jenis[1],
                                    val.properti+" ("+val.pelanggan+")",
                                    val.model.nominal.toLocaleString('id-ID',{style:'currency', currency:'IDR',}),
                                    val.tanggal,
                                    '<span class="badge badge-success text-capitalize">'+val.model.status+'</span>'
                                ]);
                            }else{
                                table.row.add([
                                    jenis[1],
                                    val.properti+" ("+val.pelanggan+")",
                                    val.model.nominal.toLocaleString('id-ID',{style:'currency', currency:'IDR',}),
                                    val.tanggal,
                                    '<span class="badge badge-warning text-capitalize">'+val.model.status+'</span>'
                                ]);
                            }
                        });
                        table.draw();
                    }
                });
        });
    });

</script>
@endsection

@section('style')
<style>
    @media (min-width: 768px) {
            .modal-xl {
                width: 90%;
            max-width:1200px;
            }
        }
</style>
@endsection

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Manajemen Data Pembayaran</h1>
    </div>

  <div class="section-body">
       <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- DataTales Example -->
        <!-- Copy drisini -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List Pembayaran</h6>
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
                    <a class= "btn btn-success text-white mb-2" href="{{route('admin-pembayaran-create')}}"><i class="fas fa-plus"></i> Tambah Data Pembayaran </a>
                    <table class="table table-hover table-bordered  " id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="table-primary">
                                <th class="col-1">Action</th>
                                <th>Pelanggan</th>
                                <th>Jenis</th>
                                <!-- <th>Properti</th> -->
                                <th>Bukti Bayar</th>
                                <th>Metode Pembayaran</th>
                                <th class="col col-sm-2">Nominal</th>
                                <th>Tanggal Pembayaran</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($index as $pembayaran)
                            <tr>
                                <td style="vertical-align: middle; text-align: center;" class="col-2">
                                @if($pembayaran->status == "pending")
                                    <a href="/admin/pembayaran/verif/{{$pembayaran->id_pembayaran}}" class="btn btn-success btn-sm col"><i class="fas fa-check"> Verifikasi</i></a><br>
                                    <a href="/admin/pembayaran/edit/{{$pembayaran->id_pembayaran}}" class="btn btn-info btn-sm col"><i class="fas fa-pencil-alt"> Ubah</i></a><br>
                                    <a style="margin-right:7px" class="btn btn-danger btn-sm col" href="/admin/pembayaran/delete/{{$pembayaran->id_pembayaran}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-trash"> Hapus</i></a>
                                @elseif($pembayaran->status == "lunas")
                                    <a class="btn btn-info text-white btn-sm col detail" id="detail-{{$pembayaran->id_pembayaran}}" data-toggle="modal" data-target="#modal-detail" ><i class="fas fa-eye"></i> Lihat Detail</a>
                                @endif
                                </td>
                                <td>
                                    {{$pembayaran->pelanggan->kependudukan->nama}}
                                </td>
                                <td class="col-2">
                                    @if(count($pembayaran->detail) != 0)
                                        <?php $v = 'null' ?>
                                        @foreach($pembayaran->detail as $m)
                                            @if($m->model_type == "App\Retribusi")
                                                @if($v == "null")
                                                    {{$v = "Retribusi"}}
                                                @elseif($m->model_type == "App\Pengangkutan")
                                                    {{$v}}." & Request Pengangkutan"
                                                @else
                                                    @continue
                                                @endif
                                            @elseif($m->model_type == "App\Pengangkutan")
                                                @if($v == "null")
                                                    $v = "Request Pengangkutan"
                                                @elseif($m->model_type == "App\Retribusi")
                                                    {{$v}}." & Retribusi"
                                                @else
                                                    @continue
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <!-- <td class="col-2"> -->
                                    <!-- @if(isset($pembayaran->retribusi))
                                        @if(count($pembayaran->retribusi) > 0)
                                            @foreach($pembayaran->retribusi as $retri)
                                                - {{$retri->properti->nama_properti}} <br>
                                            @endforeach
                                        @else
                                            - {{$pembayaran->retribusi->properti->nama_properti}} <br>
                                        @endif
                                    @else
                                        Error pada Hubungan Retribusi dan Pembayaran !
                                    @endif -->
                                    <!-- @if(count($pembayaran->detail->map->model) != 0)
                                        @foreach($pembayaran->detail->map->model as $m)
                                            - {{isset($m->properti) ? $m->properti->nama_properti : ''}} <br>
                                        @endforeach
                                    @endif
                                </td> -->
                                <td>
                                    @if(isset($pembayaran->bukti_bayar))
                                        <a class= "btn btn-success btn-sm text-white mb-2 " data-toggle="modal" data-target="#modal-single" onClick="lihatPembayaran({{$pembayaran}})"><i class="fas fa-eye"></i> Lihat bukti bayar</a>
                                        <!-- <img src="{{asset('assets/img/bukti_bayar/'.$pembayaran->bukti_bayar)}}"  height="100px" style="object-fit:cover" class="mb-3" id="prop"> -->
                                    @else
                                        Tidak Terdapat Foto Bukti Bayar
                                    @endif
                                </td>
                                <td class="col col-sm-1">
                                    {{$pembayaran->media}}
                                </td>
                                <td>
                                    {{"Rp. ".number_format($pembayaran->nominal ?? 0,0,',','.')}}
                                </td>
                                <td>
                                    {{$pembayaran->created_at->format('d M Y')}}
                                </td>
                                <td>
                                    @if($pembayaran->status == "pending")
                                        <span class="badge badge-warning">{{$pembayaran->status}}</span>
                                    @elseif($pembayaran->status == "lunas")
                                        <span class="badge badge-success">{{$pembayaran->status}}</span>
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
                                <img src="{{asset('assets/img/properti/blank.png')}}"  height="300px" style="object-fit:cover" class="mb-3 rounded mx-auto d-block" id="bayar">
                                <h1 id="ket" hidden>Tidak terdapat bukti pembayaran</h1>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-detail">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary">Detail Pembayaran</h6>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered  " id="dataTableModal" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="table-primary">
                                        <th>Jenis</th>
                                        <th>Nama</th>
                                        <th class="col col-sm-2">Nominal</th>
                                        <th>Tanggal</th>
                                        <th >Status</th>
                                    </tr>
                                </thead>
                                <tbody id="detail_pembayaran" name="detail_pembayaran">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>


@endsection