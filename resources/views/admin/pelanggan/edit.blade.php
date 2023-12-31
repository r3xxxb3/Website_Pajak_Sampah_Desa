@extends('layouts.admin-master')

@section('title')
Edit Data Pelanggan
@endsection

@section('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
                integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
                crossorigin=""/>
    <style>
        #prop{height: 50px;}
        #map-add, #map-edit { height: 300px; }
        @media (min-width: 768px) {
            .modal-xl {
                width: 90%;
            max-width:1200px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>

<script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>

<script>

    var mymap = L.map('map-add').setView([-8.34321853375031, 115.08937012402842], 8);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: '&copy; ',
            maxZoom: 18,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoicjN4eHhiMyIsImEiOiJja2xnOG8xdjM0MWh0MnBucmMzenE5MGU1In0.dz8KwmycIrK1uH2dmrdGOg'
        }).addTo(mymap);
    mymap.invalidateSize();
        
    var map_edit = L.map('map-edit').setView([-8.34321853375031, 115.08937012402842], 8);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: '&copy; ',
                maxZoom: 18,
                id: 'mapbox/streets-v11',
                tileSize: 512,
                zoomOffset: -1,
                accessToken: 'pk.eyJ1IjoicjN4eHhiMyIsImEiOiJja2xnOG8xdjM0MWh0MnBucmMzenE5MGU1In0.dz8KwmycIrK1uH2dmrdGOg'
            }).addTo(map_edit);
    map_edit.invalidateSize();

    var markerGroup = L.layerGroup().addTo(mymap);
        
    var markerGroupEdit = L.layerGroup().addTo(map_edit);
     
    mymap.on('click', function(e) {
        
        markerGroup.clearLayers();
        
        $('#lat').val(e.latlng.lat);
        $('#lng').val(e.latlng.lng);
        
        L.marker([
            e.latlng.lat,e.latlng.lng
        ]).addTo(markerGroup);
          
    });

    map_edit.on('click', function(e) {
        
        markerGroupEdit.clearLayers();
    
        $('#lat_edit').val(e.latlng.lat);
        $('#lng_edit').val(e.latlng.lng);
    
        L.marker([
            e.latlng.lat,e.latlng.lng
        ]).addTo(markerGroupEdit);
    });
</script>

<script>
    function catchProp(properti, jenis, desa, banjar, standar){
        console.log(properti.alamat);
        // console.log(pelanggan);
        console.log(jenis);
        console.log(desa);
        console.log(banjar);
        // var markerGroupEdit = L.layerGroup().addTo(map_edit);

        markerGroupEdit.clearLayers();

        if(properti.lat != null && properti.lng != null){
            // console.log("true");
            L.marker([
                properti.lat, properti.lng
            ]).addTo(markerGroupEdit);
            $('#lat_edit').val(properti.lat);
            $('#lng_edit').val(properti.lng);
        }

        if(properti.file != null){
            $('#propic').attr('src', "{{asset('assets/img/properti/')}}"+"/"+properti.file);
        }else{
            $('#propic').attr('src', "{{asset('assets/img/properti/blank.png')}}");
        }
        $('#cancelation').attr('href', "/admin/properti/cancel/"+properti.id);
        $('#action-edit').attr('action', "/admin/properti/update/"+properti.id ); 
        $('#nama_edit').val(properti.nama_properti);
        $('#alamat_edit').val(properti.alamat);
        $('#lat_edit').val(properti.lat);
        $('#lng_edit').val(properti.lng);
        if(desa != null){
            $('#desa_edit').val(desa);
        }
        
        if(banjar != null){
            $('#banjar_edit').val(banjar);
        }
        $('#jenis_edit').val(properti.id_jenis);
        $('#standar_edit option').remove();
        
        if(standar != null){
            jQuery.each( standar ,function(i, val){
                console.log(val.id_desa_adat == properti.id_desa_adat);
                if(val.id_desa_adat == properti.id_desa_adat){
                    if(val.durasi != null){
                        $('#standar_edit').append('<option value='+val.id+'>'+val.nominal_retribusi.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2})+" / "+val.durasi+" Bulan"+'</option>');
                    }else{
                        $('#standar_edit').append('<option value='+val.id+'>'+val.nominal_retribusi.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2})+" / Bulan"+'</option>').val(val.id);
                    }
                    if(val.id == properti.id_standar_retribusi){
                        $('#standar_edit').val(val.id);
                    }
                }
            });
        }
        
        $('#file_edit').change(function(){
            readURL(properti.file);
        });
        
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
            $('#propic').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("#file_edit").change(function() {
        readURL(this);
    });
    
    function readURLAdd(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
            $('#propAdd').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }
    
    $("#file").change(function() {
        readURLAdd(this);
    });

    $(document).ready( function () {
        $('#dataTable').DataTable({
            "oLanguage":{
                "sSearch": "Cari:",
                "sZeroRecords": "Data tidak ditemukan",
                "sSearchPlaceholder": "Cari properti...",
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
        
        $('.impact_edit').on('change',  function(e){
                e.preventDefault();
                const jenis = $('#jenis_edit').val();
                const desa = $('#desa_edit').val();
                console.log(jenis);
                // console.log(jenis == "" );
                if(jenis == "" ){
                    $('#standar_edit').val('');
                    $('#standar_edit').find('option[value!=""]').remove().end();
                    $('#standar_edit').attr('disabled', true);
                }else{
                    $.ajax({
                        method : 'POST',
                        url : '/admin/standar/search',
                        data : {
                        "_token" : "{{ csrf_token() }}",
                        jenis : jenis,
                        desa : desa,
                        },
                        beforeSend : function() {
                                    
                        },
                        success : (res) => {
                            $('#standar_edit').attr('disabled', false);
                            $('#standar_edit option').remove();
                            $('#standar_edit').append('<option value="">'+"Pilih Standar Retribusi"+'</option>').val("");
                            $('#standar_edit').val(null);
                            if(res[0] == "success"){
                                jQuery.each(res[1], function(i, val){
                                    if(val.id_desa_adat == desa){
                                        if(val.durasi != null){
                                            $('#standar_edit').append('<option value='+val.id+'>'+val.nominal_retribusi.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2})+" / "+val.durasi+" Bulan"+'</option>');
                                        }else{
                                            $('#standar_edit').append('<option value='+val.id+'>'+val.nominal_retribusi.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2})+" / Bulan"+'</option>');
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            });
            
            $('.impact_add').on('change',  function(e){
                e.preventDefault();
                const jenis = $('#jenis_properti').val();
                const desa = $('#desa').val();
                console.log(jenis);
                // console.log(jenis == "" );
                if(jenis == "" ){
                    $('#standar_properti').val('');
                    $('#standar_properti').find('option[value!=""]').remove().end();
                    $('#standar_properti').attr('disabled', true);
                }else{
                    $.ajax({
                        method : 'POST',
                        url : '/admin/standar/search',
                        data : {
                        "_token" : "{{ csrf_token() }}",
                        jenis : jenis,
                        desa : desa,
                        },
                        beforeSend : function() {
                                    
                        },
                        success : (res) => {
                            $('#standar_properti').attr('disabled', false);
                            $('#standar_properti option').remove();
                            $('#standar_properti').append('<option value="">'+"Pilih Standar Retribusi"+'</option>').val("");
                            $('#standar_properti').val(null);
                            if(res[0] == "success"){
                                jQuery.each(res[1], function(i, val){
                                    if(val.id_desa_adat == desa){
                                        if(val.durasi != null){
                                            $('#standar_properti').append('<option value='+val.id+'>'+val.nominal_retribusi.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2})+" / "+val.durasi+" Bulan"+'</option>');
                                        }else{
                                            $('#standar_properti').append('<option value='+val.id+'>'+val.nominal_retribusi.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2})+" / Bulan"+'</option>');
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            });
        
        var focus = <?php echo isset($focus) ? $focus : "null" ;?>;
        <?php $foc = isset($focus) ? $focus : "null" ?>
        if(focus != null){
            var properti = <?php echo $index->filter(function ($properti) use ($foc){ if($properti->id ==  $foc){$properti->detailJasa = $properti->jasa; return $properti;}})->values() ?>;
            console.log(properti[0]);
            catchProp(properti[0], properti[0].detailJasa, properti[0].id_desa_adat, properti[0].id_banjar_adat);
            $('#modal-edit').modal('show');
        }

    } );
</script>

<script>
    $('#desa_edit').on('change', function(e){
        e.preventDefault();
        const desa = $('#desa_edit').val();
        console.log(desa);

        $.ajax({
            method : 'POST',
            url : '/admin/banjar/search',
            data : {
            "_token" : "{{ csrf_token() }}",
            desa : desa,
            },
            beforeSend : function() {
                        
            },
            success : (res) => {
                if(res.status == "success"){
                    $('#banjar_edit').removeAttr('disabled');
                    $('#banjar_edit option').remove();
                    // console.log(res);
                    select = document.getElementById('banjar_edit');
                    $.each(res.banjar, function(k, v){
                        // console.log(v.nama_banjar_adat);
                        var opt = document.createElement('option');
                        opt.value = v.id;
                        opt.innerHTML = v.nama_banjar_adat;
                        select.appendChild(opt);
                    })
                }else{
                    $('#banjar_edit option').remove();
                    select = document.getElementById('banjar_edit');
                    var opt = document.createElement('option');
                    opt.innerHTML = res.status;
                    console.log(opt);
                    select.appendChild(opt);
                    $('#banjar_edit').attr('disabled');
                }
            }
        }).done(()=>{})
    });
    
    $('#desa').on('change', function(e){
        e.preventDefault();
        const desa = $('#desa').val();
        console.log(desa);

        $.ajax({
            method : 'POST',
            url : '/admin/banjar/search',
            data : {
            "_token" : "{{ csrf_token() }}",
            desa : desa,
            },
            beforeSend : function() {
                        
            },
            success : (res) => {
                if(res.status == "success"){
                    $('#banjar').removeAttr('disabled');
                    $('#banjar option').remove();
                    // console.log(res);
                    select = document.getElementById('banjar');
                    $.each(res.banjar, function(k, v){
                        // console.log(v.nama_banjar_adat);
                        var opt = document.createElement('option');
                        opt.value = v.id;
                        opt.innerHTML = v.nama_banjar_adat;
                        select.appendChild(opt);
                    })
                }else{
                    $('#banjar option').remove();
                    select = document.getElementById('banjar');
                    var opt = document.createElement('option');
                    opt.innerHTML = res.status;
                    console.log(opt);
                    select.appendChild(opt);
                    $('#banjar').attr('disabled');
                }
            }
        }).done(()=>{})
    });
</script>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Data Pelanggan</h1>
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
            <form method="POST" enctype="multipart/form-data" action="{{route('pelanggan-update', $pelanggan->id)}}">
            @csrf
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <div class="row">
                        <div class="col">
                            <h3 class="font-weight-bold text-primary"><i class="fas fa-pen"></i>Edit Data Pelanggan</h3>
                        </div>
                    </div>
                </div>
                <div class="form-group card-body">    
                    <div class="row">
                        <!-- <div class='col mb-2'>
                            <label for="kk" class="font-weight-bold text-dark">No Kartu Keluarga</label>
                            <input type="text" class="form-control @error('kk') is-invalid @enderror" id="kk" name="kk" placeholder="Masukan No Kartu Keluarga">
                                @error('kk')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div> -->
                        <div class="col mb-2">
                            <label for="nik" class="font-weight-bold text-dark">NIK<i class="text-danger text-sm text-bold">*</i></label>
                            <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" placeholder="Masukan No Induk Kependudukan" value="{{isset($pelanggan->kependudukan) ? $pelanggan->kependudukan->nik : old(nik)}}" disabled>
                                @error('nik')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="nama" class="font-weight-bold text-dark">Nama Lengkap<i class="text-danger text-sm text-bold">*</i></label>
                            <input type="nama" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" placeholder="Masukan Nama Lengkap Pengguna" value="{{isset($pelanggan->kependudukan) ? $pelanggan->kependudukan->nama : old(nama) }}" disabled>
                                @error('nama')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class='col mb-2'>
                            <label for="jenis" class="font-weight-bold text-dark">Jenis Kelamin<i class="text-danger text-sm text-bold">*</i></label>
                            <select class="form-control @error('jenis') is-invalid @enderror" id="jenis" name="jenis" disabled>
                                <option value="" selected>Pilih Jenis Kelamin</option>
                                    <option value="Pria" {{isset($pelanggan->kependudukan) ? ($pelanggan->kependudukan->jenis_kelamin == 'laki-laki' ? 'selected' : '') : ''}}>Laki-laki</option>
                                    <option value="Wanita" {{isset($pelanggan->kependudukan) ? ($pelanggan->kependudukan->jenis_kelamin == 'perempuan' ? 'selected' : '') : ''}}>perempuan</option>
                            </select>
                                @error('jenis')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="tanggal" class="font-weight-bold text-dark">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" placeholder="Masukan Tanggal Lahir" value="{{isset($pelanggan->kependudukan) ? $pelanggan->kependudukan->tanggal_lahir : ''}}" disabled>
                                @error('tanggal')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="alamat" class="font-weight-bold text-dark">Alamat</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror"  id="alamat" name="alamat" placeholder="Masukan Alamat Penggunan" value="{{isset($pelanggan->kependudukan) ? $pelanggan->kependudukan->alamat : ''}}" disabled>
                                @error('alamat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <!-- ganti desa -->
                        <!-- <div class='col mb-2'>
                            <label for="banjar" class="font-weight-bold text-dark">Banjar</label>
                            <input type="text" class="form-control @error('banjar') is-invalid @enderror" id="banjar" name="banjar" placeholder="Masukan Banjar (Tempat Tinggal)" value="{{isset($pelanggan->banjar) ? $pelanggan->banjar->nama_banjar_dinas : '' }}">
                                @error('banjar')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div> -->
                        <div class='col mb-2'>
                            <label for="no" class="font-weight-bold text-dark">No Telpon</label>
                            <input type="text" class="form-control @error('no') is-invalid @enderror" id="no" name="no" placeholder="Masukan No Telpon Aktif" value="{{isset($pelanggan->kependudukan) ? $pelanggan->kependudukan->telepon : ''}}" disabled>
                                @error('no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                    </div>

                    <!-- <div class="row mb-3">
                        <div class='col mb-2'>
                            <label for="file_kk" class="font-weight-bold text-dark">File Kartu Keluarga</label>
                            <input type="file" class="form-control @error('file_kk') is-invalid @enderror" id="file_kk" name="file_kk" placeholder="Upload File Kartu Keluarga">
                                @error('file_kk')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                    </div> -->

                    <!-- <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Menambah Data?')"><i class="fas fa-save"></i> Simpan</button>
                            <a href="{{route('pelanggan-index')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </div> -->
                </div>
            </div>
            </form>
        </div>

        <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List Properti</h6>
                </div>
                <div class="card-body">
                @if (Session::has('error-1'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-times"></i> 
                        {{ Session::get('error-1') }}
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
                    @if (Session::has('success-1'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check"></i> {{Session::get('success-1')}}
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
                <a class= "btn btn-success text-white mb-2" data-toggle="modal" data-target="#modal-add"><i class="fas fa-plus"></i> Tambah Properti</a>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="table-primary">
                            <th>Nama Properti</th>
                            <th>Jenis Properti</th>
                            <th>Alamat </th>
                            <th>Foto </th>
                            <th>Status</th>
                            <th class="col-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($index as $properti)
                        <tr>
                            <td style="vertical-align: middle; text-align: center;">
                                {{$properti->nama_properti}}
                            </td>
                            <td style="vertical-align: middle; text-align: center;">
                                {{isset($properti->jasa) ? $properti->jasa->jenis_jasa : ''}}
                            </td>
                            <td style="vertical-align: middle; text-align: center;">
                                {{$properti->alamat}}
                            </td>
                            <td style="vertical-align: middle; text-align: center;">
                                @if(!isset($properti->file))
                                <img src="{{asset('assets/img/properti/blank.png')}}"  height="300px" style="object-fit:cover" class="mb-3" id="prop">
                                @else
                                <img src="{{asset('assets/img/properti/'.$properti->file)}}"  height="300px" style="object-fit:cover" class="mb-3" id="prop">
                                @endif
                                <!-- {{$properti->file}} -->
                            </td>
                            <td style="vertical-align: middle; text-align: center;">
                                @if($properti->status == 'terverifikasi')
                                <span class="badge badge-success">{{$properti->status}}</span>
                                @elseif($properti->status == 'pending')
                                <span class="badge badge-warning">{{$properti->status}}</span>
                                @else
                                <span class="badge badge-danger">{{$properti->status}}</span>
                                @endif
                            </td>
                            <td style="vertical-align: middle; text-align: center;">
                                <a  data-toggle="modal" data-target="#modal-edit" class="btn btn-warning btn-sm text-white col mb-1" onClick="catchProp({{$properti}}, {{$properti->jasa}}, {{isset($properti->id_desa_adat) ? $properti->id_desa_adat : 'null'}}, {{isset($properti->id_banjar_adat) ? $properti->id_banjar_adat : 'null'}}, {{$properti->jasa->standar}})"><i class="fas fa-pencil-alt"></i> Ubah</a>
                                <a style="margin-right:7px" class="btn btn-danger btn-sm col " href="{{Route('admin-properti-delete', $properti->id)}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-trash"></i> Hapus</a>
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
</section>

<div class="modal fade" id="modal-add">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary">Tambah Properti Pelanggan</h6>
                            </div>
                        </div>
                        <form method="POST" enctype="multipart/form-data" action="{{route('admin-properti-store')}}">
                        @csrf
                            <div class="row">
                                <div class="col mb-2">
                                    <label for="map-add" class="font-weight-bold text-dark">Pilih Titik Koordinat</label>
                                    <div class="" id="map-add" stle="object-fit:cover"></div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <input type="text" class="form-control @error('lat') is-invalid @enderror" id="lat" name="lat" placeholder="Latitude" value="{{old('lat')}}">
                                            @error('lat')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>    
                                            @enderror
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control @error('lng') is-invalid @enderror" id="lng" name="lng" placeholder="Longitude" value="{{old('lng')}}">
                                            @error('lng')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>    
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="file" class="font-weight-bold text-dark">Upload Gambar Properti</label>
                                    <div class="col-12 d-flex justify-content-center">
                                        <img src="{{asset('assets/img/properti/blank.png')}}"  height="300px" style="object-fit:cover" class="mb-3" id="propAdd">
                                    </div>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept="image/png, image/jpeg, image/jpg" placeholder="Masukan file gambar properti" value="{{old('file')}}">
                                        @error('file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class='col mb-2'>
                                    <label for="nama_properti" class="font-weight-bold text-dark">Nama Properti</label>
                                    <input type="text" class="form-control @error('jenis') is-invalid @enderror" id="nama_properti" name="nama_properti" placeholder="Masukan Nama Properti (cth: rumah tinggal,.. etc)" value="{{old('nama_add')}}">
                                    <input type="text" class="form-control @error('pelanggan') is-invalid @enderror" id="pelanggan" name="pelanggan" placeholder="id" value="{{$pelanggan->id}}" hidden>
                                        @error('nama_properti')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                </div>
                                <div class="col mb-2">
                                    <label for="jenis_properti" class="font-weight-bold text-dark">Jenis Properti<i class="text-danger text-sm text-bold">*</i></label>
                                    <select class="form-control @error('jenis') is-invalid @enderror impact_add" id="jenis_properti" name="jenis_properti">
                                        <option value="" >Pilih Jenis Properti</option>
                                            @foreach($jenis as $j)
                                                <option value="{{$j->id}}" {{old('jenis') == $j->id ? 'selected' : ''}}>{{$j->jenis_jasa}}</option>
                                            @endforeach
                                    </select>
                                        @error('jenis_properti')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                                <div class="col mb-2">
                                    <label for="standar_properti" class="font-weight-bold text-dark">Standar Retribusi<i class="text-danger text-sm text-bold">*</i></label>
                                    <select class="form-control @error('standar') is-invalid @enderror" id="standar_properti" name="standar_properti">
                                        <option value="" selected>Pilih Jenis Jasa Terlebih Dahulu !</option>
                                    </select>
                                        @error('standar_properti')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                                <div class="col mb-2" id="additional" hidden>
                                    <label for="kamar" class="font-weight-bold text-dark">Jumlah Kamar</label>
                                    <input type="number" class="form-control @error('deskripsi') is-invalid @enderror" id="kamar" name="kamar" placeholder="Masukan Jumlah Kamar terisi (Kos)" value="{{old('kamar')}}">
                                        @error('kamar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class='col mb-2'>
                                        <label for="desa" class="font-weight-bold text-dark">Desa Adat</label>
                                        <select class="form-control @error('desa') is-invalid @enderror impact_add" id="desa" name="desa" >
                                            <option value="">Pilih Desa Adat</option>
                                                @foreach($desaAdat as $d)
                                                    <option value="{{$d->id}}" >{{$d->desadat_nama}}</option>
                                                @endforeach
                                        </select>
                                            @error('desa')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                    </div>
                                <div class="col">
                                    <label for="banjar" class="font-weight-bold text-dark">Banjar Adat</label>
                                        <select class="form-control @error('banjar') is-invalid @enderror" id="banjar" name="banjar" >
                                            <option value="" selected>Pilih Desa Adat terlebih dahulu !</option>
                                                @foreach($banjarAdat as $b)
                                                    <option value="{{$b->id}}">{{$b->nama_banjar_adat}}</option>
                                                @endforeach
                                        </select>
                                        @error('banjar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="alamat_properti" class="font-weight-bold text-dark">Alamat Properti</label>
                                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat_properti" name="alamat_properti" placeholder="Masukan Alamat Properti" value="{{old('alamat_properti')}}">
                                        @error('alamat_properti')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Mengubah Data?')"><i class="fas fa-save"></i> Simpan</button>
                                    <a data-dismiss="modal" class="btn btn-danger text-white"><i class="fas fa-times"></i> Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary">Edit Properti Pelanggan</h6>
                            </div>
                        </div>
                        <form method="POST" id="action-edit" enctype="multipart/form-data" action="">
                        @csrf
                            <div class="row">
                                <div class="col mb-2">
                                    <label for="map-edit" class="font-weight-bold text-dark">Pilih Titik Koordinat</label>
                                    <div class="" id="map-edit"></div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <input type="text" class="form-control @error('lat') is-invalid @enderror" id="lat_edit" name="lat_edit" placeholder="Latitude" value="">
                                            @error('lat_edit')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>    
                                            @enderror
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control @error('lng') is-invalid @enderror" id="lng_edit" name="lng_edit" placeholder="Longitude" value="">
                                            @error('lng_edit')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>    
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="file_edit" class="font-weight-bold text-dark">Upload Gambar Properti</label>
                                    <div class="col-12 d-flex justify-content-center">
                                        <img src="{{asset('assets/img/properti/blank.png')}}" id="propic" height="300px" style="object-fit:cover" class="mb-3" >
                                    </div>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file_edit" name="file_edit" accept="image/png, image/jpeg, image/jpg" placeholder="Masukan file gambar properti" value="{{old('file')}}">
                                        @error('file_edit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>      
                            <div class="row">
                                <div class='col mb-2'>
                                    <label for="nama_edit" class="font-weight-bold text-dark">Nama Properti</label>
                                    <input type="text" class="form-control @error('jenis') is-invalid @enderror" id="nama_edit" name="nama_edit" placeholder="Masukan Nama Properti (cth: rumah tinggal,.. etc)" value="" >
                                        @error('nama_edit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                </div>
                                <div class="col mb-2">
                                    <label for="jenis_edit" class="font-weight-bold text-dark">Jenis Properti<i class="text-danger text-sm text-bold">*</i></label>
                                    <select class="form-control @error('jenis') is-invalid @enderror impact_edit" id="jenis_edit" name="jenis_edit">
                                        <option value="" selected>Pilih Jenis Properti</option>
                                            @foreach($jenis as $j)
                                                <option value="{{$j->id}}" >{{$j->jenis_jasa}}</option>
                                            @endforeach
                                    </select>
                                        @error('jenis_edit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                                <div class="col mb-2">
                                    <label for="standar_edit" class="font-weight-bold text-dark">Standar Retribusi<i class="text-danger text-sm text-bold">*</i></label>
                                    <select class="form-control @error('standar') is-invalid @enderror" id="standar_edit" name="standar_edit">
                                        <option value="" selected>Pilih Jenis Jasa Terlebih Dahulu !</option>
                                    </select>
                                        @error('standar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                                @if(isset($properti) && $properti->jasa->jenis_jasa == "Kos-kosan")
                                <div class="col mb-2" id="additional" >
                                    <label for="kamar_edit" class="font-weight-bold text-dark">Jumlah Kamar</label>
                                    <input type="number" class="form-control @error('kamar') is-invalid @enderror" id="kamar_edit" name="kamar_edit" placeholder="Masukan Jumlah Kamar terisi (Kos)" value="" disabled>
                                        @error('kamar_edit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class='col mb-2'>
                                        <label for="desa_edit" class="font-weight-bold text-dark">Desa Adat</label>
                                        <select class="form-control @error('desa_edit') is-invalid @enderror impact_edit" id="desa_edit" name="desa_edit" >
                                            <option value="">Pilih Desa Adat</option>
                                                @foreach($desaAdat as $d)
                                                    <option value="{{$d->id}}" >{{$d->desadat_nama}}</option>
                                                @endforeach
                                        </select>
                                            @error('desa')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                </div>
                                <div class="col">
                                    <label for="banjar_edit" class="font-weight-bold text-dark">Banjar Adat</label>
                                        <select class="form-control @error('banjar_edit') is-invalid @enderror" id="banjar_edit" name="banjar_edit" >
                                            <option value="" selected>Pilih Desa Adat terlebih dahulu !</option>
                                                @foreach($banjarAdat as $b)
                                                    <option value="{{$b->id}}">{{$b->nama_banjar_adat}}</option>
                                                @endforeach
                                        </select>
                                        @error('banjar_edit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="alamat" class="font-weight-bold text-dark">Alamat Properti<i class="text-danger text-sm text-bold">*</i></label>
                                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat_edit" name="alamat_edit" placeholder="Masukan Alamat Properti" value="" >
                                        @error('alamat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Mengubah Data?')"><i class="fas fa-save"></i> Verifikasi</button>
                                    <a href="" id="cancelation" class="btn btn-warning" onclick="return confirm('Apakah Anda Yakin Ingin Membatalkan Properti ?')"><i class="fa fa-exclamation-circle"></i> Batalkan Properti</a>
                                    <a data-dismiss="modal" class="btn btn-danger text-white"><i class="fas fa-times"></i> Close</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</div>


@endsection