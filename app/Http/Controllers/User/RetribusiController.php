<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Retribusi;
use App\Pelanggan;
use App\Properti;
use App\JenisJasa;
use App\Keranjang;
use App\Pembayaran;


class RetribusiController extends Controller
{
    //
    public function index(){
        $index = Retribusi::where('id_pelanggan', auth()->guard('web')->user()->id)->with('Dpembayaran')->orderByRaw("FIELD(status, 'pending', 'lunas ') DESC")->get();
        // dd($index);
        return view('user.retribusi.index', compact('index'));
    }

    public function keranjang(Request $request){
        $info = array(
            'stat'=>null,
            'desc'=>null
        );
        if($request->id != null){
            $status = Keranjang::where('model_id', $request->id)->get();
            if(!$status->isEmpty()){
                $info['desc'] = "Retribusi sudah terdaftar dalam keranjang !";
                // dd($info);
                $info['stat'] = "Error";
                return response()->json($info, 200);
                // return redirect()->back()->with('error', "Retribusi sudah terdaftar dalam keranjang !");
            }else{
                $retri = Retribusi::where('id', $request->id)->first();
                $prop = Properti::where('id', $retri->id_properti)->first();
                if($retri->status != 'lunas'){
                    $keranjang = New Keranjang;
                    $keranjang->id_pelanggan = auth()->guard('web')->user()->id;
                    $keranjang->id_desa_adat = $prop->id_desa_adat;
                    $keranjang->model_id = $request->id;
                    $keranjang->model_type = "App\Retribusi";
                    $keranjang->save();
                    $info['stat'] = "success";
                    $info['desc'] = "Retribusi berhasil terdaftar dalam keranjang !";
                    return response()->json($info, 200);
                }else{
                    $info['stat'] = "error";
                    $info['desc'] = "Retribusi telah lunas !";
                    return response()->json($info, 200);
                }
            }
        }else{
            $info['stat'] = "error";
            $info['desc'] = "Id Retribusi yang dikirim tidak terbaca !";
            return response()->json($info, 200);
        }
    }

    public function keranjangView(){
        $keranjang = Keranjang::where('id_pelanggan', auth()->guard('web')->user()->id)->where('model_type', "App\\Retribusi")->with('model')->get();
        // dd($keranjang->map->model);
        $retribusi = $keranjang->map->model;
        if($retribusi->count() > 1){
            foreach($retribusi as $r){
                $r->tanggal = $r->created_at->format('d M Y');
                $r->jenis = $r->properti->jasa->jenis_jasa;
            }
        }else{
            foreach($retribusi as $r){
                $r->tanggal = $r->created_at->format('d M Y');
                $r->jenis = $r->properti->jasa->jenis_jasa;
                // dd($r);
            }
            // $tanggal = $retribusi->first();
            // $retribusi->map->created_at = $tanggal->created_at->format('d M Y');
            // $retribusi->map->jenis = $retribusi->map->properti->map->jasa->map->jenis_jasa;
        }
        // dd($retribusi);
        if(isset($retribusi)){
            return response()->json($retribusi, 200);
        }else{
            return response()->json(null, 200);
        }
    }

    public function keranjangHapus(Request $request){
        $keranjang = Keranjang::where('model_id', $request->id)->where('model_type', "App\\Retribusi")->first();
        if(isset($keranjang)){
            $keranjang->forceDelete();
            $info['stat'] = "success";
            $info['desc'] = "Retribusi sudah dihapus dari keranjang !";
            return response()->json($info, 200);
        }else{
            $info['stat'] = "error";
            $info['desc'] = "Retribusi tidak ditemukan dalam keranjang !";
            return response()->json($info, 200);
        }
    }

    public function pay (Request $request){

        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
            'max' => 'Ukuran File tidak boleh melebihi 5 MB',
            'numeric' => 'Kolom :attribute Hanya  Menerima Inputan Angka  !'
		];
        
        if($request->media == "transfer"){
            $this->validate($request, [
                'file' => 'required|max:5120',
                'nominal' => 'required|numeric',
                'media' => 'required',
                'id' => 'required|array',
            ],$messages);
        }else{
            $this->validate($request, [
                'nominal' => 'required',
                'media' => 'required',
                'id' => 'required|array',
            ],$messages);
        }


        $pembayaran = new Pembayaran;

        if($request->file('file')){
            //simpan file
            
            $file = $request->file('file');
            $images = auth()->guard('web')->user()->kependudukan->nik."_".$file->getClientOriginalName();
            // dd($images);
            $pembayaran->bukti_bayar = $images;
            $foto_upload = 'assets/img/bukti_bayar';
            $file->move($foto_upload,$images);
        }
        $pembayaran->id_pelanggan = auth()->guard('web')->user()->id;
        $pembayaran->media = $request->media;
        $pembayaran->nominal = $request->nominal;
        $pembayaran->jenis = $request->type;
        // dd(explode(",",$request->id[0]));
        $id = explode(",",$request->id[0]);
        // dd($id);
        if($pembayaran->save()){
            if(is_array($id)){
                // dd(count(array_unique($request->id)));
                $retribusi = Retribusi::whereIn('id', $id)->get();
                $keranjang = Keranjang::whereIn('model_id', $id)->where('model_type',  "App\\Retribusi")->get();
                // dd($keranjang);
                foreach($keranjang as $k){
                    $k->forceDelete();
                }
                $properti = Properti::whereIn('id', $retribusi->map->id_properti)->get();
                // dd(count(array_unique($properti->map->id_desa_adat->toArray())));
                if(count(array_unique($properti->map->id_desa_adat->toArray())) > 1){
                    return redirect()->back()->with('error', 'Pembayaran sekaligus hanya dapat dilakukan untuk properti dengan Desa Adat yang sama !');
                }else{
                    foreach($retribusi as $ret){
                        // dd($retribusi);
                        $pembayaran->retribusi()->attach($ret);
                    }
                }
            }else{
                $keranjang = Keranjang::whereIn('model_id', $request->id)->where('model_type',  "App\\Retribusi")->first();
                $keranjang->forceDelete();
                $retribusi = Retribusi::whereIn('id', $request->id)->get();
                $pembayaran->retribusi()->attach($request->id); 
            }
            return redirect()->back()->with('success', 'Berhasil Melakukan Pembayaran untuk Retribusi !');
        }else{
            return redirect()->back()->with('error', 'Pembayaran Gagal untuk Dilakukan !');
        }

    }


}
