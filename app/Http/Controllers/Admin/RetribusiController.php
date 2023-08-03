<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pelanggan;
use App\Properti;
use App\JenisJasa;
use App\StandarRetribusi;
use App\Retribusi;
use App\Pembayaran;

class RetribusiController extends Controller
{
    //
    public function index(){
        $index = Retribusi::whereHas('properti', function (Builder $query){
            $query->where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat);
        })->where('status', 'pending')->get();
        // dd($retribusi);
        // foreach($retribusi as $i){
        //     if(isset($i->properti->desaAdat)){
        //         // dd($i->properti->desaAdat);
        //         $index = $i;
        //     }
        // }
        return view('admin.retribusi.index', compact('index'));
    }

    public function history(){
        $index = Retribusi::whereHas('properti', function (Builder $query){
            $query->where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat);
        })->where('status', 'lunas')->get();
        return view('admin.retribusi.history', compact('index'));
    }
    
    public function generate(){
        $properti = Properti::where('status', 'terverifikasi')->get();
        $standar = "";
        // $this->info("test");
        foreach($properti as $prop){
            $retribusi = Retribusi::where('id_properti', $prop->id)->orderBy('created_at', 'DESC')->first();
            $countR = Retribusi::where('id_properti', $prop->id)->where('status', "pending")->orderBy('created_at', 'DESC')->get()->count();
            $standar = StandarRetribusi::where('id', $prop->id_standar_retribusi)->first();
            // $stanres = $standar->where('tanggal_berlaku', '<=', now())->where('tanggal_selesai', '>=', now())->Where('active', 1)->first();
            // dd($properti);
            if(!isset($retribusi)){
                if(isset($standar)){
                    $retribusi = new Retribusi;
                    $retribusi->id_pelanggan = $prop->id_pelanggan;
                    $retribusi->id_properti = $prop->id;
                    $retribusi->status = "pending";
                    $retribusi->nominal = $standar->nominal_retribusi;
                    $retribusi->save();
                }else{
                    continue;
                    $status = 'Terdapat properti dengan standar retribusi yang belum ditetapkan !';
                    // return redirect()->back()->with('error', "Tetapkan Standar retribusi pada jenis jasa terlebih dahulu !");
                }
            }elseif($retribusi->created_at->format('m') != now()->format('m') || $retribusi->created_at->format('y') < now()->format('y') ){
                if(isset($standar)){
                    $maxP = $standar->max_pending;
                    if($countR == $maxP){
                        $properti = Properti::where('id_properti', $retribusi->properti->id)->first();
                        $properti->status = "batal";
                        $properti->update();
                    }elseif($countR == ($maxP - 1)){
                        $retribusi = new Retribusi;
                        $retribusi->id_pelanggan = $prop->id_pelanggan;
                        $retribusi->id_properti = $prop->id;
                        $retribusi->status = "pending";
                        $retribusi->nominal = $standar->nominal_retribusi;
                        $retribusi->save();
                        $retribusi->pelanggan->notify(new PropertiNotif($properti, "warning"));
                    }else{
                        $retribusi = new Retribusi;
                        $retribusi->id_pelanggan = $prop->id_pelanggan;
                        $retribusi->id_properti = $prop->id;
                        $retribusi->status = "pending";
                        $retribusi->nominal = $standar->nominal_retribusi;
                        $retribusi->save();
                    }
                }else{
                    $status = 'Terdapat properti dengan standar retribusi yang belum ditetapkan !';
                    // return redirect()->back()->with('error', "Tetapkan Standar retribusi pada jenis jasa terlebih dahulu !");
                }
            }else{
                continue;
            }
        }
        return redirect()->route('admin-retribusi-index', compact('status'))->with('success', "Berhasil membuat retribusi bulanan ! ");
    }

    public function update(){
        
    }

    public function verif(){
        
    }

    public function verifMany(Request $request){
        dd($request->id);
    }
}
