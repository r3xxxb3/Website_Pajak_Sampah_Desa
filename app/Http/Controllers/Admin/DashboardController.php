<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\KramaMipil;
use App\KramaTamiu;
use App\Tamiu;
use App\Pelanggan;
use App\Pegawai;
use App\DesaAdat;
use App\BanjarAdat;
use App\Properti;
use App\JenisJasa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    
    public function __construct ()
    {
        
    }

    public function index ()
    {
        $checkKrama = KramaMipil::where('penduduk_id', auth()->guard('admin')->user()->kependudukan->id)->first();
        if(!isset($checkKrama)){
            $checkKrama = KramaTamiu::where('penduduk_id', auth()->guard('admin')->user()->kependudukan->id)->first();
            if(!isset($checkKrama)){
                $checkKrama = Tamiu::where('penduduk_id', auth()->guard('admin')->user()->kependudukan->id)->first();
            }
        }
        $banjarAdat = BanjarAdat::where('id',$checkKrama->banjar_adat_id)->first();
        if(isset($banjarAdat)){
            $desaAdat = DesaAdat::where('id',$banjarAdat->desa_adat_id)->first();
            // dd($desaAdat);
            $penduduk = [];
            if(isset($desaAdat)){
                foreach($desaAdat->banjarAdat as $loop=>$banjar){
                    $data = KramaMipil::where('banjar_adat_id', $banjar->id)->get()->toArray();
                    if(!empty($data)){
                        foreach($data as $index=>$d){
                            array_push($penduduk,$d['penduduk_id']);
                            // dd($penduduk);
                        }
                    }
                    $data = KramaTamiu::where('banjar_adat_id', $banjar->id)->get()->toArray();
                    if(!empty($data)){
                        foreach($data as $index=>$d){
                            array_push($penduduk,$d['penduduk_id']);
                            // dd($penduduk);
                        }
                    }
                    $data = Tamiu::where('banjar_adat_id', $banjar->id)->get()->toArray();
                    if(!empty($data)){
                        foreach($data as $index=>$d){
                            array_push($penduduk,$d['penduduk_id']);
                            // dd($penduduk);
                        }
                    }
                }
                // dd($penduduk);
                $indexPl = Pelanggan::whereHas('properti', function (Builder $query){
                    $query->where('id_desa_adat', auth()->guard('admin')->user()->kependudukan->mipil->banjarAdat->desaAdat->id);
                })->orWhereIn('id_penduduk', $penduduk)->get();
                $indexPg = Pegawai::where(function (Builder $query){
                    $query->where('id_desa_adat', auth()->guard('admin')->user()->kependudukan->mipil->banjarAdat->desaAdat->id);
                })->orWhereIn('id_penduduk', $penduduk)->get();
                $pengguna = $indexPl->count();
                $pegawai = $indexPg->count();
                $properti = 0;
                foreach($indexPl as $in){
                    if(Properti::where('id_pelanggan', $in->id)->where('status', "terverifikasi")->get()->isEmpty()){
                        continue;
                    }else{
                        $properti += Properti::where('id_pelanggan', $in->id)->where('status', "terverifikasi")->get()->count();
                    }
                }
                // dd($properti);
                $indexPr = Properti::whereIn('id_pelanggan', $indexPl->map->id)->where('status', "pending")->get();
                $jenis = JenisJasa::all();
                $desaAdat = DesaAdat::all();
                $banjarAdat = BanjarAdat::All();
                return view('admin.dashboard.index',compact('pengguna', 'properti', 'pegawai','indexPr', 'jenis', 'desaAdat', 'banjarAdat'));
            }else{
                return view('admin.dashboard.index')->with('error', "Desa Adat tidak ditemukan !");
            }
            
        }else{
            return view('admin.dashboard.index')->with('error', "Banjar Adat tidak ditemukan !");
        }
        // dd(Auth::guard('admin')->user()->kependudukan);
        // dd($pelanggan = Pelanggan::where('id', Auth::guard('admin')->user()->id_penduduk)->first());
        // return view('admin.dashboard.index');
    }
    //
}
