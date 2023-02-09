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
        // dd($pengangkutan->lunas);

        return view('admin.report.index', compact('pengangkutan', 'retribusi'));
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
}
