<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pengangkutan;
use App\DesaAdat;

class RequestController extends Controller
{
    //
    public function index(){
        $index = Pengangkutan::where('id_pelanggan', auth()->guard('web')->user()->id)->get();
        return view('user.request.index', compact('index'));
    }

    public function create(){
        $desaAdat = DesaAdat::all();
        return view('user.request.create', compact('desaAdat'));
    }

    public function store(Request $request){
        
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
            'max' => 'Ukuran File tidak boleh melebihi 5 MB',
            'numeric' => 'Kolom :attribute Hanya  Menerima Inputan Angka  !'
		];

        // dd($request->id);
        // dd($request->file);
        // dd($request->media);
        // dd($request->nominal);
        
        $this->validate($request, [
            'file' => 'max:5120',
            'desaAdat' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'alamat' => 'required',
        ],$messages);
        


        $requestP = new Pengangkutan;

        if($request->file('file')){
            //simpan file
            
            $file = $request->file('file');
            $images = auth()->guard('web')->user()->kependudukan->nik."_".$file->getClientOriginalName();
            // dd($images);
            $requestP->file = $images;
            $foto_upload = 'assets/img/request_p';
            $file->move($foto_upload,$images);
        }
        $requestP->id_pelanggan = auth()->guard('web')->user()->id;
        $requestP->lat = $request->lat;
        $requestP->lng = $request->lng;
        $requestP->id_desa_adat = $request->desaAdat;
        $requestP->alamat = $request->alamat;
        $requestP->status = "Pending";
        if($requestP->save()){
            return redirect()->route('request-index')->with('success', 'Berhasil membuat request pengangkutan !');
        }else{
            return redirect()->back()->with('error', 'Gagal membuat request pengangkutan !');
        }
    }

    public function edit($id){
        $requestP = Pengangkutan::where('id', $id)->first();
        $desaAdat = DesaAdat::all();
        return view('user.request.edit', compact('requestP', 'desaAdat'));
    }

    public function update(Request $request, $id){
        
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
            'max' => 'Ukuran File tidak boleh melebihi 5 MB',
            'numeric' => 'Kolom :attribute Hanya  Menerima Inputan Angka  !'
		];

        // dd($request->id);
        // dd($request->file);
        // dd($request->media);
        // dd($request->nominal);
        
        $this->validate($request, [
            'file' => 'max:5120',
            'desaAdat' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'alamat' => 'required',
        ],$messages);
        


        $requestP = Pengangkutan::where('id', $id)->first();


        if(isset($requestP)){
            if($request->file('file')){
                //simpan file
                
                $file = $request->file('file');
                $images = auth()->guard('web')->user()->nik."_".$file->getClientOriginalName();
                // dd($images);
                $requestP->file = $images;
                $foto_upload = 'assets/img/request_p';
                $file->move($foto_upload,$images);
            }
            $requestP->id_pelanggan = auth()->guard('web')->user()->id;
            $requestP->lat = $request->lat;
            $requestP->lng = $request->lng;
            $requestP->alamat = $request->alamat;
            $requestP->id_desa_adat = $request->desaAdat;
            $requestP->status = "Pending";
            if($requestP->update()){
                return redirect()->route('request-index')->with('success', 'Berhasil mengubah request pengangkutan !');
            }else{
                return redirect()->back()->with('error', 'Gagal mengubah request pengangkutan !');
            }
        }else{
            return redirect()->back()->with('error', 'Gagal mengubah request pengangkutan !');
        }

    }

    public function cancel($id){
        $requestP = Pengangkutan::where('id', $id)->first();
        if($requestP->status == 'Pending'){
            $requestP->status = "Batal";
            $requestP->update();
            return redirect()->route('request-index')->with('success', 'Berhasil membatalkan request pengangkutan !');  
        }else{
            return redirect()->back()->with('error', 'Proses pembatalan request pengangkutan tidak dapat dilakukan !');
        }
    }


}
