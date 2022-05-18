<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Retribusi;
use App\Pengguna;
use App\Properti;
use App\JenisJasa;


class RetribusiController extends Controller
{
    //
    public function index(){
        $index = Retribusi::where('id_pengguna', auth()->guard('web')->user()->id)->get();
        return view('user.retribusi.index', compact('index'));
    }

    public function pay(){

    }


}
