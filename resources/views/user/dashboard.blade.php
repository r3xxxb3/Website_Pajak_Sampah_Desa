@extends('layouts.user-master')

@section('scripts')
<script>
  $(document).ready( function () {
        $('#dataTable').DataTable({
            "oLanguage":{
                "sSearch": "Cari:",
                "sZeroRecords": "Data tidak ditemukan",
                "sSearchPlaceholder": "Cari Jadwal...",
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
</style>
@endsection

@section('title')
Dashboard User
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Dashboard</h1>
  </div>
  <div class="section-body">
    <div class="row">
          <div class="col properti" onClick="window.location='{{route('properti-index')}}';">
              <div class="card card-statistic-2">
                  <div class="card-icon shadow-primary bg-primary">
                      <i class="fas fa-building"></i>
                  </div>
                  <div class="card-wrap">
                    <div class="card-header">
                        <h4 class="font-weight-bold text-dark">Properti Terverifikasi</h4>
                    </div>
                    <div class="card-body">
                      {{isset($properti) ? $properti : '0'}}
                    </div>
                  </div>
              </div>
          </div>
          <div class="col retribusi" onClick="window.location='{{route('retribusi-index')}}';">
            <div class="card card-statistic-2" >
            <div class="card-icon shadow-primary bg-primary">
                      <i class="fas fa-receipt"></i>
                  </div>
                  <div class="card-wrap">
                    <div class="card-header">
                        <h4 class="font-weight-bold text-dark">Retribusi Pending</h4>
                    </div>
                    <div class="card-body">
                      {{isset($retribusi) ? $retribusi : '0'}}
                    </div>
                  </div>
            </div>
          </div>
          <div class="col request" onClick="window.location='{{route('request-index')}}';">
              <div class="card card-statistic-2">
                <div class="card-icon shadow-primary bg-primary">
                  <i class="fas fa-truck"></i>
                </div>
                  <div class="card-wrap">
                    <div class="card-header">
                        <h4 class="font-weight-bold text-dark">Request Pending</h4>
                    </div>
                    <div class="card-body">
                      {{isset($pengangkutan) ? $pengangkutan : '0'}}
                    </div>
                  </div>
              </div>
          </div>
          <div class="col pembayaran" onClick="window.location='{{route('pembayaran-index')}}';">
            <div class="card card-statistic-2">
            <div class="card-icon shadow-primary bg-primary">
                  <i class="fas fa-money-check-alt"></i>
                  </div>
                  <div class="card-wrap">
                    <div class="card-header">
                        <h4 class="font-weight-bold text-dark">Pembayaran Pending</h4>
                    </div>
                    <div class="card-body">
                      {{isset($pembayaran) ? $pembayaran : '0'}}
                    </div>
                  </div>
            </div>
          </div>
      </div>
      <div class='card shadow mt-2'>
        <div class="card-header py-3">
            <h3 class="font-weight-bold text-dark">Jadwal Pengangkutan Sampah</h2>
        </div>
        <div class="card-body">
          <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                      <tr class="table-primary">
                          <th>Hari</th>
                          <th>Mulai</th>
                          <th>Selesai</th>
                          <th>Jenis Sampah</th>
                          <!-- <th>Jenis</th> -->
                      </tr>
                  </thead>
                  <tbody>
                    @if(isset($jadwal))
                    @foreach($jadwal as $j)
                      <tr>
                          <td>
                            {{$j->hari}}
                          </td>
                          <td>
                            {{$j->mulai}}
                          </td>
                          <td>
                            {{$j->selesai}}
                          </td>
                          <td>
                            {{$j->jenis != null ? $j->jenis->jenis_sampah : 'Umum'}}
                          </td>
                      </tr>
                    @endforeach
                    @endif
                  </tbody>
              </table>
          </div>
        </div>
    </div>
  </div>
</section>
@endsection