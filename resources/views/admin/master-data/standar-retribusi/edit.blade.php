@extends('layouts.admin-master')

@section('title')
Edit Standar Retribusi
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Data Standar Retribusi</h1>
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
            <form method="POST" enctype="multipart/form-data" action="/admin/masterdata/retribusi/update/{{$retribusi->id}}">
            @csrf
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <div class="row">
                        <div class="col">
                            <h3 class="font-weight-bold text-primary"><i class="fas fa-university"></i> Edit Data Standar Retribusi</h3>
                        </div>
                    </div>
                </div>
                <div class="form-group card-body"> 
                    <div class="row mb-3">
                        <div class="col">
                            <label for="standar" class="font-weight-bold text-dark">Nominal Standar</label>
                            <input type="text" class="form-control" id="standar" name="standar" placeholder="Masukan Nominal Standar Retribusi" value="{{$retribusi->nominal_retribusi}}">
                            <small style="color: red">
                                @error('standar')
                                    {{$message}}
                                @enderror
                            </small>
                        </div>
                        <div class="col">
                        <label for="durasi" class="font-weight-bold text-dark">Durasi</label>
                            <input type="text" class="form-control" id="durasi" name="durasi" placeholder="Masukan Durasi Retribusi (Per-N Bulan)" value="{{$retribusi->durasi}}">
                            <small style="color: red">
                                @error('durasi')
                                    {{$message}}
                                @enderror
                            </small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Mengupdate Data?')"><i class="fas fa-save"></i> Update</button>
                            <a href="{{route('masterdata-retribusi-index')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</section>
@endsection