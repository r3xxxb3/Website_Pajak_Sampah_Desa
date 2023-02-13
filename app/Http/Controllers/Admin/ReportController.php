<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Pelanggan;
use App\Retribusi;
use App\Pengangkutan;
use App\properti;
use App\Pembayaran;
use App\DetailPembayaran;
use App\DesaAdat;
use App\Pegawai;
use App\KepuasanPengguna;

class ReportController extends Controller
{
    //
    public function index(){
        $desaAdmin = auth()->guard('admin')->user()->id_desa_adat;
        
        //Retribusi
        $properti = Properti::where('id_desa_adat', $desaAdmin)->where('status', "terverifikasi")->get();
        $retribusi = Retribusi::whereIn('id_properti', $properti->map->id)->whereMonth( 'created_at', date('m'))->whereYear( 'created_at', date('Y'))->get();
        $retribusi->pending = $retribusi->where('status', "pending")->count();
        $retribusi->lunas = $retribusi->where('status', "lunas")->count();
        $retribusi->totalLunas = array_sum($retribusi->where('status', "lunas")->pluck('nominal')->toArray());
        $sumArray = $retribusi->map->nominal->toArray();
        $retribusi->total = array_sum($sumArray);
        $kepuasanRetribusi = $retribusi->map->kepuasan->filter(function($value, $key){
            return $value->isNotEmpty();
        });
        $rateRetri = 0;
        foreach($kepuasanRetribusi as $kr){
            $rateRetri += $kr[0]->rate;
        }
        $retribusi->totalRate = $kepuasanRetribusi->count();
        $retribusi->rate = $rateRetri / $kepuasanRetribusi->count();
        // dd($retribusi->rate);
        

        //Pengangkutan
        $pengangkutan = Pengangkutan::where('id_desa_adat', $desaAdmin)->where('status', '!=', 'Batal')->whereMonth( 'created_at', date('m'))->whereYear( 'created_at', date('Y'))->with('pembayaran')->get();
        // dd($pengangkutan);
        $pengangkutan->lunas = 0;
        $pengangkutan->pending = 0;
        $sumArrayP = $pengangkutan->map->nominal->toArray(); 
        $pengangkutan->total = array_sum($sumArrayP);
        $pengangkutan->totalLunas = 0;
        
        // dd($retribusi->totalLunas);
        foreach($pengangkutan as $key=>$p){
            if(!$p->pembayaran->isEmpty()){
                if($p->pembayaran->map->status[0] == 'lunas'){
                    $pengangkutan->lunas += 1;
                    $pengangkutan->totalLunas += $p->nominal;
                }else{
                    $pengangkutan->pending += 1;
                }
            }else{
                $pengangkutan->pending += 1;
            }
        }
        $kepuasanPengangkutan = $pengangkutan->map->kepuasan->filter(function($value, $key){
            return $value->isNotEmpty();
        });
        $ratePengangkutan = 0;
        foreach($kepuasanPengangkutan as $kp){
            $ratePengangkutan += $kp[0]->rate;
        }
        $pengangkutan->rate = $ratePengangkutan / ($kepuasanPengangkutan->count() != 0 ? $kepuasanPengangkutan->count() : 1);
        $pengangkutan->totalRate = $kepuasanPengangkutan->count();
        // dd(explode('.',$pengangkutan->rate)[1]);

        $nilai = KepuasanPengguna::whereHasMorph('item', ["App\Retribusi"], function(Builder $query){
            $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->whereHas('properti', function(Builder $querys){
                $querys->where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat);
            });
        })->orWhereHasMorph('item', ["App\Pengangkutan"], function(Builder $query){
            $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat);
        })->with('item')->get();
        
        foreach($nilai as $n){
            $n->nama_pelanggan = $n->item->pelanggan->kependudukan->nama;
        }
        // dd($nilai);
        
        return view('admin.report.index', compact('pengangkutan', 'retribusi', 'nilai'));
    }

    public function keuanganSearch(Request $request){
        $data = [];
        $detailRetri = [];
        $detailPengangkutan = [];
        $desaAdmin = auth()->guard('admin')->user()->id_desa_adat;
        
        //Retribusi
        $properti = Properti::where('id_desa_adat', $desaAdmin)->where('status', "terverifikasi")->get();
        $retribusi = Retribusi::whereIn('id_properti', $properti->map->id)->whereMonth( 'created_at', $request->bulan)->whereYear('created_at', $request->tahun)->get();
        $retribusi->pending = $retribusi->where('status', "pending")->count();
        $retribusi->lunas = $retribusi->where('status', "lunas")->count();
        $retribusi->totalLunas = array_sum($retribusi->where('status', "lunas")->pluck('nominal')->toArray());
        $sumArray = $retribusi->map->nominal->toArray();
        $retribusi->total = array_sum($sumArray);
        array_push($detailRetri, $retribusi->count());
        array_push($detailRetri, $retribusi->lunas);
        array_push($detailRetri, $retribusi->pending);
        array_push($detailRetri, $retribusi->total);
        array_push($detailRetri, $retribusi->totalLunas);
        //Pengangkutan
        $pengangkutan = Pengangkutan::where('id_desa_adat', $desaAdmin)->where('status', '!=', 'Batal')->whereMonth( 'created_at', $request->bulan)->whereYear('created_at', $request->tahun )->with('pembayaran')->get();
        $pengangkutan->lunas = 0;
        $pengangkutan->pending = 0;
        $sumArrayP = $pengangkutan->map->nominal->toArray(); 
        $pengangkutan->total = array_sum($sumArrayP);
        $pengangkutan->totalLunas = 0;
        
        // dd($retribusi);
        foreach($pengangkutan as $key=>$p){
            if(!$p->pembayaran->isEmpty()){
                if($p->pembayaran->map->status[0] == 'lunas'){
                    $pengangkutan->lunas += 1;
                    $pengangkutan->totalLunas += $p->nominal;
                }else{
                    $pengangkutan->pending += 1;
                }
            }else{
            
            }
        }
        array_push($detailPengangkutan, $pengangkutan->count());
        array_push($detailPengangkutan, $pengangkutan->lunas);
        array_push($detailPengangkutan, $pengangkutan->pending);
        array_push($detailPengangkutan, $pengangkutan->total);
        array_push($detailPengangkutan, $pengangkutan->totalLunas);

        array_push($data, $retribusi);
        array_push($data, $pengangkutan);
        array_push($data, $detailRetri);
        array_push($data, $detailPengangkutan);
        // dd($pengangkutan->lunas);

        return response()->json($data, 200);
    }

    public function penilaianSearch(Request $request){
        $desaAdmin = auth()->guard('admin')->user()->id_desa_adat;
        $data = [];

        //Retribusi
        $properti = Properti::where('id_desa_adat', $desaAdmin)->where('status', "terverifikasi")->get();
        $retribusi = Retribusi::whereIn('id_properti', $properti->map->id)->whereMonth( 'created_at', $request->bulan)->whereYear( 'created_at', $request->tahun)->get();
        $kepuasanRetribusi = $retribusi->map->kepuasan->filter(function($value, $key){
            return $value->isNotEmpty();
        });
        $rateRetri = 0;
        foreach($kepuasanRetribusi as $kr){
            $rateRetri += $kr[0]->rate;
        }
        $rRate = [];
        $retribusi->totalRate = $kepuasanRetribusi->count();
        $retribusi->rate = round($rateRetri / $kepuasanRetribusi->count(), 1);
        array_push($rRate, $retribusi->rate);
        array_push($rRate, $retribusi->totalRate);

        // Pengangkutan
        $pengangkutan = Pengangkutan::where('id_desa_adat', $desaAdmin)->where('status', '!=', 'Batal')->whereMonth( 'created_at', $request->bulan)->whereYear('created_at', $request->tahun )->with('pembayaran')->get();
        $kepuasanPengangkutan = $pengangkutan->map->kepuasan->filter(function($value, $key){
            return $value->isNotEmpty();
        });
        $ratePengangkutan = 0;
        foreach($kepuasanPengangkutan as $kp){
            $ratePengangkutan += $kp[0]->rate;
        }
        $pRate = [];
        $pengangkutan->rate = round($ratePengangkutan / ($kepuasanPengangkutan->count() != 0 ? $kepuasanPengangkutan->count() : 1), 1);
        $pengangkutan->totalRate = $kepuasanPengangkutan->count();
        array_push($pRate, $pengangkutan->rate);
        array_push($pRate, $pengangkutan->totalRate);
        // dd(explode('.',$pengangkutan->rate)[1]);

        $nilai = KepuasanPengguna::whereHasMorph('item', ["App\Retribusi"], function(Builder $query) use ($request){
            $query->whereMonth('created_at', $request->bulan)->whereYear('created_at', $request->tahun)->whereHas('properti', function(Builder $querys){
                $querys->where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat);
            });
        })->orWhereHasMorph('item', ["App\Pengangkutan"], function(Builder $query) use ($request){
            $query->whereMonth('created_at', $request->bulan)->whereYear('created_at', $request->tahun)->where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat);
        })->with('item')->get();
        
        foreach($nilai as $n){
            $n->nama_pelanggan = $n->item->pelanggan->kependudukan->nama;
            $n->tanggal = $n->created_at->format('d M Y');
        }

        array_push($data, $nilai);
        array_push($data, $pRate);
        array_push($data, $rRate);

        return response()->json($data, 200);


    }
}
