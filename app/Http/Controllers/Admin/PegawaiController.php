<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pegawai;
use App\Pengguna;
use App\Banjar;
use Illuminate\Support\Facades\Hash;


class PegawaiController extends Controller
{
    //
    public function indexPegawai(){
        $index = Pegawai::all();
        return view('admin.pegawai.index',compact('index'));
    }

    public function createPegawai(){
        $banjar = Banjar::all();
        $pegawai = Pegawai::select('id_pengguna')->get()->toArray();
        // dd($pegawai);
        $index = Pengguna::whereNotIn('id', $pegawai)->get();

        return view('admin.pegawai.create', compact('banjar', 'index'));
    }

    public function storePegawai(Request $request){
        $pengguna = Pengguna::where('id', $request->idpeng)->first();
        // dd($pengguna);
        if($pengguna != null){
            $pegawai = new Pegawai;
            $pegawai->id_pengguna = $pengguna->id;
            if($pengguna->no_telp != null){
                $pegawai->username = $pengguna->no_telp;
                $pegawai->password = Hash::make($pengguna->no_telp);
            }
            $pegawai->save();
            return redirect()->route('pegawai-index')->with('success', 'Berhasil Menambahkan Data Pegawai');
        }else{
            return redirect()->back()->with('error', 'Data Pengguna yang dipilih tidak ditemukan !');
        }
    }
    
    public function storePegawaiNew(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'nik' => 'required|unique:tb_pengguna',
            'nama' => 'required',
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
        $pengguna->jenis_kelamin = $request->jenis ;
        $pengguna->save();

        $pengguna = Pengguna::where('nik', $request->nik)->first();

        $pegawai = new Pegawai;
        $pegawai->username = $request->no;
        $pegawai->password = Hash::make($request->no);
        $pegawai->save();

        return redirect()->route('pegawai-index')->with('success','Berhasil Menambah Data Pengguna dan Menambahkan Pegawai !');
    }

    public function editPegawai($id){
        $pegawai = Pegawai::where('id_pegawai', $id)->first();
        $banjar = Banjar::all();
        if($pegawai != null){
            // dd($pegawai);
            return view('admin.pegawai.edit', compact('banjar', 'pegawai'));
        }else{
            return redirect()->route('pegawai-index')->with('error', 'Data Pegawai Tidak Ditemukan !');
        }
    }

    public function updatePegawai($id, Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'nik' => 'required|unique:tb_pengguna',
            'nama' => 'required',
            'no' => 'required',
        ],$messages);

        // dd($request);

        $banjar = Banjar::where('nama_banjar_dinas', 'LIKE' , $request->banjar)->first();
        // $kota = Kota::where('name', 'LIKE', $request->tempat)->first();

        $pegawai = Pegawai::where('id_pengguna', $id)->first();
        if($pegawai != null && $pegawai->id_pengguna != null){
            $pengguna = Pengguna::where('id', $pegawai->id_pengguna)->first();
            // dd($pengguna);
            if($banjar!=null){
                $pengguna->id_banjar = $banjar->id;
            }
    
            // dd($request->alamat.' '.$request->nama );
            $pengguna->alamat = $request->alamat;
            $pengguna->nik = $request->nik ;
            $pengguna->nama_pengguna = $request->nama;
            $pengguna->tgl_lahir = $request->tanggal ;
            $pengguna->no_telp = $request->no ;
            $pengguna->jenis_kelamin = $request->jenis ;
            $pengguna->update();

            // $pegawai->username = $request->no;
            // $pegawai->password = Hash::make($request->no);
            $pegawai->update();

            return redirect()->route('pegawai-index')->with('success','Berhasil Mengubah Data Pegawai !');
        }
        
    }

    public function deletePegawai($id){
        $pegawai = Pegawai::where('id_pegawai', $id)->first();
        if($pegawai != null){
            $pegawai->delete();
            return redirect()->route('pegawai-index')->with('success', 'Berhasil Menghapus Data Pegawai');
        }else{
            return redirect()->route('pegawai-index')->with('error', 'Data Pegawai Tidak Ditemukan !');
        }
    }
}
