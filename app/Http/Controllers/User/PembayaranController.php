<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Pegawai;
use App\Properti;
use App\Pelanggan;
use App\Retribusi;
use App\Pembayaran;
use App\DetailPembayaran;
use App\Pengangkutan;
use App\DesaAdat;
use App\Keranjang;
use File;

class PembayaranController extends Controller
{
    //
    public function index(){
        // dd(auth()->guard('web')->user()->id);
        $index = Pembayaran::where('id_pelanggan' ,auth()->guard('web')->user()->id)->get();

        return view('user.pembayaran.index', compact('index'));
    }

    public function create(){
        $data = [];
        $desaP = DesaAdat::where('id', auth()->guard('web')->user()->kependudukan->mipil->banjarAdat->desaAdat->id)->first();
        $checkRetri = Keranjang::where('model_type', "App\\Retribusi")->get()->pluck('model_id');
        $checkReq = Keranjang::where('model_type', "App\\Pengangkutan")->get()->pluck('model_id');
        $prop = Properti::where('id_desa_adat', $desaP->id)->where('id_pelanggan', auth()->guard('web')->user()->id)->get();
        $ret = Retribusi::whereIn('id_properti', $prop->map->id)->where('status', "pending")->whereIn('id', $checkRetri)->with('properti')->get();
        $peng = Pengangkutan::where('id_desa_adat', $desaP->id)->where('id_pelanggan', auth()->guard('web')->user()->id)->where('status', "Selesai")->whereIn('id', $checkReq)->get();
        $total = 0;
        foreach($ret as $r){
            array_push($data, $r);
            $total += $r->nominal;
        }
        foreach($peng as $p){
            array_push($data, $p);
            $total += $p->nominal;
        }
        // dd($total);
        $properti = Properti::where('id_pelanggan', auth()->guard('web')->user()->id)->get();
        $pengangkutan = Pengangkutan::where('id_pelanggan', auth()->guard('web')->user()->id)->get();
        $desa = DesaAdat::whereIn('id', $properti->map->id_desa_adat)->orWhere('id', $pengangkutan->map->id_desa_adat)->get();
        //  dd($pembayaran->detail->map->model);
        return view('user.pembayaran.create',compact( 'desa', 'data', 'total'));
    }

    public function store (Request $request){
        $ids = explode(",",$request->id[0]);
        $checkDesa = [];
        foreach($ids as $i=>$idm){
            $raw = explode ("-", $idm);
            $id[$i] = [$raw[0], $raw[1]]; 
        }
        // dd($id);
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
        if($pembayaran->save()){
            foreach($id as $i){
                if($i[1] == "retribusi"){
                    // dd($i);
                    $retribusi = Retribusi::where('id', $i[0])->first();
                    array_push($checkDesa, $retribusi->properti->id_desa_adat);
                    // dd(count(array_unique($properti->map->id_desa_adat->toArray())));
                }elseif($i[1] = "pengangkutan"){
                    // dd($i);
                    $pengangkutan = Pengangkutan::where('id', $i[0])->first();
                    array_push($checkDesa, $pengangkutan->id_desa_adat);
                }
            }
            if(count(array_unique($checkDesa)) > 1){
                // dd($checkDesa);
                return redirect()->back()->with('error', 'Pembayaran sekaligus hanya dapat dilakukan untuk item dengan Desa Adat yang sama !');
            }else{
                foreach($id as $i){
                    if($i[1] == "retribusi"){
                        // dd($i);
                        $keranjang = Keranjang::where('model_id', $i[0])->where('model_type', "App\\".$i[1])->first();
                        $keranjang->forceDelete();
                        $retribusi = Retribusi::where('id', $i[0])->first();
                        $pembayaran->retribusi()->attach($retribusi);
                        // dd(count(array_unique($properti->map->id_desa_adat->toArray())));
                    }elseif($i[1] = "pengangkutan"){
                        // dd($i);
                        $keranjang = Keranjang::where('model_id', $i[0])->where('model_type', "App\\".$i[1])->first();
                        $keranjang->forceDelete();
                        $pengangkutan = Pengangkutan::where('id', $i[0])->first();
                        $pembayaran->pengangkutan()->attach($pengangkutan);
                    }
                }
                return redirect()->route('pembayaran-index')->with('success', 'Berhasil Melakukan Pembayaran !');
                // dd(count(array_unique($checkDesa)) > 1);  
            }
        }else{
            return redirect()->back()->with('error', 'Pembayaran Gagal untuk Dilakukan !');
        }
    }

    public function edit($id){
         $pembayaran = Pembayaran::where('id_pembayaran', $id)->with('detail')->whereHas('detail', function(Builder $query){
            $query->with('model')->whereHasMorph('model', ["App\\Retribusi", "App\\Pengangkutan"]);
         })->first();
         $properti = Properti::where('id_pelanggan', auth()->guard('web')->user()->id)->get();
         $pengangkutan = Pengangkutan::where('id_pelanggan', auth()->guard('web')->user()->id)->get();
         $desa = DesaAdat::whereIn('id', $properti->map->id_desa_adat)->orWhere('id', $pengangkutan->map->id_desa_adat)->get();
        //  dd($pembayaran->detail->map->model);
         if(isset($pembayaran)){
            return view('user.pembayaran.edit',compact('pembayaran', 'desa'));
         }else{
            return redirect()->back()->with('error', "Data Pembayaran tidak ditemukan !");
         }
    }

    public function update($id, Request $request){
        
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


        $pembayaran = Pembayaran::where('id_pembayaran', $id)->first();

        if($request->file('file')){
            if(!is_null($pembayaran->bukti_bayar)){
                $oldfile = public_path("assets/img/bukti_bayar/".$request->file);
                // dd(File::exists($oldfile));
                if (File::exists($oldfile)) {
                    // dd($oldfile);
                    File::delete($oldfile);
                    // unlink($oldfile);
                }
            }
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
        if($pembayaran->update()){
            return redirect()->back()->with('success', 'Berhasil Melakukan Pembayaran untuk Retribusi !');
        }else{
            return redirect()->back()->with('error', 'Pembayaran Gagal untuk Dilakukan !');
        }

    }

    public function delete($id){
        $pembayaran = Pembayaran::where('id_pembayaran', $id)->first();
        if($pembayaran->status != "terverifikasi"){
            // $detail = DetailPembayaran::where('id_pembayaran', $pembayaran->id_pembayaran)->get();
            // // dd($detail);
            // foreach($detail as $d){
            //     $d->forceDelete();
            // }
            $pembayaran->detail()->forceDelete();
            $pembayaran->forceDelete();
            return redirect()->route('pembayaran-index')->with('Success', "Berhasil menghapus data pembayaran !");
        }else{
            // $detail = DetailPembayaran::where('id_pembayaran', $pembayaran->id_pembayaran)->get();
            // foreach($detail as $d){
            //     $d->softDelete();
            // }
            $pembayaran->detail()->softDelete();
            $pembayaran->softDelete();
            return redirect()->route('pembayaran-index')->with('Success', "Berhasil menghapus data pembayaran !");
        }

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
                $m->properti = $m->model->alamat;
            }
        }
        $data = $model;
        // echo($model->map->model_type."-".$model->map->pelanggan."-".$model->map->properti);
        return response()->json($data, 200);
    }

    public function itemAdd(Request $request){
        $id = $request->id;
        $detail = explode("-", $id);
        $pembayaran = Pembayaran::where('id_pembayaran', $request->pembayaran)->first();
        if(isset($pembayaran)){
            if($detail[1] == "retribusi"){
                $retribusi = Retribusi::where('id', $detail[0])->first();
                $check = DetailPembayaran::where('model_id', $detail[0])->where('model_type', "App\\Retribusi")->first();
                if(isset($check)){
                    $info['stat'] = "error";
                    $info['desc'] = "Item retribusi sudah termasuk ke dalam pembayaran !";
                    return response()->json($info, 200);
                }else{
                    $pembayaran->retribusi()->attach($retribusi);
                    $info['stat'] = "success";
                    $info['desc'] = "Item retribusi berhasil masuk ke dalam pembayaran !";
                    return response()->json($info, 200);
                }
            }elseif($detail[1] == "pengangkutan"){
                $pengangkutan = Pengangkutan::where('id', $detail[0])->first();
                $check = DetailPembayaran::where('model_id', $detail[0])->where('model_type', "App\\Pengangkutan")->first();
                if(isset($check)){
                    $info['stat'] = "error";
                    $info['desc'] = "Item Request Pengangkutan sudah termasuk ke dalam pembayaran !";
                    return response()->json($info, 200);
                }else{
                    $pembayaran->pengangkutan()->attach($pengangkutan);
                    $info['stat'] = "success";
                    $info['desc'] = "Item Request Pengangkutan berhasil masuk ke dalam pembayaran !";
                    return response()->json($info, 200);
                }
            }
        }else{
            $info['stat'] = "error";
            $info['desc'] = "Data pembayaran tidak ditemukan !";
            return response()->json($info, 200);
        }
    }

    public function itemDelete(Request $request){
        $id = explode("-", $request->id[0]);
        // dd($id);
        $detail = DetailPembayaran::where('model_id', $id[0])->where('model_type', "App\\".$id[1])->first();
        // dd($detail);
        if(isset($detail)){
            DetailPembayaran::where('model_id', $id[0])->where('model_type', "App\\".$id[1])->forceDelete();
            $info['stat'] = "success";
            $info['desc'] = "Item berhasil dihapus dari pembayaran !";
            return response()->json($info, 200);
        }else{
            $info['stat'] = "error";
            $info['desc'] = "item tidak ditemukan dalam pembayaran !";
            return response()->json($info, 200);
        }
    }

    public  function itemSearch(Request $request){
        $data = [];
        // dd("true");
        if($request->jenis == "both"){
            $checkRetri = DetailPembayaran::where('model_type', "App\\Retribusi")->get()->pluck('model_id');
            $checkReq = DetailPembayaran::where('model_type', "App\\Pengangkutan")->get()->pluck('model_id');
            $properti = Properti::where('id_desa_adat', $request->desa)->where('id_pelanggan', auth()->guard('web')->user()->id)->get();
            $retribusi = Retribusi::whereIn('id_properti', $properti->map->id)->where('status', "pending")->whereNotIn('id', $checkRetri)->with('properti')->get();
            $pengangkutan = Pengangkutan::where('id_desa_adat', $request->desa)->where('id_pelanggan', auth()->guard('web')->user()->id)->where('status', "Selesai")->whereNotIn('id', $checkReq)->get();
            
            foreach($retribusi as $r){
                $r->tanggal = $r->created_at->format('d M Y');
            }
            foreach($pengangkutan as $p){
                $p->tanggal = $p->created_at->format('d M Y');
            }
            array_push($data, $retribusi->toArray());
            array_push($data, $pengangkutan->toArray());
            return response()->json($data, 200);
        }elseif($request->jenis == "retribusi"){
            $checkRetri = DetailPembayaran::where('model_type', "App\\Retribusi")->get()->pluck('model_id');
            $properti = Properti::where('id_desa_adat', $request->desa)->where('id_pelanggan', auth()->guard('web')->user()->id)->get();
            $retribusi = Retribusi::whereIn('id_properti', $properti->map->id)->where('status', "pending")->whereNotIn('id', $checkRetri)->with('properti')->get();
            foreach($retribusi as $r){
                $r->tanggal = $r->created_at->format('d M Y');
            }
            array_push($data, $retribusi->toArray());
            return response()->json($data, 200);
        }elseif($request->jenis == "pengangkutan"){
            $checkReq = DetailPembayaran::where('model_type', "App\\Pengangkutan")->get()->pluck('model_id');
            $pengangkutan = Pengangkutan::where('id_desa_adat', $request->desa)->where('id_pelanggan', auth()->guard('web')->user()->id)->where('status', "Selesai")->whereNotIn('id', $checkReq)->get();
            foreach($pengangkutan as $p){
                $p->tanggal = $p->created_at->format('d M Y');
            }
            array_push($data, $pengangkutan->toArray());
            return response()->json($data, 200);
        }

    }

    public function itemRefresh(Request $request){
        $pembayaran = Pembayaran::where('id_pembayaran', $request->id)->with('detail')->whereHas('detail', function(Builder $query){
            $query->with('model')->whereHasMorph('model', ["App\\Retribusi", "App\\Pengangkutan"]);
         })->first();
        $retribusi = Retribusi::whereIn('id', $pembayaran->retribusi->map->id)->with('properti')->get();
        foreach($retribusi as $r){
            dd($r->properti->jasa);
            $r->tanggal = $r->created_at->format('d M Y');
            $r->jasa = $r->properti->jasa->jenis_jasa;
        }
        // dd($retribusi);
        $pengangkutan = Pengangkutan::whereIn('id', $pembayaran->pengangkutan->map->id)->get();
        foreach($pengangkutan as $p){
            $p->tanggal = $p->created_at->format('d M Y');
        }
        $data =[];
        array_push($data, $retribusi->toArray());
        array_push($data, $pengangkutan->toArray());
        return response()->json($data, 200);
    }

    public function keranjang(Request $request){
        $id = $request->id;
        $detail = explode("-", $id);
        if($detail[1] == "retribusi"){
            $retribusi = Retribusi::where('id', $detail[0])->first();
            $check = Keranjang::where('model_id', $detail[0])->where('model_type', "App\\Retribusi")->first();
            // dd($detail[0]);
            if(isset($check)){
                $info['stat'] = "error";
                $info['desc'] = "Item retribusi sudah termasuk ke dalam Keranjang !";
                return response()->json($info, 200);
            }else{
                $item = new Keranjang;
                $item->model_id = $detail[0];
                $item->model_type = "App\Retribusi";
                $item->id_desa_adat = $retribusi->properti->id_desa_adat;
                $item->id_pelanggan = auth()->guard('web')->user()->id;
                if($item->save()){
                    $info['stat'] = "success";
                    $info['desc'] = "Item retribusi berhasil masuk ke dalam Keranjang !";
                    return response()->json($info, 200);
                }
            }
        }elseif($detail[1] == "pengangkutan"){
            $pengangkutan = Pengangkutan::where('id', $detail[0])->first();
            $check = Keranjang::where('model_id', $detail[0])->where('model_type', "App\\Pengangkutan")->first();
            if(isset($check)){
                $info['stat'] = "error";
                $info['desc'] = "Item Request Pengangkutan sudah termasuk ke dalam Keranjang !";
                return response()->json($info, 200);
            }else{
                $item = new Keranjang;
                $item->model_id = $detail[0];
                $item->model_type = "App\Pengangkutan";
                $item->id_desa_adat = $pengangkutan->id_desa_adat;
                $item->id_pelanggan = auth()->guard('web')->user()->id;
                if($item->save()){
                    $info['stat'] = "success";
                    $info['desc'] = "Item Request Pengangkutan berhasil masuk ke dalam Keranjang !";
                    return response()->json($info, 200);
                }
            }
        }
    }

    public function keranjangView(Request $request){
        $data = [];
        // dd("true");
        $checkRetri = Keranjang::where('model_type', "App\\Retribusi")->get()->pluck('model_id');
        $checkReq = Keranjang::where('model_type', "App\\Pengangkutan")->get()->pluck('model_id');
        $properti = Properti::where('id_pelanggan', auth()->guard('web')->user()->id)->get();
        $retribusi = Retribusi::whereIn('id_properti', $properti->map->id)->where('status', "pending")->whereIn('id', $checkRetri)->with('properti')->get();
        $pengangkutan = Pengangkutan::where('id_pelanggan', auth()->guard('web')->user()->id)->where('status', "Selesai")->whereIn('id', $checkReq)->get();
        
        foreach($retribusi as $r){
            $r->tanggal = $r->created_at->format('d M Y');
            $r->jasa = $r->properti->jasa->jenis_jasa;
        }
        foreach($pengangkutan as $p){
            $p->tanggal = $p->created_at->format('d M Y');
        }
        array_push($data, $retribusi->toArray());
        array_push($data, $pengangkutan->toArray());
        return response()->json($data, 200);
    }

    public function keranjangHapus(Request $request){
        $id = explode('-', $request->id);
        $keranjang = keranjang::where('model_id', $id[0])->where('model_type', "App\\".$id[1])->first();
        // dd($keranjang);
        if(isset($keranjang)){
            Keranjang::where('model_id', $id[0])->where('model_type', "App\\".$id[1])->forceDelete();
            $info['stat'] = "success";
            $info['desc'] = "Item berhasil dihapus dari pembayaran !";
            return response()->json($info, 200);
        }else{
            $info['stat'] = "error";
            $info['desc'] = "item tidak ditemukan dalam pembayaran !";
            return response()->json($info, 200);
        }
    }

    public function keranjangSearch(Request $request){
        $data = [];
        // dd("true");
        if($request->jenis == "both"){
            $checkRetri = DetailPembayaran::where('model_type', "App\\Retribusi")->get()->pluck('model_id');
            $checkReq = DetailPembayaran::where('model_type', "App\\Pengangkutan")->get()->pluck('model_id');
            $checkKerRetri = Keranjang::where('model_type', "App\\Retribusi")->get()->pluck('model_id');
            $checkKerReq = Keranjang::where('model_type', "App\\Pengangkutan")->get()->pluck('model_id');
            // dd($checkKerReq);
            $properti = Properti::where('id_desa_adat', $request->desa)->where('id_pelanggan', auth()->guard('web')->user()->id)->get();
            $retribusi = Retribusi::whereIn('id_properti', $properti->map->id)->where('status', "pending")->whereNotIn('id', $checkRetri)->whereNotIn('id', $checkKerRetri)->with('properti')->get();
            $pengangkutan = Pengangkutan::where('id_desa_adat', $request->desa)->where('id_pelanggan', auth()->guard('web')->user()->id)->where('status', "Selesai")->whereNotIn('id', $checkReq)->whereNotIn('id', $checkKerReq)->get();
            
            foreach($retribusi as $r){
                $r->tanggal = $r->created_at->format('d M Y');
            }
            foreach($pengangkutan as $p){
                $p->tanggal = $p->created_at->format('d M Y');
            }
            array_push($data, $retribusi->toArray());
            array_push($data, $pengangkutan->toArray());
            return response()->json($data, 200);
        }elseif($request->jenis == "retribusi"){
            $checkKerRetri = Keranjang::where('model_type', "App\\Retribusi")->get()->pluck('model_id');
            $checkRetri = DetailPembayaran::where('model_type', "App\\Retribusi")->get()->pluck('model_id');
            $properti = Properti::where('id_desa_adat', $request->desa)->where('id_pelanggan', auth()->guard('web')->user()->id)->get();
            $retribusi = Retribusi::whereIn('id_properti', $properti->map->id)->where('status', "pending")->whereNotIn('id', $checkRetri)->with('properti')->get();
            foreach($retribusi as $r){
                $r->tanggal = $r->created_at->format('d M Y');
            }
            array_push($data, $retribusi->toArray());
            return response()->json($data, 200);
        }elseif($request->jenis == "pengangkutan"){
            $checkReq = DetailPembayaran::where('model_type', "App\\Pengangkutan")->get()->pluck('model_id');
            $checkKerReq = Keranjang::where('model_type', "App\\Pengangkutan")->get()->pluck('model_id');
            $pengangkutan = Pengangkutan::where('id_desa_adat', $request->desa)->where('id_pelanggan', auth()->guard('web')->user()->id)->where('status', "Selesai")->whereNotIn('id', $checkReq)->whereNotIn('id', $checkKerReq)->get();
            foreach($pengangkutan as $p){
                $p->tanggal = $p->created_at->format('d M Y');
            }
            array_push($data, $pengangkutan->toArray());
            return response()->json($data, 200);
        }
    }
}
