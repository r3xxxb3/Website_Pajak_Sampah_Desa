@extends('layouts.auth-master')

@section('scripts')
<script>
    $('#password').on('keyup', function(e){
        const pass = $('#password').val();
        if(!count(pass) >= 8){

        }
    })

    $('#search').on('click', function(e){
        e.preventDefault();
        const nik = $('#nik').val();
        console.log(nik);

        $.ajax({
            method : 'POST',
            url : '/register/search',
            data : {
            "_token" : "{{ csrf_token() }}",
            nik : nik,
            },
            beforeSend : function() {
                        
            },
            success : (res) => {
                if(!res.error){
                    swal("Success", "Data Penduduk Ditemukan !", "success").then(function(){
                        $("#username").removeAttr('disabled');
                        $("#password").removeAttr('disabled');
                        $("#passcheck").removeAttr('disabled');
                        $("#sub").removeClass('disabled');

                        if(!res.desa){
                            $("#desa").val(null);
                        }else{
                            $("#desa").val(res.desa);
                        }
    
                        $("#nama").val(res.nama);
                        $("#no").val(res.telepon);
                        $("#tanggal").val(res.tanggal_lahir);
                        $("#jenis").val(res.jenis_kelamin);
                        $("#alamat").val(res.alamat);
                        $("#username").val(res.telepon);
                    })
                }else{
                    swal("Error", "Data Penduduk Tidak Ditemukan !", "error").then(function(){ 

                        $("#desa").val(null);
                        $("#nama").val(null);
                        $("#no").val(null);
                        $("#tanggal").val(null);
                        $("#jenis").val(null);
                        $("#alamat").val(null);
                        $("#username").val(null);
                        $("#password").val(null);
                        $("#passcheck").val(null);
                    });
                }
            },
        }).done(()=>{})

    })

</script>
@endsection

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
                            <div class="col form-group mb-2">
                                <label for="nik" class="font-weight-bold text-dark float-left">NIK<i class="text-danger text-sm text-bold">*</i></label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" placeholder="Masukan No Induk Kependudukan" value="{{old('nik')}}">
                                    <div class="input-group-append">
                                        <button class="btn btn-info" id="search" ><i class="fas fa-search"></i> Cari</button>
                                    </div>
                                    @error('nik')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>    
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-2">
                                <label for="nama" class="font-weight-bold text-dark">Nama Lengkap<i class="text-danger text-sm text-bold">*</i></label>
                                <input type="nama" class="form-control @error('nama') is-invalid @enderror " id="nama" name="nama" placeholder="Masukan Nama Lengkap Pengguna" value="{{old('nama')}}"  {{!is_null(old('nama')) ? '' : 'disabled'  }}>
                                    @error('nama')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>    
                                    @enderror
                            </div>
                            <div class='col mb-2'>
                                <label for="jenis" class="font-weight-bold text-dark">Jenis Kelamin<i class="text-danger text-sm text-bold">*</i></label>
                                <select class="form-control @error('jenis') is-invalid @enderror" id="jenis" name="jenis" {{!is_null(old('jenis')) ? '' : 'disabled'  }}>
                                    <option value="" selected>Pilih Jenis Kelamin</option>
                                        <option value="laki-laki" {{old('jenis') == "laki-laki" ? 'selected' : ''}}>laki-laki</option>
                                        <option value="perempuan" {{old('jenis') == "perempuan" ? 'selected' : ''}}>perempuan</option>
                                </select>
                                    @error('jenis')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col mb-2">
                                <label for="tanggal" class="font-weight-bold text-dark">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" placeholder="Masukan Tanggal Lahir" value="{{old('tanggal')}}" {{!is_null(old('tanggal')) ? '' : 'disabled'  }}>
                                    @error('tanggal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>    
                                    @enderror
                            </div>
                            <div class="col mb-2">
                                <label for="alamat" class="font-weight-bold text-dark">Alamat</label>
                                <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Masukan Alamat Tinggal" value="{{old('alamat')}}" {{!is_null(old('alamat')) ? '' : 'disabled'  }}>
                                    
                                    @error('alamat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>    
                                    @enderror
                            </div>
                        </div>

                        <div class="row mb-3 ">
                            <div class='col mb-2'>
                                <label for="desa" class="font-weight-bold text-dark">Desa Adat</label>
                                <input type="text" class="form-control @error('desa') is-invalid @enderror" list="desadata" id="desa" name="desa" placeholder="Masukan desa adat (Tempat Tinggal)" value="{{old('desa')}}" {{!is_null(old('desa')) ? '' : 'disabled'  }}>
                                    <datalist id="desadata">
                                        @if($desa != [])
                                            @foreach($desa as $b)
                                                <option value="{{$b->desadat_nama}}">
                                            @endforeach
                                        @endif
                                    </datalist>
                                    @error('desa')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>
                            <div class='col mb-2'>
                                <label for="no" class="font-weight-bold text-dark">Nomor Telepon</label>
                                <input type="text" class="form-control @error('no') is-invalid @enderror" id="no" name="no" placeholder="Masukan No Telpon Aktif" value="{{old('no')}}" {{!is_null(old('no')) ? '' : 'disabled'  }}>
                                    @error('no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class='col mb-2'>
                                <label for="username" class="font-weight-bold text-dark">Buat Username<i class="text-danger text-sm text-bold">*</i></label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="Buat username akun anda !" value="{{old('username')}}" {{!is_null(old('nama')) ? '' : 'disabled'  }}>
                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>
                            <div class='col mb-2'>
                                <label for="password" class="font-weight-bold text-dark">Buat Password<i class="text-danger text-sm text-bold">*</i></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Buat Password akun anda !" value="{{old('password')}}" {{!is_null(old('nama')) ? '' : 'disabled'  }}>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col mb-2">
                                <label for="passcheck" class="font-weight-bold text-dark">Konfirmasi Password<i class="text-danger text-sm text-bold">*</i></label>
                                <input type="password" class="form-control @error('pass') is-invalid @enderror" id="passcheck" name="passcheck" placeholder="Konfirmasi Password akun anda !" value="{{old('passcheck')}}" {{!is_null(old('nama')) ? '' : 'disabled'  }}>
                                    @error('passcheck')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>

                        </div>

                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-success disabled" id="sub" onclick="return confirm('Apakah Anda Yakin Ingin Menambah Data?')"><i class="fas fa-save"></i> Simpan</button>
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
