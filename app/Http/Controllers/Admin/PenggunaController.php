<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pengguna;
use App\Desa;
use App\Kota;
use App\Kecamatan;
use App\Banjar;
use App\JenisJasa;
use App\KartuK;
use App\Properti;
use App\Provinsi;
use Illuminate\Support\Facades\Hash;


class PenggunaController extends Controller
{
    //
    public function index(){
        $index = Pengguna::all();
        return view('admin.pengguna.index',compact('index'));
    }

    public function create(){
        $kota = Kota::all();
        $banjar = Banjar::all();
        return view('admin.pengguna.create', compact('banjar', 'kota'));
    }

    public function store(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'nik' => 'required|unique:tb_pengguna',
            'nama' => 'required',
            'alamat' => 'required',
            'no' => 'required',
        ],$messages);

        // dd($request);

        $banjar = Banjar::where('nama_banjar_dinas', 'LIKE' , $request->banjar)->first();
        // $kota = Kota::where('name', 'LIKE', $request->tempat)->first();

        $pengguna = new Pengguna;
        

        if($banjar!=null){
            $pengguna->id_banjar = $banjar->id;
        }

        $pengguna->alamat = $request->alamat;
        $pengguna->nik = $request->nik ;
        $pengguna->nama_pengguna = $request->nama;
        $pengguna->tgl_lahir = $request->tanggal ;
        $pengguna->no_telp = $request->no ;
        $pengguna->password = Hash::make($request->no);
        $pengguna->jenis_kelamin = $request->jenis ;
        $pengguna->save();
        return redirect()->route('pengguna-index')->with('success','Berhasil Menambah Data Pelanggan !');
    }

    public function edit($id){
        $kota = Kota::all();
        $banjar = Banjar::all();
        $pengguna = Pengguna::where('id', $id)->first();
        $jenis = JenisJasa::all();
        $index = Properti::where('id_pengguna', $pengguna->id)->get();
        if($pengguna != null){
            // dd($pengguna);
            return view('admin.pengguna.edit', compact('pengguna','banjar', 'kota', 'index', 'jenis'));
        }else{
            return redirect()->route('pengguna-index')->with('error', 'Data Pelanggan Tidak Ditemukan !');
        }
        
    }

    public function update($id, Request $request){
        $pengguna = Pengguna::where('id', $id)->first();

        if($pengguna!= null){
            $banjar = Banjar::where('nama_banjar_dinas', 'LIKE' , $request->banjar)->first();
            
            if($banjar!=null){
                $pengguna->id_banjar = $banjar->id;
            }

            $pengguna->alamat = $request->alamat;
            $pengguna->nik = $request->nik ;
            $pengguna->nama_pengguna = $request->nama;
            $pengguna->tgl_lahir = $request->tanggal ;
            $pengguna->no_telp = $request->no ;
            $pengguna->jenis_kelamin = $request->jenis ;
            $pengguna->update();
            return redirect()->route('pengguna-index')->with('success', 'Berhasil Mengubah Data Pengguna !');
        }else{
            return redirect()->route('pengguna-index')->with('error', 'Data Pengguna Tidak Ditemukan !');
        }
    }

    public function delete($id){
        $pengguna = Pengguna::where('id', $id)->first();
        if($pengguna != null){
            $pengguna->delete();
            return redirect()->route('pengguna-index')->with('success', 'Berhasil Menghapus Data Pengguna !');
        }else{
            return  redirect()->route('pengguna-index')->with('error', 'Data Pengguna Tidak Ditemukan !');
        }
    }

    public function propertiStore(){

    }

    public function propertiUpdate($id, Request $request){
        $properti = Properti::where('id', $id)->first();
        if(isset($properti)){
            if($properti->id_jenis != $request->jenis){
                $jenis = JenisJasa::where('id', $request->jenis)->first();
                $properti->id_jenis = $request->jenis;
                $properti->note = "Admin Mengubah Jenis properti menjadi ".$jenis->jenis_jasa;
                $properti->status = "Verified";
                $properti->update();
                return redirect()->back()->with('success', 'verifikasi Properti berhasil !');
            }else{
                $properti->status = "Verified";
                $properti->update();
                return redirect()->back()->with('success', 'verifikasi Properti berhasil !');
            }
        }
        return redirect()->back()->with('error-1', 'Data Properti Tidak ditemukan !');
    }

    public function propertiCancel($id){
        $properti = Properti::where('id', $id)->first();
        if(isset($properti)){
            $properti->status =  "Cancelled";
            $properti->update();
            return redirect()->back()->with('success-1', 'Pembatalan Properti berhasil !');
        }else{
            return redirect()->back()->with('error-1', 'Data Properti Tidak Ditemukan !');
        }
        
    }

    public function propertiDelete($id){
        $properti = Properti::where('id', $id)->first();
        if(isset($properti)){
            $properti->delete();
            return redirect()->back()->with('success-1', 'Penghapusan Properti berhasil !');
        }else{
            return redirect()->back()->with('error-1', 'Data Properti Tidak Ditemukan !');
        }
    }


}
