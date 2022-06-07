<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pembayaran;
use App\Retribusi;
use App\Pengguna;
use App\Pegawai;
use App\Properti;


class PembayaranController extends Controller
{
    //
    public function index(){
        $index = Pembayaran::all();
        return view('admin.pembayaran.index', compact('index'));
    }

    public function create(){

    }
}
