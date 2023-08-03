<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Pegawai;
use App\Kependudukan;


class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected $maxAttempts = 3;
    protected $decayMinutes = 1;
    // protected $redirectTo = '/admin/dashboard';
    // protected $guard = 'admin';

    public function __construct()
    {
        $this->middleware('guest:admin')->only('Login','Auth');
    }
    //
    public function Login(Request $request){
        // dd('test');
            return view('admin.auth');
    }

    public function Auth(Request $request){
        // dd(auth::guard('admin')->check());
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required|min:8'
        ]);
        $checkus = Pegawai::where('username', $request->username)->first();
        $checkpen = Kependudukan::where('telepon', $request->username)->orWhere('nik', $request->username)->first();
        if(isset($checkpen)){
            $checkpelpeg = Pegawai::where('id_penduduk', $checkpen->id)->first();
        }else{
            $checkpelpeg = null;
        }
        if(!is_null($checkus)){
            // dd(!session()->has('admin'));
           if(auth()->guard('admin')->attempt($request->only(['username', 'password']), $request->remember)) {
               $request->session()->regenerate();
               $admin = Pegawai::where('username', $request->username)->first();
               // dd($admin);
               // dd(Auth::guard('admin')->login($admin));
               // $admin = $request->user('admin');
               // dd($admin);
               // dd(session());  
               $this->clearLoginAttempts($request);
               // dd(Auth::guard('admin')->user()->nama); 
               return redirect()->route('admin-dashboard');
           }else{
                // dd($request->password);
               $this->incrementLoginAttempts($request);
               return redirect()
                   ->back()
                   ->withInput()
                   ->withErrors(["password" => "Data login Admin salah !"], 'login');
           }
        }elseif(isset($checkpen)){
            if(auth()->guard('admin')->attempt(['username' => $checkpelpeg->username, 'password' => $request->password], $request->remember)) {
               $request->session()->regenerate();
               $admin = Pegawai::where('username', $request->username)->first();
               // dd($admin);
               // dd(Auth::guard('admin')->login($admin));
               // $admin = $request->user('admin');
               // dd($admin);
               // dd(session());  
               $this->clearLoginAttempts($request);
               // dd(Auth::guard('admin')->user()->nama); 
               return redirect()->route('admin-dashboard');
           }else{
                // dd($request->password);
               $this->incrementLoginAttempts($request);
               return redirect()
                   ->back()
                   ->withInput()
                   ->withErrors(["password" => "Data login Admin salah !"], 'login');
           }
        }else{
            return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors([ "username" => "Admin tidak ditemukan !"], 'login');
        }
    }

    public function Logout(Request $request){
        auth()->guard('admin')->logout();
        session()->flush();
        // dd(auth()->guard('admin'));

        return redirect()->route('admin-login');
    }
}
