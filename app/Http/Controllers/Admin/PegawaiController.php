<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pegawai;
use App\Pelanggan;
use App\Banjar;
use App\Kependudukan;
use Illuminate\Support\Facades\Hash;


class PegawaiController extends Controller
{
    //
    public function indexPegawai(){
        $index = Pegawai::where('id_desa_adat', auth()->guard('admin')->user()->id_desa_adat)->get();
        return view('admin.pegawai.index',compact('index'));
    }

    public function createPegawai(){
        $banjar = Banjar::all();
        $pegawai = Pegawai::select('id_penduduk')->get()->toArray();
        // dd($pegawai);
        $index = Kependudukan::whereNotIn('id', $pegawai)->get();

        return view('admin.pegawai.create', compact('banjar', 'index'));
    }

    public function storePegawai(Request $request){
        $penduduk = Kependudukan::where('id', $request->idpeng)->first();
        // dd($pelanggan);
        if($penduduk != null){
            $pegawai = new Pegawai;
            $pegawai->id_desa_adat = auth()->guard('admin')->user()->kependudukan->mipil->banjarAdat->desaAdat->id;
            $pegawai->id_penduduk = $penduduk->id;
            if($penduduk->telepon != null){
                $pegawai->username = $penduduk->telepon;
                $pegawai->password = Hash::make($penduduk->telepon);
            }else{
                $pegawai->username = $penduduk->nik;
                $pegawai->password = Hash::make($penduduk->nik);
            }
            $pegawai->save();
            return redirect()->route('pegawai-index')->with('success', 'Berhasil menambahkan data pegawai !');
        }else{
            return redirect()->back()->with('error', 'Data penduduk yang dipilih tidak ditemukan !');
        }
    }
    
    // public function storePegawaiNew(Request $request){
    //     $messages = [
    //         'required' => 'Kolom :attribute Wajib Diisi!',
    //         'unique' => 'Kolom :attribute Tidak Boleh Sama!',
	// 	];

    //     $this->validate($request, [
    //         'nik' => 'required|unique:tb_pelanggan',
    //         'nama' => 'required',
    //         'no' => 'required',
    //     ],$messages);

    //     // dd($request);

    //     $banjar = Banjar::where('nama_banjar_dinas', 'LIKE' , $request->banjar)->first();
    //     // $kota = Kota::where('name', 'LIKE', $request->tempat)->first();

    //     $pelanggan = new Pelanggan;
        

    //     if($banjar!=null){
    //         $pelanggan->id_banjar = $banjar->id;
    //     }

    //     $pelanggan->alamat = $request->alamat;
    //     $pelanggan->nik = $request->nik ;
    //     $pelanggan->nama = $request->nama;
    //     $pelanggan->tgl_lahir = $request->tanggal ;
    //     $pelanggan->no_telp = $request->no ;
    //     $pelanggan->jenis_kelamin = $request->jenis ;
    //     $pelanggan->save();

    //     $pelanggan = Pelanggan::where('nik', $request->nik)->first();

    //     $pegawai = new Pegawai;
    //     $pegawai->username = $request->no;
    //     $pegawai->password = Hash::make($request->no);
    //     $pegawai->save();

    //     return redirect()->route('pegawai-index')->with('success','Berhasil Menambah Data Pelanggan dan Menambahkan Pegawai !');
    // }

    public function editPegawai($id){
        $pegawai = Pegawai::where('id_pegawai', $id)->first();
        // dd($pegawai->kependudukan);
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
            'nik' => 'required|unique:tb_pelanggan',
            'nama' => 'required',
            'no' => 'required',
        ],$messages);

        // dd($request);

        // $kota = Kota::where('name', 'LIKE', $request->tempat)->first();

        $pegawai = Pegawai::where('id_pelanggan', $id)->first();
        if($pegawai != null && $pegawai->id_penduduk != null){
            $data_kependudukan = Kependuduk::where('id', $pegawai->id_penduduk)->first();
            // dd($pelanggan);
    
            // dd($request->alamat.' '.$request->nama );
            $data_kependudukan->alamat = $request->alamat;
            $data_kependudukan->nik = $request->nik ;
            $data_kependudukan->nama = $request->nama;
            $data_kependudukan->tanggal_lahir = $request->tanggal ;
            $data_kependudukan->telepon = $request->no ;
            $data_kependudukan->jenis_kelamin = $request->jenis ;
            $data_kependudukan->update();

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
