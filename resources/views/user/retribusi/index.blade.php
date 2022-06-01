@extends('layouts.user-master')

@section('title')
Index Retribusi
@endsection

@section('style')
<style>
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
  readURL(this);
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
            <a class= "btn btn-success text-white mb-2" data-toggle="modal" data-target="#modal-choose"><i class="fas fa-cash-register"></i> Pilih Tagihan Retribusi</a>
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
                    <tr>
                        <td align="center">
                            @if($retri->status == 'pending')
                                <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-single" onClick="calculateNom_b({{$retri}})"><i class="fas fa-cart-plus"></i></a>
                            @else
                                <a href="#" class="btn btn-info btn-md" data-toggle="" data-target=""><i class="fas fa-exclamation"></i></a>
                            @endif
                        </td>
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
                            {{$retri->status}}
                        </td>

                    </tr>
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
                                        <tr>
                                            <td align="center">
                                                <input type="checkbox" name="id[]" id="id-{{$retri->id}}" value="{{$retri->id}}" onClick="calculateNom({{$retri}})">
                                                <!-- <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-{{$retri->id}}"><i class="fas fa-cart-plus"></i></a> -->
                                            </td>
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
                                                {{$retri->status}}
                                            </td>
            
                                        </tr>
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
                                        <img src="{{asset('assets/img/properti/blank.png')}}"  height="300px" style="object-fit:cover" class="mb-3" id="prop">
                                    </div>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file-multi" name="file" accept="image/png, image/jpeg, image/jpg" placeholder="File/Foto Bukti bayar"  value="{{is_null($retri->pembayaran) ? $retri->pembayaran->bukti_bayar : old('file')}}" >
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
</div>



<div class="modal fade" id="modal-single">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary">Pembayaran Retribusi {{$retri->properti->nama_properti}}</h6>
                            </div>
                        </div>
                        <form method="POST" enctype="multipart/form-data" action="{{route('pembayaran-store')}}">
                        @csrf
                                <div class='col mb-2' hidden>
                                    <input type="text" class="form-control @error('id') is-invalid @enderror" id="id" name="id[]" placeholder="" value="{{is_null($retri->id) ? $retri->id : old('id[]')}}" >
                                    <input type="text" class="form-control @error('type') is-invalid @enderror" id="type" name="type" placeholder="" value="retribusi" >
                                </div>
                            <div class="row">
                                <div class='col mb-2'>
                                    <label for="file" class="font-weight-bold text-dark">Bukti Pembayaran</label>
                                    <div class="col-12 d-flex justify-content-center">
                                        <img src="{{asset('assets/img/properti/blank.png')}}"  height="300px" style="object-fit:cover" class="mb-3" id="photo">
                                    </div>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file-single" name="file" accept="image/png, image/jpeg, image/jpg" placeholder="File/Foto Bukti bayar" value="{{is_null($retri->pembayaran) ? $retri->pembayaran->bukti_bayar : old('file')}}"  >
                                        @error('file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-2">
                                <label for="media" class="font-weight-bold text-dark">Jenis Pembayaran</label>
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
                                <label for="nominal" class="font-weight-bold text-dark">Konfirmasi Nominal Bayar</label>
                                    <input type="number" class="form-control @error('nominal') is-invalid @enderror" id="nominal" name="nominal" placeholder="Konfirmasi Nominal Pembayaran" value="{{is_null($retri->pembayaran) ? $properti->pembayaran : old('nominal')}}" >
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
</div>


@endsection

