@extends('layouts.user-master')

@section('title')
Pembayaran
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
    var table = $('#dataTableKeranjang').DataTable({
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

    var tableItem = $('#dataTableItem').DataTable({
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

    $(document).ready( function () {
        
        $('.cari').on('click', function(e){
            var desa = $('#desa').val();
            var jenis = $('#jenisItem').val();
            // console.log(desa);
            e.preventDefault();
            $.ajax({
                method : 'POST',
                    url : '{{route("pembayaran-item-cari")}}',
                    data : {
                    "_token" : "{{ csrf_token() }}",
                    desa: desa,
                    jenis: jenis,
                    },
                    beforeSend : function() {
                                
                    },
                    success : (res) => {
                        console.log(res);
                        tableItem.clear();
                        jQuery.each(res, function(i, val){
                                jQuery.each(val, function(i, nilai){
                                    if(nilai.id_properti != null){
                                        console.log(nilai);
                                        tableItem.row.add([
                                            nilai.properti.nama_properti,
                                            "Retribusi",
                                            nilai.nominal.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}),
                                            nilai.tanggal,
                                            `<a class="btn btn-success btn-sm text-white col mb-1 tambah" id="`+nilai.id+"-retribusi"+`"><i class="fas fa-plus"></i> Tambah</a>`
                                        ]);
                                    }else{
                                        console.log("false");
                                        tableItem.row.add([
                                            "Pengangkutan ("+nilai.alamat+")",
                                            "Pengangkutan",
                                            nilai.nominal.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}),
                                            nilai.tanggal,
                                            `<a class="btn btn-success btn-sm text-white col mb-1 tambah" id="`+nilai.id+"-pengangkutan"+`"><i class="fas fa-plus"></i> Tambah</a>`
                                        ]);
                                    }
                                });
                        });
                        tableItem.draw()
                    }
            });
        });

        $('table').on('click', '.tambah' ,function(e){
            if(confirm("Apakah Anda Yakin Ingin menambah item ke dalam Pembayaran ?") == true){
                var pembayaran = <?php echo $pembayaran->id_pembayaran ?>;
                console.log(pembayaran);
                var id = $(this).attr("id");
                console.log(id);
                e.preventDefault();
                $.ajax({
                    method : 'POST',
                    url : '{{route("pembayaran-item-tambah")}}',
                    data : {
                    "_token" : "{{ csrf_token() }}",
                    id: id,
                    pembayaran: pembayaran,
                    },
                    beforeSend : function() {
                                
                    },
                    success : (res) => {
                        tableItem
                                .row( $(this).parents('tr') )
                                .remove()
                                .draw();
                        console.log(res);
                        refreshItem();
                    }
                });
            }else{

            }
        });

        $('table').on('click', '.batal' ,function(e){
            if(confirm("Apakah Anda Yakin Ingin menghapus item dari Pembayaran ?")== true){
                var detail = $(this).attr("id");
                var id = detail.split(",");
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
                    url : '{{route("pembayaran-item-hapus")}}',
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
    })

    function checkStatus(status){
        if(status == "pending"){
            var button = `<span class="badge badge-warning">`+status+`</span>`;
            return button;
        }else if (status == "lunas"){
            var button = `<span class="badge badge-success">`+status+`</span>`;
            return button;
        }else if (status == "Selesai"){
            var button = `<span class="badge badge-warning">`+status+`</span>`;
            return button;
        }else{
            var button = `<span class="badge badge-warning">`+status+`</span>`;
            return button;
        }
    }

    function checkButton(pembayaran, m){
        if(pembayaran.status != "terverifikasi"){
            if(m.properti != undefined){
                return `<a class="btn btn-danger btn-sm text-white batal" id="`+m.id+"-"+`retribusi"><i class="fas fa-times"></i> Hapus</a>`;
            }else{
                return `<a class="btn btn-danger btn-sm text-white batal" id="`+m.id+"-"+`pengangkutan"><i class="fas fa-times"></i> Hapus</a>`;
            }
        }else{
            if(m.properti != undefined){
                return `<a class="btn btn-danger btn-sm text-white lihat" id="`+m.id+"-"+`retribusi"><i class="fas fa-eye"></i> Lihat Properti</a>`
            }else{
                return `<a class="btn btn-danger btn-sm text-white lihat" id="`+m.id+"-"+`pengangkutan"><i class="fas fa-eye"></i> Lihat Pengangkutan</a>`
            }
        }
    }

    function refreshItem(){
        var id = <?php echo $pembayaran->id_pembayaran ?>;
        $.ajax({
            method : 'POST',
                    url : '{{route("pembayaran-item-refresh")}}',
                    data : {
                    "_token" : "{{ csrf_token() }}",
                    id: id,
                    },
                    beforeSend : function() {
                                
                    },
                    success : (res) => {
                        var link = [];
                        var pk = [];
                        var loop = 0;
                        console.log(res);
                        table.clear()
                        jQuery.each(res, function(i, val){
                            jQuery.each(val, function(a, nilai){
                                console.log(nilai.id_properti);
                                if(nilai.id_properti != null){
                                    table.row.add([
                                        nilai.properti.nama_properti,
                                        nilai.jasa.jenis_jasa,
                                        nilai.nominal,
                                        nilai.tanggal,
                                        checkStatus(nilai.status),
                                        checkButton(<?php echo $pembayaran?>, nilai)
                                    ]);
                                    pk.push(nilai.id+"-retribusi");
                                    loop++;
                                    link[a] = nilai.id_properti+"properti";
                                }else{
                                    table.row.add([
                                        nilai.alamat,
                                        "Request Pengangkutan Sampah",
                                        nilai.nominal,
                                        nilai.tanggal,
                                        checkStatus(nilai.status),
                                        checkButton(<?php echo $pembayaran?>, nilai)
                                    ]);
                                    pk.push(nilai.id+"-pengangkutan");
                                    loop++;
                                    console.log(nilai.properti == undefined);
                                    link[loop] = nilai.id+"pengangkutan";
                                }
                            });
                        });
                        $("#id").val(pk);
                        table.draw();
                    }
        });
    }

    function readURLDetail(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#prop').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }



    $("#file-multi").change(function() {
        readURLDetail(this);
        console.log("true");
    });
</script>
@endsection

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Manajemen Pembayaran</h1>
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
            <form method="POST" enctype="multipart/form-data" action="{{route('pembayaran-update', $pembayaran->id_pembayaran)}}">
            @csrf
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <h5 class="font-weight-bold text-primary"><i class="fas fa-list"></i> List Retribusi & Request</h5>
                    <div class="col" align="end">
                        @if($pembayaran->status != "terverifikasi")
                        <a class="btn btn-warning btn-sm text-white" data-toggle="modal" data-target="#modal-ubah" ><i class="fas fa-edit"></i> Ubah</a>
                        @endif
                    </div>
                </div>
                <div class="form-group card-body">
                    <div id="myDIV" style="display: block">
                        <div class="row">
                            <div class="col">
                                <div class="table-responsive">
                                <table class="table table-bordered" id="dataTableKeranjang" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nama Item</th>
                                            <th>Jenis</th>
                                            <th>Nominal </th>
                                            <th>Tanggal Buat</th>
                                            <th>Status</th>
                                            <th class="col-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pembayaran->detail->map->model as $m)
                                        <tr>
                                            <td>{{isset($m->properti) ? $m->properti->nama_properti : "Pengangkutan (".$m->alamat.")"}}</td>
                                            <td>{{isset($m->properti) ? $m->properti->jasa->jenis_jasa : "Request Pengangkutan Sampah"}}</td>
                                            <td>Rp {{number_format($m->nominal ?? 0, 2, ',', '.')}}</td>
                                            <td>{{$m->created_at->format('d M Y')}}</td>
                                            <td>
                                                @if($m->status == "pending")
                                                    <span class="badge badge-warning">{{$m->status}}</span>
                                                @elseif($m->status == "lunas")
                                                    <span class="badge badge-success">{{$m->status}}</span>
                                                @elseif($m->status == "Selesai")
                                                <span class="badge badge-warning">pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($pembayaran->status != "terverifikasi")
                                                    @if(isset($m->properti))
                                                    <a class="btn btn-danger btn-sm text-white batal" id="{{$m->id.'-'}}retribusi"><i class="fas fa-times"></i> Hapus</a>
                                                    @else
                                                    <a class="btn btn-danger btn-sm text-white batal" id="{{$m->id.'-'}}pengangkutan"><i class="fas fa-times"></i> Hapus</a>
                                                    @endif
                                                @else
                                                    @if(isset($m->properti))
                                                    <a class="btn btn-danger btn-sm text-white lihat" href="{{Route('properti-edit', $m->properti->id)}}" id="" ><i class="fas fa-eye"></i> Lihat Properti</a>
                                                    @else
                                                    <a class="btn btn-danger btn-sm text-white lihat" href="{{Route('pengangkutan-edit', $m->id)}}" id="" ><i class="fas fa-eye"></i> Lihat Pengangkutan</a>
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
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <div class="row">
                        <div class="col">
                            <h5 class="font-weight-bold text-primary"><i class="fas fa-pen"></i> Edit Data Pembayaran</h5>
                        </div>
                    </div>
                </div>
                <div class="form-group card-body">
                    <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary"></h6>
                                <input type="text" id="id" name="id[]" hidden>
                                <input type="text" id="pembayaran" name="pembayaran" value="{{isset($pembayaran) ? $pembayaran->id_pembayaran : '' }}" hidden>
                            </div>
                        </div>
                        <div class='col mb-2' hidden>
                            <input type="text" class="form-control @error('type') is-invalid @enderror" id="type-multi" name="type" placeholder="" value="retribusi" >
                        </div>
                        <div class="row">
                            <div class='col mb-2'>
                                <label for="file" class="font-weight-bold text-dark">Bukti Pembayaran</label>
                                <div class="d-flex justify-content-center">
                                    <img src="{{isset($pembayaran->bukti_bayar) ? asset('assets/img/bukti_bayar/'.$pembayaran->bukti_bayar) : asset('assets/img/properti/blank.png') }}"  height="300px" style="object-fit:cover" class="mb-3" id="prop">
                                </div>
                                <input type="file" class="form-control @error('file') is-invalid @enderror" id="file-multi" name="file" accept="image/png, image/jpeg, image/jpg" placeholder="File/Foto Bukti bayar" {{$pembayaran->status == "terverifikasi" ? disabled : ''}}>
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
                                <select class="form-control @error('media') is-invalid @enderror" id="media-multi" name="media" {{$pembayaran->status == "terverifikasi" ? disabled : ''}}>
                                <option value="" {{$pembayaran->media == null ? 'selected' : '' }}>Pilih media Pembayaran</option>
                                    <option value="transfer" {{$pembayaran->media == "transfer" ? 'selected' : '' }}>transfer</option>
                                    <option value="cash" {{$pembayaran->media == "cash" ? 'selected' : ''}}>cash</option>
                                </select>
                                @error('media')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>    
                                    @enderror
                            </div>
                            <div class="col mb-2">
                            <label for="nominal-multi" class="font-weight-bold text-dark">Konfirmasi Nominal Bayar</label>
                                <input type="number" class="form-control @error('nominal') is-invalid @enderror" id="nominal-multi" name="nominal" placeholder="Inputkan total yang diperlihatkan !" value="{{isset($pembayaran->nominal) ? $pembayaran->nominal : old('nominal')}}" {{$pembayaran->status == "terverifikasi" ? disabled : ''}}>
                                    @error('nominal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col" style="vertical-align: middle; text-align: left;">
                                <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Data Pembayaran Sudah Benar?')"><i class="fas fa-save"></i> Ubah</button>
                                <!-- <a class="btn btn-danger text-white"><i class="fas fa-times"></i> Batal</a> -->
                            </div>
                            <div class="col" style="vertical-align: middle; text-align: end;">
                                @if($pembayaran != "terverifikasi")
                                    <h1 class="total">Total : Rp {{isset($pembayaran->nominal) ? number_format($pembayaran->nominal ?? 0, 2, ',','.') : old('nominal')}}</h1>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-ubah">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-primary">Pilih item</h5>
                    </div>
                    <div class="card-body">
                        <div id="myDIV" style="display: block">
                            <div class="row justify-content-between mb-3">
                                <div class="col col-5">
                                    <label for="jenisItem" class="font-weight-bold text-dark">Jenis Item</label>
                                    <select name="jenisItem" id="jenisItem" class="form-control @error('jenisItem') is-invalid @enderror">
                                        <option value="both" selected>Retribusi & Request Pengangkutan</option>
                                        <option value="retribusi">Retribusi</option>
                                        <option value="pengangkutan">Request Pengangkutan</option>
                                    </select>
                                </div>
                                <div class="col col-4">
                                    <label for="desa" class="font-weight-bold text-dark">Desa Adat</label>
                                    <select name="desa" id="desa" class="form-control @error('desa') is-invalid @enderror">
                                        @foreach($desa as $d)
                                            <option value="{{$d->id}}">{{$d->desadat_nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-3 pt-4" style="vertical-align: middle; text-align: center;">
                                    <a class="btn btn-info btn-sm text-white col cari" ><i class="fas fa-search"></i> Cari</a>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <table class="table table-bordered" id="dataTableItem" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nama Item</th>
                                            <th>Jenis Item</th>
                                            <th>Nominal </th>
                                            <th>Tanggal Buat</th>
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
            </div>
        </div>
    </div>
</div>

@endsection