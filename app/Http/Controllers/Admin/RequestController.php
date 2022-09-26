<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pengguna;
use App\Pengangkutan;
use App\Properti;
use App\JenisJasa;
use App\Retribusi;
use App\Pembayaran;


class RequestController extends Controller
{
    //
    public function index(){
        $index = Pengangkutan::all();
        return view('admin.request.index', compact('index'));
    }

    public function create(){

    }

    public function store(){

    }

    public function edit(){

    }

    public function update(){

    }

    public function verif(){

    }

    public function verifMany(){

    }


}
