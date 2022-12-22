@extends('layouts.user-master')

@section('title')
Edit Properti
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
$(document).ready(function() {
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

    @if(isset($properti->lat) && isset($properti->lng))
    const lat = <?php echo $properti->lat?>;
    const lng = <?php echo $properti->lng?>;
    
    L.marker([
        lat, lng
    ]).addTo(markerGroup);
    @endif
    
    mymap.on('click', function(e) {
        
        markerGroup.clearLayers();
    
        $('#lat').val(e.latlng.lat);
        $('#lng').val(e.latlng.lng);
    
        L.marker([
            e.latlng.lat,e.latlng.lng
        ]).addTo(markerGroup);
    
    });

})


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
                    $('#banjar').attr('disabled');
                    $('#banjar option').remove();
                    select = document.getElementById('banjar');
                    var opt = document.createElement('option');
                        opt.value = null;
                        opt.innerHTML = res.status;
                        select.appendChild(opt);
                }
            }
        }).done(()=>{})
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
            <form method="POST" enctype="multipart/form-data" action="{{route('properti-update', $properti->id)}}">
            @csrf
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <div class="row">
                        <div class="col">
                            <h3 class="font-weight-bold text-primary"><i class="fas fa-plus"></i>Edit Properti</h3>
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
                                    <input type="text" class="form-control @error('lat') is-invalid @enderror" id="lat" name="lat" placeholder="Latitude" value="{{isset($properti->lat) ? $properti->lat : old('lat')}}">
                                    @error('lat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>    
                                    @enderror
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control @error('lng') is-invalid @enderror" id="lng" name="lng" placeholder="Longitude" value="{{isset($properti->lng) ? $properti->lng : old('lng')}}">
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
                                @if(!isset($properti->file))
                                <img src="{{asset('assets/img/properti/blank.png')}}"  height="300px" style="object-fit:cover" class="mb-3" id="prop">
                                @else
                                <img src="{{asset('assets/img/properti/'.$properti->file)}}"  height="300px" style="object-fit:cover" class="mb-3" id="prop">
                                @endif
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
                            <input type="text" class="form-control @error('jenis') is-invalid @enderror" id="nama" name="nama" placeholder="Masukan Nama Properti (cth: rumah tinggal,.. etc)" value="{{isset($properti->nama_properti) ? $properti->nama_properti : old('nama')}}" >
                                @error('jenis')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="JENIS" class="font-weight-bold text-dark">Jenis Properti<i class="text-danger text-sm text-bold">*</i></label>
                            <select class="form-control @error('jenis') is-invalid @enderror" id="jenis" name="jenis">
                                <option value="" selected>Pilih Jenis Properti</option>
                                    @foreach($jenis as $j)
                                        <option value="{{$j->id}}" {{old('jenis') == $j->id || $properti->id_jenis == $j->id  ? 'selected' : ''}}>{{$j->jenis_jasa}}</option>
                                    @endforeach
                            </select>
                                @error('deskripsi')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        @if($properti->jasa->jenis_jasa == "Kos-kosan")
                        <div class="col mb-2" id="additional" >
                            <label for="kamar" class="font-weight-bold text-dark">Jumlah Kamar</label>
                            <input type="number" class="form-control @error('kamar') is-invalid @enderror" id="kamar" name="kamar" placeholder="Masukan Jumlah Kamar terisi (Kos)" value="{{isset($properti->jumlah_kamar) ? $properti->jumlah_kamar : old('kamar')}}" disabled>
                                @error('kamar')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class='col mb-2'>
                                <label for="desa" class="font-weight-bold text-dark">Desa Adat</label>
                                <select class="form-control @error('desa') is-invalid @enderror" id="desa" name="desa" >
                                    <option value="">Pilih Desa Adat</option>
                                        @foreach($desaAdat as $d)
                                            <option value="{{$d->id}}" {{isset($properti->id_desa_adat) ? ($properti->id_desa_adat == $d->id ? 'selected' : '')  : '' }}>{{$d->desadat_nama}}</option>
                                        @endforeach
                                </select>
                                <!-- <input type="text" class="form-control @error('desa') is-invalid @enderror" list="desadata" id="" name="" placeholder="Masukan desa adat (Tempat Tinggal)" value="" >
                                    <datalist id="desadata">
                                    </datalist> -->
                                    @error('desa')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>
                        <div class="col">
                            <label for="banjar" class="font-weight-bold text-dark">Banjar Adat</label>
                                <select class="form-control @error('banjar') is-invalid @enderror" id="banjar" name="banjar" {{isset($properti->id_banjar_adat) ? '' : 'disabled' }}>
                                    <option value="" selected>Pilih Desa Adat Terleih Dahulu !</option>
                                    @if(isset($properti->id_banjar_adat))
                                        @foreach($banjarAdat as $b)
                                            <option value="{{$b->id}}" {{isset($properti->id_banjar_adat) ? ($properti->banjarAdat->id == $b->id ? 'selected' : '') : '' }}>{{$b->nama_banjar_adat}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            
                            <!-- <input type="text" class="form-control @error('banjar') is-invalid @enderror" id="" name="" placeholder="Masukan Banjar Properti (opsional)" value="" >
                                <datalist id="banjardata">
                                </datalist>
                                @error('banjar')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="alamat" class="font-weight-bold text-dark">Alamat Properti<i class="text-danger text-sm text-bold">*</i></label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Masukan Alamat Properti" value="{{isset($properti->alamat) ? $properti->alamat : old('alamat')}}" >
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