@extends('layouts.user-master')

@section('title')
Tambah Properti
@endsection

@section('style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
            integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
            crossorigin=""/>
<style>
    #map { height: 300px; }
</style>
<link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
            integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
            crossorigin=""></script>
<script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>

<script>
var mymap = L.map('map').setView([-8.34321853375031, 115.08937012402842], 8);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: '&copy; SIG Desa 2021',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoicjN4eHhiMyIsImEiOiJja2xnOG8xdjM0MWh0MnBucmMzenE5MGU1In0.dz8KwmycIrK1uH2dmrdGOg'
    }).addTo(mymap);

    var markerGroup = L.layerGroup().addTo(mymap);
</script>

<script>
// var xlng = 0.000256;
// var xlat = 0.000200;
mymap.on('click', function(e) {

    
    markerGroup.clearLayers();

    // console.log(e.latlng.lat,e.latlng.lng);
    $('#lat').val(e.latlng.lat);
    $('#lng').val(e.latlng.lng);

    //var c = L.circle([e.latlng.lat,e.latlng.lng], {radius: 15}).addTo(map);
    //   L.polygon([
    //     [e.latlng.lat-xlat,e.latlng.lng-xlng],
    //     [e.latlng.lat+xlat,e.latlng.lng-xlng],
    //     [e.latlng.lat-xlat,e.latlng.lng+xlng],
    //     [e.latlng.lat+xlat,e.latlng.lng+xlng],
    //   ]).addTo(mymap);
    
    //   L.polyline([
    //     [e.latlng.lat,e.latlng.lng-xlng],
    //     [e.latlng.lat,e.latlng.lng+xlng]
    //   ]).addTo(mymap);

    L.marker([
        e.latlng.lat,e.latlng.lng
    ]).addTo(markerGroup);
  
});
</script>

<script>
  function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#prop').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

$("#file").change(function() {
  readURL(this);
});
</script>

<script>
    $('#desa').on('change', function(e){
        e.preventDefault();
        const desa = $('#desa').val();
        console.log(desa);

        $.ajax({
            method : 'POST',
            url : '/user/banjar/search',
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
                    opt.value = null;
                    opt.innerHTML = res.status;
                    select.appendChild(opt);
                    $('#banjar').attr('disabled');
                }
            }
        }).done(()=>{})
    });
    
     $('.impact').on('change',  function(e){
        e.preventDefault();
        const jenis = $('#jenis').val();
        const desa = $('#desa').val();
        console.log(jenis);
        // console.log(jenis == "" );
        if(jenis == "" ){
            $('#standar').val('');
            $('#standar').find('option[value!=""]').remove().end();
            $('#standar').attr('disabled', true);
        }else{
            $.ajax({
                method : 'POST',
                url : '/user/standar/search',
                data : {
                "_token" : "{{ csrf_token() }}",
                jenis : jenis,
                desa : desa,
                },
                beforeSend : function() {
                            
                },
                success : (res) => {
                    $('#standar').attr('disabled', false);
                    $('#standar option').remove();
                    $('#standar').append('<option value="">'+"Pilih Standar Retribusi"+'</option>').val("");
                    $('#standar').val(null);
                    if(res[0] == "success"){
                        jQuery.each(res[1], function(i, val){
                            if(val.id_desa_adat == desa){
                                if(val.durasi != null){
                                    $('#standar').append('<option value='+val.id+'>'+val.nominal_retribusi.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2})+" / "+val.durasi+" Bulan"+'</option>');
                                }else{
                                    $('#standar').append('<option value='+val.id+'>'+val.nominal_retribusi.toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2})+" / Bulan"+'</option>');
                                }
                            }
                        });
                    }
                }
            });
        }
    });
</script>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Properti</h1>
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
            <form method="POST" enctype="multipart/form-data" action="{{route('properti-store')}}">
            @csrf
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <div class="row">
                        <div class="col">
                            <h3 class="font-weight-bold text-primary"><i class="fas fa-plus"></i>Tambah Properti</h3>
                        </div>
                    </div>
                </div>
                <div class="form-group card-body">
                    <div class="row">
                        <div class="col mb-2">
                            <label for="map" class="font-weight-bold text-dark">Pilih Titik Koordinat</label>
                            <div class="" id="map"></div>
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
                                <img src="{{asset('assets/img/properti/blank.png')}}"  height="300px" style="object-fit:cover" class="mb-3" id="prop">
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
                            <label for="nama" class="font-weight-bold text-dark">Nama Properti<i class="text-danger text-sm text-bold">*</i></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" placeholder="Masukan Nama Properti (cth: rumah tinggal,.. etc)" value="{{old('nama')}}">
                                @error('nama')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="jenis" class="font-weight-bold text-dark">Jenis Properti<i class="text-danger text-sm text-bold">*</i></label>
                            <select class="form-control @error('jenis') is-invalid @enderror impact" id="jenis" name="jenis">
                                <option value="" selected>Pilih Jenis Properti</option>
                                    @foreach($jenis as $j)
                                        <option value="{{$j->id}}" {{old('jenis') == $j->id ? 'selected' : ''}}>{{$j->jenis_jasa}}</option>
                                    @endforeach
                            </select>
                                @error('jenis')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="standar" class="font-weight-bold text-dark">Standar Retribusi<i class="text-danger text-sm text-bold">*</i></label>
                            <select class="form-control @error('standar') is-invalid @enderror" id="standar" name="standar">
                                <option value="" selected>Pilih Jenis Jasa Terlebih Dahulu !</option>
                            </select>
                                @error('standar')
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
                    <div class="row mb-2">
                        <div class="col">
                            <label for="desaAdat" class="font-weight-bold text-dark">Desa Adat<i class="text-danger text-sm text-bold">*</i></label>
                                <select type="text" class="form-control @error('desaAdat') is-invalid @enderror impact"  id="desa" name="desa" placeholder="Pilih Desa Adat dari Properti" value="{{old('desaAdat')}}">
                                    <option value="">Pilih Desa Adat !</option>
                                    @if($desaAdat != [])
                                        @foreach($desaAdat as $d)
                                            <option value="{{$d->id}}" {{isset(auth()->guard('web')->user()->kependudukan->mipil->banjarAdat) ? (auth()->guard('web')->user()->kependudukan->mipil->banjarAdat->desaAdat->id == $d->id ? 'selected' : '') : (isset(auth()->guard('web')->user()->kependudukan->kTamiu->banjarAdat) ? (auth()->guard('web')->user()->kependudukan->kTamiu->banjarAdat->desaAdat->id == $d->id ? 'selected' : '') : (isset(auth()->guard('web')->user()->kependudukan->tamiu->banjarAdat) ? (auth()->guard('web')->user()->kependudukan->tamiu->banjarAdat->desaAdat->id == $d->id ? 'selected' : '') : '')) }}>{{$d->desadat_nama}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                    @error('desaAdat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                        </div>
                        <div class="col">
                            <label for="banjar" class="font-weight-bold text-dark">Banjar Adat</label>
                                <select class="form-control @error('banjar') is-invalid @enderror" id="banjar" name="banjar" disabled>
                                    <option value="" selected>Pilih Desa Adat terlebih dahulu !</option>
                                </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="alamat" class="font-weight-bold text-dark">Alamat Properti<i class="text-danger text-sm text-bold">*</i></label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Masukan Alamat Properti" value="{{old('alamat')}}">
                                @error('alamat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Menambah Data?')"><i class="fas fa-save"></i> Simpan</button>
                            <a href="{{route('properti-index')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</section>
@endsection