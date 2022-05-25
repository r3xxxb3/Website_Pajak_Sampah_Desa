<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pengguna;
use App\Properti;
use App\JenisJasa;
use App\Retribusi;
use App\Pembayaran;

class RetribusiController extends Controller
{
    //
    public function index(){
        $index = Retribusi::orderBy('id_pengguna', 'DESC')->get();
        // dd($index);
        return view('admin.retribusi.index', compact('index'));
    }

    public function update(){
        
    }

    public function verif(){
        
    }

    public function verifMany(Request $request){
        dd($request->id);
    }
}
