<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Pengguna;
use App\Banjar;
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
            'no_telp' => 'required',
            'password' => 'required|min:8'
        ]);
        // dd(auth()->guard('web')->attempt($request->only(['no_telp', 'password'])));
        if(auth()->guard('web')->attempt($request->only(['no_telp', 'password']))) {
            $request->session()->regenerate();
            $user = Pengguna::where('no_telp', $request->no_telp)->first();
            
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
                ->withErrors(["Incorrect user login details!"]);
        }
    }

    public function logout(){
        auth()->guard('web')->logout();
        session()->flush();
        // dd(auth()->guard('admin'));

        return redirect()->route('home');
    }

    public function registerPage(){
        $banjar = Banjar::all();
        return view('auth.register', compact('banjar'));
    }

    public function register(Request $request){
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
        return redirect()->route('login-page')->with('success','Berhasil Mendaftarkan Data Pelanggan !');
    }
    
}
