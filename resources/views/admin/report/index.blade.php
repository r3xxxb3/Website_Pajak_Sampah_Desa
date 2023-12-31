@extends('layouts.admin-master')

@section('title')
Report 
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

    const ctx = document.getElementById('myChart');
    var chartRetri = new Chart(ctx, {
        type: 'bar',
        data: {
            datasets: [{
                label: 'Nominal Lunas',
                data: [
                    <?php echo $retribusi->totalLunas?>,
                ],
                backgroundColor: [
                    '#27c499',
                ],
            },
            {
                label: 'Nominal Pending',
                data: [
                    <?php echo $retribusi->total - $retribusi->totalLunas?>,
                ],
                backgroundColor: [
                    '#ffa426',
                ],
            }],
        },
        options: {
            responsive: true,
            legend: {
                position: 'bottom',
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    const ctx2 = document.getElementById('myChart2');
    var chartPengangkutan = new Chart(ctx2, {
        type: 'bar',
        data: {
            datasets: [{
                label: 'Nominal Lunas',
                data: [
                    <?php echo $pengangkutan->totalLunas?>,
                ],
                backgroundColor: [
                    '#27c499',
                ],
            },
            {
                label: 'Nominal Pending',
                data: [
                    <?php echo $pengangkutan->total - $pengangkutan->totalLunas?>,
                ],
                backgroundColor: [
                    '#ffa426',
                ],
            }],
        },
        options: {
            responsive: true,
            legend: {
            position: 'bottom',
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    function chartRefresh(chart, data){
        // console.log(chart);
        chart.data.datasets[0].data = [data[4]];
        chart.data.datasets[1].data = [data[3] - data[4]] ;
        chart.update();
    }

    function checkComment(comment){
        if (comment != null){
            return comment;
        }else{
            return "Tidak ada komentar";
        }
    }

    function checkType(properti){
        if(properti != null) {
            return "Retribusi";
        }else{
            return "Pengangkutan";
        }
    }

    function checkStarRet(nilai){
        $('.fa-star').removeClass('partitionRetribusi');
        let dec = nilai[0].toString();
        let decimal = dec.split(".");
        // console.log('decimal');
        // console.log(decimal);
        for( i = 1; i <= 5; i++){
            // console.log(i);
            if(i <= decimal[0]){
                console.log(i+","+decimal[0]);
                $('#retri-'+i).addClass('text-warning');
            }else{
                if( i == parseInt(decimal[0]) + 1){
                    console.log(decimal[0], decimal[1]);
                    if(decimal[1] != undefined){
                        console.log($('.partitionRetribusi:after'));
                        $('#retri-'+i).addClass('partitionRetribusi');
                        $(":root").css("--0-retribusi", decimal[1]+"0%");
                    }
                }
            }
        }
    }

    function checkStarPeng(nilai){
        $('.fa-star').removeClass('text-warning');
        $('.fa-star').removeClass('partitionPengangkutan');
        let dec = nilai[0].toString();
        let decimal = dec.split(".");
        // console.log('decimal');
        // console.log(decimal);
        for( i = 1; i <= 5; i++){
            // console.log(i);
            if(i <= decimal[0]){
                // console.log($('#peng-'+i));
                $('#peng-'+i).addClass('text-warning');
            }else{
                if (i == parseInt(decimal[0]) + 1){
                    console.log(decimal[0], decimal[1]);
                    if(decimal[1] != undefined){
                        console.log($('.partitionPengangkutan:after'));
                        $('#peng-'+i).addClass('partitionPengangkutan');
                        $(":root").css("--0-pengangkutan", decimal[1]+"0%");
                    }
                }
            }
        }
    }

    $(document).ready(function(){
        
        $('.lihatPenilaian').on('click', function(e){
            var bulan = $("#bulanB").val();
            var tahun = $("#tahunB").val();
            e.preventDefault();
            $.ajax({
                method : 'POST',
                url : '{{route("admin-report-penilaian-search")}}',
                data : {
                "_token" : "{{ csrf_token() }}",
                bulan: bulan,
                tahun: tahun,
                },
                beforeSend : function() {
                            
                },
                success : (res) => {
                    table.clear();
                    jQuery.each(res, function(i,val){
                        // console.log(res);
                        if(i == 0) {
                            jQuery.each(val, function(i, nilai){
                                console.log(nilai);
                                table.row.add([
                                    nilai.nama_pelanggan,
                                    checkComment(nilai.comment),
                                    nilai.rate,
                                    checkType(nilai.item.id_properti),
                                    nilai.tanggal
                                ]);
                            });
                        }else if(i == 1) {
                            checkStarPeng(val, "Pengangkutan");
                            $('.nilai-pengangkutan').html(val[0]+' Bintang <br> Dari '+val[1]+" Penilaian");
                        }else if(i == 2) {
                            checkStarRet(val, "Retribusi");
                            $('.nilai-retribusi').html(val[0]+' Bintang <br> Dari '+val[1]+" Penilaian");
                        }
                    });
                    table.draw();
                }
            });
        });

        $('.lihat').on('click', function(e){
            var bulan = $("#bulan").val();
            var tahun = $("#tahun").val();
            e.preventDefault();
            $.ajax({
                method : 'POST',
                url : '{{route("admin-report-keuangan-search")}}',
                data : {
                "_token" : "{{ csrf_token() }}",
                bulan: bulan,
                tahun: tahun,
                },
                beforeSend : function() {
                            
                },
                success : (res) => {
                    console.log(res);
                    // console.log(chartPengangkutan);
                    jQuery.each(res, function(i, val){
                        if (i == 2) {
                            $('.total-retribusi').html(val[0]);
                            $('.retribusi-total').html((val[3]).toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}));
                            $('.lunas-retribusi').html(val[1]);
                            $('.retribusi-lunas').html((val[4]).toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}));
                            // console.log(chartRetri);
                            chartRefresh(chartRetri, val);
                        }else if (i == 3) {
                            $('.total-pengangkutan').html(val[0]);
                            $('.pengangkutan-total').html((val[3]).toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}));
                            $('.lunas-pengangkutan').html(val[1]);
                            $('.pengangkutan-lunas').html((val[4]).toLocaleString('id-ID',{style:'currency', currency:'IDR', maximumFractionDigits: 2}));
                            // console.log(chartPengangkutan);
                            chartRefresh(chartPengangkutan, val);
                        }
                    });
                }
            });
        });
    });
</script>
@endsection

@section('style')
<style>
    :root{
        --0-retribusi: {{count(explode('.', $retribusi->rate)) == 1 ? 10 : explode('.', $retribusi->rate)[1] }}0%;
        --0-pengangkutan: {{count(explode('.', $pengangkutan->rate)) == 1 ? 10 : explode('.', $pengangkutan->rate)[1] }}0%;
    }

    .fa-star {
        color: #ddd;
    }

    .partitionRetribusi {
        display: inline-block;
        position: relative;
        color: #ddd;
    }

    .partitionRetribusi:after {
        content: "\f005";
        left: 0;
        top: 0;
        overflow: hidden;
        position: absolute;
        color: #ffa426;
        width: var(--0-retribusi);
    }

    .partitionPengangkutan {
        display: inline-block;
        position: relative;
        color: #ddd;
    }

    .partitionPengangkutan:after {
        content: "\f005";
        left: 0;
        top: 0;
        overflow: hidden;
        position: absolute;
        color: #ffa426;
        width: var(--0-pengangkutan);
    }
</style>
@endsection

@section('content')
<section>

<section class="section">
    <div class="section-header">
        <h1>Data Laporan</h1>
    </div>

    <div class="section-body">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- DataTales Example -->
            <!-- Copy drisini -->
            <div class="card shadow mb-4">
                <div class="card-header shadow py-3">
                    <h6 class="m-0 font-weight-bold text-primary col">Laporan Keuangan</h6>
                    <div class="col" align="end">
                        <a class="btn btn-info btn-sm text-white"  id="lihat" data-toggle="modal" data-target="#modal-single"><i class="fas fa-search"></i> Filter Bulan & Tahun</a>
                    </div>
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
                    <div class="row">
                        <div class="col">
                            <div class="row"> 
                                <div class="card card-statistic-2">
                                    <div class="card-icon shadow-primary bg-info">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4 class="font-weight-bold text-dark">Total Retribusi</h4>
                                        </div>
                                        <div class="card-body total-retribusi">
                                            {{$retribusi->lunas + $retribusi->pending}}
                                        </div>
                                    </div>
                                
                                    <div class="card-wrap" style="position: absolute; right: 0; bottom: 0;">
                                        <div class="container" >
                                            <p class="small retribusi-total">{{"Rp ".number_format($retribusi->total ?? 0,2,',','.')}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="card card-statistic-2">
                                    <div class="card-icon shadow-primary bg-primary">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4 class="font-weight-bold text-dark">Retribusi Lunas</h4>
                                        </div>
                                        <div class="card-body lunas-retribusi">
                                            {{$retribusi->lunas}}
                                        </div>
                                    </div>
                                    <div class="card-wrap" style="position: absolute; right: 0; bottom: 0;">
                                        <div class="container" >
                                            <p class="small retribusi-lunas">{{"Rp ".number_format($retribusi->totalLunas ?? 0,2,',','.')}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row card">
                                <label class="font-weight-bold text-dark pl-3" align="center" for="myChart"> Grafik Bar Retribusi</label>
                                <canvas id="myChart" name="myChart"></canvas>
                            </div>
                        </div>
                        <div class="col card">
                            <div class="row">
                                <div class="card card-statistic-2">
                                    <div class="card-icon shadow-primary bg-info">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4 class="font-weight-bold text-dark">Total Pengangkutan</h4>
                                        </div>
                                        <div class="card-body total-pengangkutan">
                                            {{$pengangkutan->lunas + $pengangkutan->pending}}
                                        </div>
                                    </div>
                                    <div class="card-wrap" style="position: absolute; right: 0; bottom: 0;">
                                        <div class="container" >
                                            <p class="small pengangkutan-total">{{"Rp ".number_format($pengangkutan->total ?? 0,2,',','.')}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="card card-statistic-2">
                                    <div class="card-icon shadow-primary bg-primary">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4 class="font-weight-bold text-dark">Pengangkutan Lunas</h4>
                                        </div>
                                        <div class="card-body lunas-pengangkutan">
                                            {{$pengangkutan->lunas}}
                                        </div>
                                    </div>
                                    <div class="card-wrap" style="position: absolute; right: 0; bottom: 0;">
                                        <div class="container" >
                                            <p class="small pengangkutan-lunas">{{"Rp ".number_format($pengangkutan->totalLunas ?? 0,2,',','.')}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row card">
                                <label class="font-weight-bold text-dark pl-3" align="center" for="myChart2"> Grafik Bar Pengangkutan</label>
                                <canvas id="myChart2" name="myChart2"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-header shadow py-3">
                    <h6 class="m-0 font-weight-bold text-primary col">Laporan Customer Service</h6>
                    <div class="col" align="end">
                        <a class="btn btn-info btn-sm text-white"  id="lihat" data-toggle="modal" data-target="#modal-single-b"><i class="fas fa-search"></i> Filter Bulan & Tahun</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col"> 
                            <div class="card card-statistic-2">
                                <div class="card-icon shadow-primary bg-info">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4 class="font-weight-bold text-dark">Layanan Retribusi</h4>
                                    </div>
                                    <div class="card-body bintang-retribusi">
                                        @for($i = 1; $i <= 5; $i++ )
                                            @if($i <= $retribusi->rate)
                                                <span class="fa fa-star ml-2 text-warning " id="retri-{{$i}}"></span>
                                            @else
                                                @if(count(explode('.', $retribusi->rate)) > 1 && $i == (explode('.', $retribusi->rate))[0] + 1)
                                                    <span class="partitionRetribusi fa fa-star ml-2 " id="retri-{{$i}}" ></span>
                                                @else
                                                    <span class="fa fa-star ml-2" id="retri-{{$i}}"></span>
                                                @endif
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            
                                <div class="card-wrap" style="position: absolute; right: 0; bottom: 0;">
                                    <div class="container" >
                                        <p class="small nilai-retribusi">{{$retribusi->rate}} Bintang <br> Dari {{$retribusi->totalRate}} Penilaian</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card card-statistic-2">
                                <div class="card-icon shadow-primary bg-info">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4 class="font-weight-bold text-dark">Layanan Pengangkutan</h4>
                                    </div>
                                    <div class="card-body bintang-pengangkutan">
                                        @for($i = 1; $i <= 5; $i++ )
                                            @if($i <= $pengangkutan->rate)
                                                <span class="fa fa-star ml-2 text-warning " id="peng-{{$i}}" ></span>
                                            @else
                                                @if(count(explode('.', $pengangkutan->rate)) > 1 && $i == (explode('.', $pengangkutan->rate))[0] + 1)
                                                    <span class="partitionPengangkutan fa fa-star ml-2 " id="peng-{{$i}}"></span>
                                                @else
                                                    <span class="fa fa-star ml-2" id="peng-{{$i}}"></span>
                                                @endif
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <div class="card-wrap" style="position: absolute; right: 0; bottom: 0;">
                                    <div class="container" >
                                        <p class="small nilai-pengangkutan">{{$pengangkutan->rate}} Bintang <br> Dari {{$pengangkutan->totalRate}} Penilaian</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nama Pelanggan </th>
                                            <th>comment </th>
                                            <th>Rating </th>
                                            <th>Jenis </th>
                                            <th>Tanggal Buat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($nilai as $n)
                                        <tr>
                                            <td> {{$n->nama_pelanggan}}</td>
                                            <td> {{isset($n->comment) ? $n->comment : "Tidak ada komentar"}}</td>
                                            <td> {{$n->rate}}</td>
                                            <td> {{$n->item->id_properti != null ? "Retribusi" : "Pengangkutan"}}</td>
                                            <td> {{$n->created_at->format('d M Y')}}</td>
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
</section>

<div class="modal fade" id="modal-single">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Pilih Bulan & Tahun</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <label class="font-weight-bold text-dark pl-3" for="bulan"> Bulan</label>
                                <select class="form-control" name="bulan" id="bulan">
                                    <option value="01" {{date('m') == '01' ? 'selected' : ""}}>Januari</option>
                                    <option value="02" {{date('m') == '02' ? 'selected' : ""}}>Februari</option>
                                    <option value="03" {{date('m') == '03' ? 'selected' : ""}}>Maret</option>
                                    <option value="04" {{date('m') == '04' ? 'selected' : ""}}>April</option>
                                    <option value="05" {{date('m') == '05' ? 'selected' : ""}}>May</option>
                                    <option value="06" {{date('m') == '06' ? 'selected' : ""}}>Juni</option>
                                    <option value="07" {{date('m') == '07' ? 'selected' : ""}}>Juli</option>
                                    <option value="08" {{date('m') == '08' ? 'selected' : ""}}>Agustus</option>
                                    <option value="09" {{date('m') == '09' ? 'selected' : ""}}>September</option>
                                    <option value="10" {{date('m') == '10' ? 'selected' : ""}}>Oktober</option>
                                    <option value="11" {{date('m') == '11' ? 'selected' : ""}}>November</option>
                                    <option value="12" {{date('m') == '12' ? 'selected' : ""}}>Desember</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="font-weight-bold text-dark pl-3" for="tahun"> Tahun</label>
                                <select class="form-control" name="tahun" id="tahun">
                                    <option value="{{date('Y') - 5}}">{{date('Y')- 5}}</option>
                                    <option value="{{date('Y') - 4}}">{{date('Y')- 4}}</option>
                                    <option value="{{date('Y') - 3}}">{{date('Y')- 3}}</option>
                                    <option value="{{date('Y') - 2}}">{{date('Y')- 2}}</option>
                                    <option value="{{date('Y') - 1}}">{{date('Y')- 1}}</option>
                                    <option value="{{date('Y')}}" selected>{{date('Y')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-4" align="end">
                            <div class="col">
                                <a class="btn btn-info btn-sm text-white lihat"  ><i class="fas fa-search"></i> Cari</a>
                                <a class="btn btn-danger btn-sm text-white " data-toggle="modal" data-target="#modal-single"><i class="fas fa-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-single-b">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <h4>Pilih Bulan & Tahun</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <label class="font-weight-bold text-dark pl-3" for="bulanB"> Bulan</label>
                                <select class="form-control" name="bulanB" id="bulanB">
                                    <option value="01" {{date('m') == '01' ? 'selected' : ""}}>Januari</option>
                                    <option value="02" {{date('m') == '02' ? 'selected' : ""}}>Februari</option>
                                    <option value="03" {{date('m') == '03' ? 'selected' : ""}}>Maret</option>
                                    <option value="04" {{date('m') == '04' ? 'selected' : ""}}>April</option>
                                    <option value="05" {{date('m') == '05' ? 'selected' : ""}}>May</option>
                                    <option value="06" {{date('m') == '06' ? 'selected' : ""}}>Juni</option>
                                    <option value="07" {{date('m') == '07' ? 'selected' : ""}}>Juli</option>
                                    <option value="08" {{date('m') == '08' ? 'selected' : ""}}>Agustus</option>
                                    <option value="09" {{date('m') == '09' ? 'selected' : ""}}>September</option>
                                    <option value="10" {{date('m') == '10' ? 'selected' : ""}}>Oktober</option>
                                    <option value="11" {{date('m') == '11' ? 'selected' : ""}}>November</option>
                                    <option value="12" {{date('m') == '12' ? 'selected' : ""}}>Desember</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="font-weight-bold text-dark pl-3" for="tahunB"> Tahun</label>
                                <select class="form-control" name="tahunB" id="tahunB">
                                    <option value="{{date('Y') - 5}}">{{date('Y')- 5}}</option>
                                    <option value="{{date('Y') - 4}}">{{date('Y')- 4}}</option>
                                    <option value="{{date('Y') - 3}}">{{date('Y')- 3}}</option>
                                    <option value="{{date('Y') - 2}}">{{date('Y')- 2}}</option>
                                    <option value="{{date('Y') - 1}}">{{date('Y')- 1}}</option>
                                    <option value="{{date('Y')}}" selected>{{date('Y')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-4" align="end">
                            <div class="col">
                                <a class="btn btn-info btn-sm text-white lihatPenilaian"  ><i class="fas fa-search"></i> Cari</a>
                                <a class="btn btn-danger btn-sm text-white " data-toggle="modal" data-target="#modal-single-b"><i class="fas fa-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection