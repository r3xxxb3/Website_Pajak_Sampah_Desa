<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Properti;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function properti(){
        // dd(Auth::guard('web')->user()->id);
        $properti = Properti::where('id_pengguna', Auth::guard('web')->user()->id)->get();
        return view('', compact('properti'));
    }

    public function propertiCreate(){
        
    }

    public function propertiStore(){
        
    }

    public function propertiEdit(){
        
    }

    public function propertiUpdate(){
        
    }

    public function propertiDelete(){
        
    }
}
