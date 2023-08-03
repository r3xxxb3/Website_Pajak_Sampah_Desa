<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kependudukan;
use App\Properti;
use App\StandarRetribusi;
use App\Retribusi;
use App\BanjarAdat;
use App\DesaAdat;
use App\KramaMipil;
use App\KramaTamiu;
use App\Tamiu;
use App\Pelanggan;
use App\Pegawai;
use App\Pengangkutan;
use App\Pembayaran;
use App\DetailPembayaran;
use App\SnapPayment;
use App\JadwalPengangkutan;
use Illuminate\Support\Facades\Hash;


class SnapInfoController extends Controller
{ 
    public function DataApi (Request $request)
    {
        $data = json_decode($request->getContent());
        $snapPayment = SnapPayment::where('order_id', $data->order_id)->first();
        // dd($snapPayment->gross_amount == $data->gross_amount);
        if(isset($snapPayment)){
            $sigKey = hash('sha512', $data->order_id.$data->status_code.$data->gross_amount.env('MIDTRANS_SERVER_KEY'));
            // dd($sigKey);
            if($data->signature_key == $sigKey){
                $snapPayment->transaction_status = $data->transaction_status;
                $snapPayment->status_code = $data->status_code;
                $snapPayment->update();
                if($data->transaction_status == "capture" || $data->transaction_status == "settlement"){
                    $id_pembayaran = explode('-', $snapPayment->order_id[0]);
                    $pembayaran = Pembayaran::where('id_pembayaran', $id_pembayaran)->first();
                    if(isset($pembayaran)){
                        $pembayaran->status = "lunas";
                        
                        $detail_pembayaran = DetailPembayaran::where('id_pembayaran', $id_pembayaran)->get();
                        if(!$detail_pembayaran->isEmpty()){
                            foreach($detail_pembayaran as $detail){
                                if($detail->model_type == 'App//Retribusi'){
                                    $item = Retribusi::where('id', $detail->model_id)->first();
                                    $item->status = "lunas";
                                    $item->update();
                                    // $pembayaran->update();
                                    // $snapPayment->update();
                                }else{
                                    $item = Pengangkutan::where('id', $detail->model_id)->first();
                                    // $item->status = "lunas";
                                    // $item->update();
                                    // $pembayaran->update();
                                    // $snapPayment->update();
                                }
                            }
                        $pembayaran->update();
                        }else{
                            // when item on payment is not detected 
                        }
                    }
                }
            }else{
                return "Different Signature Key, Info Rejected !";
            }
        }else{
            return "Order Id is not found !";
        }
    }
}