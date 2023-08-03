@extends('layouts.user-master')

@section('title')
List Request Pengangkutan
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

@section('scripts')

<script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            "oLanguage":{
                "sSearch": "",
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

        var table = $('#dataTableKeranjang').DataTable({
            "oLanguage":{
                "sSearch": "",
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

        $("table").on('click', '.keranjang' , function(e){
            var id = $(this).attr("id");
            console.log(id);
            e.preventDefault();
            $.ajax({
                method : 'POST',
                url : '{{route("request-keranjang")}}',
                data : {
                "_token" : "{{ csrf_token() }}",
                id: id,
                },
                beforeSend : function() {
                            
                },
                success : (res) => {
                    console.log(res);
                    if (res.stat == "success" || res.desc == "Request sudah terdaftar dalam keranjang !") {
                        $('#'+id).hide();
                        $('#'+id).attr('disabled');
                    }
                }
            });
        });
        
        $('table').on('click', '.batal' ,function(e){
            if(confirm("Apakah Anda Yakin Ingin menghapus Request Pengangkutan dari keranjang ?")== true){
                var id = $(this).attr("id");
                var pkO = $("#id").val().split(",");
                console.log(pkO);
                if(pkO.length > 1 ){
                    $.each(pkO, function(i,val){
                        if(id == val){
                            pkO.splice(i, 1);
                            console.log(pkO +" "+i);
                        }
                    });
                }else{
                    $.each(pkO, function(i,val){
                        if(id == val){
                            pkO.splice(i, 1);
                            console.log(pkO +" "+i);
                        }
                    });
                }
                $("#id").val(pkO);
                e.preventDefault();
                $.ajax({
                    method : 'POST',
                    url : '{{route("request-keranjang-hapus")}}',
                    data : {
                    "_token" : "{{ csrf_token() }}",
                    id: id,
                    },
                    beforeSend : function() {
                                
                    },
                    success : (res) => {
                        console.log(res);
                        if (res.stat == "success" || res.desc == "Request sudah dihapus dari keranjang !") {
                            // $('.batal#'+id).hide();
                            // $('.batal#'+id).attr('disabled');
                            console.log("true");
                            table
                                .row( $(this).parents('tr') )
                                .remove()
                                .draw();
                            $('#'+id).show();
                            let val = parseFloat((($(this).parents('tr').find("td:eq(1)").text()).substring(3, $(this).parents('tr').find("td:eq(1)").text().length-2)).replace(/\D/g,''));
                            let old = parseFloat((($(".total").text()).substring(11, $(".total").text().length-2)).replace(/\D/g,''));
                            let total = old - val;
                            // console.log($(this).parents('tr').find("td:eq(1)").text());
                            console.log(total+" "+old+" "+val);
                            $(".total").html("Total : "+total.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}));
                            // table.rows().every( function () {
                            //     var data = this.data();
                            //     var total =+ parseFloat((data['2'].substring(3, 10)).replace(/\D/g,''));
                            //     // var minus = Number(data['0'].replace(/[^0-9\.]+/g));
                            //     console.log(total);
                            // });
                        }else {
                            table
                                .row( $(this).parents('tr') )
                                .remove()
                                .draw();
                            $('#'+id).show();
                            let val = parseFloat((($(this).parents('tr').find("td:eq(1)").text()).substring(3, $(this).parents('tr').find("td:eq(1)").text().length-2)).replace(/\D/g,''));
                            let old = parseFloat((($(".total").text()).substring(11, $(".total").text().length-2)).replace(/\D/g,''));
                            let total = old - val;
                            // console.log($(this).parents('tr').find("td:eq(1)").text());
                            console.log(total+" "+old+" "+val);
                            $(".total").html("Total : "+total.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}));
                            // table.rows().every( function (rowIndex, tableLoop, rowLoop) {
                            //     var data = this.data();
                            //     var total =+ parseFloat((data['2'].substring(3, 10)).replace(/\D/g,''));
                            //     // var minus = Number(data['0'].replace(/[^0-9\.]+/g));
                            //     console.log(total);
                            // });
                        }
                    }
                });
            }else{
                return false;
            }
        });

        $('.lihat-keranjang').on('click', function(e){
            e.preventDefault();
            var harga = 0;
            var pk = [];
            $.ajax({
                method : 'POST',
                url : '{{route("request-keranjang-view")}}',
                data : {
                "_token" : "{{ csrf_token() }}",
                },
                beforeSend : function() {
                            
                },
                success : (res) => {
                    console.log(res);
                    if(res != null) {
                        table.clear();
                        jQuery.each(res, function(i, val){
                            if(val.status == "Selesai"){
                                harga = harga + val.nominal;
                                pk.push(val.id);
                                table.row.add([
                                    val.alamat,
                                    val.nominal.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}),
                                    val.tanggal,

                                    '<a class="btn btn-danger btn-sm text-white batal" id="'+val.id+'")"><i class="fas fa-times"></i> Hapus</a>'
                                ]);
                            }
                        });
                        console.log(pk);
                        $('#id').val(pk);
                        console.log(harga);
                        $('.total').html('Total : '+ harga.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}));
                        table.draw();
                    }else{
                        
                    }
                    // console.log(harga);
                }
            });
        });

    });

    function lihatBukti(lokasi) {
        // console.log(lokasi);
        if(lokasi.proof_image != null){
            $('#proof').attr('src', "{{asset('assets/img/request_p/bukti/')}}"+"/"+lokasi.proof_image);
        }else{
            $('#proof').attr('src', "{{asset('assets/img/properti/blank.png')}}");
        }
    }

    function lihatLokasi(lokasi) {
        // console.log(lokasi);
        if(lokasi.file != null){
            $('#prop').attr('src', "{{asset('assets/img/request_p/')}}"+"/"+lokasi.file);
        }else{
            $('#prop').attr('src', "{{asset('assets/img/properti/blank.png')}}");
        }
    }

    $(document).ready( function () {
        $("table").on('click', ".status" , function(){
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
                $(".desc-stat").html('Request Pengangkutan Sampah sedang diproses');
            } else if (status == "Pending") {
                $(".statuses").show();
                $(".selesai").removeClass('active');
                $("ul.checkout-bar").removeClass('selesai');
                $(".terkonfirmasi").removeClass('active');
                $("ul.checkout-bar").removeClass('konfirm');
                $(".pending").addClass('active');
                $("ul.checkout-bar").addClass('pen');
                $(".desc-stat").html('Request Pengangkutan Sampah sedang diperiksa');
            } else if (status == "Batal") {
                $(".statuses").css("display", "none");
                $(".desc-stat").html('Request Pengangkutan Sampah telah dibatalkan');
            }
        });
    });

    function readURLKeranjang(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#propK').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("#file-multi").change(function() {
        readURLKeranjang(this);
        console.log("true");
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
                    <form action="" method="post">
                        @csrf
                        <a class= "btn btn-success text-white mb-2" href="{{route('request-create')}}" ><i class="fas fa-plus"></i> Tambah Request Pengangkutan</a>
                        <a class= "btn btn-warning text-white mb-2 lihat-keranjang" data-toggle="modal" data-target="#modal-keranjang"><i class="fas fa-eye"></i> Lihat Keranjang </a>
                        <table class="table table-bordered " id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="table-primary">
                                    <th>Nama Pelanggan</th>
                                    <th>Alamat</th>
                                    <th>Nominal </th>
                                    <th>Tanggal Request </th>
                                    <th>Lokasi</th>
                                    <th>Bukti Pengangkutan</th>
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
                                    <td style="vertical-align: middle; text-align: center;">
                                        {{isset($i->nominal) ? 'Rp'.number_format($i->nominal ?? 0, 2, ',','.') : 'Belum Ditetapkan !'}}
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        {{isset($i) ? $i->created_at->format('d M Y') : ''}}
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        <a class= "btn btn-success text-white mb-2" data-toggle="modal" data-target="#modal-single" onClick="lihatLokasi({{$i}})"><i class="fas fa-eye"></i> Lihat Lokasi</a>
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        <a class= "btn btn-success text-white mb-2" data-toggle="modal" data-target="#modal-proof" onClick="lihatBukti({{$i}})"><i class="fas fa-eye"></i> Lihat Bukti</a>
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        @if($i->status == "Pending")
                                        <span class="badge badge-warning status" name="{{$i->status}}" data-toggle="modal" data-target="#modal-status">{{$i->status}}</span>
                                        @elseif($i->status == "Terkonfirmasi")
                                        <span class="badge badge-info status" name="{{$i->status}}" data-toggle="modal" data-target="#modal-status">{{$i->status}}</span>
                                        @elseif($i->status == "Selesai")
                                        <span class="badge badge-success status" name="{{$i->status}}"  data-toggle="modal" data-target="#modal-status">{{$i->status}}</span>
                                        @else
                                        <span class="badge badge-danger status" name="{{$i->status}}"  data-toggle="modal" data-target="#modal-status">{{$i->status}}</span>
                                        @endif
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        @if($i->status == "Selesai" && $i->pembayaran->isEmpty() && $i->keranjang->isEmpty())
                                            <a class="btn btn-success btn-sm text-white col mb-1 keranjang" id="{{$i->id}}"><i class="fas fa-cart-plus"></i> Keranjang</a>
                                            <a href="/user/request/edit/{{$i->id}}" class="btn btn-info btn-sm col"><i class="fas fa-eye"></i> Lihat</a><br>
                                        @elseif($i->status == "Terkonfirmasi" || $i->status == "Selesai")
                                            <a href="/user/request/edit/{{$i->id}}" class="btn btn-info btn-sm col"><i class="fas fa-eye"></i> Lihat</a><br>
                                        @elseif($i->status == "Batal")
                                            <a style="margin-right:7px" class="btn btn-danger btn-sm col" href="/user/request/delete/{{$i->id}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-trash"></i> Hapus</a><br>
                                        @elseif($i->status != "Selesai" && $i->status != "Terkonfirmasi")
                                            <a href="/user/request/edit/{{$i->id}}" class="btn btn-warning btn-sm col mb-1"><i class="fas fa-pencil-alt"></i> Ubah</a><br>
                                            <a style="margin-right:7px" class="btn btn-danger btn-sm col " href="/user/request/cancel/{{$i->id}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-times"></i> Batal</a><br>
                                        @else
                                            <a href="/user/request/edit/{{$i->id}}" class="btn btn-info btn-sm col"><i class="fas fa-pencil-alt"></i> Ubah</a><br>
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

<div class="modal fade" id="modal-proof">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                    <div class="row justify-content-between mb-3">
                        <div class="col">
                            <img src="{{asset('assets/img/properti/blank.png')}}"  height="300px" style="object-fit:cover" class="mb-3 rounded mx-auto d-block" id="proof">
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


<div class="modal fade" id="modal-keranjang">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-primary mb-1">
                <h6>Keranjang Request Pengangkutan Sampah</h6>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{route('request-keranjang-bayar')}}">
                    @csrf
                    <div id="myDIV" style="display: block">
                        <div class="row">
                            <div class="col">
                                <div class="table-responsive">
                                <table class="table table-bordered" id="dataTableKeranjang" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Alamat</th>
                                            <th>Nominal </th>
                                            <th>Tanggal Request </th>
                                            <th class="col-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between mb-3">
                        <div class="col">
                            <h6 class="m-0 font-weight-bold text-primary"></h6>
                            <input type="text" id="id" name="id[]" hidden>
                        </div>
                    </div>
                    <div class='col mb-2' hidden>
                        <input type="text" class="form-control @error('type') is-invalid @enderror" id="type-multi" name="type" placeholder="" value="retribusi" >
                    </div>
                    <div class="row">
                        <div class='col mb-2'>
                            <label for="file" class="font-weight-bold text-dark">Bukti Pembayaran</label>
                            <div class="d-flex justify-content-center">
                                <img src=""  height="300px" style="object-fit:cover" class="mb-3" id="propK">
                            </div>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file-multi" name="file" accept="image/png, image/jpeg, image/jpg" placeholder="File/Foto Bukti bayar" >
                                @error('file')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-2">
                        <label for="media" class="font-weight-bold text-dark">Media Pembayaran</label>
                            <select class="form-control @error('media') is-invalid @enderror" id="media-multi" name="media">
                            <option value="" selected>Pilih media Pembayaran</option>
                                <option value="transfer" >transfer</option>
                                <option value="cash" >cash</option>
                            </select>
                            @error('media')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        <div class="col mb-2">
                        <label for="nominal-multi" class="font-weight-bold text-dark">Konfirmasi Nominal Bayar</label>
                            <input type="number" class="form-control @error('nominal') is-invalid @enderror" id="nominal-multi" name="nominal" placeholder="Inputkan total yang diperlihatkan !" >
                                @error('nominal')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col" style="vertical-align: middle; text-align: left;">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Data Pembayaran Sudah Benar?')"><i class="fas fa-save"></i> Bayar</button>
                            <a data-dismiss="modal" class="btn btn-danger text-white"><i class="fas fa-times"></i> Close</a>
                        </div>
                        <div class="col" style="vertical-align: middle; text-align: end;">
                            <h1 class="total">Total : Rp0,00</h1>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection