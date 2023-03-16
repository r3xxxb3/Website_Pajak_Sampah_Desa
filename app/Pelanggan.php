<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class Pelanggan extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    //
    protected $table = 'tb_pelanggan';
    protected $primaryKey = 'id';

    protected $fillable = [ 'chat_id'];
    protected $hidden = ['password'];


    public function banjar(){
        return $this->belongsTo(Banjar::class, 'id_banjar', 'id');
    }

    public function kependudukan(){
        return $this->belongsTo(Kependudukan::class, 'id_penduduk', 'id');
    }

    public function pengangkutan(){
        return $this->hasMany(Pengangkutan::class, 'id_pelanggan', 'id');
    }

    public function properti(){
        return $this->hasMany(Properti::class, 'id_pelanggan', 'id');
    }

    public function pembayaran(){
        return $this->hasMany(Pembayaran::class, 'id_pelanggan', 'id');
    }

    public function kritik(){
        return $this->hasMany(KritikSaran::class, 'id_pelanggan', 'id');
    }
}
