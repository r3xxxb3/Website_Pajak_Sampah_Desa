@extends('layouts.auth-master')

@section('content')
<div class="container">
            <div class="card card-success ">
                <div class="card-header"><h4>{{ __('Register') }}</h4></div>
                <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">    
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
                                <label for="nik" class="font-weight-bold text-dark">NIK</label>
                                <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" placeholder="Masukan No Induk Kependudukan" value="{{old('nik')}}">
                                    @error('nik')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>    
                                    @enderror
                            </div>
                            <div class="col mb-2">
                                <label for="nama" class="font-weight-bold text-dark">Nama Lengkap</label>
                                <input type="nama" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" placeholder="Masukan Nama Lengkap Pengguna" value="{{old('nama')}}">
                                    @error('nama')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>    
                                    @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class='col mb-2'>
                                <label for="jenis" class="font-weight-bold text-dark">Jenis Kelamin</label>
                                <select class="form-control @error('jenis') is-invalid @enderror" id="jenis" name="jenis">
                                    <option value="" selected>Pilih Jenis Kelamin</option>
                                        <option value="Pria" {{old('jenis') == "Pria" ? 'selected' : ''}}>Pria</option>
                                        <option value="Wanita" {{old('jenis') == "Wanita" ? 'selected' : ''}}>Wanita</option>
                                </select>
                                    @error('jenis')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>
                            <div class="col mb-2">
                                <label for="tanggal" class="font-weight-bold text-dark">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" placeholder="Masukan Tanggal Lahir" value="{{old('tanggal')}}">
                                    @error('tanggal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>    
                                    @enderror
                            </div>
                            <div class="col mb-2">
                                <label for="alamat" class="font-weight-bold text-dark">Alamat</label>
                                <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Masukan Alamat Tinggal" value="{{old('alamat')}}">
                                    
                                    @error('alamat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>    
                                    @enderror
                            </div>
                        </div>

                        <div class="row mb-3 ">
                            <div class='col mb-2'>
                                <label for="banjar" class="font-weight-bold text-dark">Banjar</label>
                                <input type="text" class="form-control @error('banjar') is-invalid @enderror" list="banjardata" id="banjar" name="banjar" placeholder="Masukan Banjar (Tempat Tinggal)" value="{{old('banjar')}}">
                                    <datalist id="banjardata">
                                        @if($banjar != [])
                                            @foreach($banjar as $b)
                                                <option value="{{$b->nama_banjar_dinas}}">
                                            @endforeach
                                        @endif
                                    </datalist>
                                    @error('banjar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>
                            <div class='col mb-2'>
                                <label for="no" class="font-weight-bold text-dark">No Telpon</label>
                                <input type="text" class="form-control @error('no') is-invalid @enderror" id="no" name="no" placeholder="Masukan No Telpon Aktif" value="{{old('no')}}">
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

                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Menambah Data?')"><i class="fas fa-save"></i> Simpan</button>
                                <a href="{{route('home')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
                </div>
            </div>
</div>
@endsection
