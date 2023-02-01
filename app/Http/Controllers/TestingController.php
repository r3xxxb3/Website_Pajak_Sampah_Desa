<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Properti;
use App\StandarRetribusi;
use App\Retribusi;
use Illuminate\Support\Facades\Hash;
use Log;

class TestingController extends Controller
{
    //
    public function test($id){
        dd(Hash::make($id));
        // $properti = Properti::where('status', 'terverifikasi')->get();
        // dd($properti);
        // $this->info("test");
    //     foreach($properti as $i=>$prop){
    //         $retribusi = Retribusi::where('id_properti', $prop->id)->orderBy('created_at', 'DESC')->first();
    //         $standar = StandarRetribusi::where('id_jenis_jasa', $prop->id_jenis)->where('id_desa_adat', $prop->id_desa_adat)->where(function ($query){
    //             $query->where('tanggal_berlaku', '<=', now())->orWhere('tanggal_selesai', '>=', now())->orWhere('active', 1);
    //         })->first();
    //         // $stanres = $standar->where('tanggal_berlaku', '<=', now())->where('tanggal_selesai', '>=', now())->Where('active', 1)->first();
    //         // dd($properti);
    //         if(!isset($retribusi)){
    //             $standar = StandarRetribusi::where('id_jenis_jasa', $prop->id_jenis)->get();
    //             // dd($standar);
    //             dd($retribusi);
    //             if(isset($standar)){
    //                 $retribusi = new Retribusi;
    //                 $retribusi->id_pelanggan = $prop->id_pelanggan;
    //                 $retribusi->id_properti = $prop->id;
    //                 $retribusi->status = "pending";
    //                 $retribusi->nominal = $standar->nominal_retribusi;
    //                 $retribusi->save();
    //             }else{
    //                 continue;
    //             }
    //         }elseif($retribusi->created_at->format('m') != now()->format('m') || $retribusi->created_at->format('y') < now()->format('y') ){
    //             dd($retribusi->created_at->format('m') != now()->format('m'));
    //             if(isset($standar)){
    //                 $retribusi = new Retribusi;
    //                 $retribusi->id_pelanggan = $prop->id_pelanggan;
    //                 $retribusi->id_properti = $prop->id;
    //                 $retribusi->status = "pending";
    //                 $retribusi->nominal = $standar->nominal_retribusi;
    //                 $retribusi->save();
    //             }else{
    //                 continue;
    //             }
    //         }else{
    //             dd($retribusi->created_at->format('m') != now()->format('m') || $retribusi->created_at->format('y') < now()->format('y') );
    //             continue;
    //         }
    //     }

    }
}
