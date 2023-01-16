<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pengangkutan extends Model
{
    //
    public $timestamps = true;
    protected $table = 'tb_request_pengangkutan';
    protected $fillable = ['id_pelanggan', 'status', 'alamat', 'file'];


    public function pelanggan(){
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id');
    }

    public function Dpembayaran(){
        return $this->morphMany(DetailPembayaran::class, 'model');
    }

    public function pembayaran() {
        return $this->morphToMany(Pembayaran::class, 'detail', 'tb_detail_pembayaran');
    }
    
}
