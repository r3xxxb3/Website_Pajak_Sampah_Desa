<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pengangkutan extends Model
{
    //
    public $timestamps = true;
    protected $table = 'tb_request_pengangkutan';
    protected $fillable = ['id_pengguna', 'status', 'alamat', 'file'];


    public function pelanggan(){
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }

    public function pembayaran(){
        return $this->morphedToMany(Pembayaran::class, 'model', 'tb_detail_pembayaran', 'model_id', 'id_pembayaran');
    }

    
}
