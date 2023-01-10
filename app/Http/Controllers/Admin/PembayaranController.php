<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Pembayaran;
use App\Retribusi;
use App\Pengangkutan;
use App\Pelanggan;
use App\Pegawai;
use App\Properti;


class PembayaranController extends Controller
{
    //
    public function index(){
        $index = Pembayaran::with('detail')->whereHas('detail', function(builder $query){
            $query->with('model')->whereHasMorph('model', ['App\Retribusi'], function(builder $querys){
                $prop = Properti::where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat)->get();
                $ret = Retribusi::whereIn('id_properti', $prop->map->id)->get();
                $req = Pengangkutan::where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat)->get();
                $querys->where('model_type', "App\Retribusi" )->whereIn('model_id', $ret->map->id)->orWhere('model_type', "App\Pengangkutan")->whereIn('model_id',$req->map->id);
                // dd($querys);
            });
        })->orderByRaw("FIELD(status, 'pending', 'lunas') DESC")->get();
        // foreach($index as $pembayaran){
        //     dd($pembayaran->retribusi()->get());
        // }
        // foreach($index as $i){
        //     // dd($i->detail);
        //     foreach($i->detail as $d){
        //         dd($d->model->pelanggan);
        //     }
        // }
        // dd($index);
        return view('admin.pembayaran.index', compact('index'));
    }

    public function create(){

    }

    public function verif($id){
        $pembayaran = Pembayaran::where('id_pembayaran', $id)->first();
        if($pembayaran != null){
            // dd($pembayaran);
            $pembayaran->id_pegawai = auth()->guard('admin')->user()->id_pegawai;
            $pembayaran->status = "lunas";
            foreach($pembayaran->retribusi()->get() as $retribusi){
                if(!empty($retribusi)){
                    // dd($retribusi);
                    $retribusi->status = "lunas";
                    $retribusi->update();
                }
            }
            foreach($pembayaran->pengangkutan()->get() as $pengangkutan){
                if(!empty($pengangkutan)){
                    // dd($pengangkutan);
                    $pengangkutan->status = "lunas";
                    $pengangkutan->update();
                }
            }
            $pembayaran->update();
            return redirect()->back()->with('success', 'Pembayaran berhasil terverifikasi !');
        }else{
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan !');
        }
    }

    public function update(){

    }

    public function delete(){

    }

    public function search(Request $request){
        // echo($request->pembayaran);
        $pembayaran = Pembayaran::where('id_pembayaran', $request->pembayaran)->first();
        // echo($pembayaran);
        $detail = $pembayaran->detail;
        // echo($detail->map->model->map->pelanggan->map->kependudukan);
        $model = $detail;
        foreach($model as $m){
            $m->tanggal = $m->model->created_at->format('d M Y');
            if($m->model_type == "App\\Retribusi"){
                // dd($m->properti = $m->model->properti);
                $m->pelanggan = $m->model->pelanggan->kependudukan->nama;
                $m->properti = $m->model->properti->nama_properti;
            }elseif($m->model_type == "App\\Pengangkutan"){
                // dd($m->model->pengangkutan->alamat);
                $m->pelanggan = $m->model->pelanggan->kependudukan->nama;
                $m->properti = $m->model->pengangkutan->alamat;
            }
        }
        $data = $model;
        // echo($model->map->model_type."-".$model->map->pelanggan."-".$model->map->properti);
        return response()->json($data, 200);
    }
}
