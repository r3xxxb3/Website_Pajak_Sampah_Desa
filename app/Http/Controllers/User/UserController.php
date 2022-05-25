<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\JenisJasa;
use Illuminate\Http\Request;
use App\Properti;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Pengguna;
use App\Banjar;
use App\Pegawai;
use App\Notifications\PropertiNotif;
use File;

class UserController extends Controller
{
    //

    public function dataIndex(){
        $pengguna = Pengguna::where('id', Auth()->guard('web')->user()->id)->first();
        return view('user.data-diri.index', compact('pengguna'));
    }

    public function dataUpdate(Request $request){
        $pengguna = Pengguna::where('id', Auth()->guard('web')->user()->id)->first();

        if($pengguna!= null){
            $banjar = Banjar::where('nama_banjar_dinas', 'LIKE' , $request->banjar)->first();
            
            if($banjar!=null){
                $pengguna->id_banjar = $banjar->id;
            }

            $pengguna->alamat = $request->alamat;
            $pengguna->nik = $request->nik ;
            $pengguna->nama_pengguna = $request->nama ;
            $pengguna->tgl_lahir = $request->tanggal ;
            $pengguna->no_telp = $request->no ;
            $pengguna->jenis_kelamin = $request->jenis ;
            $pengguna->update();
            return redirect()->route('data-index')->with('success', 'Berhasil Mengubah Data Diri !');
        }else{
            return redirect()->route('data-index')->with('error', 'Data Pengguna Tidak Ditemukan !');
        }
    }

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
            'max' => 'Ukuran File tidak boleh melebihi 5 MB',
		];

        $this->validate($request, [
            'jenis' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'file' => 'max:5120',
        ],$messages);
        
        $properti = new Properti;

        if($request->file('file')){
            //simpan file
            
            $file = $request->file('file');
            $images = auth()->guard('web')->user()->nik."_".$request->nama."_".$file->getClientOriginalName();
            // dd($images);
            $properti->file = $images;
            $foto_upload = 'assets/img/properti';
            $file->move($foto_upload,$images);
        }

        $properti->nama_properti = $request->nama;
        $properti->alamat = $request->alamat;
        $properti->id_jenis = $request->jenis;
        $properti->lat = $request->lat;
        $properti->lng = $request->lng;
        $properti->status = "Pending";
        $properti->id_pengguna = Auth::guard('web')->user()->id;
        $properti->jumlah_kamar = $request->kamar;
        
        if($properti->save()){
            $pegawai = Pegawai::all();
            // dd($properti->id_jenis);
            // $properti->toArray();
            foreach($pegawai as $p){
                $p->notify(new PropertiNotif($properti, "create"));
            }
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
            'max' => 'Ukuran File tidak boleh melebihi 5 MB',
		];

        $this->validate($request, [
            'jenis' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'file' => 'max:5120',
        ],$messages);

        $properti = Properti::where('id', $id)->first();
        if(isset($properti)){

            if($request->file('file')){
                //simpan file
                if(!is_null($properti->file)){
                    $oldfile = public_path("assets/img/properti/".$properti->file);
                    // dd(File::exists($oldfile));
                    if (File::exists($oldfile)) {
                        // dd($oldfile);
                        File::delete($oldfile);
                        // unlink($oldfile);
                    }
                }
                $file = $request->file('file');
                $images = auth()->guard('web')->user()->nik."_".$request->nama."_".$file->getClientOriginalName();
                // dd($images);
                $properti->file = $images;
                $foto_upload = 'assets/img/properti';
                $file->move($foto_upload,$images);
            }
            $properti->lat = $request->lat;
            $properti->lng = $request->lng;
            $properti->nama_properti = $request->nama;
            $properti->alamat = $request->alamat;
            if($properti->id_jenis != $request->jenis){
                $properti->id_jenis = $request->jenis;
                $properti->status = "Pending";
                
                $properti->id_pengguna = Auth::guard('web')->user()->id;
                $properti->jumlah_kamar = $request->kamar;

                if($properti->update()){
                    $pegawai = Pegawai::all();
                    foreach($pegawai as $p){
                        $p->notify(new PropertiNotif($properti, "update"));
                    }
                    return redirect()->route('properti-index')->with('success','Berhasil Mengubah Data Properti, Properti akan segera diproses !');    
                }else{
                    return redirect()->route('properti-index')->with('error','Proses Penngubahan Properti Tidak Berhasil !');
                }
            }else{
                $properti->id_pengguna = Auth::guard('web')->user()->id;
                $properti->jumlah_kamar = $request->kamar;

                if($properti->update()){
                    return redirect()->route('properti-index')->with('success','Berhasil Mengubah Data Properti, Properti akan segera diproses !');    
                }else{
                    return redirect()->route('properti-index')->with('error','Proses Penngubahan Properti Tidak Berhasil !');
                }
            }
        }else{
            return redirect()->route('properti-index')->with('error','Data Properti Tidak Ditemukan !');
        }
        
    }

    public function propertiCancel($id){

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
