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
        dd($notif);
    }

    public function retribusiRedirect($id){
        
    }

    public function pengangkutanRedirect($id){
        
    }

    public function PembayaranRedirect($id){
        
    }
}
