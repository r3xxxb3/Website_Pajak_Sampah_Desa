<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Notifications\PengangkutanNotif;
use Illuminate\Http\Request;
use App\Pegawai;
use App\Pengangkutan;
use App\DesaAdat;
use App\keranjang;
use App\DetailPembayaran;
use App\Pembayaran;

class RequestController extends Controller
{
    //
    public function index(){
        $index = Pengangkutan::where('id_pelanggan', auth()->guard('web')->user()->id)->get();
        return view('user.request.index', compact('index'));
    }

    public function create(){
        $desaAdat = DesaAdat::all();
        return view('user.request.create', compact('desaAdat'));
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
            'desaAdat' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'alamat' => 'required',
        ],$messages);
        


        $requestP = new Pengangkutan;

        if($request->file('file')){
            //simpan file
            
            $file = $request->file('file');
            $images = auth()->guard('web')->user()->kependudukan->nik."_".$file->getClientOriginalName();
            // dd($images);
            $requestP->file = $images;
            $foto_upload = 'assets/img/request_p';
            $file->move($foto_upload,$images);
        }
        $requestP->id_pelanggan = auth()->guard('web')->user()->id;
        $requestP->lat = $request->lat;
        $requestP->lng = $request->lng;
        $requestP->id_desa_adat = $request->desaAdat;
        $requestP->alamat = $request->alamat;
        $requestP->status = "Pending";
        if($requestP->save()){
            $pegawai = Pegawai::where('id_desa_adat', $requestP->id_desa_adat)->get();
            // dd($properti->id_jenis);
            // $properti->toArray();
            foreach($pegawai as $p){
                $p->notify(new PengangkutanNotif($requestP, "create"));
            }
            return redirect()->route('request-index')->with('success', 'Berhasil membuat request pengangkutan !');
        }else{
            return redirect()->back()->with('error', 'Gagal membuat request pengangkutan !');
        }
    }

    public function edit($id){
        $requestP = Pengangkutan::where('id', $id)->first();
        $desaAdat = DesaAdat::all();
        return view('user.request.edit', compact('requestP', 'desaAdat'));
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
            'desaAdat' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'alamat' => 'required',
        ],$messages);
        


        $requestP = Pengangkutan::where('id', $id)->first();


        if(isset($requestP)){
            if($request->file('file')){
                //simpan file
                
                $file = $request->file('file');
                $images = auth()->guard('web')->user()->nik."_".$file->getClientOriginalName();
                // dd($images);
                $requestP->file = $images;
                $foto_upload = 'assets/img/request_p';
                $file->move($foto_upload,$images);
            }
            $requestP->id_pelanggan = auth()->guard('web')->user()->id;
            $requestP->lat = $request->lat;
            $requestP->lng = $request->lng;
            $requestP->alamat = $request->alamat;
            $requestP->id_desa_adat = $request->desaAdat;
            $requestP->status = "Pending";
            if($requestP->update()){
                $pegawai = Pegawai::where('id_desa_adat', $requestP->id_desa_adat)->get();
                // dd($properti->id_jenis);
                // $properti->toArray();
                foreach($pegawai as $p){
                    $p->notify(new PengangkutanNotif($requestP, "update"));
                }
                return redirect()->route('request-index')->with('success', 'Berhasil mengubah request pengangkutan !');
            }else{
                return redirect()->back()->with('error', 'Gagal mengubah request pengangkutan !');
            }
        }else{
            return redirect()->back()->with('error', 'Gagal mengubah request pengangkutan !');
        }

    }

    public function cancel($id){
        $requestP = Pengangkutan::where('id', $id)->first();
        if($requestP->status == 'Pending'){
            $requestP->status = "Batal";
            $requestP->update();
            $pegawai = Pegawai::where('id_desa_adat', $requestP->id_desa_adat)->get();
            // dd($properti->id_jenis);
            // $properti->toArray();
            foreach($pegawai as $p){
                $p->notify(new PengangkutanNotif($requestP, "cancel"));
            }
            return redirect()->route('request-index')->with('success', 'Berhasil membatalkan request pengangkutan !');  
        }else{
            return redirect()->back()->with('error', 'Proses pembatalan request pengangkutan tidak dapat dilakukan !');
        }
    }

    public function keranjang(Request $request){
        $info = array(
            'stat'=>null,
            'desc'=>null
        );
        if($request->id != null){
            $status = Keranjang::where('model_id', $request->id)->where('model_type', "App\\Pengangkutan")->get();
            $statusP = DetailPembayaran::where('model_id', $request->id)->where('model_type', "App\\Pengangkutan")->get();
            if(!$status->isEmpty()){
                $info['desc'] = "Pengangkutan sudah terdaftar dalam keranjang !";
                // dd($info);
                $info['stat'] = "Error";
                return response()->json($info, 200);
                // return redirect()->back()->with('error', "Retribusi sudah terdaftar dalam keranjang !");
            }else{
                $pengangkutan = Pengangkutan::where('id', $request->id)->first();
                // $prop = Properti::where('id', $retri->id_properti)->first();
                // dd($statusP);
                if((!$statusP->isEmpty()) ? $statusP->pembayaran->status == "lunas" : false){
                    $info['stat'] = "error";
                    $info['desc'] = "Pengangkutan telah lunas !";
                    return response()->json($info, 200);
                }elseif($pengangkutan->status == 'Selesai'){
                    $keranjang = New Keranjang;
                    $keranjang->id_pelanggan = auth()->guard('web')->user()->id;
                    $keranjang->id_desa_adat = $pengangkutan->id_desa_adat;
                    $keranjang->model_id = $request->id;
                    $keranjang->model_type = "App\Pengangkutan";
                    $keranjang->save();
                    $info['stat'] = "success";
                    $info['desc'] = "Pengangkutan berhasil terdaftar dalam keranjang !";
                    return response()->json($info, 200);
                }
            }
        }else{
            $info['stat'] = "error";
            $info['desc'] = "Id Pengangkutan yang dikirim tidak terbaca !";
            return response()->json($info, 200);
        }
    }

    public function keranjangView(Request $request){
        $keranjang = Keranjang::where('id_pelanggan', auth()->guard('web')->user()->id)->where('model_type', "App\\Pengangkutan")->with('model')->get();
        // dd($keranjang->map->model);
        $pengangkutan = $keranjang->map->model;
        if($pengangkutan->count() > 1){
            foreach($pengangkutan as $p){
                $p->tanggal = $p->created_at->format('d M Y');
            }
        }else{
            foreach($pengangkutan as $p){
                $p->tanggal = $p->created_at->format('d M Y');
                // dd($p);
            }
            // $tanggal = $retribusi->first();
            // $retribusi->map->created_at = $tanggal->created_at->format('d M Y');
            // $retribusi->map->jenis = $retribusi->map->properti->map->jasa->map->jenis_jasa;
        }
        // dd($retribusi);
        if(isset($pengangkutan)){
            return response()->json($pengangkutan, 200);
        }else{
            return response()->json(null, 200);
        }
    }
    
    public function keranjangHapus(Request $request){
        $keranjang = Keranjang::where('model_id', $request->id)->where('model_type', "App\\Pengangkutan")->first();
        if(isset($keranjang)){
            $keranjang->forceDelete();
            $info['stat'] = "success";
            $info['desc'] = "Request Pengangkutan sudah dihapus dari keranjang !";
            return response()->json($info, 200);
        }else{
            $info['stat'] = "error";
            $info['desc'] = "Request Pengangkutan tidak ditemukan dalam keranjang !";
            return response()->json($info, 200);
        }
    }

    public function pay(Request $request){

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
                $pengangkutan = Pengangkutan::whereIn('id', $id)->get();
                $keranjang = Keranjang::whereIn('model_id', $id)->where('model_type',  "App\\Pengangkutan")->get();
                // dd($keranjang);
                foreach($keranjang as $k){
                    $k->forceDelete();
                }
                // $properti = Properti::whereIn('id', $retribusi->map->id_properti)->get();
                // dd(count(array_unique($properti->map->id_desa_adat->toArray())));
                if(count(array_unique($pengangkutan->map->id_desa_adat->toArray())) > 1){
                    return redirect()->back()->with('error', 'Pembayaran sekaligus hanya dapat dilakukan untuk Pengangkutan dengan Desa Adat yang sama !');
                }else{
                    foreach($pengangkutan as $peng){
                        // dd($retribusi);
                        $pembayaran->pengangkutan()->attach($peng);
                    }
                }
            }else{
                $keranjang = Keranjang::whereIn('model_id', $request->id)->where('model_type',  "App\\Pengangkutan")->first();
                $keranjang->forceDelete();
                $retribusi = Pengangkutan::whereIn('id', $request->id)->get();
                $pembayaran->pengangkutan()->attach($request->id); 
            }
            return redirect()->back()->with('success', 'Berhasil Melakukan Pembayaran untuk Request Pengangkutan Sampah !');
        }else{
            return redirect()->back()->with('error', 'Pembayaran Gagal untuk Dilakukan !');
        }

    }


}
