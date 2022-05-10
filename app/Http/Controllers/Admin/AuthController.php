<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Pegawai;


class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected $maxAttempts = 3;
    protected $decayMinutes = 1;
    // protected $redirectTo = '/admin/dashboard';
    // protected $guard = 'admin';

    public function __construct()
    {
        $this->middleware('guest:admin')->only('Login','auth');
    }
    //
    public function Login(Request $request){
        // dd('tai');
            return view('admin.auth');
    }

    public function Auth(Request $request){
        // dd(auth::guard('admin')->check());
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required|min:8'
        ]);
         // dd(!session()->has('admin'));
        if(auth()->guard('admin')->attempt($request->only(['username', 'password']))) {
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
            $this->incrementLoginAttempts($request);
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(["Incorrect user login details!"]);
        }
    }

    public function Logout(Request $request){
        auth()->guard('admin')->logout();
        session()->flush();
        // dd(auth()->guard('admin'));

        return redirect()->route('admin-login');
    }
}
