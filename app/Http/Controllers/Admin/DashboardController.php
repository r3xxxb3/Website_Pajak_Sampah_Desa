<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pelanggan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    
    public function __construct ()
    {
        
    }

    public function index ()
    {
        // dd(Auth::guard('admin')->user()->kependudukan);
        // dd($pelanggan = Pelanggan::where('id', Auth::guard('admin')->user()->id_penduduk)->first());
        return view('admin.dashboard.index');
    }
    //
}
