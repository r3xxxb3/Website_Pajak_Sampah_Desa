<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\JenisJasa;
use Illuminate\Http\Request;
use App\Properti;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function properti(){
        // dd(Auth::guard('web')->user()->id);
        $index = Properti::where('id_pengguna', Auth::guard('web')->user()->id)->get();
        return view('user.properti.index', compact('index'));
    }

    public function propertiCreate(){
        $jenis = JenisJasa::all();
        return view('user.properti.create', compact('jenis'));
    }

    public function propertiStore(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'jenis' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
        ],$messages);

        $properti = new Properti;
        $properti->nama_properti = $request->nama;
        $properti->alamat = $request->alamat;
        $properti->id_jenis = $request->jenis;
        $properti->status = "Pending";
        $properti->id_pengguna = Auth::guard('web')->user()->id;
        $properti->jumlah_kamar = $request->kamar;
        $properti->file = $request->file;

        if($properti->save()){
            return redirect()->route('properti-index')->with('success','Berhasil Menambah Properti, Properti akan segera diproses !');    
        }else{
            return redirect()->route('properti-index')->with('error','Proses Penambahan Properti Tidak Berhasil !');
        }
        
    }

    public function propertiEdit($id){
        $jenis = Jenisjasa::all();
        $properti = Properti::where('id', $id)->first();
        return view('user.properti.edit', compact('properti', 'jenis'));
    }

    public function propertiUpdate($id, Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'jenis' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
        ],$messages);

        $properti = Properti::where('id', $id)->first();
        if(isset($properti)){
            $properti->nama_properti = $request->nama;
            $properti->alamat = $request->alamat;
            if($properti->id_jenis != $request->jenis){
                $properti->id_jenis = $request->jenis;
                $properti->status = "Pending";
            }
            
            $properti->id_pengguna = Auth::guard('web')->user()->id;
            $properti->jumlah_kamar = $request->kamar;
            $properti->file = $request->file;

            if($properti->update()){
                return redirect()->route('properti-index')->with('success','Berhasil Mengubah Data Properti, Properti akan segera diproses !');    
            }else{
                return redirect()->route('properti-index')->with('error','Proses Penngubahan Properti Tidak Berhasil !');
            }
        }else{
            return redirect()->route('properti-index')->with('error','Data Properti Tidak Ditemukan !');
        }
        
    }

    public function propertiDelete($id){
        $properti = Properti::where('id', $id)->where('id_pengguna', Auth::guard('web')->user()->id)->first();
        // dd($properti);
        if(isset($properti)){
            if($properti->status == 'Cancelled'){
                $properti->delete();
                return redirect()->route('properti-index')->with('success', 'Data Properti berhasil dihapus !');
            }elseif($properti->status == 'Pending'){
                return redirect()->route('properti-index')->with('error', 'Data Properti belum diperiksa Admin !');
            }elseif($properti->status == 'Verified'){
                return redirect()->route('properti-index')->with('error', 'Data Properti telah Terdaftar, Ajukan Pembatalan Properti Terlebih Dahulu !');
            }else{
                $properti->delete();
                return redirect()->route('properti-index')->with('success', 'Data Properti berhasil dihapus !');
            }

        }
    }
}
