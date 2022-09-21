<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Pengguna;
use App\Banjar;
use App\BanjarAdat;
use App\Desa;
use App\DesaAdat;
use App\Kependudukan;
use App\KramaMipil;
use App\KramaTamiu;
use App\Tamiu;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    
    use AuthenticatesUsers;
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $maxAttempts = 3;
    protected $decayMinutes = 1;
    
    public function __construct()
    {
        $this->middleware('guest:web')->only('login', 'loginPage', 'registerPage', 'register');
    }

    public function loginPage(){
        return view('auth.login');
    }

    public function login(Request $request){
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required|min:8'
        ]);
        // dd(auth()->guard('web')->attempt($request->only(['no_telp', 'password'])));
        $checkus = Pengguna::where('username', $request->username)->first();
        if(!is_null($checkus)){
            if(auth()->guard('web')->attempt($request->only(['username', 'password']))) {
                $request->session()->regenerate();
                // $user = Pengguna::where('no_telp', $request->no_telp)->first();
                
                // dd(Auth::guard('web')->login($user));
                // dd($user);
                // dd(session());  
                $this->clearLoginAttempts($request);
                // dd(Auth::guard('admin')->user()->nama); 
                return redirect()->route('home');
            }else{
                $this->incrementLoginAttempts($request);
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(["password" => "Data login pengguna salah !"], 'login');
            }
        }else{
            return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors([ "username" => "Username pengguna tidak ditemukan !"], 'login');
        }
    }

    public function logout(){
        auth()->guard('web')->logout();
        session()->flush();
        // dd(auth()->guard('admin'));

        return redirect()->route('home');
    }

    public function registerPage(){
        $desa = DesaAdat::all();
        return view('auth.register', compact('desa'));
    }

    public function register(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
            'min' => ':attribute Harus Lebih Dari 8 Karakter',
            'same' => 'Konfirmasi Password Berbeda dengan Password !',
		];

        $this->validate($request, [
            'nik' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'password' => 'required|min:8',
            'passcheck' => 'required|same:password'
        ],$messages);

        // dd($request);

        $desa = Desa::where('name', 'LIKE' , $request->desa)->first();
        // $kota = Kota::where('name', 'LIKE', $request->tempat)->first();

        $pengguna = new Pengguna;
        

        if($desa!=null){
            $pengguna->id_desa = $desa->id;
        }

        $pengguna->alamat = $request->alamat;
        $pengguna->nik = $request->nik ;
        $pengguna->nama_pengguna = $request->nama;
        $pengguna->tgl_lahir = $request->tanggal ;
        $pengguna->no_telp = $request->no ;
        $pengguna->password = Hash::make($request->no);
        $pengguna->jenis_kelamin = $request->jenis ;
        $pengguna->save();
        return redirect()->route('login-page')->with('success','Berhasil Mendaftarkan Data Pelanggan !');
    }
    
    public function registerSearch(Request $request){
        $cpelanggan = Kependudukan::where('nik', $request->nik)->first();

        if(!is_null($cpelanggan)){
            $checkKrama = KramaMipil::where('penduduk_id', $cpelanggan->id)->first();
            if(!isset($checkKrama)){
                $checkKrama = KramaTamiu::where('penduduk_id', $cpelanggan->id)->first();
                if(!isset($checkKrama)){
                    $checkKrama = Tamiu::where('penduduk_id', $cpelanggan->id)->first();
                }
            }
            
            $banjarAdat = BanjarAdat::where('id',$checkKrama->banjar_adat_id)->first();
            
            if(isset($banjarAdat)){
                $desaAdat = DesaAdat::where('id',$banjarAdat->desa_adat_id)->first();
            }
            if(!is_null($desaAdat)){
                $data['desa'] = $desaAdat->desadat_nama;
            }
            $data['nama'] = $cpelanggan->nama;
            $data['tanggal_lahir'] = $cpelanggan->tanggal_lahir ;
            $data['jenis_kelamin'] = $cpelanggan->jenis_kelamin ;
            $data['alamat'] = $cpelanggan->alamat ;
            $data['telepon'] = $cpelanggan->telepon ;

            return response()->json($data, 200);
        }else{
            $data['error'] = "Data Penduduk Tidak Ditemukan"; 

            return response()->json($data, 200);
        }
    }

}
