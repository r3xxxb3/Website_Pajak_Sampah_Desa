<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\PengangkutanNotif;
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
        $desaAdat = auth()->guard('admin')->user()->id_desa_adat;
        $pelanggan = Pelanggan::whereHas('properti', function(Builder $query) use ($desaAdat){
            $query->where('id_desa_adat', $desaAdat);
        })->orWhereHas('pengangkutan', function(Builder $query) use ($desaAdat){
            $query->where('id_desa_adat', $desaAdat);
        })->get();
        return view('admin.request.create', compact('desaAdat', 'pelanggan'));
    }

    public function store(Request $request){
        
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
        
        $this->validate($request, [
            'file' => 'max:5120',
            'pelanggan' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'alamat' => 'required',
        ],$messages);
        

        $pelanggan = Pelanggan::where('id', $request->pelanggan)->first();
        if(!isset($pelanggan)){
            return redirect()->back()->with('error', "Pelanggan tidak ditemukan !");
        }

        $requestP = new Pengangkutan;

        if($request->file('file')){
            //simpan file
            
            $file = $request->file('file');
            $images = $pelanggan->kependudukan->nik."_".$file->getClientOriginalName();
            // dd($images);
            $requestP->file = $images;
            $foto_upload = 'assets/img/request_p';
            $file->move($foto_upload,$images);
        }
        $requestP->id_pelanggan = $pelanggan->id;
        $requestP->lat = $request->lat;
        $requestP->lng = $request->lng;
        $requestP->id_desa_adat = auth()->guard('admin')->user()->id_desa_adat;
        $requestP->alamat = $request->alamat;
        $requestP->status = "Pending";
        if($requestP->save()){
            $pelanggan->notify(new PengangkutanNotif($requestP, "create"));
            return redirect()->route('admin-request-index')->with('success', 'Berhasil membuat request pengangkutan !');
        }else{
            return redirect()->back()->with('error', 'Gagal membuat request pengangkutan !');
        }
    }

    public function edit($id){
        $requestP = Pengangkutan::where('id', $id)->first();
        $desaAdat = auth()->guard('admin')->user()->id_desa_adat;
        $pelanggan = Pelanggan::whereHas('properti', function(Builder $query) use ($desaAdat){
            $query->where('id_desa_adat', $desaAdat);
        })->orWhereHas('pengangkutan', function(Builder $query) use ($desaAdat){
            $query->where('id_desa_adat', $desaAdat);
        })->get();
        return view('admin.request.edit', compact('requestP', 'pelanggan'));
    }

    public function update(Request $request, $id){
        
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
        
        $this->validate($request, [
            'file' => 'max:5120',
            'pelanggan' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'alamat' => 'required',
        ],$messages);
        

        $pelanggan = Pelanggan::where('id', $request->pelanggan)->first();
        if(!isset($pelanggan)){
            return redirect()->back()->with('error', "Pelanggan tidak ditemukan !");
        }

        $requestP = Pengangkutan::where('id', $id)->first();
        if(isset($requestP)){
            if($request->file('file')){
                //simpan file
                
                $file = $request->file('file');
                $images = $pelanggan->kependudukan->nik."_".$file->getClientOriginalName();
                // dd($images);
                $requestP->file = $images;
                $foto_upload = 'assets/img/request_p';
                $file->move($foto_upload,$images);
            }
            $requestP->id_pelanggan = $pelanggan->id;
            $requestP->lat = $request->lat;
            $requestP->lng = $request->lng;
            $requestP->id_desa_adat = auth()->guard('admin')->user()->id_desa_adat;
            $requestP->alamat = $request->alamat;
            $requestP->status = "Pending";
            if($requestP->update()){
                $pelanggan->notify(new PengangkutanNotif($requestP, "update"));
                return redirect()->route('admin-request-index')->with('success', 'Berhasil mengubah request pengangkutan !');
            }else{
                return redirect()->back()->with('error', 'Gagal mengubah request pengangkutan !');
            }
        }else{
            return redirect()->back()->with('error', 'Data request pengangkutan tidak ditemukan !');
        }
        
    }

    public function verif(Request $request){
        $requestP = Pengangkutan::where('id', $request->idReq)->first();
        $pelanggan = $requestP->pelanggan;
        // dd($requestP);
        if(isset($requestP)){
            if($requestP->status == "Pending"){
                return redirect()->back()->with('error', "Lakukan Konfirmasi request pengangkutan terlebih dahulu !");
            }elseif($requestP->status == "Terkonfirmasi"){
                if($request->nominal != 0){
                    if($request->file('bukti')){
                        //simpan file
                        $file = $request->file('bukti');
                        $images = "proof-".$requestP->id.auth()->guard('admin')->user()->id_pegawai."_".$file->getClientOriginalName();
                        // dd($images);
                        $requestP->proof_image = $images;
                        $foto_upload = 'assets/img/request_p/bukti';
                        $file->move($foto_upload,$images);
                    }
                    $requestP->nominal = $request->nominal;
                    $requestP->status = "Selesai";
                    if($requestP->update()){
                        $pelanggan->notify(new PengangkutanNotif($requestP, "verify"));
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
        $pelanggan = $requestP->pelanggan;
        if(isset($requestP)){
            $requestP->status = "Terkonfirmasi";
            if($requestP->save()){
                $pelanggan->notify(new PengangkutanNotif($requestP, "confirm"));
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
