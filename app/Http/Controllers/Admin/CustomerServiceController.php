<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\KepuasanPengguna;
use App\Pembayaran;
use App\Pelanggan;
use App\Retribusi;
use App\Pengangkutan;
use App\Properti;
use App\KritikSaran;

class CustomerServiceController extends Controller
{
    //
    public function penilaianIndex(){
        $data = [];
        $kepRet = KepuasanPengguna::where('item_type', 'App\Retribusi')->get();
        $kepPeng = KepuasanPengguna::where('item_type', 'App\Pengangkutan')->get();
        $properti = Properti::where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat)->get();
        $kepuasan = KepuasanPengguna::whereHasMorph('item', ["App\Retribusi"], function(Builder $querys){
            $properti = Properti::where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat)->get();
            $querys->whereIn('id_properti', $properti->map->id); 
        })->orWhereHasMorph('item', ["App\Pengangkutan"], function(Builder $querys){
            $querys->where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat); 
        })->get();
        // dd($kepuasan);
        $retribusi = Retribusi::whereIn('id_properti', $properti->map->id)->with(['properti', 'kepuasan','pelanggan'])->whereIn('id', $kepRet->map->item_id)->where('status', 'lunas')->get();
        foreach($retribusi as $r){
            $r->nama_pelanggan = $r->pelanggan->kependudukan->nama;
            array_push($data, $r);
        }
        $pengangkutan = Pengangkutan::where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat)->whereHas('pembayaran', function(Builder $query){
            $query->where('status', 'lunas');
        })->whereIn('id', $kepPeng->map->item_id)->with(['kepuasan','pelanggan'])->get();
        foreach($pengangkutan as $p){
            $p->nama_pelanggan = $p->pelanggan->kependudukan->nama;
            array_push($data, $p);
        }
        // dd($data);
        return view('admin.customer-service.penilaian-index', compact('data', 'kepuasan'));
    }

    public function penilaianStore(Request $request){
        $id = explode("-",$request->id);
        $checkPenilaian = KepuasanPengguna::where('item_id', $id[0])->where('item_type', "App\\".$id[1])->first();
        if(!isset($checkPenilaian)){
            $penilaian  = new KepuasanPengguna;
            $penilaian->item_id = $id[0];
            $penilaian->rate = $request->rate;
            $penilaian->item_type = "App\\".$id[1];
            $penilaian->comment = $request->comment;
            if($penilaian->save()){
                return redirect()->route('admin-custom-penilaian-index')->with('success', "Berhasil menambahkan penilaian !");
            }else{
                return redirect()->route('admin-custom-penilaian-index')->with('error', "Gagal menambahkan penilaian ! (ERROR)");
            }
        }else{
            return redirect()->route('admin-custom-penilaian-index')->with('error', "Penilaian telah diberikan ! lakukan perubahan pada tombol ubah !");
        }
        
    }

    public function kritikIndex(){
        $kritik = KritikSaran:: where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat)->get();
        
        return view('admin.customer-service.kritik-index', compact('kritik'));
    }

    public function kritikDelete($id){
        $kritik = KritikSaran::where('id', $id)->first();
        if(isset($kritik)){
            if($kritik->Delete()){
                return redirect()->route('admin-custom-kritik-index')->with('success', "Berhasil menghapus data !");
            }else{
                return redirect()->back()->with('error', "terdapat kesalahan saat proses penghapusan data !");
            }
        }else{
            return redirect()->back()->with('error', "data tidak ditemukan !");
        }
    }

}
