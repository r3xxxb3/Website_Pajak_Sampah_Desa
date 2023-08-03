<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\PembayaranNotif;
use Illuminate\Http\Request;
use App\Pembayaran;
use App\Retribusi;
use App\Pengangkutan;
use App\Pelanggan;
use App\Pegawai;
use App\Properti;
use App\DesaAdat;
use App\BanjarAdat;
use App\DetailPembayaran;
use App\Keranjang;


class PembayaranController extends Controller
{
    //
    public function index(){
        $banjarAdat = BanjarAdat::where('desa_adat_id', auth()->guard('admin')->user()->id_desa_adat)->get();
        $pelanggan = Pelanggan::whereHas('kependudukan', function(Builder $query) use ($banjarAdat) {
          $query->whereHas('mipil', function(Builder $query) use ($banjarAdat){
            $query->whereIn('banjar_adat_id', $banjarAdat->map->id);
          })->orWhereHas('ktamiu', function(Builder $query) use ($banjarAdat){
            $query->whereIn('banjar_adat_id', $banjarAdat->map->id);
          })->orWhereHas('tamiu', function(Builder $query) use ($banjarAdat){
            $query->whereIn('banjar_adat_id', $banjarAdat->map->desa_adat_id);
          });
        })->get();
        // dd($pelanggan);
        
        $index = Pembayaran::with('detail')->whereHas('detail', function(builder $query){
            $query->with('model')->orWhereHasMorph('model', ['App\Retribusi', 'App\Pengangkutan'], function(builder $querys){
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
        $properti = Properti::where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat)->get('id_pelanggan');
        $request = Pengangkutan::where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat)->get('id_pelanggan');
        $pelanggan = Pelanggan::whereIn('id', $properti)->orWhere('id', $request)->with('kependudukan')->get();

        return view('admin.pembayaran.create', compact('pelanggan'));
    }

    public function store(Request $request){
        $ids = explode(",",$request->id[0]);
        $idP = $request->pelanggan;
        $pelanggan = Pelanggan::where('id', $idP)->first();
        // dd($pelanggan);
        if(!isset($pelanggan)){
            return redirect()->back()->with('error', "Id Pelanggan tidak ditemukan !");
        }

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
            $images = $pelanggan->kependudukan->nik."_".$file->getClientOriginalName();
            // dd($images);
            $pembayaran->bukti_bayar = $images;
            $foto_upload = 'assets/img/bukti_bayar';
            $file->move($foto_upload,$images);
        }
        $pembayaran->id_pelanggan = $pelanggan->id;
        $pembayaran->media = $request->media;
        $pembayaran->nominal = $request->nominal;
        $pembayaran->jenis = $request->type;
        if($pembayaran->save()){
            $pelanggan->notify(new PembayaranNotif($pembayaran, "create"));
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
                return redirect()->route('admin-pembayaran-index')->with('success', 'Berhasil Melakukan Pembayaran !');
                // dd(count(array_unique($checkDesa)) > 1);  
            }
        }else{
            return redirect()->back()->with('error', 'Pembayaran Gagal untuk Dilakukan !');
        }
    }

    public function edit($id){
        $pembayaran = Pembayaran::where('id_pembayaran', $id)->with('detail')->first();
         $properti = Properti::where('id_pelanggan', $pembayaran->id_pelanggan)->get();
         $pengangkutan = Pengangkutan::where('id_pelanggan', $pembayaran->id_pelanggan)->get();
         $desa = DesaAdat::whereIn('id', $properti->map->id_desa_adat)->orWhere('id', $pengangkutan->map->id_desa_adat)->get();
        //  dd($pembayaran);
         if(isset($pembayaran)){
            return view('admin.pembayaran.edit',compact('pembayaran', 'desa'));
         }else{
            return redirect()->back()->with('error', "Data Pembayaran tidak ditemukan !");
         }
    }

    public function update(Request $request){
        
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
                // 'file' => 'required|max:5120',
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


        $pembayaran = Pembayaran::where('id_pembayaran', $request->pembayaran)->first();

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
            $images = $pembayaran->pelanggan->kependudukan->nik."_".$file->getClientOriginalName();
            // dd($images);
            $pembayaran->bukti_bayar = $images;
            $foto_upload = 'assets/img/bukti_bayar';
            $file->move($foto_upload,$images);
        }
        // dd($pembayaran);
        $pembayaran->media = $request->media ? $request->media : '';
        $pembayaran->nominal = $request->nominal;
        $pembayaran->jenis = $request->type;
        $pelanggan = $pembayaran->pelanggan;
        if($pembayaran->update()){
            $pelanggan->notify(new PembayaranNotif($pembayaran, "update"));
            return redirect()->back()->with('success', 'Berhasil Melakukan Perubahan Data Pembayaran !');
        }else{
            return redirect()->back()->with('error', 'Pembayaran Gagal untuk Dilakukan !');
        }

    }

    public function delete(){
        $pembayaran = Pembayaran::where('id_pembayaran', $id)->first();
        if($pembayaran->status != "terverifikasi"){
            // $detail = DetailPembayaran::where('id_pembayaran', $pembayaran->id_pembayaran)->get();
            // // dd($detail);
            // foreach($detail as $d){
            //     $d->forceDelete();
            // }
            $pembayaran->detail()->forceDelete();
            $pembayaran->forceDelete();
            return redirect()->route('admin-pembayaran-index')->with('Success', "Berhasil menghapus data pembayaran !");
        }else{
            // $detail = DetailPembayaran::where('id_pembayaran', $pembayaran->id_pembayaran)->get();
            // foreach($detail as $d){
            //     $d->softDelete();
            // }
            $pembayaran->detail()->softDelete();
            $pembayaran->softDelete();
            return redirect()->route('admin-pembayaran-index')->with('Success', "Berhasil menghapus data pembayaran !");
        }
    }

    public function verif($id){
        $pembayaran = Pembayaran::where('id_pembayaran', $id)->first();
        $pelanggan = $pembayaran->pelanggan;
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

                }
            }
            $pembayaran->update();
            $pelanggan->notify(new PembayaranNotif($pembayaran, 'verify'));
            return redirect()->back()->with('success', 'Pembayaran berhasil terverifikasi !');
        }else{
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan !');
        }
    }

    public function search(Request $request){
        // echo($request->pembayaran);
        $pembayaran = Pembayaran::where('id_pembayaran', $request->pembayaran)->with('detail')->whereHas('detail', function(Builder $query){
            $query->with('model')->whereHasMorph('model', ['App\Retribusi', 'App\Pengangkutan'], function(builder $querys){
                $prop = Properti::where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat)->get();
                $ret = Retribusi::whereIn('id_properti', $prop->map->id)->get();
                $req = Pengangkutan::where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat)->get();
                $querys->where('model_type', "App\Retribusi" )->whereIn('model_id', $ret->map->id)->orWhere('model_type', "App\Pengangkutan")->whereIn('model_id',$req->map->id);
                // dd($querys);
            });
        })->first();
        // dd($pembayaran->detail);
        $detail = $pembayaran->detail;
        // echo($detail->map->model->map->pelanggan->map->kependudukan);
        $model = $detail;
        foreach($model as $m){
            $m->tanggal = $m->model->created_at->format('d M Y');
            if($m->model_type == "App\\Retribusi"){
                // dd($m->properti = $m->model->properti);
                $m->pelanggan = $m->model->pelanggan->kependudukan->nama;
                $properti = Properti::withTrashed()->where('id', $m->model->id_properti)->first();
                $m->properti = $properti->nama_properti;
                $m->status = $m->pembayaran->status;
            }elseif($m->model_type == "App\\Pengangkutan"){
                // dd($m->model->pengangkutan->alamat);
                $m->pelanggan = $m->model->pelanggan->kependudukan->nama;
                $m->properti = $m->model->alamat;
                $m->status = $m->pembayaran->status;
            }
        }
        $data = $model;
        // echo($model->map->model_type."-".$model->map->pelanggan."-".$model->map->properti);
        return response()->json($data, 200);
    }

    public function pelangganSearch(Request $request){
        $data = [];
        $checkRetri = DetailPembayaran::where('model_type', "App\\Retribusi")->get()->pluck('model_id');
        $checkReq = DetailPembayaran::where('model_type', "App\\Pengangkutan")->get()->pluck('model_id');
        $checkKerRetri = Keranjang::where('model_type', "App\\Retribusi")->get()->pluck('model_id');
        $checkKerReq = Keranjang::where('model_type', "App\\Pengangkutan")->get()->pluck('model_id');
        // dd($checkKerReq);
        $properti = Properti::where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat)->where('id_pelanggan', $request->pelanggan)->get();
        $retribusi = Retribusi::whereIn('id_properti', $properti->map->id)->where('status', "pending")->whereNotIn('id', $checkRetri)->whereNotIn('id', $checkKerRetri)->with('properti')->get();
        $pengangkutan = Pengangkutan::where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat)->where('id_pelanggan', $request->pelanggan)->where('status', "Selesai")->whereNotIn('id', $checkReq)->whereNotIn('id', $checkKerReq)->get();
        
        foreach($retribusi as $r){
            $r->tanggal = $r->created_at->format('d M Y');
        }
        foreach($pengangkutan as $p){
            $p->tanggal = $p->created_at->format('d M Y');
        }
        array_push($data, $retribusi->toArray());
        array_push($data, $pengangkutan->toArray());
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
                    $pelanggan = $pembayaran->pelanggan;
                    $pelanggan->notify(new PembayaranNotif($pembayaran, "itemAdd"));
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
                    $pelanggan = $pembayaran->pelanggan;
                    $pelanggan->notify(new PembayaranNotif($pembayaran, "itemAdd"));
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
            $pembayaran = Pembayaran::where('id_pembayaran', $detail->id_pembayaran)->first();
            DetailPembayaran::where('model_id', $id[0])->where('model_type', "App\\".$id[1])->forceDelete();
            $info['stat'] = "success";
            $info['desc'] = "Item berhasil dihapus dari pembayaran !";
            $pelanggan = $pembayaran->pelanggan;
            $pelanggan->notify(new PembayaranNotif($pembayaran, "itemDelete"));
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
            $properti = Properti::where('id_desa_adat', $request->desa)->where('id_pelanggan', $request->pelanggan)->get();
            $retribusi = Retribusi::whereIn('id_properti', $properti->map->id)->where('status', "pending")->whereNotIn('id', $checkRetri)->with('properti')->get();
            $pengangkutan = Pengangkutan::where('id_desa_adat', $request->desa)->where('id_pelanggan', $request->pelanggan)->where('status', "Selesai")->whereNotIn('id', $checkReq)->get();
            
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
            $properti = Properti::where('id_desa_adat', $request->desa)->where('id_pelanggan', $request->pelanggan)->get();
            $retribusi = Retribusi::whereIn('id_properti', $properti->map->id)->where('status', "pending")->whereNotIn('id', $checkRetri)->with('properti')->get();
            foreach($retribusi as $r){
                $r->tanggal = $r->created_at->format('d M Y');
            }
            array_push($data, $retribusi->toArray());
            return response()->json($data, 200);
        }elseif($request->jenis == "pengangkutan"){
            $checkReq = DetailPembayaran::where('model_type', "App\\Pengangkutan")->get()->pluck('model_id');
            $pengangkutan = Pengangkutan::where('id_desa_adat', $request->desa)->where('id_pelanggan', $request->pelanggan)->where('status', "Selesai")->whereNotIn('id', $checkReq)->get();
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
                $item->id_pelanggan = $request->pelanggan;
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
                $item->id_pelanggan = $request->pelanggan;
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
        $properti = Properti::where('id_pelanggan', $request->pelanggan)->get();
        $retribusi = Retribusi::whereIn('id_properti', $properti->map->id)->where('status', "pending")->whereIn('id', $checkRetri)->with('properti')->get();
        $pengangkutan = Pengangkutan::where('id_pelanggan', $request->pelanggan)->where('status', "Selesai")->whereIn('id', $checkReq)->get();
        
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
            $properti = Properti::where('id_desa_adat', $request->desa)->where('id_pelanggan', $request->pelanggan)->get();
            $retribusi = Retribusi::whereIn('id_properti', $properti->map->id)->where('status', "pending")->whereNotIn('id', $checkRetri)->whereNotIn('id', $checkKerRetri)->with('properti')->get();
            $pengangkutan = Pengangkutan::where('id_desa_adat', $request->desa)->where('id_pelanggan', $request->pelanggan)->where('status', "Selesai")->whereNotIn('id', $checkReq)->whereNotIn('id', $checkKerReq)->get();
            
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
            $properti = Properti::where('id_desa_adat', $request->desa)->where('id_pelanggan', $request->pelanggan)->get();
            $retribusi = Retribusi::whereIn('id_properti', $properti->map->id)->where('status', "pending")->whereNotIn('id', $checkRetri)->with('properti')->get();
            foreach($retribusi as $r){
                $r->tanggal = $r->created_at->format('d M Y');
            }
            array_push($data, $retribusi->toArray());
            return response()->json($data, 200);
        }elseif($request->jenis == "pengangkutan"){
            $checkReq = DetailPembayaran::where('model_type', "App\\Pengangkutan")->get()->pluck('model_id');
            $checkKerReq = Keranjang::where('model_type', "App\\Pengangkutan")->get()->pluck('model_id');
            $pengangkutan = Pengangkutan::where('id_desa_adat', $request->desa)->where('id_pelanggan', $request->pelanggan)->where('status', "Selesai")->whereNotIn('id', $checkReq)->get();
            foreach($pengangkutan as $p){
                $p->tanggal = $p->created_at->format('d M Y');
            }
            array_push($data, $pengangkutan->toArray());
            return response()->json($data, 200);
        }
    }
}
