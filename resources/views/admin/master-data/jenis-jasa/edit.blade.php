@extends('layouts.admin-master')

@section('title')
Edit Jenis Jasa
@endsection

@section('style')

<style>
    /* The switch - the box around the slider */
    .switch {
     position: relative;
     display: inline-block;
     width: 60px;
     height: 34px;
   }
   
   /* Hide default HTML checkbox */
   .switch input {
     opacity: 0;
     width: 0;
     height: 0;
   }
   
   /* The slider */
   .slider {
     position: absolute;
     cursor: pointer;
     top: 0;
     left: 0;
     right: 0;
     bottom: 0;
     background-color: #ccc;
     -webkit-transition: .4s;
     transition: .4s;
   }
   
   .slider:before {
     position: absolute;
     content: "";
     height: 26px;
     width: 26px;
     left: 4px;
     bottom: 4px;
     background-color: white;
     -webkit-transition: .4s;
     transition: .4s;
   }
   
   input:checked + .slider {
     background-color: #2196F3;
   }
   
   input:focus + .slider {
     box-shadow: 0 0 1px #2196F3;
   }
   
   input:checked + .slider:before {
     -webkit-transform: translateX(26px);
     -ms-transform: translateX(26px);
     transform: translateX(26px);
   }
   
   /* Rounded sliders */
   .slider.round {
     border-radius: 34px;
   }
   
   .slider.round:before {
     border-radius: 50%;
   } 
   </style>

@endsection

@section('scripts')
<script>
    function setJenis( id ){
            $('#id').val(id);
            // console.log(id);
    };

</script>
<script>
//Switch Status Pengumuman
  function statusBtn(id) {
    var checkBox = document.getElementById("status_"+id);
    // If the checkbox is checked, display the output text

    if (checkBox.checked == true){
      swal({
          title: 'Anda yakin ingin mengaktifkan Standar Retribusi ini?',
          icon: 'warning',
          buttons: ["Tidak", "Ya"],
      }).then(function(value) { 
          if (value) {
            jQuery.ajax({  
              url: "/admin/masterdata/retribusi/"+id+"/active",
              type: "GET",
              success: function(result){
                // $(".statVal").each(function(){
                //     if(this.id == "status_"+id){
                // //         continue;
                //         // alert(this.id);
                //     }else{
                //         $(this).prop('checked', false);
                //     }
                // });
              }
          });
        }else{
            document.getElementById("status_"+id).checked = false;
        }
      });
    } else {
      swal({
          title: 'Anda yakin ingin menonaktifkan Standar Retribusi ini?',
          icon: 'warning',
          buttons: ["Tidak", "Ya"],
      }).then(function(value) {
          if (value) {
            jQuery.ajax({
              url: "/admin/masterdata/retribusi/"+id+"/not-active",
              type: "GET",
              success: function(result){
                // $(".statVal").each(function(){
                //     if(this.id == "status_"+id){
                // //         continue;
                //     // alert(this.id);
                //     }else{
                //         $(this).prop('checked', false);
                //     }
                // });
              }
          });
        }else{
            document.getElementById("status_"+id).checked = true;
        }
      });
    }

  }

</script>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Data Jenis Jasa</h1>
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
            <form method="POST" enctype="multipart/form-data" action="/admin/masterdata/jenis-jasa/update/{{$jenis->id}}">
            @csrf
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <div class="row">
                        <div class="col">
                            <h3 class="font-weight-bold text-primary"><i class="fas fa-cogs"></i> Edit Data Jenis Jasa</h3>
                        </div>
                    </div>
                </div>
                <div class="form-group card-body"> 
                    <div class="row mb-3">
                        <div class="col">
                            <label for="jenis" class="font-weight-bold text-dark">Jenis Jasa</label>
                            <input type="text" class="form-control @error('jenis') is-invalid @enderror" id="jenis" name="jenis" placeholder="Masukan Nama Jenis Jasa" value="{{$jenis->jenis_jasa}}">
                                @error('jenis')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>  
                                @enderror
                        </div>
                        <div class="col">
                        <label for="deskripsi" class="font-weight-bold text-dark">Deskripsi</label>
                            <input type="text" class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" placeholder="Masukan Deskripsi Jenis Jasa " value="{{$jenis->deskripsi}}">
                                @error('deskripsi')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>  
                                @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Mengubah Data?')"><i class="fas fa-save"></i> Update</button>
                            <a href="{{route('masterdata-jenisjasa-index')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>

        <div class="container-fluid">
            <!-- DataTales Example -->
            <!-- Copy drisini -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List Standar Retribusi</h6>
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
                <a class= "btn btn-success text-white mb-2" data-toggle="modal" data-target="#modal-add" onclick="setJenis(<?php echo $jenis->id ?>)"><i class="fas fa-plus"></i> Tambah Standar Retribusi</a>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="col-2">Action</th>
                            <th>Nominal</th>
                            <th>Durasi</th>
                            <th>Status Aktif</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($index as $retri)
                        <tr>
                            <td align="center">
                                <a  data-toggle="modal" data-target="#modal-{{$retri->id}}" class="btn btn-info btn-sm text-white" ><i class="fas fa-pencil-alt"></i></a>
                                <a style="margin-right:7px" class="btn btn-danger btn-sm" href="/admin/masterdata/retribusi/delete/{{$retri->id}}" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-trash"></i></a>
                            </td>
                            <td>
                                {{"Rp."." ".number_format($retri->nominal_retribusi)}}
                            </td>
                            <td>
                                {{$retri->durasi." "."Bulan"}}
                            </td>
                            <div>
                                <td style="width: fit-content;" class="text-center">
                                    <label class="switch"  >
                                        @if($retri->active == '1')
                                            <input class="statVal" type="checkbox" id="status_{{$retri->id}}" onclick="statusBtn(<?php echo $retri->id ?>)" checked>
                                        @else
                                            <input class="statVal" type="checkbox" id="status_{{$retri->id}}" onclick="statusBtn(<?php echo $retri->id ?>)">
                                        @endif
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </div>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary">Tambah Standar Retribusi</h6>
                            </div>
                        </div>
                        <form method="POST" enctype="multipart/form-data" action="{{route('masterdata-retribusi-store')}}">
                        @csrf   
                            <div class="row mb-2">
                                <input type="text" class="form-control @error('id') is-invalid @enderror" id="id" name="id" hidden>
                                <div class='col mb-2'>
                                    <label for="standar" class="font-weight-bold text-dark">Nominal Standar</label>
                                    <input type="text" class="form-control @error('standar') is-invalid @enderror" id="standar" name="standar" placeholder="Masukan Nominal Standar Retribusi">
                                        @error('standar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                </div>
                                <div class="col mb-2">
                                    <label for="durasi" class="font-weight-bold text-dark">Durasi</label>
                                    <input type="text" class="form-control @error('durasi') is-invalid @enderror" id="durasi" name="durasi" placeholder="Durasi Pembayaran (Bulan)">
                                        @error('durasi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class='col mb-2'>
                                    <label for="mulai" class="font-weight-bold text-dark">Tanggal Berlaku</label>
                                    <input type="date" class="form-control @error('mulai') is-invalid @enderror" id="mulai" name="mulai" placeholder="Masukan Nominal Standar Retribusi">
                                        @error('mulai')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                </div>
                                <div class="col mb-2">
                                    <label for="selesai" class="font-weight-bold text-dark">Tanggal Selesai</label>
                                    <input type="date" class="form-control @error('selesai') is-invalid @enderror" id="selesai" name="selesai" placeholder="Durasi Pembayaran (Bulan)">
                                        @error('selesai')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Menambah Data?')"><i class="fas fa-save"></i> Simpan</button>
                                    <a data-dismiss="modal" class="btn btn-danger text-white"><i class="fas fa-times"></i> Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</div>

@foreach($index as $retri)
<div class="modal fade" id="modal-{{$retri->id}}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary">Edit Standar Retribusi</h6>
                            </div>
                        </div>
                        <form method="POST" enctype="multipart/form-data" action="{{route('masterdata-retribusi-update', $retri->id)}}">
                        @csrf   
                            <div class="row mb-2">
                                <input type="text" class="form-control @error('id') is-invalid @enderror" id="id" name="id" hidden>
                                <div class='col mb-2'>
                                    <label for="standar" class="font-weight-bold text-dark">Nominal Standar</label>
                                    <input type="text" class="form-control @error('standar') is-invalid @enderror" id="standar" name="standar" placeholder="Masukan Nominal Standar Retribusi" value="{{$retri->nominal_retribusi}}">
                                        @error('standar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                </div>
                                <div class="col mb-2">
                                    <label for="durasi" class="font-weight-bold text-dark">Durasi</label>
                                    <input type="text" class="form-control @error('durasi') is-invalid @enderror" id="durasi" name="durasi" placeholder="Durasi Pembayaran (Bulan)" value="{{$retri->durasi}}">
                                        @error('durasi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class='col mb-2'>
                                    <label for="mulai" class="font-weight-bold text-dark">Tanggal Berlaku</label>
                                    <input type="date" class="form-control @error('mulai') is-invalid @enderror" id="mulai" name="mulai" placeholder="Masukan Nominal Standar Retribusi" value="{{$retri->tanggal_berlaku}}">
                                        @error('mulai')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                </div>
                                <div class="col mb-2">
                                    <label for="selesai" class="font-weight-bold text-dark">Tanggal Selesai</label>
                                    <input type="date" class="form-control @error('selesai') is-invalid @enderror" id="selesai" name="selesai" placeholder="Durasi Pembayaran (Bulan)" value="{{$retri->tanggal_selesai}}">
                                        @error('selesai')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>    
                                        @enderror
                                </div>
                            </div>
                            <div class="row">
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
@endforeach
@endsection