<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\PembayaranNotif;
use Illuminate\Http\Request;
use App\Pegawai;
use App\Properti;
use App\Pelanggan;
use App\Retribusi;
use App\Pembayaran;
use App\SnapPayment;
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
        $index = Pembayaran::with('detail')->whereHas('detail', function(builder $query){
            $query->with('model')->orWhereHasMorph('model', ['App\Retribusi', 'App\Pengangkutan'], function(builder $querys){
                $prop = Properti::where('id_pelanggan', auth()->guard('web')->user()->id)->get();
                $ret = Retribusi::whereIn('id_properti', $prop->map->id)->get();
                $req = Pengangkutan::where('id_pelanggan', auth()->guard('web')->user()->id)->get();
                $querys->where('model_type', "App\Retribusi" )->whereIn('model_id', $ret->map->id)->orWhere('model_type', "App\Pengangkutan")->whereIn('model_id',$req->map->id);
                // dd($querys);
            });
        })->with('snap')->where('id_pelanggan', auth()->guard('web')->user()->id)->orderByRaw("FIELD(status, 'pending', 'lunas') DESC")->get();
        // $index = Pembayaran::where('id_pelanggan' ,auth()->guard('web')->user()->id)->with('detail')->withTrashed()->get();
        // dd($index);
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
    
    public function generateSnapToken(Request $request){
         // dd(Hash::make($id));
         \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
        $pembayaran = Pembayaran::where('id_pembayaran', $request->pembayaran)->whereHas('detail', function(builder $query){
            $query->with('model')->orWhereHasMorph('model', ['App\Retribusi', 'App\Pengangkutan'], function(builder $querys){
                $querys->where('model_type', "App\Retribusi" )->orWhere('model_type', "App\Pengangkutan");
                // dd($querys);
            });
        })->first();
        
        
        if($pembayaran != null){
            if($pembayaran->snap_token != null){
                if (!$pembayaran->snap->isEmpty()){
                    if($pembayaran->snap->last()->transaction_status == "settlement" || $pembayaran->snap->last()->transaction_status == "capture"){
                        return response()->json($pembayaran->snap_token, 200);
                    }elseif ($pembayaran->snap->last()->transaction_status == "pending"){
                        return response()->json($pembayaran->snap_token, 200);
                    }
                }                
            }
            $pelanggan = $pembayaran->pelanggan->kependudukan;
            $name = explode(" ",$pelanggan->nama);
            $firstname = $name[0];
            $lastname = $name[count($name) - 1];
            $phonenumber = $pelanggan->telepon;
            $items = $pembayaran->detail->map->model;
            
            $params = array(
                'transaction_details' => array(
                    'order_id' => $pembayaran->id_pembayaran."_".$phonenumber."_".date("Y-m-d_H:i:s"),
                    'gross_amount' => $pembayaran->nominal,
                ),
                'item_details' => array(
                    
                    
                ),
                'customer_details' => array(
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'phone' => $phonenumber,
                ),
            );
            
            foreach($items as $it){
                if($it->id_properti != null){
                    array_push($params['item_details'], array(
                        "id" => "RET-".$it->id,
                        "price" => $it->nominal,
                        "quantity" => 1,
                        "name" => "Retribusi ".$it->properti->nama_properti." ".$it->created_at
                    ));    
                }else{
                    array_push($params['item_details'], array(
                        "id" => "REQ-".$it->id,
                        "price" => $it->nominal,
                        "quantity" => 1,
                        "name" => "Request Pengangkutan ".$it->alamat
                    ));
                }
            }
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            $pembayaran->snap_token = $snapToken;
            $pembayaran->snap_time = date("Y-m-d H:i:s");
            $pembayaran->update();
            // dd($snapToken);
            // return $snapToken;
            return response()->json($snapToken, 200);
        }else{
            $status = "Transaksi pembayaran tidak ditemukan !";
            return response()->json($status, 200);
        }
    }
    
    
    public function snapSave(Request $request){
        // dd($request->result['order_id']);
        if(array_key_exists('order_id', $request->result)){
            $id_pembayaran = explode("_", $request->result['order_id']);
            $check = SnapPayment::where('order_id', $request->result['order_id'])->first();
        }else{
            $param = explode('?', $request->result['finish_redirect_url'])[1];
            $first_param = explode('&', $param)[0];
            $order_id = explode('=', $first_param)[1];
            $id_pembayaran = explode('_', $order_id)[0];
            // dd(rawurldecode($order_id));
            $check = SnapPayment::where('order_id', rawurldecode($order_id))->first();
        }
        if(!isset($check)){
            $snap = new SnapPayment;
            $snap->id_pembayaran = $id_pembayaran[0];
            $snap->order_id = $request->result['order_id'];
            $snap->payment_type = $request->result['payment_type'];
            $snap->gross_amount = $request->result['gross_amount'];
            if($request->result['payment_type'] == "cstore"){
                $snap->payment_code = $request->result['payment_code'];
            }else if ($request->result['payment_type'] == "bank_transfer"){
                $snap->fraud_status = $request->result['fraud_status'];
                $snap->va_numbers = json_encode($request->result['va_numbers']);
            }
            // $snap->payment_code = $request;
            $snap->status_code = $request->result['status_code'];
            $snap->status_message = $request->result['status_message'];
            $snap->transaction_status = $request->result['transaction_status'];
            $snap->transaction_time = $request->result['transaction_time'];
            $snap->pdf_url = isset($request->result['pdf_url']) ? $request->result['pdf_url'] : null ;
            if($snap->save()){
                $status = "success";
            }else{
                $status = "error";
            }
            return response()->json($status, 200);
        }
        
        $check->transaction_status = $request->result['transaction_status'];
        if($check->update()){
            $status = "success";
        }else{
            $status = "error";
        }
        
        if($request->result['transaction_status'] == "capture" || $request->result['transaction_status'] == "settlement"){
            $id_pembayaran = explode('-', $request->result['order_id'])[0];
            $pembayaran = Pembayaran::where('id_pembayaran', $id_pembayaran)->first();
            if(isset($pembayaran)){
                $pembayaran->status = "lunas";
                $detail_pembayaran = DetailPembayaran::where('id_pembayaran', $id_pembayaran)->get();
                if(!$detail_pembayaran->isEmpty()){
                    foreach($detail_pembayaran as $detail){
                        if($detail->model_type == 'App//Retribusi'){
                            $item = Retribusi::where('model_id', $detail->model_id)->first();
                            $item->status = "lunas";
                            $item->update();
                            // $snapPayment->update();
                        }else{
                            $item = Pengangkutan::where('model_id', $detail->model_id)->first();
                            // $item->status = "lunas";
                            // $item->update();
                            // $snapPayment->update();
                        }
                    }
                }else{
                    // when item on payment is not detected 
                }
                $pembayaran->update();
            }
        }
        
        return response()->json($status, 200);
    }

    public function store (Request $request){
        $ids = explode(",",$request->id[0]);
        $checkDesa = [];
        foreach($ids as $i=>$idm){
            $raw = explode ("-", $idm);
            if(!empty($raw[0])){
                // dd("true");
                $id[$i] = [$raw[0], $raw[1]]; 
            }else{
                return redirect()->back()->with('error', "Tidak terdapat item pada Pembayaran, Tambahkan Item !");
            }
        }
        $nominal = 0;
        foreach($id as $i){
            if($i[1] == "retribusi"){
                // dd($i);
                $retribusi = Retribusi::where('id', $i[0])->first();
                $nominal += $retribusi->nominal; 
                // dd(count(array_unique($properti->map->id_desa_adat->toArray())));
            }elseif($i[1] == "pengangkutan"){
                // dd($i);
                $pengangkutan = Pengangkutan::where('id', $i[0])->first();
                $nominal += $pengangkutan->nominal; 
            }
        }
        if($nominal != $request->nominal){
            return redirect()->back()->with('error', "Nominal pembayaran tidak sesuai !");
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
                $pegawai = Pegawai::where('id_desa_adat', $checkDesa[0])->get();
                // dd($properti->id_jenis);
                // $properti->toArray();
                foreach($pegawai as $p){
                    $p->notify(new PembayaranNotif($pembayaran, "create"));
                }
                foreach($id as $i){
                    if($i[1] == "retribusi"){
                        // dd($i);
                        $keranjang = Keranjang::where('model_id', $i[0])->where('model_type', "App\\".$i[1])->first();
                        $keranjang->forceDelete();
                        $retribusi = Retribusi::where('id', $i[0])->first();
                        $pembayaran->retribusi()->attach($retribusi);
                        // dd(count(array_unique($properti->map->id_desa_adat->toArray())));
                    }elseif($i[1] == "pengangkutan"){
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
            $query->with('model')->orWhereHasMorph('model', ["App\\Retribusi", "App\\Pengangkutan"]);
         })->first();
         $total = 0;
         if(isset($pembayaran->detail)){
             foreach($pembayaran->detail as $d){
                 $total += $d->model->nominal;
             }
         }
         $properti = Properti::where('id_pelanggan', auth()->guard('web')->user()->id)->get();
         $pengangkutan = Pengangkutan::where('id_pelanggan', auth()->guard('web')->user()->id)->get();
         $desa = DesaAdat::whereIn('id', $properti->map->id_desa_adat)->orWhere('id', $pengangkutan->map->id_desa_adat)->get();
        //  dd($total);
         if(isset($pembayaran)){
            return view('user.pembayaran.edit',compact('pembayaran', 'desa', 'total'));
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
        
        $pembayaran = Pembayaran::where('id_pembayaran', $id)->first();
        
        
        if($request->media == "transfer"){
            if($pembayaran->bukti_bayar != null){
                $this->validate($request, [
                    'nominal' => 'required|numeric',
                    'media' => 'required',
                    'id' => 'required|array',
                ],$messages);
            }else{
                $this->validate($request, [
                    // 'file' => 'required|max:5120',
                    'nominal' => 'required|numeric',
                    'media' => 'required',
                    'id' => 'required|array',
                ],$messages);
            }
        }else{
            $this->validate($request, [
                'nominal' => 'required',
                'media' => 'required',
                'id' => 'required|array',
            ],$messages);
        }


        
        $detail = DetailPembayaran::where('id_pembayaran', $pembayaran->id_pembayaran)->get();
        // dd($detail);
        $nominal = 0;
        foreach($detail as $i){
            if($i->model_type == "App\Retribusi"){
                // dd($i);
                $retribusi = Retribusi::where('id', $i->model_id)->first();
                $nominal += $retribusi->nominal; 
                // dd(count(array_unique($properti->map->id_desa_adat->toArray())));
            }elseif($i->model_type == "App\Pengangkutan"){
                // dd($i);
                $pengangkutan = Pengangkutan::where('id', $i->model_id)->first();
                // dd($pengangkutan);
                $nominal += $pengangkutan->nominal; 
            }
        }
        // dd($nominal);
        if($nominal != $request->nominal){
            return redirect()->back()->with('error', "Nominal pembayaran tidak sesuai !");
        }

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
            $pegawai = Pegawai::whereIn('id_desa_adat', $pembayaran->retribusi->map->properti->map->id_desa_adat)->orWhereIn('id_desa_adat', $pembayaran->pengangkutan->map->id_desa_adat)->get();
            // dd($properti->id_jenis);
            // $properti->toArray();
            foreach($pegawai as $p){
                $p->notify(new PembayaranNotif($pembayaran, "update"));
            }
            return redirect()->back()->with('success', 'Berhasil Melakukan perubahan pada pembayaran !');
        }else{
            return redirect()->back()->with('error', 'Perubahan Pembayaran Gagal untuk Dilakukan !');
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
            return redirect()->route('pembayaran-index')->with('success', "Berhasil menghapus data pembayaran !");
        }else{
            // $detail = DetailPembayaran::where('id_pembayaran', $pembayaran->id_pembayaran)->get();
            // foreach($detail as $d){
            //     $d->softDelete();
            // }
            $pembayaran->detail()->softDelete();
            $pembayaran->softDelete();
            return redirect()->route('pembayaran-index')->with('success', "Berhasil menghapus data pembayaran !");
        }

    }

    public function search(Request $request){
        // echo($request->pembayaran);
        // Pembayaran::where('id_pembayaran', $request->pembayaran)->first()
        $total = 0;
        $pembayaran = Pembayaran::where('id_pembayaran', $request->pembayaran)->with('detail')->whereHas('detail', function(builder $query){
            $query->with('model')->withTrashed()->whereHasMorph('model', ['App\Retribusi', 'App\Pengangkutan'], function(builder $querys){
                $prop = Properti::where('id_pelanggan', auth()->guard('web')->user()->id)->get();
                $ret = Retribusi::whereIn('id_properti', $prop->map->id)->get();
                $req = Pengangkutan::where('id_pelanggan', auth()->guard('web')->user()->id)->get();
                $querys->where('model_type', "App\Retribusi" )->whereIn('model_id', $ret->map->id)->orWhere('model_type', "App\Pengangkutan")->whereIn('model_id',$req->map->id);
                // dd($querys);
            });
        })->first();
        // dd($pembayaran->detail->map->model);
        // if(isset($pembayaran->detail)){
        //     foreach($pembayaran->detail as $d){
        //         $total += $d->model->nominal;
        //     }
        // }
        if(isset($pembayaran->detail)){
            $detail = $pembayaran->detail;
        }else{
            $data = "not found";
            return response()->json($data, 200);
        }
        // echo($detail->map->model->map->pelanggan->map->kependudukan);
        $model = $detail;
        foreach($model as $m){
            $m->tanggal = $m->model->created_at->format('d M Y');
            $m->pembayaran = $pembayaran->status;
            if($m->model_type == "App\\Retribusi"){
                // dd($m->properti = $m->model->properti);
                $m->pelanggan = $m->model->pelanggan->kependudukan->nama;
                $properti = Properti::withTrashed()->where('id', $m->model->id_properti)->first();
                $m->properti = $properti->nama_properti;
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
                    $pegawai = Pegawai::where('id_desa_adat', $retribusi->properti->id_desa_adat)->get();
                    // dd($properti->id_jenis);
                    // $properti->toArray();
                    foreach($pegawai as $p){
                        $p->notify(new PembayaranNotif($pembayaran, "itemAdd"));
                    }
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
                    $pegawai = Pegawai::whereIn('id_desa_adat', $pengangkutan->id_desa_adat)->get();
                    // dd($properti->id_jenis);
                    // $properti->toArray();
                    foreach($pegawai as $p){
                        $p->notify(new PembayaranNotif($pembayaran, "itemAdd"));
                    }
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
            $pegawai = Pegawai::where('id_desa_adat', $detail->model->id_desa_adat)->orWhere('id_desa_adat', $detail->model->properti->id_desa_adat)->get();
            // dd($properti->id_jenis);
            // $properti->toArray();
            foreach($pegawai as $p){
                $p->notify(new PembayaranNotif($detail->pembayaran, "itemDelete"));
            }
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
            // dd($r->properti->jasa);
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
