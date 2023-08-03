@extends('layouts.user-master')

@section('title')
Index Retribusi
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
    $(document).ready( function () {
        $('#dataTable').DataTable({
            "oLanguage":{
                "sSearch": "",
                "sZeroRecords": "Data tidak ditemukan",
                "sSearchPlaceholder": "Cari retribusi...",
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

        $(".keranjang").on('click', function(e){
            var id = $(this).attr("id");
            console.log(id);
            e.preventDefault();
            $.ajax({
                method : 'POST',
                url : '{{route("retribusi-keranjang")}}',
                data : {
                "_token" : "{{ csrf_token() }}",
                id: id,
                },
                beforeSend : function() {
                            
                },
                success : (res) => {
                    console.log(res);
                    if (res.stat == "success" || res.desc == "Retribusi sudah terdaftar dalam keranjang !") {
                        $('#'+id).hide();
                        $('#'+id).attr('disabled');
                    }
                }
            });
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

        $('table').on('click', '.batal' ,function(e){
            if(confirm("Apakah Anda Yakin Ingin menghapus retribusi dari keranjang ?")== true){
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
                    url : '{{route("retribusi-keranjang-hapus")}}',
                    data : {
                    "_token" : "{{ csrf_token() }}",
                    id: id,
                    },
                    beforeSend : function() {
                                
                    },
                    success : (res) => {
                        console.log(res);
                        if (res.stat == "success" || res.desc == "Retribusi sudah dihapus dari keranjang !") {
                            // $('.batal#'+id).hide();
                            // $('.batal#'+id).attr('disabled');
                            console.log("true");
                            table
                                .row( $(this).parents('tr') )
                                .remove()
                                .draw();
                            $('#'+id).show();
                            let val = parseFloat((($(this).parents('tr').find("td:eq(2)").text()).substring(3, $(this).parents('tr').find("td:eq(2)").text().length-2)).replace(/\D/g,''));
                            let old = parseFloat((($(".total").text()).substring(11, $(".total").text().length-2)).replace(/\D/g,''));
                            let total = old - val;
                            console.log(total);
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
                            let val = parseFloat((($(this).parents('tr').find("td:eq(2)").text()).substring(3, $(this).parents('tr').find("td:eq(2)").text().length-2)).replace(/\D/g,''));
                            let old = parseFloat((($(".total").text()).substring(11, $(".total").text().length-2)).replace(/\D/g,''));
                            let total = old - val;
                            console.log(total);
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
                url : '{{route("retribusi-keranjang-view")}}',
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
                            if(val.status == "pending"){
                                harga = harga + val.nominal;
                                pk.push(val.id);
                                table.row.add([
                                    val.properti.nama_properti,
                                    val.properti.jasa.jenis_jasa,
                                    val.nominal.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}),
                                    val.tanggal,
                                    '<span class="badge badge-warning">'+val.status+'</span>',
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
    
</script>

<script>

    function detail(retribusi, properti){
        console.log(properti);
        $(".nama").html("Detail Retribusi "+properti.nama_properti);
        $(".status").html(retribusi.status);
        $("#nama").val(properti.nama_properti);
        $("#nominal").val(retribusi.nominal);
        $("#alamat").val(properti.alamat);
        if(properti.file != null){
            console.log(properti.file)
            $('#prop').attr('src', "{{asset('assets/img/properti/')}}"+"/"+properti.file);
        }else{
            $('#prop').attr('src', "{{asset('assets/img/properti/blank.png')}}");
        }
    }
        
    function calculateNom(retri){
        checkBox = document.getElementById('id-'+retri.id);
        if(checkBox.checked){
            var old = document.getElementById('nominal-multi').value;
            
            document.getElementById('nominal-multi').value = parseInt(old) + parseInt(retri.nominal);
        }else{
            var old = document.getElementById('nominal-multi').value;
            document.getElementById('nominal-multi').value = old - retri.nominal;
        }
    }

    function calculateNom_b(retri){
        // console.log('true');
        document.getElementById('nominal').value = retri.nominal;
        document.getElementById('id').value = retri.id;
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#prop').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    function readURLKeranjang(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#propK').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    function readURL_single(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
            $('#photo').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }


    $("#file-multi").change(function() {
        readURLKeranjang(this);
        console.log("true");
    });


    $("#file-single").change(function() {
        readURL_single(this);
    });
</script>

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
                    <a class= "btn btn-warning text-white mb-2 lihat-keranjang" data-toggle="modal" data-target="#modal-keranjang"><i class="fas fa-eye"></i> Lihat Keranjang </a>
                    <!-- <a class= "btn btn-info text-white mb-2" data-toggle="modal" data-target="#modal-cicil"><i class="fas fa-cash-register"></i> Cicil Tagihan Retribusi</a> -->
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="table-primary">
                                <th>Nama Properti</th>
                                <th>Jenis Properti</th>
                                <th>Nominal </th>
                                <th>Tanggal Retribusi </th>
                                <th>Status</th>
                                <th class="col-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($index as $retri)
                            @if($retri->Dpembayaran->map->pembayaran->isEmpty())
                            <tr>
                                <td style="vertical-align: middle; text-align: center;">
                                    {{isset($retri->properti) ? $retri->properti->nama_properti : 'Error Data Kosong !'}}
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    {{isset($retri->properti->jasa)? $retri->properti->jasa->jenis_jasa : 'Error Data Kosong !'}}
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    Rp {{number_format($retri->nominal ?? 0,2,'.',',')}}
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    {{$retri->created_at->format('d M Y')}}
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                @if($retri->status == "pending")
                                    <span class="badge badge-warning">{{$retri->status}}</span>
                                @elseif($retri->status == "lunas")
                                    <span class="badge badge-success">{{$retri->status}}</span>
                                @endif
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    @if($retri->Dpembayaran->map->pembayaran->isEmpty() && $retri->keranjang->isEmpty() && $retri->status != 'lunas' )
                                        <!-- <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-single" onClick="calculateNom_b({{$retri}})"><i class="fas fa-cart-plus"></i> Keranjang</a> -->
                                        <a class="btn btn-success btn-sm text-white col mb-1 keranjang" id="{{$retri->id}}"><i class="fas fa-cart-plus"></i> Keranjang</a>
                                        <a href="#" data-toggle="modal" data-target="#modal-detail" class="btn btn-info btn-sm col detail" onClick="detail({{$retri}}, {{$retri->properti}})" ><i class="fas fa-eye"></i> Lihat</a>
                                    @elseif($retri->status == 'lunas')
                                        <a href="#" data-toggle="modal" data-target="#modal-detail" class="btn btn-info btn-sm col mb-1 detail" onClick="detail({{$retri}}, {{$retri->properti}})"><i class="fas fa-eye"></i> Lihat</a>
                                        <!-- <a href="#" class="btn btn-info btn-md text-white" data-toggle="" data-target=""><i class="fas fa-exclamation"></i></a> -->
                                    @else
                                        <a class="btn btn-success btn-sm text-white col mb-1 keranjang" style="display: none;" id="{{$retri->id}}"><i class="fas fa-cart-plus"></i> Keranjang</a>
                                        <a href="#" data-toggle="modal" data-target="#modal-detail" class="btn btn-info btn-sm col detail" onClick="detail({{$retri}}, {{$retri->properti}})" ><i class="fas fa-eye"></i> Lihat</a>
                                    @endif
                                </td>
                            </tr>
                            @endif
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

<div class="modal fade" id="modal-detail">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary nama">Detail Retribusi
                                    <span class="badge badge-warning status"></span>
                                </h6>
                            </div>
                        </div>
                        <form method="POST" enctype="multipart/form-data" action="">
                            <div class="row">
                                <div class="col mb-2 d-flex justify-content-center">
                                    <!-- Properti -->
                                        <img src="{{asset('assets/img/properti/blank.png')}}"  height="300px" style="object-fit:cover" class="mb-3" id="prop">
                                    @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>    
                                    @enderror
                                </div>

                            </div>   
                            <div class="row">
                                <div class='col mb-2'>
                                    <label for="nama" class="font-weight-bold text-dark">Nama Properti</label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" placeholder="" value="{{old('nama')}}" disabled>
                                        @error('nama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                </div>
                                <div class="col">
                                    <label for="alamat" class="font-weight-bold text-dark">Alamat Properti</label>
                                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Masukan Alamat Properti" value="{{old('alamat')}}" disabled>
                                        @error('alamat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <!-- nominal -->
                                    <label for="nominal" class="font-weight-bold text-dark" >Nominal</label>
                                    <input type="text" class="form-control @error('nominal') is-invalid @enderror" id="nominal" name="nominal" placeholder="Masukan Nominal Retribusi" value="{{old('keterangan')}}" disabled>
                                        @error('nominal')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col">
                                    <!-- <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Mengubah Data?')"><i class="fas fa-check"></i> Ubah</button> -->
                                    <a data-dismiss="modal" class="btn btn-danger text-white"><i class="fas fa-times"></i> Tutup</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-keranjang">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header text-primary mb-1">
                <h6>Keranjang Retribusi</h6>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{route('retribusi-keranjang-bayar')}}">
                    @csrf
                    <div id="myDIV" style="display: block">
                        <div class="row">
                            <div class="col">
                                <div class="table-responsive">
                                <table class="table table-bordered" id="dataTableKeranjang" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nama Properti</th>
                                            <th>Jenis Properti</th>
                                            <th>Nominal </th>
                                            <th>Tanggal Retribusi </th>
                                            <th>Status</th>
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

<!-- 
<div class="modal fade" id="modal-choose">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                    <form method="POST" enctype="multipart/form-data" action="{{route('pembayaran-store')}}">
                    @csrf
                        <div class="row">
                            <div class="col">
                                <div class="table-responsive">
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
                                        @if($retri->status == "pending")
                                            <tr>
                                                <td align="center">
                                                    <input type="checkbox" name="id[]" id="id-{{$retri->id}}" value="{{$retri->id}}" onClick="calculateNom({{$retri}})"> -->
                                                    <!-- <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-{{$retri->id}}"><i class="fas fa-cart-plus"></i></a> -->
                                                <!-- </td>
                                                <td>
                                                    {{isset($retri->properti) ? $retri->properti->nama_properti : ''}}
                                                </td>
                                                <td>
                                                    {{isset($retri->properti->jasa)? $retri->properti->jasa->jenis_jasa : ''}}
                                                </td>
                                                <td>
                                                    Rp. {{number_format($retri->nominal)}}
                                                </td>
                                                <td>
                                                    {{$retri->created_at->format('d M Y')}}
                                                </td>
                                                <td>
                                                @if($retri->status == "pending")
                                                    <span class="badge badge-warning">{{$retri->status}}</span>
                                                @elseif($retri->status == "lunas")
                                                    <span class="badge badge-success">{{$retri->status}}</span>
                                                @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>

                            <div class="row justify-content-between mb-3">
                                <div class="col">
                                    <h6 class="m-0 font-weight-bold text-primary"></h6>
                                </div>
                            </div>
                                    <div class='col mb-2' hidden>
                                        <input type="text" class="form-control @error('type') is-invalid @enderror" id="type-multi" name="type" placeholder="" value="retribusi" >
                                    </div>
                                <div class="row">
                                    <div class='col mb-2'>
                                        <label for="file" class="font-weight-bold text-dark">Bukti Pembayaran</label>
                                        <div class="d-flex justify-content-center">
                                            <img src=""  height="300px" style="object-fit:cover" class="mb-3" id="prop">
                                        </div>
                                        <input type="file" class="form-control @error('file') is-invalid @enderror" id="file-multi" name="file" accept="image/png, image/jpeg, image/jpg" placeholder="File/Foto Bukti bayar"  value="{{old('file')}}" >
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
                                        <input type="number" class="form-control @error('nominal') is-invalid @enderror" id="nominal-multi" name="nominal" placeholder="Konfirmasi Nominal Pembayaran" value="0" >
                                            @error('nominal')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Data Pembayaran Sudah Benar?')"><i class="fas fa-save"></i> Bayar</button>
                                        <a data-dismiss="modal" class="btn btn-danger text-white"><i class="fas fa-times"></i> Close</a>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</div> -->



<!-- <div class="modal fade" id="modal-single">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary">Pembayaran Retribusi {{isset($retri->properti) ? $retri->properti->nama_properti : ''}}</h6>
                            </div>
                        </div>
                        <form method="POST" enctype="multipart/form-data" action="{{route('pembayaran-store')}}">
                        @csrf
                                <div class='col mb-2' hidden>
                                    <input type="text" class="form-control @error('id') is-invalid @enderror" id="id" name="id[]" placeholder="" value="{{isset($retri->id) ? $retri->id : old('id[]')}}" >
                                    <input type="text" class="form-control @error('type') is-invalid @enderror" id="type" name="type" placeholder="" value="retribusi" >
                                </div>
                            <div class="row">
                                <div class='col mb-2'>
                                    <label for="file" class="font-weight-bold text-dark">Bukti Pembayaran</label>
                                    <div class="col-12 d-flex justify-content-center">
                                        <img src=""  height="300px" style="object-fit:cover" class="mb-3" id="photo">
                                    </div>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file-single" name="file" accept="image/png, image/jpeg, image/jpg" placeholder="File/Foto Bukti bayar" value="{{isset($retri->Dpembayaran->map->pembayaran) ? $retri->Dpembayaran->map->pembayaran->bukti_bayar : old('file')}}"  >
                                        @error('file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-2">
                                <label for="media" class="font-weight-bold text-dark">Jenis Pembayaran<i class="text-danger text-sm text-bold">*</i></label>
                                    <select class="form-control @error('media') is-invalid @enderror" id="media" name="media">
                                    <option value="" selected>Pilih Jenis Pembayaran</option>
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
                                <label for="nominal" class="font-weight-bold text-dark">Konfirmasi Nominal Bayar<i class="text-danger text-sm text-bold">*</i></label>
                                    <input type="number" class="form-control @error('nominal') is-invalid @enderror" id="nominal" name="nominal" placeholder="Konfirmasi Nominal Pembayaran" value="{{isset($retri->Dpembayaran->map->pembayaran) ? $properti->Dpembayaran->map->pembayaran->nominal : old('nominal')}}" >
                                        @error('nominal')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Data Pembayaran Sudah Benar?')"><i class="fas fa-save"></i> Bayar</button>
                                    <a data-dismiss="modal" class="btn btn-danger text-white"><i class="fas fa-times"></i> Close</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</div> -->


@endsection

