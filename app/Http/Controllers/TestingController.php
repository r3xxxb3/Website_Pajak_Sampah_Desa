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
        //     print_r($prop->nama_properti);
        //     $retribusi = Retribusi::where('id_properti', $prop->id)->orderBy('created_at', 'DESC')->first();
        //     $standar = StandarRetribusi::where('id_jenis_jasa', $prop->id_jenis)->where(function ($query){
        //         $query->where('tanggal_berlaku', '<=', now())->orWhere('tanggal_selesai', '>=', now())->orWhere('active', 1);
        //     })->first();
        //     // $stanres = $standar->where('tanggal_berlaku', '<=', now())->where('tanggal_selesai', '>=', now())->Where('active', 1)->first();
        //     // dd($standar);
        //     if(!isset($retribusi)){
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
        //     }elseif($retribusi->created_at->format('m') != now()->format('m') && $retribusi->created_at->format('m') < now()->format('m') ){
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
