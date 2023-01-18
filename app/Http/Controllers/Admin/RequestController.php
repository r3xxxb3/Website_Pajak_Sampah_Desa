<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pelanggan;
use App\Pengangkutan;
use App\Properti;
use App\JenisJasa;
use App\Retribusi;
use App\Pembayaran;
use App\DesaAdat;


class RequestController extends Controller
{
    //
    public function index(){
        $index = Pengangkutan::where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat)->orderByRaw("FIELD(status, 'selesai', 'pending') DESC")->get();
        // dd($index);
        return view('admin.request.index', compact('index'));
    }

    public function create(){
        
    }

    public function store(Request $request){

    }

    public function edit($id){
        $requestP = Pengangkutan::where('id', $id)->first();
        $desaAdat = DesaAdat::all();
        return view('admin.request.edit', compact('requestP', 'desaAdat'));
    }

    public function update(Request $request, $id){

    }

    public function verif(Request $request){
        $requestP = Pengangkutan::where('id', $request->idReq)->first();
        // dd($requestP);
        if(isset($requestP)){
            if($requestP->status == "Pending"){
                return redirect()->back()->with('error', "Lakukan Konfirmasi request pengangkutan terlebih dahulu !");
            }elseif($requestP->status == "Terkonfirmasi"){
                if($request->nominal != 0){
                    $requestP->nominal = $request->nominal;
                    $requestP->status = "Selesai";
                    if($requestP->update()){
                        return redirect()->route('admin-request-index')->with('success', "Verifikasi Request Pengangkutan Berhasil !");
                    }else{
                        return redirect()->back()->with('error', "verifikasi Request Pengangkutan Gagal !");
                    }
                }else{
                    return redirect()->back()->with('error', "Tetapkan Nominal request pengangkutan terlebih dahulu !");
                }
            }
        }else{
            return redirect()->back()->with('error', "Data Request tidak ditemukan !");
        }
    }

    public function confirm($id){
        $requestP = Pengangkutan::where('id', $id)->first();
        if(isset($requestP)){
            $requestP->status = "Terkonfirmasi";
            if($requestP->save()){
                return redirect()->back()->with('success', "Request Pengangkutan berhasil terkonfirmasi !");
            }else{
                return redirect()->back()->with('success', "Request Pengangkutan gagal terkonfirmasi !");
            }
        }else{
            return redirect()->back()->with('error', "Request Pengangkutan tidak ditemukan !");
        }
    }

    public function cancel($id){
        $requestP = Pengangkutan::where('id', $id)->first();
        if($requestP->status == 'Pending'){
            $requestP->status = "Batal";
            $requestP->update();
            return redirect()->route('admin-request-index')->with('success', 'Berhasil membatalkan request pengangkutan !');  
        }else{
            return redirect()->back()->with('error', 'Proses pembatalan request pengangkutan tidak dapat dilakukan !');
        }
    }

    public function delete($id){
        $requestP = Pengangkutan::where('id', $id)->first();
        if($requestP->status != "Selesai" || $requestP->status != "Terkonfirmasi" ){
            if($requestP->status == null){
                $requestP->delete();
                return redirect()->route('admin-request-index')->with('success', 'Berhasil menghapus request pengangkutan !');  
            }else{
                return redirect()->back()->with('error', 'Proses penghapusan request pengangkutan tidak dapat dilakukan !');
            }
        }
    }


}
