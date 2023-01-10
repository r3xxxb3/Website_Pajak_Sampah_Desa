@extends('layouts.admin-master')

@section('title')
Dashboard
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Dashboard</h1>
  </div>
  <!-- {{Auth::guard('admin')->user()}} -->
  <div class="section-body">
    <div class="row">
        <div class="col">
            <div class="card card-statistic-2">
                <div class="card-icon shadow-primary bg-primary">
                    <i class="fas fa-user"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                      <h4 class="font-weight-bold text-dark">Jumlah Pelanggan</h4>
                  </div>
                  <div class="card-body">
                    {{isset($pengguna) ? $pengguna : '0'}}
                  </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-statistic-2">
              <div class="card-icon shadow-primary bg-primary">
                <i class="fas fa-building"></i>
              </div>
                <div class="card-wrap">
                  <div class="card-header">
                      <h4 class="font-weight-bold text-dark">Properti Terdaftar</h4>
                  </div>
                  <div class="card-body">
                    {{isset($properti) ? $properti : '0'}}
                  </div>
                </div>
            </div>
        </div>
        <div class="col">
          <div class="card card-statistic-2">
          <div class="card-icon shadow-primary bg-primary">
                    <i class="fas fa-user-cog"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                      <h4 class="font-weight-bold text-dark">Jumlah Pegawai</h4>
                  </div>
                  <div class="card-body">
                    {{isset($pegawai) ? $pegawai : '0'}}
                  </div>
                </div>
          </div>
        </div>
    </div>
    <div class='card shadow mt-2'>
        <div class="card-header py-3">
            <h3 class="font-weight-bold text-dark">Daftar Properti Pending</h2>
        </div>
        <div class="card-body">
          <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                      <tr class="table-primary">
                          <th class="col-2">Action</th>
                          <th>Nama Properti</th>
                          <th>Pemilik Properti</th>
                          <th>Jenis Properti</th>
                          <th>Alamat </th>
                          <th>Status</th>
                      </tr>
                  </thead>
                  <tbody>
                  @foreach ($indexPr as $prop)
                      <tr>
                          <td align="center">
                                <a  data-toggle="modal" data-target="#modal-edit" class="btn btn-info btn-sm text-white" onClick="catchProp({{$prop}}, {{$prop->jasa}}, {{$prop->id_desa_adat}}, {{$prop->id_banjar_adat }})"><i class="fas fa-pencil-alt"></i></a>
                                <a style="margin-right:7px" class="btn btn-danger btn-sm" href="{{Route('admin-properti-delete', $prop->id)}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-trash"></i></a>
                          </td>
                          <td>
                              {{$prop->nama_properti}}
                          </td>
                          <td>
                              {{$prop->pelanggan->kependudukan->nama}}
                          </td>
                          <td>
                              {{isset($prop->jasa)? $prop->jasa->jenis_jasa : ''}}
                          </td>
                          <td>
                              {{$prop->alamat}}
                          </td>
                          <td>
                              @if($prop->status == 'terverifikasi')
                              <span class="badge badge-success">{{$prop->status}}</span>
                              @elseif($prop->status == 'pending')
                              <span class="badge badge-warning">{{$prop->status}}</span>
                              @else
                              <span class="badge badge-danger">{{$prop->status}}</span>
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

</section>
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
                                        <img src="{{asset('assets/img/properti/blank.png')}}" id="propic" height="300px" style="object-fit:cover" class="mb-3" id="prop">
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
                                    <input type="text" class="form-control @error('jenis') is-invalid @enderror" id="nama_edit" name="nama_edit" placeholder="Masukan Nama Properti (cth: rumah tinggal,.. etc)" value="" disabled>
                                        @error('nama_edit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                </div>
                                <div class="col mb-2">
                                    <label for="jenis_edit" class="font-weight-bold text-dark">Jenis Properti</label>
                                    <select class="form-control @error('jenis') is-invalid @enderror" id="jenis_edit" name="jenis_edit">
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
                            </div>
                            <div class="row">
                                <div class='col mb-2'>
                                        <label for="desa_edit" class="font-weight-bold text-dark">Desa Adat</label>
                                        <select class="form-control @error('desa_edit') is-invalid @enderror" id="desa_edit" name="desa_edit" >
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
                                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Masukan Alamat Properti" value="{{isset($prop->alamat) ? $prop->alamat : old('alamat')}}" >
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

@section('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
                integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
                crossorigin=""/>
    <style>
        #prop{height: 50px;}
        #map-edit { height: 300px;}
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
    var map_edit = L.map('map-edit').setView([-8.34321853375031, 115.08937012402842], 8);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: '&copy; ',
                maxZoom: 18,
                id: 'mapbox/streets-v11',
                tileSize: 512,
                zoomOffset: -1,
                accessToken: 'pk.eyJ1IjoicjN4eHhiMyIsImEiOiJja2xnOG8xdjM0MWh0MnBucmMzenE5MGU1In0.dz8KwmycIrK1uH2dmrdGOg'
            }).addTo(map_edit);
   
        
    var markerGroupEdit = L.layerGroup().addTo(map_edit);

    map_edit.on('click', function(e) {
        
        markerGroupEdit.clearLayers();
    
        $('#lat_edit').val(e.latlng.lat);
        $('#lng_edit').val(e.latlng.lng);
    
        L.marker([
            e.latlng.lat,e.latlng.lng
        ]).addTo(markerGroupEdit);
    });

    
    function catchProp(properti, jenis, desa, banjar){
      console.log(properti);
      // console.log(pelanggan);
      console.log(jenis);
      console.log(desa);
      console.log(banjar);
      // var markerGroupEdit = L.layerGroup().addTo(map_edit);
  
      markerGroupEdit.clearLayers();
  
      if(properti.lat != null && properti.lng != null){
          console.log("true");
          
  
          L.marker([
              properti.lat, properti.lng
          ]).addTo(markerGroupEdit);
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
      $('#desa_edit').val(desa);
      $('#banjar_edit').val(banjar);
      $('#jenis_edit').val(properti.id_jenis);
      $('#lat_edit').val(properti.lat);
      $('#lng_edit').val(properti.lng);
      $('#file_edit').change(function(){
          readURL(properti.file);
      });
    }

    $('#modal-edit').on('show.bs.modal', function(){
      setTimeout(function() {
        map_edit.invalidateSize(true);
      }, 1);
    })
</script>


<script>
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
</script>


<script>
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
    });
</script>

@endsection
