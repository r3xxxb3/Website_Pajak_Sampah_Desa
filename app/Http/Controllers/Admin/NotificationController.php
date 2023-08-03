<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Notifications\PropertiNotif;
use App\Notifications\RetribusiNotif;
use App\Notifications\PengangkutanNotif;
use App\Notifications\PembayaranNotif;
use App\Pelanggan;
use App\Pegawai;
use App\Properti;
use App\Pengangkutan;
use App\Pembayaran;

class NotificationController extends Controller
{
    //
    public function propertiRedirect($id){
        $notif = auth()->guard('admin')->user()->unreadNotifications->where('id', $id)->first();
        // dd(date('Y-m-d H:i:s'));
        $notif->read_at = now();
        $notif->update();
        // dd($notif);
        if($notif->data["type"] == "create"){
            return redirect()->route('pelanggan-edit', $notif->data["user_id"]."-".$notif->data["item_id"]);
        }elseif($notif->data["type"] == "update"){
            return redirect()->route('pelanggan-edit',  $notif->data["user_id"]."-".$notif->data["item_id"]);
        }elseif($notif->data["type"] == "cancel"){
            return redirect()->route('pelanggan-edit',  $notif->data["user_id"]."-".$notif->data["item_id"]);
        }
    }

    public function retribusiRedirect($id){
        $notif = auth()->guard('admin')->user()->unreadNotifications->where('id', $id)->first();
        // dd($notif);
        // if($notif->data["type"] == "create"){
        //     return redirect()->route('pelanggan-edit', $notif->data["user_id"]."-".$notif->data["item_id"]);
        // }elseif($notif->data["type"] == "update"){
        //     return redirect()->route('pelanggan-edit',  $notif->data["user_id"]."-".$notif->data["item_id"]);
        // }elseif($notif->data["type"] == "cancel"){
        //     return redirect()->route('pelanggan-edit',  $notif->data["user_id"]."-".$notif->data["item_id"]);
        // }
    }

    public function requestRedirect($id){
        $notif = auth()->guard('admin')->user()->unreadNotifications->where('id', $id)->first();
        $notif->read_at = now();
        $notif->update();
        // dd($notif);
        if($notif->data["type"] == "create"){
            return redirect()->route('admin-request-edit', $notif->data["item_id"]);
        }elseif($notif->data["type"] == "update"){
            return redirect()->route('admin-request-edit',  $notif->data["item_id"]);
        }elseif($notif->data["type"] == "cancel"){
            return redirect()->route('admin-request-edit',  $notif->data["item_id"]);
        }
    }

    public function PembayaranRedirect($id){
        $notif = auth()->guard('admin')->user()->unreadNotifications->where('id', $id)->first();
        // dd($notif);
        $notif->read_at = now();
        $notif->update();
        // dd($notif);
        if($notif->data["type"] == "create"){
            return redirect()->route('admin-pembayaran-edit',$notif->data["item_id"]);
        }elseif($notif->data["type"] == "update"){
            return redirect()->route('admin-pembayaran-edit', $notif->data["item_id"]);
        }elseif($notif->data["type"] == "cancel"){
            return redirect()->route('admin-pembayaran-edit', $notif->data["item_id"]);
        }else{
            return redirect()->route('admin-pembayaran-edit', $notif->data["item_id"]);
        }
    }
}
