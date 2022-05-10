@extends('layouts.admin-master')

@section('title')
Edit Jadwal
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Data Jadwal Pengangkutan</h1>
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
            <form method="POST" enctype="multipart/form-data" action="{{route('masterdata-jadwal-update', $jadwal->id_jadwal)}}">
            @csrf
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <div class="row">
                        <div class="col">
                            <h3 class="font-weight-bold text-primary"><i class="fas fa-plus"></i>Edit Data Jadwal Pengangkutan</h3>
                        </div>
                    </div>
                </div>
                <div class="form-group card-body">    
                    <div class="row">
                        <div class='col mb-2'>
                            <label for="mulai" class="font-weight-bold text-dark">Jadwal Mulai</label>
                            <input type="time" class="form-control @error('mulai') is-invalid @enderror" id="mulai" name="mulai" placeholder="Masukan waktu mulai (Standar waktu 24 Jam)" value="{{$jadwal->mulai}}">
                                @error('mulai')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="selesai" class="font-weight-bold text-dark">Jadwal Selesai</label>
                            <input type="time" class="form-control @error('selesai') is-invalid @enderror" id="selesai" name="selesai" placeholder="Masukan Waktu Selesai (Standar Waktu 24 jam)" value="{{$jadwal->selesai}}">
                                @error('selesai')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        <div class="col mb-4">
                            <label for="alamat" class="font-weight-bold text-dark">Hari</label>
                            <select class="form-control @error('hari') is-invalid @enderror" id="hari" name="hari">
                                <option value="" {{$jadwal->hari == '' ? 'selected' : ''}} >Pilih Hari</option>
                                    <option value="Senin" {{$jadwal->hari == 'Senin' ? 'selected' : ''}} >Senin</option>
                                    <option value="Selasa" {{$jadwal->hari == 'Selasa' ? 'selected' : ''}} >Selasa</option>
                                    <option value="Rabu" {{$jadwal->hari == 'Rabu' ? 'selected' : ''}} >Rabu</option>
                                    <option value="Kamis" {{$jadwal->hari == 'Kamis' ? 'selected' : ''}} >Kamis</option>
                                    <option value="Jumat" {{$jadwal->hari == 'Jumat' ? 'selected' : ''}} >Jumat</option>
                                    <option value="Sabtu" {{$jadwal->hari == 'Sabtu' ? 'selected' : ''}} >Sabtu</option>
                                    <option value="Minggu" {{$jadwal->hari == 'Minggu' ? 'selected' : ''}} >Minggu</option>
                            </select>
                                @error('hari')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Menambah Data?')"><i class="fas fa-save"></i> Simpan</button>
                            <a href="{{route('masterdata-jadwal-index')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</section>
@endsection