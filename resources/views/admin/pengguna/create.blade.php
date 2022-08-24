@extends('layouts.admin-master')

@section('title')
Create Data Pelanggan
@endsection

@section('scripts')
<script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            "oLanguage":{
                "sSearch": "Cari:",
                "sZeroRecords": "Data tidak ditemukan",
                "sSearchPlaceholder": "Cari penduduk...",
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
    } );

    function create(id){
        swal({
            title: 'Anda yakin ingin menambahkan pelanggan ?', 
            icon: 'warning', 
            buttons:{
                cancel:{
                    text: 'Tidak',
                    value: null,
                    visible: true,
                    closeModal: true,
                },
                confirm:{
                    text: 'Ya',
                    value: true,
                    visible: true,
                    closeModal: true
                }
            }
        }).then(function(value){
                if(value){
                    jQuery.ajax({  
                        url: "/admin/pengguna/store/"+id,
                        type: "GET",
                        success: function(result){
                            if(result){
                                // console.log(result.status);
                                if(result.status == 'success'){
                                    swal({
                                        title: result.info,
                                        icon: result.status
                                    })
                                }else{
                                    swal({
                                        title: result.info,
                                        icon: result.status
                                    })
                                }
                            }
                        },
                        error: function(xhr, thrownError){
                            swal({
                                title: 'Gagal menambahkan data pelanggan !',
                                icon: 'error'
                            })
                        },
                    });
                }
        })
    }
</script>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Data Pelanggan</h1>
    </div>

    <div class="section-body">
        <!-- <div class="container-fluid">
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
            <form method="POST" enctype="multipart/form-data" action="">
            @csrf
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <div class="row">
                        <div class="col">
                            <h3 class="font-weight-bold text-primary"><i class="fas fa-plus"></i>Tambah Data Pelanggan</h3>
                        </div>
                    </div>
                </div>
                <div class="form-group card-body">    
                    <div class="row">
                        <div class="col mb-2">
                            <label for="nik" class="font-weight-bold text-dark">NIK</label>
                            <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik" name="nik" placeholder="Masukan No Induk Kependudukan">
                                @error('nik')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="nama" class="font-weight-bold text-dark">Nama Lengkap</label>
                            <input type="nama" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" placeholder="Masukan Nama Lengkap Pengguna">
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
                                    <option value="Pria">Pria</option>
                                    <option value="Wanita">Wanita</option>
                            </select>
                                @error('jenis')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="tanggal" class="font-weight-bold text-dark">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" placeholder="Masukan Tanggal Lahir">
                                @error('tanggal')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                        <div class="col mb-2">
                            <label for="alamat" class="font-weight-bold text-dark">Alamat</label>
                            <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" placeholder="Masukan Alamat Tinggal">
                                
                                @error('alamat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>    
                                @enderror
                        </div>
                    </div>

                    <div class="row mb-3 ">
                        <div class='col mb-2'>
                            <label for="no" class="font-weight-bold text-dark">No Telpon</label>
                            <input type="text" class="form-control @error('no') is-invalid @enderror" id="no" name="no" placeholder="Masukan No Telpon Aktif">
                                @error('no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Menambah Data?')"><i class="fas fa-save"></i> Simpan</button>
                            <a href="{{route('pengguna-index')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div> -->

        <div class="container-fluid">
            <!-- DataTales Example -->
            <!-- Copy drisini -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pilih Data Penduduk</h6>
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
                <!-- <a class= "btn btn-success text-white mb-2" href="{{route('pengguna-create')}}"><i class="fas fa-plus"></i> Tambah Data Pengguna</a> -->
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="table-primary">
                            <th class="col-2">Action</th>
                            <!-- <th>No Kartu Keluarga</th> -->
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>No Telp</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($index as $penduduk)
                        <tr>
                            <td align="center">
                                <a class="btn btn-success btn-sm text-white" data-toggle="modal" data-target="#modal-global-filter" onclick="create({{$penduduk->id}})" ><i class="fas fa-plus"></i></a>
                            </td>
                            <td>
                                {{$penduduk->nik}}
                            </td>
                            <td>
                                {{$penduduk->nama}}
                            </td>
                            <td>
                                {{$penduduk->jenis_kelamin}}
                            </td>
                            <td>
                                {{$penduduk->telepon}}
                            </td>
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

@endsection