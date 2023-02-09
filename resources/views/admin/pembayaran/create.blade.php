@extends('layouts.admin-master')

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

    var tablePelanggan = $('#dataTablePelanggan').DataTable({
        "oLanguage":{
            "sSearch": "Cari:",
            "sZeroRecords": "Data tidak ditemukan",
            "sSearchPlaceholder": "Cari Pelanggan..",
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

    
    function checkStatus(status){
        if(status == "pending"){
            var button = `<span class="badge badge-warning">`+status+`</span>`;
            return button;
        }else if (status == "lunas"){
            var button = `<span class="badge badge-success">`+status+`</span>`;
            return button;
        }else if (status == "Selesai"){
            var button = `<span class="badge badge-warning">`+"pending"+`</span>`;
            return button;
        }else{
            var button = `<span class="badge badge-warning">`+status+`</span>`;
            return button;
        }
    }

    function checkButton(m){
        if(m.properti != undefined){
            return `<a class="btn btn-danger btn-sm text-white batal" id="`+m.id+"-"+`retribusi"><i class="fas fa-times"></i> Hapus</a>`;
        }else{
            return `<a class="btn btn-danger btn-sm text-white batal" id="`+m.id+"-"+`pengangkutan"><i class="fas fa-times"></i> Hapus</a>`;
        }
    }

    function refreshItem(pelanggan){
        // // console.log("testing");
        $.ajax({
            method : 'POST',
                    url : '{{route("admin-pembayaran-keranjang-view")}}',
                    data : {
                    "_token" : "{{ csrf_token() }}",
                    pelanggan: pelanggan,
                    },
                    beforeSend : function() {
                                
                    },
                    success : (res) => {
                        var pk = [];
                        var total = 0;
                        var link = [];
                        var loop = 0;
                        // console.log(res);
                        table.clear()
                        jQuery.each(res, function(i, val){
                            jQuery.each(val, function(a, nilai){
                                if(nilai.id_properti != null){
                                    // // console.log(true);
                                    table.row.add([
                                        nilai.properti.nama_properti,
                                        nilai.jasa,
                                        nilai.nominal.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}),
                                        nilai.tanggal,
                                        checkStatus(nilai.status),
                                        checkButton(nilai)
                                    ]);
                                    pk.push(nilai.id+"-retribusi");
                                    total += nilai.nominal;
                                    // // console.log(nilai.nominal);
                                    loop++;
                                    link[a] = nilai.id_properti+"properti";
                                }else{
                                    // // console.log(false);
                                    table.row.add([
                                        "Pengangkutan ("+ nilai.alamat +")",
                                        "Request Pengangkutan Sampah",
                                        nilai.nominal.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}),
                                        nilai.tanggal,
                                        checkStatus(nilai.status),
                                        checkButton(nilai)
                                    ]);
                                    pk.push(nilai.id+"-pengangkutan");
                                    total += nilai.nominal;
                                    // // console.log(total);
                                    loop++;
                                    // console.log(nilai.properti == undefined);
                                    link[loop] = nilai.id+"pengangkutan";
                                }
                            });
                        });
                        $("#id").val(pk);
                        table.draw();
                        $('.total').html('Total : '+total.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}));
                        // console.log(link);
                    }
        });
    }

    function pelangganCari(pelanggan){
        // pelanggan = $(this).attr("id");
        $("#pelanggan").val(pelanggan);
        console.log(pelanggan);
        $.ajax({
            method : 'POST',
                url : '{{route("admin-pembayaran-pelanggan-cari")}}',
                data : {
                "_token" : "{{ csrf_token() }}",
                pelanggan: pelanggan,
                },
                beforeSend : function() {
                            
                },
                success : (res) => {
                    // console.log(res);
                    tableItem.clear();
                    jQuery.each(res, function(i, val){
                            jQuery.each(val, function(i, nilai){
                                if(nilai.id_properti != null){
                                    // // console.log(nilai);
                                    tableItem.row.add([
                                        nilai.properti.nama_properti,
                                        "Retribusi",
                                        nilai.nominal.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}),
                                        nilai.tanggal,
                                        `<a class="btn btn-success btn-sm text-white col mb-1 tambah" id="`+nilai.id+"-retribusi"+`"><i class="fas fa-plus"></i> Tambah</a>`
                                    ]);
                                }else{
                                    // // console.log("false");
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
                    refreshItem(pelanggan);
                    tableItem.draw();
                }
        });
    }

    $(document).ready( function () {
        var pelanggan = null;
        $('.pelanggan').on("click", function(e){
            pelanggan = $(this).attr("id");
        });
        

        // echo "var data = ".json_encode($data).";"
        // var pk = [];
        // jQuery.each(data, function(i, val){
        //     if(val.id_properti != null){
        //         pk.push(val.id+"-retribusi");
        //     }else{
        //         // console.log(val);
        //         pk.push(val.id+"-pengangkutan");
        //     }
        // });
        // $("#id").val(pk);
        
        $('.cari').on('click', function(e){
            var desa = $('#desa').val();
            var jenis = $('#jenisItem').val();
            // // console.log(desa);
            e.preventDefault();
            $.ajax({
                method : 'POST',
                    url : '{{route("admin-pembayaran-keranjang-cari")}}',
                    data : {
                    "_token" : "{{ csrf_token() }}",
                    desa: desa,
                    jenis: jenis,
                    },
                    beforeSend : function() {
                                
                    },
                    success : (res) => {
                        // console.log(res);
                        tableItem.clear();
                        jQuery.each(res, function(i, val){
                                jQuery.each(val, function(i, nilai){
                                    if(nilai.id_properti != null){
                                        // console.log(nilai);
                                        tableItem.row.add([
                                            nilai.properti.nama_properti,
                                            "Retribusi",
                                            nilai.nominal.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}),
                                            nilai.tanggal,
                                            `<a class="btn btn-success btn-sm text-white col mb-1 tambah" id="`+nilai.id+"-retribusi"+`"><i class="fas fa-plus"></i> Tambah</a>`
                                        ]);
                                    }else{
                                        // console.log("false");
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
                        // refreshItem();
                        tableItem.draw();
                    }
            });
        });

        $('table').on('click', '.tambah' ,function(e){
            if(confirm("Apakah Anda Yakin Ingin menambah item ke dalam Pembayaran ?") == true){
                var id = $(this).attr("id");
                // console.log(id);
                e.preventDefault();
                $.ajax({
                    method : 'POST',
                    url : '{{route("admin-pembayaran-keranjang")}}',
                    data : {
                    "_token" : "{{ csrf_token() }}",
                    id: id,
                    },
                    beforeSend : function() {
                                
                    },
                    success : (res) => {
                        tableItem
                                .row( $(this).parents('tr') )
                                .remove()
                                .draw();
                        console.log(pelanggan);
                        refreshItem(pelanggan);
                    }
                });
            }else{

            }
        });

        $('table').on('click', '.batal' ,function(e){
            if(confirm("Apakah Anda Yakin Ingin menghapus item dari Pembayaran ?")== true){
                var detail = $(this).attr("id");
                var id = detail;
                var pkO = $("#id").val().split(",");
                // console.log(pkO);
                if(pkO.length > 1 ){
                    $.each(pkO, function(i,val){
                        if(id == val){
                            pkO.splice(i, 1);
                            // console.log(pkO +" "+i);
                        }
                    });
                }else{
                    $.each(pkO, function(i,val){
                        if(id == val){
                            pkO.splice(i, 1);
                            // console.log(pkO +" "+i);
                        }
                    });
                }
                // console.log(pkO);
                $("#id").val(pkO);
                e.preventDefault();
                $.ajax({
                    method : 'POST',
                    url : '{{route("admin-pembayaran-keranjang-hapus")}}',
                    data : {
                    "_token" : "{{ csrf_token() }}",
                    id: id,
                    },
                    beforeSend : function() {
                                
                    },
                    success : (res) => {
                        // console.log(res);
                        if (res.stat == "success" || res.desc == "Retribusi sudah dihapus dari keranjang !") {
                            // $('.batal#'+id).hide();
                            // $('.batal#'+id).attr('disabled');
                            // console.log("true");
                            table
                                .row( $(this).parents('tr') )
                                .remove()
                                .draw();
                            $('#'+id).show();
                            let val = parseFloat((($(this).parents('tr').find("td:eq(2)").text()).substring(3, $(this).parents('tr').find("td:eq(2)").text().length-2)).replace(/\D/g,''));
                            let old = parseFloat((($(".total").text()).substring(11, $(".total").text().length-2)).replace(/\D/g,''));
                            let total = old - val;
                            // console.log($(this).parents('tr').find("td:eq(2)").text());
                            // console.log(total+" "+val+" "+old);
                            $(".total").html("Total : "+total.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}));
                            // table.rows().every( function () {
                            //     var data = this.data();
                            //     var total =+ parseFloat((data['2'].substring(3, 10)).replace(/\D/g,''));
                            //     // var minus = Number(data['0'].replace(/[^0-9\.]+/g));
                            //     // console.log(total);
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
                            // console.log($(this).parents('tr').find("td:eq(2)").text());
                            // console.log(total+" "+val+" "+old);
                            $(".total").html("Total : "+total.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}));
                            // table.rows().every( function (rowIndex, tableLoop, rowLoop) {
                            //     var data = this.data();
                            //     var total =+ parseFloat((data['2'].substring(3, 10)).replace(/\D/g,''));
                            //     // var minus = Number(data['0'].replace(/[^0-9\.]+/g));
                            //     // console.log(total);
                            // });
                        }
                        // refresh item table;
                        console.log(pelanggan);
                        pelangganCari(pelanggan);
                    }
                });
            }else{
                return false;
            }
        });
    })

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
        // console.log("true");
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
            <form method="POST" enctype="multipart/form-data" action="{{route('admin-pembayaran-store')}}">
            @csrf
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <h5 class="font-weight-bold text-primary"><i class="fas fa-user"></i> List Pelanggan</h5>
                </div>
                <div class="form-group card-body">
                    <div id="myDIV" style="display: block">
                        <div class="row">
                            <div class="col">
                                <div class="table-responsive">
                                <table class="table table-bordered" id="dataTablePelanggan" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="vertical-align: middle; text-align: center;">NIK</th>
                                            <th style="vertical-align: middle; text-align: center;">Nama Pelanggan</th>
                                            <th style="vertical-align: middle; text-align: center;">No Telp</th>
                                            <th style="vertical-align: middle; text-align: center;" class="col-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pelanggan as $p)
                                        <tr>
                                            <td>{{$p->kependudukan->nik}}</td>
                                            <td>{{$p->kependudukan->nama}}</td>
                                            <td>{{$p->kependudukan->telepon}}</td>
                                            <td><a class="btn btn-success btn-sm text-white col mb-1 pelanggan" id="{{$p->id}}" onclick="pelangganCari({{$p->id}})"><i class="fas fa-search"></i> Cari</a></td>
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
                    <h5 class="font-weight-bold text-primary"><i class="fas fa-list"></i> List Retribusi & Request</h5>
                    <div class="col" align="end">
                        <a class="btn btn-warning btn-sm text-white" data-toggle="modal" data-target="#modal-ubah" ><i class="fas fa-edit"></i> Ubah</a>
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
                                            <th>Jenis </th>
                                            <th>Nominal </th>
                                            <th>Tanggal Buat</th>
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
                </div>
            </div>
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <div class="row">
                        <div class="col">
                            <h5 class="font-weight-bold text-primary"><i class="fas fa-pen"></i> Tambah Data Pembayaran</h5>
                        </div>
                    </div>
                </div>
                <div class="form-group card-body">
                    <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary"></h6>
                                <input type="text" id="id" name="id[]" hidden>
                                <input type="text" name="pelanggan" id="pelanggan" hidden>
                                <input type="text" id="pembayaran" name="pembayaran" value="{{isset($pembayaran) ? $pembayaran->id_pembayaran : '' }}" hidden>
                            </div>
                        </div>
                        <div class='col mb-2' hidden>
                            <!-- <input type="text" class="form-control @error('type') is-invalid @enderror" id="type-multi" name="type" placeholder="" value="retribusi" > -->
                        </div>
                        <div class="row">
                            <div class='col mb-2'>
                                <label for="file" class="font-weight-bold text-dark">Bukti Pembayaran</label>
                                <div class="d-flex justify-content-center">
                                    <img src="{{asset('assets/img/properti/blank.png') }}"  height="300px" style="object-fit:cover" class="mb-3" id="prop">
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
                                <option value="" >Pilih media Pembayaran</option>
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
                                <input type="number" class="form-control @error('nominal') is-invalid @enderror" id="nominal-multi" name="nominal" placeholder="Inputkan total yang diperlihatkan !" value=" old('nominal')}}" >
                                    @error('nominal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col" style="vertical-align: middle; text-align: left;">
                                <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Data Pembayaran Sudah Benar?')"><i class="fas fa-save"></i> Simpan</button>
                                <!-- <a class="btn btn-danger text-white"><i class="fas fa-times"></i> Batal</a> -->
                            </div>
                            <div class="col" style="vertical-align: middle; text-align: end;">
                                    <h1 class="total">Total : Rp {{isset($total) ? number_format($total ?? 0, 2, ',','.') : old('nominal')}}</h1>
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