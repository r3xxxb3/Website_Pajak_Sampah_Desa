<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Pengguna;

class DashboardController extends Controller
{
    
    public function __construct ()
    {
        
    }

    public function index ()
    {
        // dd(Auth::guard('admin')->user()->pengguna());
        // dd($pengguna = Pengguna::where('id', Auth::guard('admin')->user()->id_pengguna)->first());
        return view('admin.dashboard.index');
    }
    //
}
