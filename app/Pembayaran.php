<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    //
    use SoftDeletes;
    protected $table='tb_pembayaran';
    protected $primaryKey = 'id_pembayaran';

    public function retribusi(){
        return $this->morphedByMany(Retribusi::class, 'model', 'tb_detail_pembayaran', 'id_pembayaran', 'model_id')->withTimeStamps();
    }

    public function pengangkutan(){
        return $this->morphedByMany(Pengangkutan::class, 'model', 'tb_detail_pembayaran', 'id_pembayaran', 'model_id')->withTimeStamps();
    }


    public function detail(){
        return $this->hasMany(DetailPembayaran::Class, 'id_pembayaran', 'id_pembayaran');
    }
    

    public function pelanggan(){
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id');
    }
}
