<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DesaAdat;
use App\Jadwal;
use App\JenisSampah;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function landing(){
        $desa = DesaAdat::all();
        $jenis = JenisSampah::all();
        return view('layouts.landing', compact('desa', 'jenis'));
    }

    public function searchJadwal(Request $request){
        $jadwal = Jadwal::where('id_desa', $request->id)->get();
        foreach($jadwal as $j){
            if($j->jenis != null){
                $j->jenis_sampah = $j->jenis->jenis_sampah;  
            }else{
                $j->jenis_sampah = "Umum";
            }
        }
        return response()->json($jadwal, 200);
    }
}
