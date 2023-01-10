<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Retribusi;
use App\Pelanggan;
use App\Properti;
use App\JenisJasa;


class RetribusiController extends Controller
{
    //
    public function index(){
        $index = Retribusi::where('id_pelanggan', auth()->guard('web')->user()->id)->with('Dpembayaran')->orderByRaw("FIELD(status, 'pending', 'lunas ') DESC")->get();
        // dd($index);
        return view('user.retribusi.index', compact('index'));
    }

    public function pay(){

    }


}
