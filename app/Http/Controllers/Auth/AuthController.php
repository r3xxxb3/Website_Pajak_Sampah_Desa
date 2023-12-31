<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Pelanggan;
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
            'password' => 'required'
        ]);
        // dd(auth()->guard('web')->attempt($request->only(['no_telp', 'password'])));
        $checkus = Pelanggan::where('username', $request->username)->first();
        $checkpen = Kependudukan::where('telepon', $request->username)->orWhere('nik', $request->username)->first();
        // dd($checkpen);
        if(isset($checkpen)){
            $checkpel = Pelanggan::where('id_penduduk', $checkpen->id)->first();
        }else{
            $checkpel = null;
        }
        if(isset($checkus)){
            if(auth()->guard('web')->attempt($request->only(['username', 'password']), $request->remember)) {
                $request->session()->regenerate();
                // $user = Pelanggan::where('no_telp', $request->no_telp)->first();
                
                // dd(Auth::guard('web')->login($user));
                // dd($user);
                // dd(session());  
                $this->clearLoginAttempts($request);
                return redirect()->route('user-dashboard');
            }else{
                $this->incrementLoginAttempts($request);
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(["password" => "Data login pelanggan salah !"], 'login');
            }
        }elseif(isset($checkpel)){
            // dd(auth()->guard('web')->attempt(['username' => $checkpel->username, 'password' => $request->password], $request->remember));
            if(auth()->guard('web')->attempt(['username' => $checkpel->username, 'password' => $request->password], $request->remember)) {
               $request->session()->regenerate();
               $this->clearLoginAttempts($request);
               return redirect()->route('user-dashboard');
           }else{
               $this->incrementLoginAttempts($request);
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(["password" => "Data login pelanggan salah !"], 'login');
           }
        }else{
            return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors([ "username" => "Pelanggan tidak ditemukan !"], 'login');
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
            'username' => 'required|unique:tb_pelanggan',
            'nik' => 'required',
            'password' => 'required|min:8',
            'passcheck' => 'required|same:password'
        ],$messages);

        // dd($request);
        $penduduk = Kependudukan::where('nik', $request->nik)->first();
        $cekpelanggan = Pelanggan::where('id_penduduk', $penduduk->id)->first();
        
        if(isset($cekpelanggan)){
            return redirect()->back()->with('error', "Penduduk telah terdaftar sebagai pelanggan !");
        }
        
        if(isset($penduduk)){
            $pelanggan = new Pelanggan;
            $pelanggan->id_penduduk = $penduduk->id;
            $pelanggan->username = $request->username;
            $pelanggan->password = Hash::make($request->password);
            $pelanggan->save();
            return redirect()->route('login-page')->with('success','Berhasil Mendaftarkan Data Pelanggan !');
        }else{
            return redirect()->route('login-page')->with('Error','Query Error !');
        }
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
            $data['tempat_lahir'] = $cpelanggan->tempat_lahir;
            $data['alamat'] = $cpelanggan->alamat ;
            $data['telepon'] = $cpelanggan->telepon ;

            return response()->json($data, 200);
        }else{
            $data['error'] = "Data Penduduk Tidak Ditemukan"; 

            return response()->json($data, 200);
        }
    }

}
