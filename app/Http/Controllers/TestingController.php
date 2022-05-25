<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Properti;
use App\StandarRetribusi;
use App\Retribusi;

class TestingController extends Controller
{
    //
    public function test(){
        // $properti = Properti::where('status', 'Verified')->get();
        // foreach($properti as $prop){
        //     $retribusi = Retribusi::where('id_properti', $prop->id)->orderBy('created_at', 'ASC')->first();
        //     $standar = StandarRetribusi::where('id_jenis_jasa', $prop->id_jenis)->get();
        //     $stanres = $standar->where('tanggal_berlaku', '<=', now())->where('tanggal_selesai', '>=', now())->Where('active', 1)->first();
        //     if(!isset($retribusi)){
        //         if($prop->id_jenis == 5 || $prop->id_jenis == 5){
        //             // dd('asem');
        //         }
        //         if(isset($standar)){
        //             $retribusi = new Retribusi;
        //             $retribusi->id_pengguna = $prop->id_pengguna;
        //             $retribusi->id_properti = $prop->id;
        //             $retribusi->status = "pending";
        //             $retribusi->nominal = $stanres->nominal_retribusi;
        //             $retribusi->save();
        //         }else{
        //             continue;
        //         }
        //     }elseif($retribusi->created_at->format('m') != now()->format('m')){
        //         if($prop->id_jenis == 5 || $prop->id_jenis == 5){
        //             dd('manis');
        //         }
        //         if(isset($standar)){
        //             $retribusi = new Retribusi;
        //             $retribusi->id_pengguna = $prop->id_pengguna;
        //             $retribusi->id_properti = $prop->id;
        //             $retribusi->status = "pending";
        //             $retribusi->nominal = $stanres->nominal_retribusi;
        //             $retribusi->save();
        //         }else{
        //             continue;
        //         }
        //     }else{
        //         continue;
        //     }
        // }
    }
}
