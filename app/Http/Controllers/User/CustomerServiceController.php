<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\KepuasanPengguna;
use App\Pembayaran;
use App\Pelanggan;
use App\Retribusi;
use App\Pengangkutan;
use App\Properti;
use App\KritikSaran;
use App\DesaAdat;

class CustomerServiceController extends Controller
{
    //
    public function penilaianIndex(){
        $data = [];
        $kepRet = KepuasanPengguna::where('item_type', 'App\Retribusi')->get();
        $kepPeng = KepuasanPengguna::where('item_type', 'App\Pengangkutan')->get();
        $properti = Properti::where('id_pelanggan', auth()->guard('web')->user()->id)->get();
        $kepuasan = KepuasanPengguna::whereHasMorph('item', ["App\Retribusi"], function(Builder $querys){
            $properti = Properti::where('id_pelanggan', auth()->guard('web')->user()->id)->get();
            $querys->whereIn('id_properti', $properti->map->id); 
        })->orWhereHasMorph('item', ["App\Pengangkutan"], function(Builder $querys){
            $querys->where('id_pelanggan', auth()->guard('web')->user()->id); 
        })->get();
        // dd($kepuasan);
        $retribusi = Retribusi::whereIn('id_properti', $properti->map->id)->with(['properti', 'kepuasan'])->where('status', 'lunas')->get();
        foreach($retribusi as $r){
            array_push($data, $r);
        }
        $pengangkutan = Pengangkutan::where('id_pelanggan', auth()->guard('web')->user()->id)->whereHas('pembayaran', function(Builder $query){
            $query->where('status', 'lunas');
        })->with('kepuasan')->get();
        foreach($pengangkutan as $p){
            array_push($data, $p);
        }
        // dd($data);
        return view('user.customservice.penilaian-index', compact('data', 'kepuasan'));
    }

    public function penilaianStore(Request $request){
        $id = explode("-",$request->id);
        // dd($request->id);
        $checkPenilaian = KepuasanPengguna::where('item_id', $id[0])->where('item_type', "App\\".$id[1])->first();
        if(!isset($checkPenilaian)){
            $penilaian  = new KepuasanPengguna;
            $penilaian->item_id = $id[0];
            $penilaian->rate = $request->rate;
            $penilaian->item_type = "App\\".$id[1];
            $penilaian->comment = $request->comment;
            if($penilaian->save()){
                return redirect()->route('custom-penilaian-index')->with('success', "Berhasil menambahkan penilaian !");
            }else{
                return redirect()->route('custom-penilaian-index')->with('error', "Gagal menambahkan penilaian ! (ERROR)");
            }
        }else{
            return redirect()->route('custom-penilaian-index')->with('error', "Penilaian telah diberikan ! lakukan perubahan pada tombol ubah !");
        }
        
    }

    public function penilaianUpdate(Request $request){
        $id = explode("-",$request->uid);
        $checkPenilaian = KepuasanPengguna::where('item_id', $id[0])->where('item_type', "App\\".$id[1])->first();
        if(isset($checkPenilaian)){
            $checkPenilaian->item_id = $id[0];
            $checkPenilaian->rate = $request->urate;
            $checkPenilaian->item_type = "App\\".$id[1];
            $checkPenilaian->comment = $request->ucomment;
            // dd($checkPenilaian);
            if($checkPenilaian->update()){
                return redirect()->route('custom-penilaian-index')->with('success', "Berhasil mengubah penilaian !");
            }else{
                return redirect()->route('custom-penilaian-index')->with('error', "Gagal mengubah penilaian ! (ERROR)");
            }
        }else{
            return redirect()->route('custom-penilaian-index')->with('error', "Penilaian tidak ditemukan !");
        }
    }

    // public function penilaianDelete(){
        
    // }

    public function kritikIndex(){
        $properti = Properti::where('id_pelanggan', auth()->guard('web')->user()->id)->get('id_desa_adat');
        $request = Pengangkutan::where('id_pelanggan', auth()->guard('web')->user()->id)->get('id_desa_adat');
        $kritik = KritikSaran::where('id_pelanggan', auth()->guard('web')->user()->id)->get();
        $desa = DesaAdat::whereIn('id', $properti->map->id_desa_adat)->orWhereIn('id', $request->map->id_desa_adat)->get();
        return view('user.customservice.kritik-index', compact('kritik', 'desa'));
    }

    public function kritikStore(Request $request){
        $kritik = new KritikSaran;
        $kritik->id_pelanggan = auth()->guard('web')->user()->id;
        $kritik->subjek = $request->subjek;
        $kritik->kritik = $request->comment;
        $kritik->id_desa_adat = $request->desa;
        if($kritik->save()){
            return redirect()->route('custom-kritik-index')->with('success', "Berhasil menambahkan kritik & saran !");
        }else{
            return redirect()->back()->with('error', "Terdapat kesalahan pada saat penyimpanan data kritik & saran !");
        }
    }

    public function kritikUpdate(Request $request){
        $kritik = KritikSaran::where('id', $request->uid)->first();
        if(isset($kritik)){
            $kritik->kritik = $request->ucomment;
            $kritik->subjek = $request->usubjek;
            $kritik->id_desa_adat = $request->udesa;
            if($kritik->update()){
                return redirect()->route('custom-kritik-index')->with('success', "Berhasil mengubah data kritik & saran !");
            }else{
                return redirect()->route('custom-kritik-index')->with('error', "terdapat kesalahan pada saat update data kritik & saran !");
            }
        }else{
            return redirect()->back()->with('error', "Data tidak ditemukan !");
        }
    }

    public function kritikDelete($id){
        $kritik = KritikSaran::where('id', $id)->first();
        if(isset($kritik)){
            if($kritik->softDelete()){
                return redirect()->route('custom-kritik-index')->with('success', "Berhasil menghapus data !");
            }else{
                return redirect()->back()->with('error', "terdapat kesalahan saat proses penghapusan data !");
            }
        }else{
            return redirect()->back()->with('error', "data tidak ditemukan !");
        }
    }

}
