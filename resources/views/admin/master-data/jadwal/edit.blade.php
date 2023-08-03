@extends('layouts.admin-master')

@section('title')
Edit Jadwal
@endsection

@section('scripts')
<script>
    $(document).ready( function () {
        var tableExist = $('#dataTable').DataTable({
            "oLanguage":{
                "sSearch": "Cari:",
                "sZeroRecords": "Data tidak ditemukan",
                "sSearchPlaceholder": "Cari pegawai...",
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
        
        var tablePeg = $('#dataTablePegawai').DataTable({
            "oLanguage":{
                "sSearch": "Cari:",
                "sZeroRecords": "Data tidak ditemukan",
                "sSearchPlaceholder": "Cari pegawai...",
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
        
        $('table').on('click', '.add', function(e){
            var p = $(this).attr('id');
            var pId = p.split('-')[1];
            var j = {{$jadwal->id_jadwal}};
            
            
            console.log(j);
            $.ajax({
                    method : 'POST',
                    url : '{{route("masterdata-detail-jadwal-create")}}',
                    data : {
                    "_token" : "{{ csrf_token() }}",
                    pId : pId,
                    jadwal : j,
                    },
                    beforeSend : function() {
                                
                    },
                    success : (res) => {
                        console.log(res);
                        tableExist.clear();
                        tableExist.draw();
                        tablePeg.clear();
                        tablePeg.draw();
                        if (res[0] == "success") 
                        {
                            jQuery.each(res[1], function(i, val){
                                tableExist.row.add([
                                    val.kependudukan.nama,
                                    val.kependudukan.telepon,
                                    `<a class="btn btn-danger btn-sm col text-white mb-1 delete" id="del-`+val.id_pegawai+`"><i class="fas fa-times"></i> Batal</a>`
                                ]);
                            });
                            jQuery.each(res[2], function(i, val){
                                tablePeg.row.add([
                                    val.kependudukan.nama,
                                    val.kependudukan.telepon,
                                    `<a class="btn btn-success btn-sm col text-white mb-1 add" id="add-`+val.id_pegawai+`"><i class="fas fa-plus"></i> Tambah</a>`
                                ]);
                            });
                            tableExist.draw();
                            tablePeg.draw();
                        } else if (res[1] == "error") 
                        {
                            alert("Terdapat error pada bagian data dalam Database !");
                        }
                    }
            });
            
        });
        
        $('table').on('click', '.delete', function(e){
            var p = $(this).attr('id');
            var pId = p.split('-')[1];
            var j = {{$jadwal->id_jadwal}};
            
            
            console.log(j);
            $.ajax({
                    method : 'POST',
                    url : '{{route("masterdata-detail-jadwal-delete")}}',
                    data : {
                    "_token" : "{{ csrf_token() }}",
                    pId : pId,
                    jadwal : j,
                    },
                    beforeSend : function() {
                                
                    },
                    success : (res) => {
                        console.log(res);
                        tableExist.clear();
                        tableExist.draw();
                        tablePeg.clear();
                        tablePeg.draw();
                        if (res[0] == "success") 
                        {
                            jQuery.each(res[1], function(i, val){
                                // console.log(val);
                                tablePeg.row.add([
                                    val.kependudukan.nama,
                                    val.kependudukan.telepon,
                                    `<a class="btn btn-danger btn-sm col text-white mb-1 delete" id="del-`+val.id_pegawai+`"><i class="fas fa-times"></i> Batal</a>`
                                ]);
                            });
                            jQuery.each(res[2], function(i, val){
                                tablePeg.row.add([
                                    val.kependudukan.nama,
                                    val.kependudukan.telepon,
                                    `<a class="btn btn-success btn-sm col text-white mb-1 add" id="add-`+val.id_pegawai+`"><i class="fas fa-plus"></i> Tambah</a>`
                                ]);
                            });
                            tableExist.draw();
                            tablePeg.draw();
                        } else if (res[1] == "error") 
                        {
                            alert("Terdapat error pada bagian data dalam Database !");
                        }
                    }
            }); 
        });
    });
</script>

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
                            <h4 class="font-weight-bold text-primary"><i class="fas fa-pen"></i> Edit Data Jadwal Pengangkutan</h3>
                        </div>
                    </div>
                </div>
                <div class="form-group card-body">    
                    <div class="row">
                        <div class="col mb-4">
                            <label for="alamat" class="font-weight-bold text-dark">Hari<i class="text-danger text-sm text-bold">*</i></label>
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
                        <div class="col mb-4">
                            <label for="jenis" class="font-weight-bold text-dark">Jenis Sampah<i class="text-danger text-sm text-bold">*</i></label>
                            <select class="form-control @error('jenis') is-invalid @enderror" id="jenis" name="jenis">
                                <option value="umum" {{!isset($jadwal->id_jenis_sampah) ? 'selected' : ''}}>Umum</option>
                                    @foreach($jenis as $jen)
                                        <option value="{{$jen->id}}" {{!isset($jadwal->id_jenis_sampah) && $jadwal->id_jenis_sampah == $jen->id ? 'selected' : ''}}>{{$jen->jenis_sampah}}</option>
                                    @endforeach
                            </select>
                                @error('hari')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class='col mb-2'>
                            <label for="mulai" class="font-weight-bold text-dark">Jadwal Mulai<i class="text-danger text-sm text-bold">*</i></label>
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
        <div class="container-fluid">
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <div class="row">
                        <div class="col">
                            <h4 class="font-weight-bold text-primary"><i class="fas fa-pen"></i> Edit Pegawai Pengangkutan</h3>
                        </div>
                    </div>
                </div>
                <div class="form-group card-body">
                <a class="btn btn-success btn-sm text-white mb-2" data-toggle="modal" data-target="#modal-global-add" ><i class="fas fa-plus"></i> Tambah Pegawai</a>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="table-primary">
                            <th>Pegawai</th>
                            <th>No Telp</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($index as $pegawai)
                        <tr>
                            <td style="vertical-align: middle;">
                                {{$pegawai->kependudukan->nama}}
                            </td>
                            <td style="vertical-align: middle;">
                                {{$pegawai->kependudukan->telepon}}
                            </td>
                            <td align="center" class="col-2">
                                <a class="btn btn-danger btn-sm col text-white mb-1 delete" id="del-{{$pegawai->id_pegawai}}"><i class="fas fa-times"></i> Batal</a>
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

<div class="modal fade" id="modal-global-add">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="myDIV" style="display: block">
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary">Pilih Pegawai</h6>
                            </div>
                        </div>
                        <table class="table table-bordered" id="dataTablePegawai" width="100%" cellspacing="0">
                            <thead>
                                <tr class="table-primary">
                                    <th>Pegawai</th>
                                    <th>No Telp</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($list as $p)
                                <tr>
                                    <td style="vertical-align: middle;">
                                        {{$p->kependudukan->nama}}
                                    </td>
                                    <td style="vertical-align: middle;">
                                        {{$p->kependudukan->telepon}}
                                    </td>
                                    <td align="center" class="col-2">
                                        <a class="btn btn-success btn-sm col text-white mb-1 add" id="add-{{$p->id_pegawai}}"><i class="fas fa-plus"></i></a>
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
@endsection