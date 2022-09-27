<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pelanggan;
use App\KramaMipil;
use App\KramaTamiu;
use App\Tamiu;
use App\Desa;
use App\DesaAdat;
use App\Kota;
use App\Kecamatan;
use App\Banjar;
use App\BanjarAdat;
use App\JenisJasa;
use App\KartuK;
use App\Properti;
use App\Provinsi;
use App\Kependudukan;
use App\Notifications\PropertiNotif;
use Illuminate\Support\Facades\Hash;


class PelangganController extends Controller
{
    //
    public function index(){
        $checkKrama = KramaMipil::where('penduduk_id', auth()->guard('admin')->user()->kependudukan->id)->first();
        if(!isset($checkKrama)){
            $checkKrama = KramaTamiu::where('penduduk_id', auth()->guard('admin')->user()->kependudukan->id)->first();
            if(!isset($checkKrama)){
                $checkKrama = Tamiu::where('penduduk_id', auth()->guard('admin')->user()->kependudukan->id)->first();
            }
        }
        $banjarAdat = BanjarAdat::where('id',$checkKrama->banjar_adat_id)->first();
        if(isset($banjarAdat)){
            $desaAdat = DesaAdat::where('id',$banjarAdat->desa_adat_id)->first();
            $penduduk = [];
            if(isset($desaAdat)){
                foreach($desaAdat->banjarAdat as $banjar){
                    $data = KramaMipil::where('banjar_adat_id', $banjar->id)->get()->toArray();
                    if(!empty($data)){
                        foreach($data as $index=>$d){
                            array_push($penduduk,$d['penduduk_id']);
                            // dd($penduduk);
                        }
                    }
                    $data = KramaTamiu::where('banjar_adat_id', $banjar->id)->get()->toArray();
                    if(!empty($data)){
                        foreach($data as $index=>$d){
                            array_push($penduduk,$d['penduduk_id']);
                            // dd($penduduk);
                        }
                    }
                    $data = Tamiu::where('banjar_adat_id', $banjar->id)->get()->toArray();
                    if(!empty($data)){
                        foreach($data as $index=>$d){
                            array_push($penduduk,$d['penduduk_id']);
                            // dd($penduduk);
                        }
                    }
                }
                // dd($penduduk);
                $index = Pelanggan::whereIn('id_penduduk', $penduduk)->get();
                return view('admin.pelanggan.index',compact('index'));
            }else{
                return view('admin.pelanggan.index')->with('error', "Desa Adat tidak ditemukan !");
            }
        }else{
            return view('admin.pelanggan.index')->with('error', "Banjar Adat tidak ditemukan !");
        }
    }

    public function create(){
        $checkKrama = KramaMipil::where('penduduk_id', auth()->guard('admin')->user()->kependudukan->id)->first();
        if(!isset($checkKrama)){
            $checkKrama = KramaTamiu::where('penduduk_id', auth()->guard('admin')->user()->kependudukan->id)->first();
            if(!isset($checkKrama)){
                $checkKrama = Tamiu::where('penduduk_id', auth()->guard('admin')->user()->kependudukan->id)->first();
            }
        }
        $banjarAdat = BanjarAdat::where('id',$checkKrama->banjar_adat_id)->first();
        if(isset($banjarAdat)){
            $desaAdat = DesaAdat::where('id',$banjarAdat->desa_adat_id)->first();
            $penduduk = [];
            if(isset($desaAdat)){
                foreach($desaAdat->banjarAdat as $banjar){
                    $data = KramaMipil::where('banjar_adat_id', $banjar->id)->get()->toArray();
                    if(!empty($data)){
                        foreach($data as $index=>$d){
                            array_push($penduduk,$d['penduduk_id']);
                            // dd($penduduk);
                        }
                    }
                    $data = KramaTamiu::where('banjar_adat_id', $banjar->id)->get()->toArray();
                    if(!empty($data)){
                        foreach($data as $index=>$d){
                            array_push($penduduk,$d['penduduk_id']);
                            // dd($penduduk);
                        }
                    }
                    $data = Tamiu::where('banjar_adat_id', $banjar->id)->get()->toArray();
                    if(!empty($data)){
                        foreach($data as $index=>$d){
                            array_push($penduduk,$d['penduduk_id']);
                            // dd($penduduk);
                        }
                    }
                }
                // dd($penduduk);
                $index = Kependudukan::whereIn('id', $penduduk)->get();
                return view('admin.pelanggan.create',compact('index'));
            }else{
                return view('admin.pelanggan.create')->with('error', "Desa Adat tidak ditemukan !");
            }
        }else{
            return view('admin.pelanggan.create')->with('error', "Banjar Adat tidak ditemukan !");
        }
        // $index = Kependudukan::where('desa_id', auth()->guard('admin')->user()->kependudukan->desa_id)->get();
        // $kota = Kota::all();
        // $banjar = Banjar::all();
        // return view('admin.pelanggan.create', compact('banjar', 'kota', 'index'));
    }

    // public function storeOld(Request $request){
    //     $messages = [
    //         'required' => 'Kolom :attribute Wajib Diisi!',
    //         'unique' => 'Kolom :attribute Tidak Boleh Sama!',
	// 	];

    //     $this->validate($request, [
    //         'nik' => 'required|unique:tb_pelanggan',
    //         'nama' => 'required',
    //         'alamat' => 'required',
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
    //     $pelanggan->password = Hash::make($request->no);
    //     $pelanggan->jenis_kelamin = $request->jenis ;
    //     $pelanggan->save();
    //     return redirect()->route('pelanggan-index')->with('success','Berhasil Menambah Data Pelanggan !');
    // }

    public function store($id){
        $penduduk = Kependudukan::where('id', $id)->first();
        if(isset($penduduk)){
            $check = Pelanggan::where('id_penduduk', $penduduk->id)->first();
            if(!isset($check)){
                $pelanggan = new Pelanggan;
                $pelanggan->id_penduduk = $penduduk->id;
                if($penduduk->telepon != null){
                    $pelanggan->username = $penduduk->telepon;
                    $pelanggan->password = Hash::make($penduduk->telepon);
                    $pelanggan->save();
                }else{
                    $pelanggan->username = $penduduk->nik;
                    $pelanggan->password = Hash::make($penduduk->nik);
                    $pelanggan->save();
                }
                return response()->json(['status'=> 'success','info' => 'Berhasil menambahkan data pelanggan !']);
            }else{
                return response()->json(['status'=> 'error','info' => 'Penduduk sudah terdaftar sebagai pelanggan !']);
            }
        }else{
            return response()->json(['status'=> 'error','info' => 'Data penduduk tidak ditemukan !']);
        }
    }

    public function edit($id){
        $desaAdat = DesaAdat::all();
        $banjarAdat = BanjarAdat::all();
        $pelanggan = Pelanggan::where('id', $id)->first();
        $jenis = JenisJasa::all();
        $index = Properti::where('id_pelanggan', $pelanggan->id)->get();
        if($pelanggan != null){
            // dd($pelanggan);
            return view('admin.pelanggan.edit', compact('pelanggan', 'index', 'jenis', 'desaAdat', 'banjarAdat'));
        }else{
            return redirect()->route('pelanggan-index')->with('error', 'Data Pelanggan Tidak Ditemukan !');
        }
        
    }

    public function update($id, Request $request){
        $pelanggan = Pelanggan::where('id', $id)->first();

        if($pelanggan!= null){
            $desa = Desa::where('name', 'LIKE' , $request->desa)->first();
            
            if($desa!=null){
                $pelanggan->id_desa = $desa->id;
            }

            $pelanggan->kependudukan->alamat = $request->alamat;
            $pelanggan->kependudukan->nik = $request->nik ;
            $pelanggan->kependudukan->nama = $request->nama;
            $pelanggan->kependudukan->tanggal_lahir = $request->tanggal ;
            $pelanggan->kependudukan->telepon = $request->no ;
            $pelanggan->kependudukan->jenis_kelamin = $request->jenis ;
            $pelanggan->update();
            return redirect()->route('pelanggan-index')->with('success', 'Berhasil Mengubah Data Pelanggan !');
        }else{
            return redirect()->route('pelanggan-index')->with('error', 'Data Pelanggan Tidak Ditemukan !');
        }
    }

    public function delete($id){
        $pelanggan = Pelanggan::where('id', $id)->first();
        if($pelanggan != null){
            $pelanggan->delete();
            return redirect()->route('pelanggan-index')->with('success', 'Berhasil Menghapus Data Pelanggan !');
        }else{
            return  redirect()->route('pelanggan-index')->with('error', 'Data Pelanggan Tidak Ditemukan !');
        }
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
        $pelanggan = Pelanggan::where('id', $request->pelanggan)->first();
        // dd($pelanggan);
        if(!isset($pelanggan)){
            return redirect()->back->with('error','Pelanggan Tidak Terdeteksi, Kesalahan pada Kode !');
        }

        if($request->file('file')){
            //simpan file
            
            $file = $request->file('file');
            $images = $pelanggan->kependudukan->nik."_".$request->nama."_".$file->getClientOriginalName();
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
        $properti->id_pelanggan = $pelanggan->id;
        $properti->jumlah_kamar = $request->kamar;
        
        if($properti->save()){
            $pelanggan->notify(new PropertiNotif($properti, "create"));
            return redirect()->route('pelanggan-edit', $request->pelanggan)->with('success','Berhasil Menambah Properti, Properti akan segera diproses !');    
        }else{
            return redirect()->route('pelanggan-edit', $request->pelanggan)->with('error','Proses Penambahan Properti Tidak Berhasil !');
        }
        
    }

    public function propertiUpdate($id, Request $request){
        $properti = Properti::where('id', $id)->first();
        if(isset($properti)){
            if($request->file('file_edit')){
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
                $file = $request->file('file_edit');
                $images = $properti->pelanggan->kependudukan->nik."_".$request->nama."_".$file->getClientOriginalName();
                // dd($images);
                $properti->file = $images;
                $foto_upload = 'assets/img/properti';
                $file->move($foto_upload,$images);
            }

            if($properti->id_jenis != $request->jenis_edit){
                $jenis = JenisJasa::where('id', $request->jenis_edit)->first();
                $properti->lat = $request->lat_edit;
                $properti->lng = $request->lat_edit;
                $properti->id_jenis = $request->jenis_edit;
                $properti->note = "Admin Mengubah Jenis properti menjadi ".$jenis->jenis_jasa;
                $properti->status = "Terverifikasi";
                $properti->update();
                return redirect()->back()->with('success', 'verifikasi Properti berhasil !');
            }else{
                $properti->lat = $request->lat_edit;
                $properti->lng = $request->lng_edit;
                $properti->status = "Terverifikasi";
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

    public function indexPenduduk(){
        $index = Kependudukan::all();
        return view('admin.penduduk.index',compact('index'));
    }

    public function createPenduduk(){
        $kota = Kota::all();
        $banjar = Banjar::all();
        return view('admin.penduduk.create', compact('banjar', 'kota'));
    }

    public function storePenduduk(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'nik' => 'required|unique:tb_pelanggan',
            'nama' => 'required',
            'alamat' => 'required',
            'no' => 'required',
        ],$messages);

        // dd($request);

        // $banjar = Banjar::where('nama_banjar_dinas', 'LIKE' , $request->banjar)->first();
        // $kota = Kota::where('name', 'LIKE', $request->tempat)->first();

        $penduduk = new Kependudukan;
        

        // if($banjar!=null){
        //     $pelanggan->id_banjar = $banjar->id;
        // }

        $penduduk->alamat = $request->alamat;
        $penduduk->nik = $request->nik ;
        $penduduk->nama = $request->nama;
        $penduduk->tanggal_lahir = $request->tanggal ;
        $penduduk->telepon = $request->no ;
        $penduduk->jenis_kelamin = $request->jenis ;
        $penduduk->save();
        return redirect()->route('penduduk-index')->with('success','Berhasil Menambah Data Penduduk !');
    }

    public function editPenduduk($id){
        $kota = Kota::all();
        $desa = Desa::all();
        $penduduk = Kependudukan::where('id', $id)->first();
        $jenis = JenisJasa::all();
        $index = Properti::where('id_pelanggan', $penduduk->pelanggan->id)->get();
        if($penduduk != null){
            // dd($penduduk);
            return view('admin.penduduk.edit', compact('penduduk','desa', 'kota', 'index', 'jenis'));
        }else{
            return redirect()->route('penduduk-index')->with('error', 'Data Penduduk Tidak Ditemukan !');
        }
        
    }

    public function updatePendudukan($id, Request $request){
        $pelanggan = Pelanggan::where('id', $id)->first();

        if($pelanggan!= null){
            $banjar = Banjar::where('nama_banjar_dinas', 'LIKE' , $request->banjar)->first();
            
            if($banjar!=null){
                $pelanggan->id_banjar = $banjar->id;
            }

            $pelanggan->alamat = $request->alamat;
            $pelanggan->nik = $request->nik ;
            $pelanggan->nama = $request->nama;
            $pelanggan->tgl_lahir = $request->tanggal ;
            $pelanggan->no_telp = $request->no ;
            $pelanggan->jenis_kelamin = $request->jenis ;
            $pelanggan->update();
            return redirect()->route('pelanggan-index')->with('success', 'Berhasil Mengubah Data Pelanggan !');
        }else{
            return redirect()->route('pelanggan-index')->with('error', 'Data Pelanggan Tidak Ditemukan !');
        }
    }

    public function banjarCheck(Request $request){
        $desa = DesaAdat::where('id', $request->desa)->first();
        if(isset($desa)){
            $banjar = BanjarAdat::where('desa_adat_id', $desa->id)->get();
            if(!$banjar->isEmpty()){
                $data['banjar'] = $banjar;
                $data['status'] = "success";
    
                return response()->json($data, 200);
            }else{
                $data['status'] = "Data banjar tidak ditemukan !";
    
                return response()->json($data, 200);
            }
        }else{
            $data['status'] = "Data desa adat tidak ditemukan !";
            return response()->json($data, 200);
        }
    }


}