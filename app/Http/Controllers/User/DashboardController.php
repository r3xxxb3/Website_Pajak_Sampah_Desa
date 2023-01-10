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

class DashboardController extends Controller
{
    //
    public function dashboard(){
        // dd(auth()->guard('web')->user()->id);
        $properti = Properti::where('id_pelanggan', auth()->guard('web')->user()->id)->where('status', "terverifikasi")->get()->count();
        $retribusi = Retribusi::where('id_pelanggan', auth()->guard('web')->user()->id)->where('status', 'pending')->get()->count();
        $request = Pengangkutan::where('id_pelanggan', auth()->guard('web')->user()->id)->where('status', 'pending')->get()->count();
        $pembayaran = Pembayaran::where('id_pelanggan', auth()->guard('web')->user()->id)->where('status', 'pending')->get()->count();
        // dd($properti->map->nama_properti);
        return view('user.dashboard', compact('properti', 'retribusi', 'request', 'pembayaran'));
    }
}
