<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pelanggan;
use App\Properti;
use App\Retribusi;
use App\Pengangkutan;
use App\Pembayaran;
use App\DesaAdat;
use App\BanjarAdat;
use App\Kependudukan;
use App\Jadwal;

class DashboardController extends Controller
{
    //
    public function dashboard(){
        // dd(auth()->guard('web')->user()->id);
        if(isset(auth()->guard('web')->user()->kependudukan->mipil->banjarAdat)){
            $jadwal = Jadwal::where('id_desa', auth()->guard('web')->user()->kependudukan->mipil->banjarAdat->desaAdat->id)->get();
        }elseif(isset(auth()->guard('web')->user()->kependudukan->Ktamiu->banjarAdat)){
            $jadwal = Jadwal::Where('id_desa', auth()->guard('web')->user()->kependudukan->Ktamiu->banjarAdat->desaAdat->id)->get();
        }elseif(isset(auth()->guard('web')->user()->kependudukan->tamiu->banjarAdat)){
            $jadwal = Jadwal::Where('id_desa', auth()->guard('web')->user()->kependudukan->tamiu->banjarAdat->desaAdat->id)->get();
        }else{
            $jadwal = null;
        }
        // dd($jadwal);
        $properti = Properti::where('id_pelanggan', auth()->guard('web')->user()->id)->where('status', "terverifikasi")->get()->count();
        $retribusi = Retribusi::where('id_pelanggan', auth()->guard('web')->user()->id)->where('status', 'pending')->get()->count();
        $request = Pengangkutan::where('id_pelanggan', auth()->guard('web')->user()->id)->where('status', 'pending')->get()->count();
        $pembayaran = Pembayaran::where('id_pelanggan', auth()->guard('web')->user()->id)->where('status', 'pending')->get()->count();
        // dd($properti->map->nama_properti);
        return view('user.dashboard', compact('jadwal','properti', 'retribusi', 'request', 'pembayaran'));
    }
}
