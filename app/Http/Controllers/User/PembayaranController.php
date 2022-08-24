<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pegawai;
use App\Properti;
use App\Pengguna;
use App\Retribusi;
use App\Pembayaran;

class PembayaranController extends Controller
{
    //
    public function store (Request $request){

        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
            'max' => 'Ukuran File tidak boleh melebihi 5 MB',
            'numeric' => 'Kolom :attribute Hanya  Menerima Inputan Angka  !'
		];

        // dd($request->id);
        // dd($request->file);
        // dd($request->media);
        // dd($request->nominal);
        
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
            $images = auth()->guard('web')->user()->nik."_".$file->getClientOriginalName();
            // dd($images);
            $pembayaran->bukti_bayar = $images;
            $foto_upload = 'assets/img/bukti_bayar';
            $file->move($foto_upload,$images);
        }
        $pembayaran->id_pengguna = auth()->guard('web')->user()->id;
        $pembayaran->media = $request->media;
        $pembayaran->nominal = $request->nominal;
        $pembayaran->jenis = $request->type;
        if($pembayaran->save()){
            if(is_array($request->id)){
                foreach($request->id as $id){
                    $pembayaran->retribusi()->attach($id);
                }
            }else{
                $pembayaran->retribusi()->attach($request->id); 
            }
            return redirect()->back()->with('success', 'Berhasil Melakukan Pembayaran untuk Retribusi !');
        }else{
            return redirect()->back()->with('error', 'Pembayaran Gagal untuk Dilakukan !');
        }

    }
}