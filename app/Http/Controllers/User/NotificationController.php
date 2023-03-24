<?php

namespace App\Http\Controllers\User;

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
        $notif = auth()->guard('web')->user()->unreadNotifications->where('id', $id)->first();
        // dd($notif);
        $notif->read_at = date('Y-m-d H:i:s');
        $notif->update();
        if($notif->data["type"] == "create"){
            return redirect()->route('properti-edit', $notif->data["item_id"]);
        }elseif($notif->data["type"] == "update"){
            return redirect()->route('properti-edit',  $notif->data["item_id"]);
        }elseif($notif->data["type"] == "cancel"){
            return redirect()->route('properti-edit',  $notif->data["item_id"]);
        }
    }

    public function retribusiRedirect($id){
        $notif = auth()->guard('web')->user()->unreadNotifications->where('id', $id)->first();
        // dd($notif);
        $notif->read_at = date('Y-m-d H:i:s');
        $notif->update();
        if($notif->data["type"] == "create"){
            return redirect()->route('retribusi-index');
        }
    }

    public function requestRedirect($id){
        $notif = auth()->guard('web')->user()->unreadNotifications->where('id', $id)->first();
        // dd($notif);
        $notif->read_at = date('Y-m-d H:i:s');
        $notif->update();
        if($notif->data["type"] == "create"){
            return redirect()->route('request-edit', $notif->data["item_id"]);
        }elseif($notif->data["type"] == "update"){
            return redirect()->route('request-edit',  $notif->data["item_id"]);
        }elseif($notif->data["type"] == "cancel"){
            return redirect()->route('request-edit',  $notif->data["item_id"]);
        }elseif($notif->data["type"] == "confirm"){
            return redirect()->route('request-edit',  $notif->data["item_id"]);
        }elseif($notif->data["type"] == "verify"){
            return redirect()->route('request-edit',  $notif->data["item_id"]);
        }
    }

    public function PembayaranRedirect($id){
        $notif = auth()->guard('web')->user()->unreadNotifications->where('id', $id)->first();
        // dd($notif);
        $notif->read_at = date('Y-m-d H:i:s');
        $notif->update();
        if($notif->data["type"] == "create"){
            return redirect()->route('pembayaran-index');
        }elseif($notif->data["type"] == "update"){
            return redirect()->route('pembayaran-index');
        }elseif($notif->data["type"] == "cancel"){
            return redirect()->route('pembayaran-index');
        }
    }
}
