<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Builder;
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
        $index = Retribusi::whereHas('properti', function (Builder $query){
            $query->where('id_desa_adat', auth()->guard('admin')->user()->kependudukan->mipil->banjarAdat->desaAdat->id);
        })->orderByRaw("FIELD(status, 'pending', 'lunas ') DESC")->get();
        // dd($retribusi);
        // foreach($retribusi as $i){
        //     if(isset($i->properti->desaAdat)){
        //         // dd($i->properti->desaAdat);
        //         $index = $i;
        //     }
        // }
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
