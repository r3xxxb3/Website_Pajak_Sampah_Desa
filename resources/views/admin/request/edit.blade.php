@extends('layouts.admin-master')

@section('title')
Edit Request Pengangkutan
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

        @if(isset($requestP->lat) && isset($requestP->lng))
        const lat = <?php echo $requestP->lat?>;
        const lng = <?php echo $requestP->lng?>;
        
        L.marker([
            lat, lng
        ]).addTo(markerGroup);
        @endif

        // var xlng = 0.000256;
        // var xlat = 0.000200;
        mymap.on('click', function(e) {
            markerGroup.clearLayers();

            // console.log(e.latlng.lat,e.latlng.lng);
            @if($requestP->status == "Selesai" || $requestP->status == "Terkonfirmasi" )

            @else
                $('#lat').val(e.latlng.lat);
                $('#lng').val(e.latlng.lng);
            @endif

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
    $(document).ready( function(){

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
        
    })
</script>
@endsection

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Manajemen Request Pengangkutan</h1>
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
            <form method="POST" enctype="multipart/form-data" action="{{route('admin-request-update', $requestP->id)}}">
            @csrf
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <div class="row">
                        <div class="col">
                            @if($requestP->status == "Selesai")
                            <h3 class="font-weight-bold text-primary"><i class="fas fa-eye"></i> Lihat Request Pengangkutan</h3>
                            @else
                            <h3 class="font-weight-bold text-primary"><i class="fas fa-pencil-alt"></i> Edit Request Pengangkutan</h3>
                            @endif
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
                                    <input type="text" class="form-control @error('lat') is-invalid @enderror" id="lat" name="lat" placeholder="Latitude" value="{{isset($requestP->lat) ? $requestP->lat : old('lat')}}" {{$requestP->status == "Selesai" || $requestP->status == "Terkonfirmasi" ? 'disabled' : ''}}>
                                    @error('lat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>    
                                    @enderror
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control @error('lng') is-invalid @enderror" id="lng" name="lng" placeholder="Longitude" value="{{isset($requestP->lng) ? $requestP->lng : old('lng')}}" {{$requestP->status == "Selesai" || $requestP->status == "Terkonfirmasi" ? 'disabled' : ''}}>
                                    @error('lng')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>    
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <label for="file" class="font-weight-bold text-dark">Upload Gambar Lokasi Request</label>
                            <div class="col-12 d-flex justify-content-center">
                                @if(isset($requestP->file))
                                <img src="{{asset('/assets/img/request_p/'.$requestP->file)}}"  height="300px" style="object-fit:cover" class="mb-3" id="prop">
                                @else
                                <img src=""  height="300px" style="object-fit:cover" class="mb-3" id="prop">
                                @endif
                            </div>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept="image/png, image/jpeg, image/jpg" placeholder="Masukan file gambar Lokasi" value="{{isset($requestP->file) ? $requestP->file : old('file')}}" {{$requestP->status == "Selesai" || $requestP->status == "Terkonfirmasi" ? 'disabled' : ''}}>
                                @error('file')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-2">
                            <label for="desaAdat" class="font-weight-bold text-dark">Desa Adat</label>
                            <select class="form-control" name="desaAdat" id="desaAdat" {{$requestP->status == "Selesai" || $requestP->status == "Terkonfirmasi" ? 'disabled' : ''}}>
                                <option value="">Pilih Desa Adat Lokasi Pengangkutan !</option>
                                @foreach($desaAdat as $d)
                                    <option value="{{$d->id}}" {{isset($requestP->id_desa_adat) ? ($requestP->id_desa_adat == $d->id ? 'selected' : '') : ''}}>{{$d->desadat_nama}}</option>
                                @endforeach
                            </select>                            
                            @error('desaAdat')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>    
                            @enderror
                        </div>
                        <div class="col">
                            <label for="alamat" class="font-weight-bold text-dark">Alamat Lengkap</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Masukan Alamat Lengkap lokasi Request" value="{{isset($requestP->alamat) ? $requestP->alamat : old('alamat')}}" {{$requestP->status == "Selesai" || $requestP->status == "Terkonfirmasi" ? 'disabled' : ''}}>
                            
                            @error('alamat')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>    
                            @enderror
                        </div>
                    </div>
                    @if($requestP->status == "Selesai" || $requestP->status == "Terkonfirmasi" )
                    <div class="row mt-4">
                        <div class="col">
                            <a href="{{route('admin-request-index')}}" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                    @else
                    <div class="row mt-4">
                        <div class="col">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Mengubah Data?')"><i class="fas fa-save"></i> Simpan</button>
                            <a href="{{route('admin-request-index')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            </form>
        </div>
    </div>
</section>
@endsection