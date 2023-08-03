<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Properti;
use App\Pembayaran;
use App\StandarRetribusi;
use App\Retribusi;
use App\Pengangkutan;
use Illuminate\Support\Facades\Hash;
use Telegram;
use Log;

class TestingController extends Controller
{
    //
    public function test($id){
        // dd(Hash::make($id));
         \Midtrans\Config::$serverKey = 'SB-Mid-server-YBNALLKE6j3DlXtUl5eOBl7F';
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
        $pembayaran = Pembayaran::where('id_pembayaran', $id)->whereHas('detail', function(builder $query){
            $query->with('model')->orWhereHasMorph('model', ['App\Retribusi', 'App\Pengangkutan'], function(builder $querys){
                $querys->where('model_type', "App\Retribusi" )->orWhere('model_type', "App\Pengangkutan");
                // dd($querys);
            });
        })->first();
        
        if($pembayaran != null){
            if($pembayaran->snap_token == null || (($pembayaran->snap_time - datetime.now()) > 1)){
                $pelanggan = $pembayaran->pelanggan->kependudukan;
                $name = explode(" ",$pelanggan->nama);
                $firstname = $name[0];
                $lastname = $name[count($name) - 1];
                $phonenumber = $pelanggan->telepon;
                $items = $pembayaran->detail->map->model;
                
                $params = array(
                    'transaction_details' => array(
                        'order_id' => $pembayaran->id_pembayaran,
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
                dd($snapToken);
                // return $snapToken;
                return response()->json($snapToken, 200);    
            }else{
                return response()->json($pembayaran->snap_token, 200);
            }
        }else{
            $status = "Transaksi pembayaran tidak ditemukan !";
            return response()->json($status, 200);
        }
        
        
        
        // $properti = Properti::where('status', 'terverifikasi')->get();
        // dd($properti);
        // $this->info("test");
    //     foreach($properti as $i=>$prop){
    //         $retribusi = Retribusi::where('id_properti', $prop->id)->orderBy('created_at', 'DESC')->first();
    //         $standar = StandarRetribusi::where('id_jenis_jasa', $prop->id_jenis)->where('id_desa_adat', $prop->id_desa_adat)->where(function ($query){
    //             $query->where('tanggal_berlaku', '<=', now())->orWhere('tanggal_selesai', '>=', now())->orWhere('active', 1);
    //         })->first();
    //         // $stanres = $standar->where('tanggal_berlaku', '<=', now())->where('tanggal_selesai', '>=', now())->Where('active', 1)->first();
    //         // dd($properti);
    //         if(!isset($retribusi)){
    //             $standar = StandarRetribusi::where('id_jenis_jasa', $prop->id_jenis)->get();
    //             // dd($standar);
    //             dd($retribusi);
    //             if(isset($standar)){
    //                 $retribusi = new Retribusi;
    //                 $retribusi->id_pelanggan = $prop->id_pelanggan;
    //                 $retribusi->id_properti = $prop->id;
    //                 $retribusi->status = "pending";
    //                 $retribusi->nominal = $standar->nominal_retribusi;
    //                 $retribusi->save();
    //             }else{
    //                 continue;
    //             }
    //         }elseif($retribusi->created_at->format('m') != now()->format('m') || $retribusi->created_at->format('y') < now()->format('y') ){
    //             dd($retribusi->created_at->format('m') != now()->format('m'));
    //             if(isset($standar)){
    //                 $retribusi = new Retribusi;
    //                 $retribusi->id_pelanggan = $prop->id_pelanggan;
    //                 $retribusi->id_properti = $prop->id;
    //                 $retribusi->status = "pending";
    //                 $retribusi->nominal = $standar->nominal_retribusi;
    //                 $retribusi->save();
    //             }else{
    //                 continue;
    //             }
    //         }else{
    //             dd($retribusi->created_at->format('m') != now()->format('m') || $retribusi->created_at->format('y') < now()->format('y') );
    //             continue;
    //         }
    //     }

    }

    // public function telegramTest(){
    //     // Laravel
    //     $response = Telegram::setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL')]);
        
    // }

    // public function webhook(){
    //     $updates = Telegram::commandsHandler(true);
    //     $chat_id = $updates->message->chat->id;
    //     $username = $updates->message->chat->first_name;

    //     // return response()->json("testing", 200);
    //     // dd($chat_id." ".$username);
    //     // dd(!$updates->message->isEmpty());

    //     if(!$updates->message->isEmpty()){
    //         if(strtolower($updates->message->text) === 'halo'){
    //             // dd('True');
    //             return Telegram::sendMessage([
    //                 'chat_id' => $chat_id,
    //                 'text' => 'testing success '.$username
    //             ]);
    //         }else{
    //             return Telegram::sendMessage([
    //                 'chat_id' =>$chat_id,
    //                 'text' => 'testing success '.$username
    //             ]);
    //         }
    //     }
    //     // dd($updates);
    // }
}
