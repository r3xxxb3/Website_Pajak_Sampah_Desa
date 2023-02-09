@extends('layouts.admin-master')

@section('title')
Kritik & Saran
@endsection

@section('style')
<style>
    div.dataTables_wrapper div.dataTables_filter label {
      width: 100%; 
    }

    div.dataTables_wrapper div.dataTables_filter input {
      width: 100%; 
    }

    
    div.dataTables_wrapper div.dataTables_length select {
      width: 20%; 
    }

    div.dataTables_wrapper div.dataTables_length label {
      width: 100%; 
    }

    table.dataTable tr td {
        vertical-align: middle;
        text-align: center;
    }

    table.dataTable tr th {
        vertical-align: middle;
        text-align: center;
    }

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
    var table = $('#dataTable').DataTable({
        "oLanguage":{
            "sSearch": "Cari:",
            "sZeroRecords": "Data tidak ditemukan",
            "sSearchPlaceholder": "Cari item",
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
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]]
    });
    
    // function update(data){
    //     console.log(data);
    //     $("#uid").val(data.id);
    //     $("#usubjek").val(data.subjek);
    //     $("#ucomment").val(data.kritik);
    // };

</script>
@endsection

@section('content')
    
<section class="section">
    <div class="section-header">
        <h1>Penilaian Layanan</h1>
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
            <div class="card shadow">
                <div class="form-group card-header shadow">
                    <h5 class="font-weight-bold text-primary"><i class="fas fa-list"></i> List Kritik & Saran</h5>
                </div>
                <div class="form-group card-body">
                    <div id="myDIV" style="display: block">
                        <div class="row">
                            <div class="col">
                                <div class="table-responsive">
                                    <!-- <a class="btn btn-success text-white mb-2" data-toggle="modal" data-target="#modal-add"><i class="fas fa-plus"></i> Buat Kritik & Saran</a> -->
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Subjek</th>
                                            <th class="col-6">Isi</th>
                                            <th>Tanggal Buat</th>
                                            <th class="col-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kritik as $k)
                                        <tr>
                                            <td>{{$k->subjek}}</td>
                                            <td>{{$k->kritik}}</td>
                                            <td>{{$k->created_at->format('d M Y')}}</td>
                                            <td>
                                                <a class="btn btn-danger text-white col mb-2" href="{{route('admin-custom-kritik-delete', $k->id)}}" onclick="return confirm('Anda yakin akan menghapus data ?')"><i class="fas fa-trash"></i> Hapus</a>
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
            </div>
        </div>
    </div>
</section>
<!-- 
<div class="modal fade" id="modal-add">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-primary">Beri Kritik & Saran</h5>
                    </div>
                    <hr>
                    <div class="card-body">
                        <form action="{{route('custom-kritik-store')}}" enctype="multipart/form-data" method="post">
                            @csrf
                            <div id="myDIV" style="display: block">
                                <div class="row justify-content-between mb-3">
                                    <input type="text" name="id" id="id" hidden>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="subjek" class="font-weight-bold text-primary">Jenis Subjek</label>
                                    <select class="form-control" name="subjek" id="subjek">
                                        <option value="pelayanan">Pelayanan</option>
                                        <option value="website">Website</option>
                                        <option value="jadwal pengangkutan">Jadwal Pengangkutan</option>
                                        <option value="jenis jasa & retribusi">Jenis Jasa & Retribusi</option>
                                        <option value="notifikasi">Notifikasi</option>
                                        <option value="lain-lain">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="row" style="height:150px;">
                                <div class="col" >
                                    <label for="comment" class="font-weight-bold text-primary">Kritik & Saran</label>
                                    <textarea class="form-control" name="comment" id="comment" style="height:100%; background-color:light;" placeholder="Masukan kritik & saran anda !">
    
                                    </textarea>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col" style="vertical-align: middle; text-align: right;">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Sudah Benar?')"><i class="fas fa-save"></i> Simpan</button>
                                    <a class="btn btn-danger text-white" data-toggle="modal" data-target="#modal-rate"><i class="fas fa-times"></i> Batal</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection